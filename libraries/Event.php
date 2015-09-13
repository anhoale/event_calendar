<?php 
class Event {
	//Init DB Variables
	private $db;
	
	/*
	 * Constructor
	 */
	public function __construct() {
		$this->db = new Database();
	}

	/*
	 * Create Event
	*/
	public function create($data){
		//Insert Query
		$this->db->query("INSERT INTO events (title, user_id, start_date, end_date, all_day, details)
											VALUES (:title, :user_id, :start_date, :end_date, :all_day, :details)");
		//Bind Values
		$this->db->bind(':title', $data['title']);
		$this->db->bind(':user_id', $data['user_id']);
		$this->db->bind(':start_date', $data['start_date']);
		$this->db->bind(':end_date', $data['end_date']);
		$this->db->bind(':all_day', $data['all_day']);
		$this->db->bind(':details', $data['details']);
		//Execute
		if($this->db->execute()){
			//if insert successful
			$last_id = $this->db->lastInsertId();
			return json_encode(array('status'=>'success','eventid'=>$last_id));
		} 
		else {
			return false;
		}
	}


	public function updateDateTime($data){
		//Update Query
		$this->db->query("UPDATE events SET title= :title , start_date = :start_date, end_date = :end_date, all_day = :all_day
						WHERE id = :id ");
		//Bind Values
		$this->db->bind(':title', $data['title']);
		$this->db->bind(':id', $data['id']);
		$this->db->bind(':start_date', $data['start_date']);
		$this->db->bind(':end_date', $data['end_date']);
		$this->db->bind(':all_day', $data['all_day']);
		//Execute
		if($this->db->execute()){
			//if update successful
			return json_encode(array('status'=>'success'));
		} 
		else {
			return false;
		}
	}

	public function updateAll($data){
		//Update Query
		$this->db->query("UPDATE events SET title= :title , start_date = :start_date, end_date = :end_date, all_day = :all_day, details = :details 
						WHERE id = :id ");
		//Bind Values
		$this->db->bind(':title', $data['title']);
		$this->db->bind(':id', $data['id']);
		$this->db->bind(':start_date', $data['start_date']);
		$this->db->bind(':end_date', $data['end_date']);
		$this->db->bind(':all_day', $data['all_day']);
		$this->db->bind(':details', $data['details']);
		//Execute
		if($this->db->execute()){
			//if update successful
			return json_encode(array('status'=>'success', 'eventid'=>$data['id']));
		} 
		else {
			return false;
		}
	}

	/* Remove Date
	*/
	public function remove($data) {
		//delete query
		$this->db->query("DELETE FROM events WHERE id = :id ");
		//Bind Values
		$this->db->bind(':id', $data['id']);
			//Execute
		if($this->db->execute()){
			//if update successful
			return json_encode(array('status'=>'success'));
		} 
		else {
			return false;
		}
	}

	/*
	*Fetch events
	*/
	public function fetch() {
		$events = array();
		$this->db->query("SELECT * FROM events ");
		$results = $this->db->resultset();

		foreach ($results as $event) {
			$e = array();
			$e['id'] = $event->id;
			$e['title'] = $event->title;
			$e['start'] = $event->start_date;
			$e['end'] = $event->end_date;

			$all_day = ($event->all_day == "true") ? true : false;
			$e['allDay'] = $all_day;

			array_push($events, $e);
		}

		return json_encode($events);


	}

	/*
	* Fetch event details
	*/
	public function getDetails($data) {
		$this->db->query("SELECT * FROM events WHERE id = :id");
		//Bind Values
		$this->db->bind(':id', $data['id']);
		
		//Assign Row
		if ($event = $this->db->single()) {
			
			$e = array();
			$e['id'] = $event->id;
			$e['title'] = $event->title;
			$e['start'] = $event->start_date;
			$e['end'] = $event->end_date;

			$all_day = ($event->all_day == "true") ? true : false;
			$e['allDay'] = $all_day;
			$e['details'] = $event->details;
			$e['status'] = "success";

			return json_encode($e);
		}
		else {
			return false;
		}

	}





}