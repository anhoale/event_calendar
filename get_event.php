<?php 
require('core/init.php');

if (!isset($_POST['type'])) {
	redirect("index.php");
}

//create an Event object
$event = new Event();

$type = $_POST['type'];

if ( $type == 'fetch' ){
	echo $event->fetch();
}

if ($type == 'getDetails') {
	$data = array();
	$data['id'] = $_POST['eventid'];
	echo $event->getDetails($data);
}