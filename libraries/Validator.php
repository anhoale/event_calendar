<?php 
class Validator{
	/*
	 * Check required fields
	 * field_array => list of required fields
	 * data => an array of posted values
	 */
	public static function isRequired($field_array, $data){
		foreach($field_array as $field) {
			if ($data[''.$field.''] == '') {
				return false;
			}
		}
		
		return true;
	}
	
	/*
	 * Validate Email field
	 */
	public static function isValidEmail($email){
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			return false;
		}
	}
	/*
	 * Check Password Match
	 */
	public static function passwordsMatch($pw1, $pw2) {
		if ($pw1 == $pw2) {
			return true;
		} else {
			return false;
		}
	}

	/*
	*Check End date larger than Start Date
	*/
	public static function endDateAfterStartDate($start_date, $end_date) {
		if (!empty($start_date) && !empty($end_date)) {
			if (strtotime($end_date) > strtotime($start_date)) {
				return  true;
			}
			else {
				return false;
			}
		}
		
	}
}

?>