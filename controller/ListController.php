<?php
/**
 * @author helmuth
 * Refactored by Wolfgang
 * 
 */
class ListController {
   
    private $jsonView; //AusgabeView ... für ganz unten
//    private $dh;
    
    //my GETparams
    private $projektebene; 
    private $parentID;
   
    public function __construct() {
        $this->jsonView = new JsonView();  // Wir machen mal ein ausgabeobjekt 
/*
        $dh = new DebugHelper();            // Mein kleiner meldungs-dienst
        $dh->msg("Jetzt geht's lohooos  :-)");
 * ist zwar nett gedacht .. müsste man aber jetzt in jedermethode wieder neu instanzieren  :-( 
 * DENKFEHLER ??
 */
    }
    
    // Get RequestParameters
    public function route(){
        if ($this->fetchRequestParams() == true) { // Parameter holen --> ?? fehlerfreie parameter
            $listGenerator = new ListModel(); 
            $data = $listGenerator->listProjects($this->projektebene,$this->parentID); // DER holt die daten ....
            //DEBUG---------------
            $cnt = count($data);
            echo ("\n");
            for ($xi=0;$xi<$cnt;$xi++) {
                echo (implode (" / " ,$data[$xi]) . "\n");
                //echo (strval($data[$xi]) . "\n");
            }

            $this->formatAndDisplayData($data); // DER gibt sie aus
        } else {
            // ############################# FIXME hier kommt die mekkerecke (im endeffekt als jason
            echo ("\nParameterfehler:   listtype=projekts|floors|rooms|devices|sensors & parentID:0...n");
        }
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
        // inhalt OK ? Hier testen wir noch auf listtype=projekts|floors|rooms|devices|sensors  und parentID formal valide
        //######################### FIXME fehlt noch
        
        return $requParOK;        
    }

    
    private function formatAndDisplayData($data){        
        $projectsList = array();
        
        foreach($data as $dbEntry){
            $projectsList[] = array(
                "name"=> $dbEntry['name'], 
                // Bei der URL ist noch irgendein denkfehler drin .... müsste ja die nchstniederere ebene sein
                // FIXME #########################################
                "url"=> "http://localhost/Uebung3/index.php?listtype=" . $this->projektebene . "&parentid=" . $dbEntry['id']
            );
        }
        $outputData = array (
            "listtype" => $this->projektebene,
            "items" => $projectsList
        );
        $this->jsonView->streamOutput($outputData);
    }
    

}
