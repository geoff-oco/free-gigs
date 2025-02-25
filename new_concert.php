<?php
require 'gigs_connect.php';

	redirect_attendee();
  admin_redirect_public();

if (isset($_POST['submit']))
{
  $errors = [];
  $adulting = 0;
  $bad_date = false;
	$current_datetime = new DateTime();
	
  if ($_POST['band_name'] == '') //check if the band name is empty
  {
	  array_push($errors,'You need to select a band');;
  }

  if ($_POST['venue_name'] == '') 
  {
	  array_push($errors,'You need to select a venue');;
  }

  if ($_POST['concert_date'] == '') //check if the concert date is empty
  {
	  array_push($errors,'You need to select a date');
    $bad_date = true;
  }

  if($bad_date == false){
    $sql_datetime = new DateTime($_POST['concert_date']);
    if($current_datetime >= $sql_datetime) {
      array_push($errors,'You cannot make a concert in the past');
    }

  }

  if(isset($_POST['adult'])){
    $adulting = 1;
  }

  if($errors){
	  foreach ($errors as $error)//loop through the errors and display them
    {
		    echo '<p>'.$error.'</p>';
	  }
   
    echo '<a href="javascript: window.history.back()">Return to form</a>';
  }
	else{
  	$stmt = $db->prepare("INSERT INTO concert (band_id, venue_id, concert_date, adult) VALUES (?, ?, ?, ?)");//prepare the statement to insert the concert into the database
	  $result = $stmt->execute([$_POST['band_name'] ,$_POST['venue_name'], $_POST['concert_date'], $adulting]);//execute the statement
		
	if($result){
		header("Location: list_concerts.php");
    log_event($db, "New Concert Created", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Concert created with band ID: " . htmlentities($_POST['band_id'], ENT_QUOTES, 'UTF-8'). ' and venue ID: '.htmlentities($_POST['venue_id'], ENT_QUOTES, 'UTF-8')." on date: " . htmlentities($_POST['concert_date'], ENT_QUOTES, 'UTF-8')." with over 18 code: " . $adulting);
	}
	else{
    $sqlerror = $stmt->errorCode();
		echo $sqlerror;
		log_event($db, "Failed to create new concert", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Concert details failed to be created with errorcode: " .$sqlerror);
		echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
	}
  }

}


?>
<!DOCTYPE html>
<html>
  <head>
    <title>New Concert Handler</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to handle a newly created concerts" />
	<link rel="stylesheet" href="assignment_styles1.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  </head>
  <body>
  
  </body>