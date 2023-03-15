<?php

class TaskModel{

    // ATRIBUTS
    private $_jsonFile = ROOT_PATH . ("/db/tasks.json");

    public $arrTasks;

    public $arrFields = array (
        'id_task' => '0',
        'description' => '',
        'currentStatus' => 'initiated',
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
                // var_dump($singleTask);
            }
        }
    }

    public function destroy($id) {
        $key = -1;
        // echo "<br> Entra en destroy(".$id.") <br>";
        foreach ($this->arrTasks as $key => $task) {
            // echo "<br>task['description'] = " . $task['description'];
            if ($task['id_task'] == $id){                
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

        echo "<br>TaskModel::updateTask... valor de _POST['inpDescrip']=" . $_POST['inpDescrip'] . "<br>";
        echo "<br>TaskModel::updateTask... valor de _POST['inpId']=" . $_POST['inpId'] . "<br>";
        // die;
        echo "<br>TaskModel::updateTask... valor de idToModify=" . $idToModify . "<br>";

        foreach ($arrTasks as $task) {
            // echo "en el foreach... task['id_task']=" . $task['id_task'];            
            if ($task['id_task'] == $idToModify)  {
                // if ($task['masterUsr_id'] == $_SESSION['user_id']){
                    // implementar aquí
                // }
                $task['description'] = $_POST['inpDescrip'];
                $task['slaveUsr_id'] = $_POST['cmbUserSlave'];      

                $result = $this->saveJson($arrTasks,$task);
                echo "<br>result de saveJson = " . $result . "<br>";
            }            
        }
        return true;      
    }

    // CAMBIOS DE STATUS... pendiente para estudiar si lo usamos o no...
    public function completedTask($taskid){
        $tasks = $this->arrTask;
        if (is_array($tasks)){
            foreach ($tasks as $task) {
                if($task['id_task'] === $taskid){
                $task['currentStatus'] = 'completed';
                $task['done'] = date("Y-m-d");
                }
            }
         $this->saveJson($task);
        }
    }

    public function initiatedTask($taskid){
        $tasks = $this->arrTask;
        if (is_array($tasks)){
            foreach ($tasks as $task) {
                if($task['id_task'] === $taskid){
                    $task['currentStatus'] = 'in progress';                    
                }
            }
            $this->saveJson($task);
        }
    }

}

?>