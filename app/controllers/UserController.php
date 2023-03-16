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

            if ( isset($_POST['inpNom']) && isset($_POST['inpPwd']) ){
                // COOKIES - només si marcat 'recordar per X temps', en segons, p.ex.: 86400 = 1 day
                if (!empty($_POST['remember'])) {
                    setcookie('userDevelopersTeam', $_POST['inpNom'], time()+3600 + (int) $_POST['rememberTime'], "/"); 
                }

                // carreguem els valors dels txtBox a dins un array
                $fields = array(
                    'nom' => $_POST["inpNom"], 
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
                    // indiquem que vagi a ruta 'viewalltask' que és: '/web/viewalltask' (TaskController::viewAllAction)
                    header("Location: viewalltask");

                }else{
                    // echo "Usuari no trobat. Vols registrar-te?<br>"; --> incrustem en la vista                    
                    // si clickem, continuarà en aquest fitxer UserController -> mètode addAction
                }
                // tanquem sessió
                // session_destroy();
            }                
        }
    }

    // GET ALL USERS (para pasar a la view y pintar el user correcto)
    public function getAllUsersAction(){        
        $fields = array(
            'nom' => '', 
            'cog' => '', 
            'rol' => '', 
            'pwd' => ''
        );       
        $objUser = new UserModel($fields);
        return $objUser->getUsers();  
    }

    // funció per AFEGIR (CREATE)
    public function addAction(){                        

        // DEBUG: 
        // echo "<br>UserController::addAction -> comprobant si inpNom i inpRol estan plens...<br>";           

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ((isset($_POST['inpNom'])) && (isset($_POST['inpCog'])) && (isset($_POST['inpRol'])) && (isset($_POST['inpPwd']))) {                   

                // 1. recollim les dades
                $fields = array(
                    'nom' => $_POST["inpNom"],
                    'cog' => $_POST["inpCog"],
                    'rol' => $_POST["inpRol"],
                    'pwd' => $_POST["inpPwd"]                    
                );

                // 2. Instanciem l'objecte real        
                $objUser = new UserModel($fields);            
            
                // 3. interactuar amb Model (mètode seu) per llegir/grabar
                $result = $objUser->saveJson($objUser->_arrUsers, $objUser->_fields);                 

                // 4. permetem anar a View Tasks, o mens Error (el què fa és anar a TaskController::indexAction)
                if ($result==true){
                    // header("Location: listtask");
                    header("Location: index");
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

    function logoutAction(){
        session_destroy();
        header("Location: index");
    }

}

?>