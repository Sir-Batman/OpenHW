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
//echo '<br>QUERY: ', $row['firstname'], ' ', $row['lastname'], $row['password'];

if( $row['password'] == $_POST['password'] ) {

	//Log in via sessions
	$_SESSION['last_activity'] = 1;

	//echo "<br>session: ", $_SESSION['last_activity'];
	header('Location: ../landing.html');
	exit;
}
else{

	header('Location: ../login.html?fail=1');
}

?>




