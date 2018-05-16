<?php
/**
 * @author helmuth
 * Refactored by Wolfgang
 * 
 */
class ListController {
   
    private $jsonView; //AusgabeView ... für ganz unten
    //my GETparams
    private $currentLevel; 
    private $parentID;
   
    public function __construct() {
        $this->jsonView = new JsonView();  // Wir machen mal ein ausgabeobjekt ... kann nie schaden
    }
    
    // Get RequestParameters
    public function route(){
        if ($this->fetchRequestParams() == false) {
            $this->currentLevel = "PROJECTS";   // back to the roots
        }

        $listGenerator = new ListModel(); 
        $data = $listGenerator->queryCurrentLevelData($this->currentLevel,$this->parentID); // DER holt die daten ....
        $this->formatAndDisplayData($data); // DER gibt sie aus
    }

    private function fetchRequestParams() {
       //GET parameter holen 
        $paramNumOf = 0;
        if( isset($_GET['listtype']) ){
            $paramNumOf = 1;
            $this->currentLevel = strtoupper($_GET['listtype']); // uppercase aus dem parameter machen und abspeichern in lokale variable!!
        }
        if (isset ($_GET['parentid'])) {
            $this->parentID = strtoupper($_GET['parentid']);
            $paramNumOf +=1;
        }
        
        //#################### Validieren der Parameter
        $requParOK = true;
        if ($paramNumOf != 2) { // Anzahl OK ?
            $requParOK = false;
        }
        // inhalt OK ? Hier könnten wir noch auf listtype=projekts|floors|rooms|devices|sensors  und parentID formal valide testen

        return $requParOK;        
    }

    private function formatAndDisplayData($data){        
        $projectsList = array();
        //$link = "";
        $nextLevel = $this->getNextLevel($this->currentLevel);

        foreach($data as $dbEntry){
            if ($nextLevel != "lastLevel") {
                $projectsList[] = array(
                    "name"=> $dbEntry['name'], 
                    "url"=> "http://localhost/Uebung3/index.php?listtype=" . $nextLevel . "&parentid=" . $dbEntry['id']
                );
            } else {
                $projectsList[] = array(
                    "name"=> $dbEntry['name'], 
                    "url"=> ""
                );
            }
        }
        $outputData = array (
            "listtype" => $this->currentLevel,
            "items" => $projectsList
        );
        $this->jsonView->streamOutput($outputData);
    }
 
    private function getNextLevel($currentLevel) {
        $nextDataModelLevel = "";
        switch ($currentLevel) {
            case "PROJECTS":
                $nextDataModelLevel = "FLOORS";
                break;
            case "FLOORS":
                $nextDataModelLevel = "ROOMS";
                ;
            case "ROOMS":
                $nextDataModelLevel = "DEVICES";
                break;
            case "DEVICES":
                $nextDataModelLevel = "SENSORS";
                break;
            case "SENSORS":
                $nextDataModelLevel = "lastLevel";
                break;
            default:
                $nextDataModelLevel = "PROJECTS";
        }
 //echo ($this->projektebene . "\n" . $nextEbene . "\n");
        return $nextDataModelLevel;
    }

}
