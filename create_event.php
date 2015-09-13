<?php 
require('core/init.php');

if (!isset($_POST['type'])) {
	redirect("index.php");
}

//create an Event object
$event = new Event();

$type = $_POST['type'];

if ($type == 'create') {
	$data = array();
	$data['title'] = $_POST['title'];
	$data['start_date'] = $_POST['start'];
	$data['end_date'] = "";
	$data['all_day'] = 'true';
	$data['user_id'] = 0;
	$data['details'] = '';
	echo $event->create($data);
}