<?php
//Start Session
session_start();

//Include Configuration
require_once('config/config.php');

//Helper Function Files
require_once('helpers/system_helpers.php');
require_once('helpers/format_helpers.php');
require_once('helpers/db_helpers.php');

//Autoload CLasses
function __autoload($class_name){
	require_once('libraries/'.$class_name.'.php');
}