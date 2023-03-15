<?php
//require_once ('app/models/TaskModel.php');
 require ROOT_PATH.'/app/models/TaskModel.php';

 
class TaskController extends Controller {

    // MENU OPTIONS FOR TASKS -- NOT USED / ERASED
    public function indexAction(){
        echo "<br> TaskController::indexAction -> menu opciones tasks";
    }

    // TO PRESENT A FORM TO THE USER, TO LET HIM INPUT SOME 'TASK DATA'
    public function addAction(){
        // echo "<br>TaskController::addAction...<br>";
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
                    die;
                }
            }
        }
        header('Location: viewalltask');
    }

    // EDITAR UNA TASCA --------------------------------
    public function editAction(){

        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if (isset ($_GET['id_task'])) {
                $objTask = new TaskModel([
                    'description' => 'descrip',
                    'masterUsr_id' => '1',
                    'slaveUsr_id' => '1'
                ]);
                $id = $_GET['id_task'];

                // si estem cridant al formulari, encara els inputs buits --> cridem la View Edit, passant la info
                if (empty($_POST)) {
                    $this->view->__set('data', $objTask->getTaskById($id));

                // si estem retornant del formulari, els inputs ja plens --> cridem mètode Grabar i tornem a View llistat
                } else {
                    $objTask->updateTask($objTask->getTasks(), $id);
                    header("Location: viewalltask");
                }
            }
        }  
    }


}

?>
