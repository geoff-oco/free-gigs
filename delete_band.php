<?php
  require 'gigs_connect.php';
  
  	redirect_attendee();
    admin_redirect_public();
  
  if (!isset($_GET['band_id']) || !ctype_digit($_GET['band_id']))//check if the band id is set and is a number
  { 
    header("Location: list_bands.php");
    exit;
  }
  
  $stmt = $db->prepare("DELETE FROM band WHERE band_id = ?");
  $result = $stmt->execute( [$_GET['band_id']] );//execute the statement to delete the band
  
  if($result){
	  header("Location: list_bands.php");//redirect to the list of bands
    log_event($db, "Band deleted", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Band deleted with ID: " . htmlentities($_GET['band_id'], ENT_QUOTES, 'UTF-8'));
  }
  else{
	  echo '<p>Something went wrong</p>';
    echo '<a href="list_bands.php></a>"';
    log_event($db, "Failed band deletion", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Failed deletion with ID: " . htmlentities($_GET['band_id'], ENT_QUOTES, 'UTF-8'));
  }
  
?>