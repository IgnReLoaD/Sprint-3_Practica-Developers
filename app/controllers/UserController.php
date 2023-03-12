<?php

// -------------------------------------------------------------------------------
// FUNCTIONS:
// @ indexAction --> landing page where user input the credentials (usr/pwd)
//                   ... if user found, can step into landing listtasks view
//                   ... else user blocked, or can Register into adduser view
// @ addAction ----> adduser view to let Register a NEW user into BD

// @ delAction ----> deluser view let search by Id and Update field deleted=true

// -------------------------------------------------------------------------------

require_once ROOT_PATH . ('/app/models/UserModel.php');

class UserController extends ApplicationController
{    
    // LANDING - Funció per Entrar Login usuari
    // '/web/'  or  '/web/index'
	public function indexAction(){

        if ( $_SERVER['REQUEST_METHOD'] == 'POST') {

            if ( isset($_POST['inpName']) && isset($_POST['inpPwd']) ){
                // COOKIES - només si marcat 'recordar per X temps', en segons, p.ex.: 86400 = 1 day
                if (!empty($_POST['remember'])) {
                    setcookie('userDevelopersTeam', $_POST['inpName'], time()+3600 + (int) $_POST['rememberTime'], "/"); 
                }
                // DEBUG:
                // echo "entrem a UserController::indexAction -> IF-isset-POST[inpName]<br><br>";


                // carreguem els valors dels txtBox a dins un array
                $fields = array(
                    'nom' => $_POST["inpName"], 
                    'cog' => '',     // $_POST["inpCog"],
                    'rol' => '',     // $_POST["inpRol"]
                    'pwd' => $_POST["inpPwd"] 
                );       
                // DEBUG:
                // var_dump($fields);                        

                // instanciem objecte i el seu constructor omple els camps
                $objUser = new UserModel($fields);                   

                // comprobem que existeixi:
                if ( $objUser->exists($fields['nom'], $fields['pwd']) ){

                    // echo "<br>Usuario SI encontrado!!  --> puedes ir a listtask...<br>";
                    // if (!isset($_SESSION)){
                    //     session_start();
                    // } 
                    $_SESSION['nom'] = $objUser->getFields('strName');
                    $_SESSION['rol'] = $objUser->getFields('strRol');                    
                    // $_SESSION['tasks'] = $objUser->getTasksByUserId();  
                    
                    // indiquem que vagi a ruta 'listtask' que és: '/web/listtask' (TaskController::index)
                    header("Location: listtask");
                }else{
                    // echo "Usuari no trobat. Vols registrar-te?<br>"; --> incrustem en la vista                    
                    // si clickem, continuarà en aquest fitxer UserController -> mètode addAction
                }
                // tanquem sessió
                // session_destroy();
            }                
        }
    }

    // des de '/viewsfunció per AFEGIR (CREATE)
    public function addAction(){                        

        // DEBUG: 
        echo "entrant a user addAction<br>";
        echo "UserController::addAction -> comprobant si inpNom i inpRol estan plens...";                

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "UserController::addAction -> comprobant si REQUEST_METHOD es POST...";

            if ((isset($_POST['inpNom'])) && (isset($_POST['inpRol']))) {   

                echo "OJO:  aqui no entra.... va a view de EditUser !!! ";

                // 1. recollim les dades
                $fields = array(
                    'nom' => $_POST["inpNom"],
                    'cog' => $_POST["inpCog"],
                    'rol' => $_POST["inpRol"],
                    'pwd' => $_POST["inpPwd"]                    
                );

                echo "UserController::addAction -> apunt de instanciar objUser amb seus fields...";                
                die;

                // 2. Instanciem l'objecte real        
                $objUser = new UserModel($fields);            
            
                // 3. interactuar amb Model (mètode seu) per llegir/grabar
                $result = $objUser->saveJson($objUser->_arrUsers,$objUser->_fields);                 

                // 4. permetem anar a View Tasks, o mens Error
                if ($result==true){
                    header("Location: listtask");
                    // header('Location:' .ROOT_PATH.'/app/views/scripts/user/index.phtml');
                }else{
                    echo "No hem pogut grabar el nou usuari.";
                    die;
                }
            }
        }        
    }

    // Funció per Eliminar (només si ets Admin 'boss')
    public function delAction($id){
        if ($_SESSION['rol'] == "boss") {
            $arrUsersToShow = [];
            foreach ($json_users as $register){
                $objUser = new UserModel;
                array_push($arrUsersToShow,$objUser);
            }
            foreach ($register as $field){
                echo $field->show();
            }
        }
    }


    function proves(){
        // Instanciem l'objecte real - omplint Array d'Objectes per cada User 
        $arrUsers = [];
        foreach ($json_users as $register){
            // $objUser = new UserModel("1",$register[1],$register[2]);
            $objUser = new UserModel;
            // $objUser->__construct($id,$name,$rol)
            // $objUser->setName($register[1]);
            // $objUser->setRol($register[2]);            
            array_push($arrUsers,$objUser);
        }
        foreach ($arrUsers as $person){
            echo $person->show();
        }
        // $objUser->showUser();
    }

}

?>