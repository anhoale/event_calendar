<?php 
require('core/init.php');

if (!isset($_POST['type'])) {
	redirect("index.php");
}

//create an Event object
$event = new Event();

$type = $_POST['type'];

if ($type == 'remove') {
	$data = array();
	$data['id'] =  $_POST['eventid'];
	echo $event->remove($data);
}