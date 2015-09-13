<?php 
require('core/init.php');

if (!isset($_POST['type'])) {
	redirect("index.php");
}

//create an Event object
$event = new Event();

$type = $_POST['type'];

switch ($type) {
	case 'updateAll':
		$message  = '';

		$data = array();
		$data['id'] = $_POST['eventid'];
		$data['title'] = $_POST['title'];
		$data['start_date'] = $_POST['start'];
		$data['end_date'] = $_POST['end'];
		$data['all_day'] = $_POST['allDay'];
		$data['details'] = trim($_POST['details']);
		/*
		* Process validation
		*/
		//Required Fields
		$field_array = array("title", "start_date");
		if (Validator::isRequired($field_array, $data) == false) {
			$message .= 'Please fill in all required fields (Event Title, and Event Start Date).';
		}

		if (!empty($data['end_date']) && Validator::endDateAfterStartDate($data['start_date'], $data['end_date']) == false) {
			$message .= 'End Date have to be after Start Date.';
		}

		if (!empty($message)) {
			echo json_encode(array('status'=>'error', 'error_message' => $message));
		}
		else {
			//No error message, Validation passed, proceed updating Event Details
			echo $event->updateAll($data);
		}

		break;

	case 'updateDateTime':
		$data = array();
		$data['id'] = $_POST['eventid'];
		$data['title'] = $_POST['title'];
		$data['start_date'] = $_POST['start'];
		$data['end_date'] = $_POST['end'];
		$data['all_day'] = $_POST['allDay'];

		echo $event->updateDateTime($data);

		break;
	default:
		echo json_encode(array('status'=>'error', 'error_message' => 'Process type cannot be identified.'));

}

