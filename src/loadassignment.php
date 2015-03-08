<?php
session_start();
$return_array = array();

if(isset($_SESSION['last_activity'])){
//If the user is logged in, then connect to the server and get a list of assignments
header("content-type:application/json");

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
		}


$ass = $db->query("select * from assignments where ass_id=1");
if($ass->num_rows > 0){
	while($row = $ass->fetch_assoc()){
		//var_dump($row);
		//echo $row["ass_id"]."<br>";
		$row_arr['name'] = $row['ass_t'];
	}
}
else{
	echo "Ass is 0<br>";
}


$q = $db->query("select * from questions where ass_id=1");

if($q->num_rows > 0){
	$row_arr['questions'] = array();

	while($row = $q->fetch_assoc()){
		
		$sol = $db->query("select * from solutions where q_id='{$row['q_id']}'");
		$q_arr['questionname'] = $row['q_id'];
		$q_arr['question']=$row['q_t'];
		//array_push($return_array, $row['q_t']);

		$sol_arr = array();
		while($row_s = $sol->fetch_assoc()){

			array_push($sol_arr, $row_s['sol_t']);
		}
		$q_arr['answers']=$sol_arr;
		//THIS IS HARDCODED
		$q_arr['type']="multiplechoice";
		array_push($row_arr['questions'], $q_arr);
	}

	//the last push
	$return_array['assignment'] = $row_arr;
}
else{
	echo "q is zero<br>";
}

//echo "return array!!!";
//echo "return array: ".$return_array;

mysqli_close($db);
}

else{
	//If the user is not logged in...
	$return_array['logged_in'] = 0;
}
echo json_encode($return_array);
?>
