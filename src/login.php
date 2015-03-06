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
//echo "row: ",$row, "<br>";
//echo '<br>QUERY: ', $row['firstname'], ' ', $row['lastname'], $row['password'], '<br>';
//echo "num rows: ", $query->columnCount(), "HHH<br>";
if( $username && ($row) && ($row['password'] == $_POST['password'])  ) {
	//echo "valid pass<br>";
	//echo "bool" , $row['password'] == $_POST['password'], "<br>";
	//echo "db: ", $row['password'], "<br>";
	//echo "post: ", $_POST['password'],"<br>";
	//Log in via sessions
	$_SESSION['last_activity'] = 1;

	//echo "<br>session: ", $_SESSION['last_activity'];
	header('Location: ../landing.html');
	exit;
}
else{
	//echo "Invalid combo";

	header('Location: ../login.html?fail=1');
}

?>




