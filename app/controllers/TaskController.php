<?php
//require_once ('app/models/TaskModel.php');
 require ROOT_PATH.'/app/models/TaskModel.php';
 require ROOT_PATH.'/app/models/UserModel.php';
 
class TaskController extends Controller {

    // MENU OPTIONS FOR TASKS -- NOT USED / ERASED
    public function indexAction(){
        echo "<br> TaskController::indexAction -> menu opciones tasks";
    }

    // TO PRESENT A FORM TO THE USER, TO LET HIM INPUT SOME 'TASK DATA'
    public function addAction(){
        $objUser = new UserModel([
            'nom' => '', 
            'cog' => '', 
            'rol' => '', 
            'pwd' => ''
        ]);
        $this->view->__set('users', $objUser->getUsers() );
    }
    
    // STORE IN BD THE INPUT FORM VALUES -----------------------------
    public function storeAction(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){            
            if (isset($_POST['masterUsr_id']) && isset($_POST['description']) && isset($_POST['created_at']) && isset($_POST['slaveUsr_id']) ){

                // 1. recollim les dades de la Tasca
                $fields = array(
                    'description' => $_POST["description"],
                    'masterUsr_id' => $_POST["masterUsr_id"],
                    'slaveUsr_id' => $_POST["slaveUsr_id"],
                    'created_at' => $_POST["created_at"]
                    // 'currentStatus' => $_POST["cmbCurrentStatus"]
                );

                // 2. Instanciem l'objecte Tasca
                $objTask = new TaskModel($fields);

                // 3. interactuar amb Model (mètode implementat a la Classe del Model per grabar)
                $result = $objTask->saveJson($objTask->arrTasks, $objTask->arrFields);                    
                
                // 4. si result OK podrem anar a la View de Tasks altre cop, on veurem la nova tasca llistada
                if ($result==true){
                    header("Location: viewalltask");
                }else{
                    echo "Error creating Task";
                }
                return $result;   
            }
        }
    }

    // LIST OF ALL TASKS ---------------------------------------------
    public function viewallAction(){        
        $taskObj = new TaskModel([
            'description' => 'descrip',
            'masterUsr_id' => '1',
            'slaveUsr_id' => '1'
        ]);
        return $taskObj->getTasks();  
    }

    // ELIMINAR UNA TASCA -------------------------------------------
    public function delAction(){ 
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if (isset ($_GET['id_task'])) {
                $objTask = new TaskModel([
                    'description' => 'descrip',
                    'masterUsr_id' => '1',
                    'slaveUsr_id' => '1'
                ]);
                $result = $objTask->destroy($_GET['id_task']);
                if ($result){
                    $tasks = $objTask->getTasks();
                    header('Location: viewalltask');
                }else{
                    echo "ATENCIO: no s'ha pogut eliminar!!";
                }
            }
        }
        header('Location: viewalltask');
    }

    // EDITAR UNA TASCA --------------------------------
    public function editAction(){
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if (isset ($_GET['id_task'])) {
                $objUser = new UserModel([
                    'nom' => '', 
                    'cog' => '', 
                    'rol' => '', 
                    'pwd' => ''
                ]);
                $objTask = new TaskModel([
                    'description' => 'descrip',
                    'masterUsr_id' => '1',
                    'slaveUsr_id' => '1'
                ]);
                $id = $_GET['id_task'];                
                $this->view->__set('data', $objTask->getTaskById($id) );
                $this->view->__set('users', $objUser->getUsers() );
            }
        }  
    }

    // UPDATE GRABAR TASCA EDITADA ----------------------
    public function updateAction(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $objTask = new TaskModel([
                'description' => 'descrip',
                'masterUsr_id' => '1',
                'slaveUsr_id' => '1'
            ]);
            $objTask->updateTask($objTask->getTasks(), $_POST['inpId']);
            header("Location: viewalltask");
        }
    }


}

?>
