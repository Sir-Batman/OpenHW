<?php
//For Debugging
ini_set('display_errors', 'On');
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
		$return_array['logged_in'] = 1;	


		$query = $db->prepare("SELECT * FROM solutions WHERE  ass_id=? AND q_id=? AND sol_t=?");
		$query->bind_param("iis", $assid, $qid,  $solt);

		$assid = $_POST['ass_id'];
		$qid = $_POST['q_id'];
		$solt = $_POST['sol_t'];

		$query->execute();
		$result = $query->get_result();
		$row = $result->fetch_assoc();
		echo "assid: ", $row['ass_id'],"<br>";

		echo $_SESSION['last_activity'],"<br>";


		$questionid = 'q' . $qid;
		$questionid = $db->real_escape_string($questionid);

		$gradequery = $db->prepare("UPDATE assignment1Grades SET ". $questionid ."=? WHERE ssid=?");
		echo  "vardump: ",var_dump($gradequery);
		$gradequery->bind_param("ii", $grade,  $ssid);
		$ssid = $_SESSION['last_activity'];
		if($row['sol_true'] == 1){
			//Their answer is right!
			$grade = 5;
		}
		else{
			$grade = 0;
		}
		$gradequery->execute();
		

	}

}
else{
	$return_array['logged_in'] = 0;
}
echo json_encode($return_array);
?>
