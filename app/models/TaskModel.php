<?php

class TaskModel{

    // ATRIBUTS
    private $_jsonFile = ROOT_PATH . ("/db/tasks.json");

    public $arrTasks;

    public $arrFields = array (
        'id_task' => '0',
        'description' => '',
        // 'currentStatus' => 'initiated',
        'created_at' => '', 
        'done' => '',
        'masterUsr_id'=>'',
        'slaveUsr_id'=>''
    );
   
    // CONSTRUCTOR      
    public function __construct($arrFields) {
        
        // if (!file_exists(__DIR__.'../db/tasks.json')) {
        if ( !file_exists(ROOT_PATH . "/db/tasks.json") ) {
            $this->arrTasks =  file_put_contents(ROOT_PATH . "/db/tasks.json","[]");
        }
        // file_get: llegeix Fitxer txt  (retorna text, en aquest cas format json)
        $jsnTasks = file_get_contents($this->_jsonFile);
        // json_decode:  converteix un JSON string, en un ARRAY
        $arrTasks = json_decode($jsnTasks, true); 
        // ens guardem en State la llista de Tasques
        $this->arrTasks = $arrTasks;

        // ens guardem en State la Tasca actual que ha construit
        $this->arrFields = array(
            'id_task' => $this->getMaxId(),
            'created_at' => date("Y-m-d"),
            'description' => $arrFields['description'],
            'masterUsr_id' => $arrFields['masterUsr_id'],
            'slaveUsr_id'  => $arrFields['slaveUsr_id']
            // 'currentStatus' => $arrFields['currentStatus']
        );  
    }

    private function getMaxId(){
        if ($this->arrTasks > 0) { 
            $maxId = count($this->arrTasks)+1;
        }else{
            $maxId = 1;
        }
        return $maxId;       
    }

    public function saveJson($arrTasks, array $singleTask){
        $result = false;
        if (!empty($singleTask)){      
            // afegim al STATE dels Atributs, pero encara és VOLATIL
            array_push($arrTasks, $singleTask); 
            // json_encode:  converteix un ARRAY en un JSON string
            $jsnTasks = json_encode($arrTasks,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            // file_put: graba en Fitxer txt
            $result = file_put_contents($this->_jsonFile, $jsnTasks);
        }
        return $result? true : false;
    }
    
    public function getTasks(){
        $tasks = json_decode(file_get_contents(ROOT_PATH.'/db/tasks.json'),true);
        return $tasks;        
    }

    public function getTaskById($id){
        $arrTasks = $this->getTasks();
        foreach ($arrTasks as $singleTask){
            if ($singleTask['id_task'] == $id) {
                return $singleTask;
            }
        }
    }

    public function destroy($idToDestroy) {
        $key = -1;
        foreach ($this->arrTasks as $key => $task) {
            if ($task['id_task'] == $idToDestroy){                
                $posKey = $key;
                // echo "<br> destroy ... key = " . $posKey . "<br>";
            }
        }
        unset($this->arrTasks[$posKey]);
        $jsnTasks = json_encode($this->arrTasks,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        $result = file_put_contents($this->_jsonFile, $jsnTasks);
        return $result? true : false;
    }

    // CRUD-UPDATE ... MODIFICAR
    public function updateTask($arrTasks, $idToModify){

        // echo "<br>TaskModel::updateTask... valor de _POST['inpDescrip']=" . $_POST['inpDescrip'] . "<br>";
        // echo "<br>TaskModel::updateTask... valor de _POST['inpId']=" . $_POST['inpId'] . "<br>";
        // die;
        // echo "<br>TaskModel::updateTask... valor de idToModify=" . $idToModify . "<br>";

        foreach ($this->arrTasks as $key => $task) {

            // echo "<br>en el foreach... task['id_task']=" . $task['id_task'] . "<br>";            
            
            if ($task['id_task'] == $idToModify)  {

                // echo "<br> Encontrado:  task['id_task']=".$task['id_task'] . ", idToModify=" . $idToModify . "<br>";
                // die;

                // if ($task['masterUsr_id'] == $_SESSION['user_id']){
                    // implementar aquí
                // }
                $task['description']   = $_POST['inpDescrip'];
                $task['slaveUsr_id']   = $_POST['cmbUserSlave'];   
                // $task['currentStatus'] = $_POST['cmbCurrentStatus'];

                // echo "<br> POSICION ARRAY MODIFICADO:  task['description']=" . $task['description'] . ", task['slaveUsr_id']=" . $task['slaveUsr_id'] . "<br>";
                // die;

                // amb saveJson va afegint noves tasques, com és llogic, pq fa array_push... tan millor fer:
                // tallar el json en la posició indicada, i enmig posar la que toca ?????
                // en comptes de fer ---> array_push($arrTasks, $singleTask); 
                // hem de fer un insert a la posicio concreta del JSON ..... 
                // potser amb un indexOf() i amb un array_replace() del sub array dels fields  ?????
            }   
        }
        // echo "<br>var_dump de arrTasks antes del encode = ";
        // var_dump($arrTasks[$idToModify-1]);

        // $this->arrTasks = $arrTasks;
        $jsnTasks = json_encode($this->arrTasks, JSON_PRETTY_PRINT);                
        $result = file_put_contents($this->_jsonFile, $jsnTasks);
        return $result? true : false;     
    }

    // CAMBIOS DE STATUS... pendiente para estudiar si lo usamos o no...
    public function completedTask($taskid){
        $tasks = $this->arrTask;
        if (is_array($tasks)){
            foreach ($tasks as $task) {
                if($task['id_task'] === $taskid){
                // $task['currentStatus'] = 'completed';
                $task['done'] = date("Y-m-d");
                }
            }
         $this->saveJson($task);
        }
    }

}

?>