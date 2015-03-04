<?php
//For Debugging
//ini_set('display_errors', 1);
error_reporting(E_ALL);
//End Debugging

session_start();
$return_array = array();

if(isset($_SESSION['last_activity'])){
//If the user is logged in, then connect to the server and get a list of assignments
	$dbinfo = parse_ini_file("../database.ini");
	$db = new mysqli($dbinfo['server'],
				$dbinfo['username'], 
				$dbinfo['password'], 
				$dbinfo['database']);

	if( !$db || $db->connect_errno) {
		echo "There was an error connecting to the database: ".$db->connect_errno." ; ".$db->connect_error;
	}
	else{
		//echo "*Hacker voice* 'We're in'<BR>";
		$return_array['logged_in'] = 1;	

		$ass_numbers = array();
		//Get the list of assignment id numbers and append it to the return array
		$query = $db->query("SELECT ass_id FROM assignments");
		while($row = $query->fetch_assoc()){
			array_push($ass_numbers, $row['ass_id']);
		}

		$return_array['assignments'] = $ass_numbers;


	}

}
else{
	$return_array['logged_in'] = 0;
}
echo json_encode($return_array);
?>
