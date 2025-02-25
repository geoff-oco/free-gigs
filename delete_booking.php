<?php
  require 'gigs_connect.php';
  
  	redirect_admin();
    attendee_redirect_public();
  
  if (!isset($_GET['booking_id']) || !ctype_digit($_GET['booking_id']))//check if the booking id is set and is a number
  { 
    header("Location: list_bands.php");
    exit;
  }
  
  $stmt = $db->prepare("DELETE FROM bookings WHERE booking_id = ? AND mobile_num = ?");//prepare the statement to delete the booking
  $result = $stmt->execute( [$_GET['booking_id'], $_SESSION['mobile_num']] );//execute the statement to delete the booking
  
  if($result){
	  header("Location: attendee_home.php");
    log_event($db, "Booking deleted", htmlentities($_SESSION['mobile_num'], ENT_QUOTES, 'UTF-8'), "Booking deleted with ID: " . htmlentities($_GET['booking_id'], ENT_QUOTES, 'UTF-8'));
  }
  else{
	  echo '<p>Something went wrong</p>';
    echo '<a href="attendee_home.php></a>"';
    log_event($db, "Failed Booking deletion", htmlentities($_SESSION['mobile_num'], ENT_QUOTES, 'UTF-8'), "Failed to delete booking with ID: " . htmlentities($_GET['booking_id'], ENT_QUOTES, 'UTF-8'));
  }
  
?>