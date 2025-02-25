<?php
  require 'gigs_connect.php';
  
  	redirect_attendee();
    admin_redirect_public();
  
  if (!isset($_GET['venue_id']) || !ctype_digit($_GET['venue_id']))//check if the venue id is set and is a number
  { 
    header("Location: list_venues.php");
    exit;
  }
  
  $stmt = $db->prepare("DELETE FROM venue WHERE venue_id = ?");//prepare the statement to delete the venue
  $result = $stmt->execute( [$_GET['venue_id']] );//execute the statement to delete the venue
  
  if($result){
	  header("Location: list_venues.php");//redirect to the list of venues
    log_event($db, "Venue deleted", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Deleted Venue with ID: " . htmlentities($_GET['venue_id'], ENT_QUOTES, 'UTF-8'));
  }
  else{
	  echo '<p>Something went wrong</p>';
    echo '<a href="list_venues.php></a>"';
    log_event($db, "Venue deleted fail", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Failed to delete Venue with ID: " . htmlentities($_GET['venue_id'], ENT_QUOTES, 'UTF-8'));
  }
  
?>