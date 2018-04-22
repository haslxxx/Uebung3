<?php
error_reporting(E_ALL);

include "controller/ListController.php";
include "services/Database.php";
include "models/ListModel.php";
include "views/JsonView.php";

include "models/DebugHelper.php";

define ("DBHost", "localhost");
define ("DBName", "uebung3_db");
define ("DBUser", "root");
define ("DBPass", "");

define("DEBUG",1);  // schaltet debugmessages ein/aus
