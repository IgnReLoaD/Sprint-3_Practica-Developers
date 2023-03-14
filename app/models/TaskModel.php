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
        
        if (!file_exists(__DIR__.'../db/tasks.json')) {
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
            // 'done' => date("Y-m-d")
        );  
    }

    public function saveJson($arrTasks, array $singleTask){
        //json_encode(file_put_contents(ROOT_PATH. '/db/tasks.json', $arrFields));        
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

    public function getTaskById($taskId){
        $tasks = $this->arrTasks;
        foreach ($tasks as $task) {
            if ($task['id_task']== $taskId) {
                return json_encode($task);
            }
        }return null;
        // $tasks = $this->getTasks();
        // $key = array_column($tasks,'id_task');
        // return json_encode($tasks[$key]);
    }
    
    public function getId(){
        $tasks = $this->arrTasks;
        foreach ($tasks as $task) {
          $taskid = $task ['id_task'];
           return json_encode($taskid);
        }
    }

    // public function setId(){
    //     $record_number = count($this->arrTasks);
    //     for ($i=0; $i <= $record_number ; $i++) { 
            
    //         if ($this->arrFields['id_task'] == 0) {
    //             $this->arrFields['id_task']=1;
    //         }else{
    //             $this->arrFields['id_task']=+1;
    //         }
    //     }        
    // }
   
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
    public function deletedTask($taskid) {
        $tasks = $this->arrTask;
        if (is_array($tasks)){
            foreach ($tasks as $task) {
                if($task['id_task'] === $taskid){
                    $task['currentStatus'] = 'deleted';
                    $task['done'] = date("Y-m-d") ;
                }
            }
            $this->saveJson($task);
        }
    }

    // public function createTask($arrFields) {
    //     $tasks = self::getTasks();
    //     $arrFields['id_task']=0;
    //     if ($this->arrFields['id_task']=0) {
    //         $this->arrFields['id_task']=1;
    //     }else {
    //         $this->arrFields['id_task']=+1;
    //     }
    //     $tasks[]=$this->arrFields;
    //     self::saveJson($this->arrTasks, $this->$tasks);
    //     return $this->arrFields;
    // }

    private function getMaxId(){
        if ($this->arrTasks > 0) { 
            $maxId = count($this->arrTasks)+1;
        }else{
            $maxId = 1;
        }
        // DEBUG:
        // echo "<br> UserModel->getMaxId...maxId: " . $maxId . "<br>";
        return $maxId;       
    }
}

?>