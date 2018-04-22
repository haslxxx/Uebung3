<?php

/**
 * Description of Database
 *
 * @author helmuth
 */
class Database extends PDO{
    private $dbHandler;
    
    public function __construct($dbHost, $dbName, $dbUser,$dpPass){
        $this->dbHandler = new PDO("mysql:host=".$dbHost.";dbname=".$dbName.";charset=utf8", $dbUser, $dpPass);
    }

    public function query( $sql){
        $resultTable = array();
        //DEBUG ----------------------------
        echo ("\nSQL2: " . $sql);
        try{
            foreach ($this->dbHandler->query($sql) as $row) {
                $resultTable[] = $row;
                // echo (strval($row)); //FIXME ############################ wie bekomme ich $row angezeigt
            }
        } catch (PDOException $ex){
            error_log("PDO ERROR: querying database: " . $ex->getMessage()."\n".$sql);
            return $resultTable;
        }
        
        return $resultTable;
    }
}
