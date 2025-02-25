<?php
	session_start();//start the session
  
  try
  { 
    $db = new PDO('mysql:host=localhost;port=6033;dbname=iwd_assignment', 'root', '');//connect to the database
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);//set the error mode to silent
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  }
  catch (PDOException $e) 
  {
    echo 'Error connecting to database server:<br />';//echo the error message
    echo $e->getMessage();//echo the error message
    exit;//exit the script
  } 

  //event logging
  function log_event($db, $event_type, $username, $event_details){
    $logstmt = $db->prepare("INSERT INTO event_log (event_type, username, ip_address, event_details) VALUES (?, ?, ?, ?)");
    $result = $logstmt->execute([$event_type, $username, $_SERVER['REMOTE_ADDR'], $event_details]);
      
    if(!$result){
      echo $logstmt->errorCode();
    }

  }
  //redirect the attendee to the attendee home page
  function redirect_attendee(){
	if(isset($_SESSION['mobile_num'])){
		header('Location: attendee_home.php');
	}
  }
  //redirect the admin to the list of concerts
  function redirect_admin(){
	if(isset($_SESSION['username'])){
		header('Location: list_concerts.php');
	}
  }

  function attendee_redirect_public(){
    if(!isset($_SESSION['mobile_num'])){
      header('Location: Index.php');
    }
  }

    function admin_redirect_public(){
      if(!isset($_SESSION['username'])){
        header('Location: Index.php');
      }
    }
  
  //welcome the attendee
  function welcome_attendee(){
	if(isset($_SESSION['mobile_num'])){
    echo '<div class="title-section">';
	  echo '<h1>Welcome '.htmlentities($_SESSION['first_name']).' '.htmlentities($_SESSION['last_name']).' </h1>';
    echo '</div>';
	}
	else{
	  header('Location: Index.php');
	}
  }
  //welcome the admin
  function welcome_admin(){
	if(isset($_SESSION['username'])){
    echo '<div class="title-section">';
	  echo '<h1>Welcome '.htmlentities($_SESSION['username']).' </h1>';
    echo '</div>';
	}
	else{
	  header('Location: Index.php');
	}
  }
  
?>