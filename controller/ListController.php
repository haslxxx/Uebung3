<?php
/**
 * @author helmuth
 * Refactored by Wolfgang
 * 
 */
class ListController {
   
    private $jsonView; //AusgabeView ... für ganz unten
    //my GETparams
    private $projektebene; 
    private $parentID;
    
    private $nextEbene;
   
    public function __construct() {
        $this->jsonView = new JsonView();  // Wir machen mal ein ausgabeobjekt 
    }
    
    // Get RequestParameters
    public function route(){
        if ($this->fetchRequestParams() == false) {
            $this->projektebene = "PROJECTS";   // back to the roots
        }

        $listGenerator = new ListModel(); 
        $data = $listGenerator->listProjects($this->projektebene,$this->parentID); // DER holt die daten ....
        $this->formatAndDisplayData($data); // DER gibt sie aus
    }

    private function fetchRequestParams() {
       //GET parameter holen 
        $paramNumOf = 0;
        if( isset($_GET['listtype']) ){
            $paramNumOf = 1;
            $this->projektebene = strtoupper($_GET['listtype']); // uppercase aus dem parameter machen und abspeichern in lokale variable!!
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
        $link = "";
        
        switch ($this->projektebene) {
            case "PROJECTS":
                $this->nextEbene = "FLOORS";
                break;
            case "FLOORS":
                $this->nextEbene = "ROOMS";
                ;
            case "ROOMS":
                $this->nextEbene = "DEVICES";
                break;
            case "DEVICES":
                $this->nextEbene = "SENSORS";
                break;
            case "SENSORS":
                $this->nextEbene = "lastLevel";
                break;
            default:
                $this->nextebene = "PROJECTS";
        }
 //echo ($this->projektebene . "\n" . $this->nextEbene . "\n");
     
        foreach($data as $dbEntry){
            if ($this->nextEbene != "lastLevel") {
                $projectsList[] = array(
                    "name"=> $dbEntry['name'], 
                    "url"=> "http://localhost/Uebung3/index.php?listtype=" . $this->nextEbene . "&parentid=" . $dbEntry['id']
                );
            } else {
                $projectsList[] = array(
                    "name"=> $dbEntry['name'], 
                    "url"=> ""
                );
            }
        }
        $outputData = array (
            "listtype" => $this->projektebene,
            "items" => $projectsList
        );
        $this->jsonView->streamOutput($outputData);
    }
}
