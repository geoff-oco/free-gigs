<?php
  require 'gigs_connect.php';
  
  redirect_admin();
  attendee_redirect_public();


  $bookings = $db->prepare("SELECT * FROM bookings WHERE mobile_num = ?");
  $bookings->execute([$_SESSION['mobile_num']]);
  $booking_result = $bookings->fetchAll();
  $booking_count = 0;
  $max_attendance = false;
  $already_booked = false;

  if($booking_result){
    $booking_count = count($booking_result);
    if($booking_count >= 2){
      $max_attendance = true;
    }
    foreach($booking_result as $book){
        if($_GET['concert_id'] == $book['concert_id']){
          $already_booked = true;
        }
    }
  }

  //new trial
  $errors = [];
  $concert_id;
  $concert_date;
  $adult;
  $age;

  if (isset($_GET['concert_id'])){
    $concert_id = $_GET['concert_id'];
    if(!ctype_digit($concert_id)){
      array_push($errors,'ID must be numerical');
    }
  } //check if the venue name is empty
  else{
	  array_push($errors,'No concert ID found');
  }

  if (isset($_GET['date'])){
    $concert_date = $_GET['date'];
  } //check if the venue name is empty
  else{
	  array_push($errors,'No date found');
  }

  if (isset($_GET['adult'])){
    $adult = $_GET['adult'];
    if(!ctype_digit($adult) || $adult >= 2 || $adult < 0){
      array_push($errors,'over18 is a numerical boolean');
    }
    if($adult == 1){
      $age = date_diff(date_create($_SESSION['dob']), date_create($_GET['date']))->y;
      if($age < 18){
        array_push($errors,'You are too young to attend this concert');
      }
    }
  } //check if the venue name is empty
  else{
	  array_push($errors,'Is it over 18 or not?');
  }
  if($already_booked == true){
    array_push($errors,'You cannot book for a concert you are already booked into');
  }

  if($errors){
	  foreach ($errors as $error)//loop through the errors and display them
    {
		    echo '<p>'.$error.'</p>';
	  }
   
    echo '<a href="attendee_home.php">Return to form</a>';
  }
  else{
    $stmt = $db->prepare("INSERT INTO bookings (mobile_num, concert_id) VALUES (?, ?)");//prepare the statement to insert the booking into the database
        $result = $stmt->execute([$_SESSION['mobile_num'], $_GET['concert_id']]);//execute the statement
        if($result){
          header("Location: attendee_home.php");
          log_event($db, "New Booking Created", htmlentities($_SESSION['mobile_num'], ENT_QUOTES, 'UTF-8'), "Booking created for concert with ID: " . htmlentities($_GET['concert_id'], ENT_QUOTES, 'UTF-8'));
        }
        else{
          $sqlerror = $stmt->errorCode();
		      echo $sqlerror;
		      log_event($db, "Failed to create new booking", htmlentities($_SESSION['mobile_num'], ENT_QUOTES, 'UTF-8'), "New booking failed to be created with errorcode: " .$sqlerror);
          echo '<p><a href="javascript:history.back()">back to previous page</a></p>';
        }
  }
//end trial
//testing http://localhost:2431/csg2431/assignment/process_booking.php?concert_id=12&date=October%2017%202025%2009:00:00%20AM&adult=1

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Booking handler</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to handle a new attendee booking" />
	<link rel="stylesheet" href="assignment_style1.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  </head>
  <body>
  
  </body>