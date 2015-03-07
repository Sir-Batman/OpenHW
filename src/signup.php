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

//We are going to accept values, assuming that javascript/jQuery validation for the fields (ie, emails match, and passwords match and are valid regex's)
//Use NULL as the placeholder for the ssid, since it is setup as auto incrementing
$query = $db->prepare("INSERT INTO students (lastname, firstname, ssid, email, password, username) VALUES (?, ?, NULL, ?, ?, ?)");
$insertPassword = base64_encode(hash('sha256', $_POST['password'] . "TotesSecureM8"));
$query->bind_param("sssss", $_POST['lname'], $_POST['fname'], $_POST['email'], $insertPassword, $_POST['username']);

$query->execute();
header('Location: ../landing.html');
exit;
?>
