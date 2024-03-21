<?php


// Este es el modelo del método GET

require_once "connection.php";

class GetModel{

    // PETICIONES GET SIN FILTRO

    static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt){

        // Sin ordenar y limitar datos

        $sql = "SELECT $select FROM $table";

        //Sentencia pasa ordenar pero no limitar

        if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode";
        }
        
        //Sentencia pasa ordenar y limitar

        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        // Sentencia solo para limitar

        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table LIMIT $startAt, $endAt";
        }

        $stmt =  Connection::connect()->prepare($sql);

        $stmt -> execute();

        // Utilizamos PDO FETCH_CLASS como argumente para que nos devuelva objetos en vez de indices que seria sin poner ningún argumento a fetchAll
        return $stmt -> fetchAll(PDO::FETCH_CLASS);
    }

    // PETICIONES GET CON FILTROS, creamos dos arrays que cogen la URL, la despiezan en caso de ser necesario y crean la sentencia.

    static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){

        $linkToArray = explode(",",$linkTo); // Separamos las columnas del WHERE mediante una ,
        $equalToArray = explode("_",$equalTo); // Separamos los valores de las columnas a buscar por un guión bajo _
        $linkToText ="";

        if(count($linkToArray)>1){
            foreach ($linkToArray as $key => $value) {
                if($key > 0){
                    $linkToText .= "AND ".$value." = :".$value." ";
                }
            }
        }

        // Sin ordenar ni limitar datos

        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText"; //Enlace de parámetro

        // Ordenando  datos sin limites

        if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
        }
               
        //Sentencia pasa ordenar y limitar
        
        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }
        
        // Sentencia solo para limitar
        
        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText LIMIT $startAt, $endAt";
        }

        
        $stmt =  Connection::connect()->prepare($sql);

        foreach ($linkToArray as $key => $value){
            $stmt -> bindParam(":".$value, $equalToArray[$key], PDO::PARAM_STR);
        }

        $stmt -> execute();

        // Utilizamos PDO FETCH_CLASS como argumente para que nos devuelva objetos en vez de indices que seria sin poner ningún argumento a fetchAll
        return $stmt -> fetchAll(PDO::FETCH_CLASS);
    }

    // PETICIONES GET SIN FILTRO ENTRE TABLAS RELACIONADAS

    static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt){


        $relArray = explode(",",$rel); // Separamos tablas de la URL mediante coma
        $typeArray = explode(",",$type); // Separamos tipos de la URL mediante coma

        $innerJoinText ="";

        if(count($relArray)>1){

            foreach ($relArray as $key => $value) {

                if($key > 0){

                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0] ." = ".$value.".id_".$typeArray[$key]." ";
                }
            }
        
        // Sin ordenar ni limitar datos
            
        $sql = "SELECT $select FROM $relArray[0] $innerJoinText";

        //Sentencia pasa ordenar pero no limitar

        if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode";
        }
        
        //Sentencia pasa ordenar y limitar

        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        // Sentencia solo para limitar

        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText LIMIT $startAt, $endAt";
        }

        $stmt =  Connection::connect()->prepare($sql);

        $stmt -> execute();

        // Utilizamos PDO FETCH_CLASS como argumento para que nos devuelva objetos en vez de indices que seria sin poner ningún argumento a fetchAll

        return $stmt -> fetchAll(PDO::FETCH_CLASS);

        }else{
            return null;
        }
    }
    // PETICIONES GET CON FILTRO ENTRE TABLAS RELACIONADAS

    static public function getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt){

        /* ----ORGANIZAMOS LOS FILTROS---- */

        $linkToArray = explode(",",$linkTo); // Separamos las columnas del WHERE mediante una ,
        $equalToArray = explode("_",$equalTo); // Separamos los valores de las columnas a buscar por un guión bajo _
        $linkToText ="";

        if(count($linkToArray)>1){
            foreach ($linkToArray as $key => $value) {
                if($key > 0){
                    $linkToText .= "AND ".$value." = :".$value." ";
                }
            }
        }


        /* ----ORGANIZAMOS LAS RELACIONES---- */

        $relArray = explode(",",$rel); // Separamos tablas de la URL mediante coma
        $typeArray = explode(",",$type); // Separamos tipos de la URL mediante coma
        $innerJoinText ="";

        if(count($relArray)>1){

            foreach ($relArray as $key => $value) {

                if($key > 0){

                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0] ." = ".$value.".id_".$typeArray[$key]." ";
                }
            }
        
        // Sin ordenar ni limitar datos
            
        $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";

        //Sentencia pasa ordenar pero no limitar

        if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
        }
        
        //Sentencia pasa ordenar y limitar

        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        // Sentencia solo para limitar

        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText LIMIT $startAt, $endAt";
        }

        $stmt =  Connection::connect()->prepare($sql);

        foreach ($linkToArray as $key => $value){
            $stmt -> bindParam(":".$value, $equalToArray[$key], PDO::PARAM_STR);
        }

        $stmt -> execute();

        // Utilizamos PDO FETCH_CLASS como argumente para que nos devuelva objetos en vez de indices que seria sin poner ningún argumento a fetchAll
        return $stmt -> fetchAll(PDO::FETCH_CLASS);

        }else{
            return null;
        }
    }

    // PETICIONES GET PARA EL BUSCADOR SIN RELACIONES

    static public function getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){

        $linkToArray = explode(",",$linkTo); // Separamos las columnas del WHERE mediante una ,
        $searchArray = explode("_",$search); // Separamos los valores de las columnas a buscar por un guión bajo _
        $linkToText ="";

        if(count($linkToArray)>1){
            foreach ($linkToArray as $key => $value) {
                if($key > 0){
                    $linkToText .= "AND ".$value." = :".$value." ";
                }
            }
        }

        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText";

        //Sentencia pasa ordenar pero no limitar

        if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode";
        }
        
        //Sentencia pasa ordenar y limitar

        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        // Sentencia solo para limitar

        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText LIMIT $startAt, $endAt";
        }

        $stmt =  Connection::connect()->prepare($sql);

        foreach ($linkToArray as $key => $value){

            if($key > 0){

                $stmt -> bindParam(":".$value, $searchArray[$key], PDO::PARAM_STR);

            }
        }

        $stmt -> execute();


        return $stmt -> fetchAll(PDO::FETCH_CLASS);
    }

     // PETICIONES GET BUSCADOR CON TABLAS RELACIONADAS

     static public function getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt){

        /* ----ORGANIZAMOS LOS FILTROS---- */

        $linkToArray = explode(",",$linkTo); // Separamos las columnas del WHERE mediante una ,
        $searchArray = explode("_",$search); // Separamos los valores de las columnas a buscar por un guión bajo _
        $linkToText ="";

        if(count($linkToArray)>1){
            foreach ($linkToArray as $key => $value) {
                if($key > 0){
                    $linkToText .= "AND ".$value." = :".$value." ";
                }
            }
        }


        /* ----ORGANIZAMOS LAS RELACIONES---- */

        $relArray = explode(",",$rel); // Separamos tablas de la URL mediante coma
        $typeArray = explode(",",$type); // Separamos tipos de la URL mediante coma
        $innerJoinText ="";

        if(count($relArray)>1){

            foreach ($relArray as $key => $value) {

                if($key > 0){

                    $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0] ." = ".$value.".id_".$typeArray[$key]." ";
                }
            }
        
        // Sin ordenar ni limitar datos
            
        $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText";

        //Sentencia pasa ordenar pero no limitar

        if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode";
        }
        
        //Sentencia pasa ordenar y limitar

        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        // Sentencia solo para limitar

        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText LIMIT $startAt, $endAt";
        }

        $stmt =  Connection::connect()->prepare($sql);

        foreach ($linkToArray as $key => $value){

            if($key > 0){

                $stmt -> bindParam(":".$value, $searchArray[$key], PDO::PARAM_STR);

            }
        }

        $stmt -> execute();



        // Utilizamos PDO FETCH_CLASS como argumente para que nos devuelva objetos en vez de indices que seria sin poner ningún argumento a fetchAll
        return $stmt -> fetchAll(PDO::FETCH_CLASS);

        }else{
            return null;
        }
    }
    //PETICIÓN GET PARA SELECCIÓN DE RANGOS

    static public function getDataRange($table, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo){

        $filter = "";

        if($filterTo != null && $inTo != null){
            $filter = "AND ".$filterTo." IN (".$inTo.")";
        }
        //Sin ordenar ni limitar datos

        $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter";

        //Sentencia pasa ordenar pero no limitar

        if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
            $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode";
        }
        
        //Sentencia pasa ordenar y limitar

        if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        }

        // Sentencia solo para limitar

        if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
            $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter LIMIT $startAt, $endAt";
        }

        $stmt =  Connection::connect()->prepare($sql);

        $stmt -> execute();

        // Utilizamos PDO FETCH_CLASS como argumente para que nos devuelva objetos en vez de indices que seria sin poner ningún argumento a fetchAll
        return $stmt -> fetchAll(PDO::FETCH_CLASS);
                        
    }
        //PETICIÓN GET PARA SELECCIÓN DE RANGOS CON RELACIONES

        static public function getRelDataRange($rel, $type, $select, $linkTo, $between1, $between2, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo){

            $filter = "";
    
            if($filterTo != null && $inTo != null){
                $filter = "AND ".$filterTo." IN (".$inTo.")";
            }

            $relArray = explode(",",$rel); // Separamos tablas de la URL mediante coma
            $typeArray = explode(",",$type); // Separamos tipos de la URL mediante coma
            $innerJoinText ="";
    
            if(count($relArray)>1){
    
                foreach ($relArray as $key => $value) {
    
                    if($key > 0){
    
                        $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0] ." = ".$value.".id_".$typeArray[$key]." ";
                    }
            }
            //Sin ordenar ni limitar datos
    
            $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter";
    
            //Sentencia pasa ordenar pero no limitar
    
            if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode";
            }
            
            //Sentencia pasa ordenar y limitar
    
            if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
            }
    
            // Sentencia solo para limitar
    
            if($orderBy == null && $orderMode == null && $startAt != null && $endAt != null){
                $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter LIMIT $startAt, $endAt";
            }
    
            $stmt =  Connection::connect()->prepare($sql);
    
            $stmt -> execute();
    
            // Utilizamos PDO FETCH_CLASS como argumente para que nos devuelva objetos en vez de indices que seria sin poner ningún argumento a fetchAll
            return $stmt -> fetchAll(PDO::FETCH_CLASS);
                            
        }else{
            return null;
        }
    }
}

?>