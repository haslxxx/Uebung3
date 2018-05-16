<?php
/**
 * @author helmuth
 * refactored by Wolfgang
 * 
 */
class ListModel {
    
    private $E_kastenDatabase; //Hier sind unsere ganzen Daten aller Projekte drinnen
    //private $sqlStatement;
    
    public function __construct() {
        // Im Konstruktor datenbank verbinden, zugangsdaten in config.php
        $this->E_kastenDatabase = new Database(DBHost, DBName, DBUser, DBPass); 
    }
    
    public function queryCurrentLevelData($tableToQuery, $foreignKey){
        
        $sqlStatement = $this->getSqlStatement ($tableToQuery, $foreignKey);
         
        // DEBUG
        //echo ("\nSQL1: " . $sqlStatement);

        $result = array(); // Hier sammeln wir die abfrageergebniszeilen
        try{
            //iterieren über alle existierenden zeilen
            foreach ($this->E_kastenDatabase->query($sqlStatement) as $row) {
                $result[] = $row; //die Zeile in das Array schreiben
            }
        } catch (PDOException $ex){
            //error handling, wenn die query schief ging
            error_log("PDO ERROR: querying database: " . $ex->getMessage()."\n".$sqlStatement);
        }
        
        return $result;
    }
    
    private function getSqlStatement ($tableToQuery, $foreignKey) {
        // sql-statement basteln
        $sqlStatement = "";
        //DEBUG #####################
        //echo ("PARAMS: " . $tableToQuery . " / " . $foreignKey);
// DAS GINGE AUCH OHNE SWITCH ---->  ABER !!!!
// die tabellen haben ja alle andere felder (außer id und name), die man vielleicht mal brauchen wird können ....
// und spätestens dann könnte eine exklusive behandlung aller tabellen queries nützlich sein        
        switch ($tableToQuery) {
            case "PROJECTS":
                $sqlStatement = " SELECT id, name FROM projects "; 
                break;           
            case "FLOORS":
                $sqlStatement = " SELECT id, name FROM floors"
                    . " WHERE projects_id = " . $foreignKey; 
                break;           
            case "ROOMS":
                $sqlStatement = " SELECT id, name FROM rooms " 
                    . " WHERE floors_id = " . $foreignKey; 
                break;           
            case "DEVICES":
                $sqlStatement = " SELECT id, name FROM devices "
                    . " WHERE rooms_id = " . $foreignKey; 
                break;           
            case "SENSORS":
                $sqlStatement = " SELECT id, name FROM sensors " 
                    . " WHERE devices_id = " . $foreignKey; 
                break;   
            default :
                $sqlStatement = " SELECT id, name FROM projects "; 
        } 
        return $sqlStatement;
    }
    
}
