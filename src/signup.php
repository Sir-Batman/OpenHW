<?php
//For Debugging
ini_set('display_errors', 'On');
error_reporting(E_ALL);
//End Debugging
session_start();

//Connect to the database
$dbinfo = parse_ini_file("../database.ini");
$db = new mysqli($dbinfo['server'],
			$dbinfo['username'], 
			$dbinfo['password'], 
			$dbinfo['database']);

if( !$db || $db->connect_errno) {
	//echo "There was an error connecting to the database: ".$db->connect_errno." ; ".$db->connect_error;
}
else{
	////echo "*Hacker voice* 'We're in'<BR>";
}

//We are going to accept values, assuming that javascript/jQuery validation for the fields (ie, emails match, and passwords match and are valid regex's)
//Use NULL as the placeholder for the ssid, since it is setup as auto incrementing

//Make user in students table
$query = $db->prepare("INSERT INTO students (lastname, firstname, ssid, email, password, username) VALUES (?, ?, NULL, ?, ?, ?)");
$insertPassword = base64_encode(hash('sha256', $_POST['password'] . "TotesSecureM8"));
$query->bind_param("sssss", $_POST['lname'], $_POST['fname'], $_POST['email'], $insertPassword, $_POST['username']);
$query->execute();

//Get ssid
$ssidquery = $db->prepare("SELECT ssid FROM students where username=?");
$ssidquery->bind_param("s", $_POST['username']);
$ssidquery->execute();

$result = $ssidquery->get_result();
$row = $result->fetch_assoc();
$ssid = $row['ssid'];
//echo "<br>row: ", var_dump($row);

//Setup Assignments and grading
$grading = $db->prepare("INSERT INTO grades (ass_id, ass_t, ssid, grade) VALUES (1, 'Intro to Thermonuclear Physics', ?, 0)");
//echo "<br>grading: ", var_dump($grading);
$grading->bind_param("i", $ssid );
$grading->execute();

$ass1grades = $db->prepare("INSERT INTO assignment1Grades (ssid, q1, q2, q3) VALUES (?, 0, 0, 0)");
$ass1grades->bind_param("i", $ssid);
$ass1grades->execute();

$_SESSION['last_activity'] = $ssid;

header('Location: ../landing.html');
exit;
?>
