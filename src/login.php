<?php
session_start();

//Connect to the database
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

$query = $db->prepare("SELECT * FROM students WHERE username = ?");
$query->bind_param("s", $username);

$username = $_POST['username'];
$query->execute();

$result = $query->get_result();

$row = $result->fetch_assoc();
if($username && $row) {
	$checkme = base64_encode(hash('sha256', $_POST['password'] . "TotesSecureM8"));
	if ($row['password'] == $checkme){
		//Log in via sessions
		$_SESSION['last_activity'] = $row['ssid'];

		header('Location: ../landing.html');
		exit;
	}
	else{
		//Invalid combo
		header('Location: ../login.html?fail=1');
	}
}
else{
	//Invalid combo
	header('Location: ../login.html?fail=1');
}

?>




