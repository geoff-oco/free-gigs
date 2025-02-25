<?php
require 'gigs_connect.php';

redirect_attendee();
admin_redirect_public();

if (isset($_POST['submit']))
{
	$errors = [];//create an array to store the errors
	
  if (trim($_POST['band_name'] == '')) //check if the band name is empty
  {
	  array_push($errors, 'You need to give the band a name dude');
  }
  if($errors){
	  foreach ($errors as $error)//loop through the errors and display them
    {
		    echo '<p>'.$error.'</p>';
	  }
   
    echo '<a href="javascript: window.history.back()">Return to form</a>';
  }
  else{
	$stmt = $db->prepare("INSERT INTO band (band_name) VALUES (?)");//prepare the statement to insert the band name into the database
	$result = $stmt->execute([$_POST['band_name']]);//execute the statement
		
	if($result){
		header("Location: list_bands.php");
    log_event($db, "New Band Created", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Band created with ID: " . htmlentities($_POST['band_id'], ENT_QUOTES, 'UTF-8'). ' Band Name: '.htmlentities($_POST['band_name'], ENT_QUOTES, 'UTF-8'));
	}
	else{
    $sqlerror = $stmt->errorCode();
		echo $sqlerror;
		log_event($db, "Failed to create new band", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Band details failed to be created with errorcode: " .$sqlerror);
		echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
  }
}
}


?>
<!DOCTYPE html>
<html>
  <head>
    <title>New Band Handler</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to handle a newly created band" />
	<link rel="stylesheet" href="assignment_styles1.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  </head>
  <body>
  
  </body>