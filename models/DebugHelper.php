<?php

/**
 * Stellt eine ausgabemethode zur verfügung 
 *
 * @author ego
 */
class DebugHelper {
    
        // ######## Debug Helferlein (verträgt 1 oder 2 strings im aufruf
    public function msg($tag = "",$toShow=""){ // methoden überladen geht in php nicht, ergo trick mit zuweisung in der parameterdefinition
        if (DEBUG==1) {
            if ($toShow == ""){ // wenn nur EIN string übergeben wird, dann isses nicht der TAG sondern der ausgabetext
                echo "--> " . $tag . "\n"; // ausgabetext im ersten parameter
            } else {
                echo "--> " . $tag . ": " . $toShow . "\n";   // wenn  \n nicht wirkt .. dann "<br />\n"             
            }
        }
    }

}
