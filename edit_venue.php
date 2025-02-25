<?php
require 'gigs_connect.php';

	redirect_attendee();
	admin_redirect_public();

if (isset($_POST['submit']))//check if the form is submitted
{
	$stmt_check = $db->prepare("SELECT c.concert_id, COUNT(b.booking_id) AS booking_count
							FROM bookings b
							JOIN concert c ON b.concert_id = c.concert_id
							JOIN venue v ON c.venue_id = v.venue_id
							WHERE v.venue_id = ?
							GROUP BY c.concert_id
							ORDER BY booking_count DESC");

	$stmt_check->execute([$_POST['venue_id']]);
	$check_result = $stmt_check->fetchAll();

	$max_booking = 0;

	foreach($check_result as $check){
		if($check['booking_count'] > $max_booking){
			$max_booking = $check['booking_count'];
		}
	}

	$errors = [];

	if ($_POST['venue_name'] == '') //check if the venue name is empty
	{
		array_push($errors,'You need to give the venue a name');;
	}
  
	if(!ctype_digit($_POST['capacity']) || $_POST['capacity'] == ''){
	  array_push($errors,'You need to give the venue a capacity');
	}
	else{
		if($_POST['capacity'] < $max_booking){
			array_push($errors,'You cant reduce the capacity lower than whats already booked');
		}
	}
  
	if($errors){
		foreach ($errors as $error)//loop through the errors and display them
	  {
			  echo '<p>'.$error.'</p>';
		}
	 
	  echo '<a href="javascript: window.history.back()">Return to form</a>';
	}
	else{
		$stmt = $db->prepare("UPDATE venue SET venue_name = ?, capacity = ? WHERE venue_id = ?");//prepare the statement to update the venue
		$result = $stmt->execute([$_POST['venue_name'], $_POST['capacity'], $_POST['venue_id']]);//execute the statement to update the venue
			
		if($result){
			header("Location: list_venues.php");
			log_event($db, "Band details edited", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Venue details edited with ID: " . htmlentities($_POST['venue_id'], ENT_QUOTES, 'UTF-8'). ' Venue Name changed to '.htmlentities($_POST['venue_name'], ENT_QUOTES, 'UTF-8'));
		}
		else{
			$sqlerror = $stmt->errorCode();
			echo $sqlerror;
			log_event($db, "Failed to edit band details", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Band details failed to be edited with errorcode: " .$sqlerror);
			echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
		}
	}
}



?>
<!DOCTYPE html>
<html>
  <head>
    <title>Edit Venue Handler</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to handle a edited venue" />
	<link rel="stylesheet" href="assignment_styles1.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  </head>
  <body>
  
  </body>