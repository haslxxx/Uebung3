<?php
/**
 * @author helmuth
 * refactored by Wolfgang
 * 
 */
class ListModel {
    
    private $E_kastenDatabase; //Hier sind unsere ganzen Daten aller Projekte drinnen
    private $sqlStatement;
    
    public function __construct() {
        $this->E_kastenDatabase = new Database(DBHost, DBName, DBUser, DBPass); // Im KOnstruktor 
    }
    
    public function listProjects($tableToQuery, $foreignKey){
        // sql-statement basteln
        //unterscheidung in Top-(PROJECTS), middle- und bottom(DEVICES, SENSORS)level
        
        //DEBUG #####################
        echo ("PARAMS: " . $tableToQuery . " / " . $foreignKey);
// DAS GINGE AUCH OHNE SWITCH ---->  ABER !!!!
// die tabellen haben ja alle andere felder, die man vielleicht mal brauchen wird können ....
// und spätestens dann könnte eine exklusive behandlung aller tabellen queries nützlich sein        
        switch ($tableToQuery) {
            case "PROJECTS":
                $this->sqlStatement = " SELECT id, name FROM projects "; 
                echo ("\nPING1 !!!!!!!!!!!!");
                break;           
            case "FLOORS":
                $this->sqlStatement = " SELECT id, name FROM floors"
                    . " WHERE projects_id = " . $foreignKey; 
                echo ("\nPING2 !!!!!!!!!!!!");
                break;           
            case "ROOMS":
                $this->sqlStatement = " SELECT id, name FROM rooms " 
                    . " WHERE floors_id = " . $foreignKey; 
                echo ("\nPING3 !!!!!!!!!!!!");
                break;           
            case "DEVICES":
                $this->sqlStatement = " SELECT id, name FROM devices "
                    . " WHERE rooms_id = " . $foreignKey; 
                echo ("\nPING4 !!!!!!!!!!!!");
                break;           
            case "SENSORS":
                $this->sqlStatement = " SELECT id, name FROM sensors " 
                    . " WHERE devices_id = " . $foreignKey; 
                echo ("\nPING5 !!!!!!!!!!!!");
                break;           
        } 
        
        // DEBUG
        //echo ("\nSQL1: " . $this->sqlStatement);

        $result = array(); // Hier sammeln wir die abfrageergebniszeilen
        try{
            //iterieren für alle moeglichen zeilen
            foreach ($this->E_kastenDatabase->query($this->sqlStatement) as $row) {
                $result[] = $row; //die Zeile in den Array schreiben
            }
        } catch (PDOException $ex){
            //error handling, wenn der query schief ging
            error_log("PDO ERROR: querying database: " . $ex->getMessage()."\n".$this->sqlStatement);
        }
        
        return $result;
    }
    
}
