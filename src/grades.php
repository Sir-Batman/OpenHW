<?php
//For Debugging
//ini_set('display_errors', 1);
error_reporting(E_ALL);
//End Debugging

session_start();
$return_array = array();

if(isset($_SESSION['last_activity'])){
//If the user is logged in, then connect to the server and get a list of grades
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
		//echo "session: ",$_SESSION['last_activity'];
		$return_array['logged_in'] = 1;	

		$grades= array();

		$query = $db->prepare("SELECT * FROM grades WHERE ssid=?");
		$query->bind_param("i", $ssid);
		$ssid = $_SESSION['last_activity'];
		$query->execute();
		$result = $query->get_result();
		//echo "result: ", var_dump($result);
		//$row = $result->fetch_assoc();
		//echo "<br>row: ", var_dump($row);
		while ($row = $result->fetch_assoc()){
			//echo "<br>in whle";
			//echo "<br>assid: ", $row['ass_id'];
			$grades[$row['ass_t']] = $row['grade'];
		}
		$return_array['grades'] =  $grades;

	}

}
else{
	$return_array['logged_in'] = 0;
}
echo json_encode($return_array);
?>
