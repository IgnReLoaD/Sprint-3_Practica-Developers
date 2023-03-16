<?php

class UserModel {

    // ATRIBUTS
    private $_jsonFile = ROOT_PATH . ("/db/users.json"); 

    public $_arrUsers;

    public $_fields = array(
        'id_user' => '0',
        'createdAt' => '',
        'name' => '',
        'surn'  => '',
        'pwd'  => '',
        'rol'  => '',
        'deleted' => '0'
    );

    // CONSTRUCTOR      
    public function __construct($arrFields){
        
        if ( !file_exists(ROOT_PATH . "/db/users.json") ) {
            $this->_jsonFile = file_put_contents(ROOT_PATH . "/db/users.json","[]");
        }
        // file_get: llegeix Fitxer txt  (retorna text, en aquest cas format json)
        $jsnUsers = file_get_contents($this->_jsonFile);
        // json_decode:  converteix un JSON string, en un ARRAY
        $arrUsers = json_decode($jsnUsers,true);             
        // ens guardem en State la llista d'users
        $this->_arrUsers = $arrUsers;

        // ens guardem en State l'usuari actual que ha entrat
        $this->_fields=array(
            'id_user' => $this->getMaxId(),
            'createdAt' => date("Y-m-d H:i:s"),
            'name' => $arrFields['nom'],
            'surn' => $arrFields['cog'],
            'pwd'  => $arrFields['pwd'],
            'rol'  => $arrFields['rol'],
            'deleted' => '0'
        );  
    }

    // GETTERS-SETTERS
    public function getFields(){
        return $this->_fields;
    }

    // METODES ESPECIFICS de Classe:
    public function exists($nom,$pwd){
        $match = false;
        if ($this->_arrUsers != null){
            foreach ($this->_arrUsers as $user){
                // echo "var_dump de $ user : " . var_dump($user) . "<br>";
                if ( ($user['name'] == $nom) && ($user['pwd']==$pwd) ) {
                    $match = true;
                }
            }
        }
        return $match;
    }

    public function saveJson($arrUsers, array $singleUser){
        $result = false;
        if (!empty($singleUser)){      
            // afegim al STATE dels Atributs, pero encara és VOLATIL
            array_push($arrUsers, $singleUser); 
            // json_encode:  converteix un ARRAY en un JSON string
            $jsnUsers = json_encode($arrUsers,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            // file_put: graba en Fitxer txt
            $result = file_put_contents($this->_jsonFile, $jsnUsers);
        }
        return $result? true : false;                
    }

    private function getMaxId(){
        if ($this->_arrUsers > 0) { 
            $maxId = count($this->_arrUsers)+1;
        }else{
            $maxId = 1;
        }
        return $maxId;       
    }

    // implementamos aquí el DELETE a JSON (un recycle nos permite recuperarlo, todavia no Delete total)
    public function recycle($data,$status) {
        $this->setDeleted($status);        
        json_encode($data);
        file_put_contents("../db/users.json",$data);
    }

    // necesitamos una función para listar usuarios, en realidad no listar 
    // pero sí llamarla desde UserController y así pasarlos a VIEW de Tasks
    public function getUsers(){
        $users = json_decode(file_get_contents(ROOT_PATH.'/db/users.json'),true);
        return $users; 
    }

}

?>