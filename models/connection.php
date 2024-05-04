<?php 

    require_once "get.model.php";


    class Connection{

        /*===================
        Información de la base de datos
        ====================*/
            static public function infoDatabase(){
                $infoDB = array(
                    "database"=>"dyvitpmr_proyectoMIM",
                    "user" => "dyvitpmr_admin",
                    "pass" => "Jose123456@123456"
                );

                return $infoDB;
            }

        /*===================
        Conexión a la base de datos
        ====================*/

        static public function connect(){
                $link = null;
            try{
                $link = new PDO(
                    "mysql:host=localhost;dbname=".Connection::infoDatabase()["database"],
                    Connection::infoDatabase()["user"],
                    Connection::infoDatabase()["pass"]
                );

                $link->exec("set names utf8");

            }catch(PDOException $e){
                die("Error: ".$e->getMessage());
            }   
        return $link;
        }

        static public function getColumnsData($table, $columns){

            $database = Connection::infoDatabase()["database"];

            $validate = Connection::connect()
            ->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
            ->fetchAll(PDO::FETCH_OBJ);

            if(empty($validate)){
                return null;
            }else{
                if($columns[0] == "*"){
                    array_shift($columns);
                }
                $sum = 0;
                foreach($validate as $key => $value){
                    $sum += in_array($value->item, $columns);
                }
                return $sum == count($columns) ? $validate : null;
            }
        }

        // Generación del token de seguridad por email

        static public function jwt($id, $email){
            $time = time();
            $token = array(
                "iat" => $time, //Tiempo en el que inicia el token
                "exp" => $time + (60*60*24*7), // Tiempo de expiracion del token (Una semana)
                "data" => [
                    "id" => $id,
                    "email" => $email
                ]
            );
            return $token;
        }

        // Validar token de seguridad

        static public function tokenValidate($token){
           $user = GetModel::getDataFilter($table, "token_exp_".$suffix, "token_".$suffix, $token, null, null, null, null);
            if(!empty($user)){
                //Validamos que el token no haya expirado.
                $time = time();
                if($user[0]->{"token_exp_".$suffix} > $time){
                    return "ok";
                }else{
                    return "expirado";
                }
            }else{
                return "no-autorizado";
            }
        }
    }
?>