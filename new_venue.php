<?php
require 'gigs_connect.php';

	redirect_attendee();
  admin_redirect_public();

if (isset($_POST['submit']))
{
  $errors = [];

  if ($_POST['venue_name'] == '') //check if the venue name is empty
  {
	  array_push($errors,'You need to give the venue a name');;
  }

  if(!ctype_digit($_POST['capacity']) || $_POST['capacity'] == ''){
    array_push($errors,'You need to giv the venue a capacity');
  }

  if($errors){
	  foreach ($errors as $error)//loop through the errors and display them
    {
		    echo '<p>'.$error.'</p>';
	  }
   
    echo '<a href="javascript: window.history.back()">Return to form</a>';
  }
  else{

  	$stmt = $db->prepare("INSERT INTO venue (venue_name, capacity) VALUES (?, ?)");//prepare the statement to insert the venue name into the database
	  $result = $stmt->execute([$_POST['venue_name'], $_POST['capacity']]);//execute the statement
		
	if($result){
    log_event($db, "New Venue Created", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Venue created with ID: " . htmlentities($_POST['venue_id'], ENT_QUOTES, 'UTF-8'). ' Venue Name: '.htmlentities($_POST['venue_name'], ENT_QUOTES, 'UTF-8'));
		header("Location: list_venues.php");
	}
	else{
    $sqlerror = $stmt->errorCode();
		echo $sqlerror;
		log_event($db, "Failed to create new venue", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Venue details failed to be created with errorcode: " .$sqlerror);
		echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
	}
  }
}


?>
<!DOCTYPE html>
<html>
  <head>
    <title>New Venue Handler</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to handle a newly created venue" />
	<link rel="stylesheet" href="assignment_styles1.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  </head>
  <body>
  
  </body>