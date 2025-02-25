<?php
require 'gigs_connect.php';

	redirect_admin();
	redirect_attendee();

  $existing = $db->prepare("SELECT mobile_num 
                FROM attendee");
  $existing->execute();
  $existing_mobiles = $existing->fetchAll();


if (isset($_POST['submit']))
{

  $errors = [];
  $mobile_bad = false;


  
 
  if (!ctype_digit($_POST['mobile']) || strlen($_POST['mobile']) != 10)//check if the mobile number is 10 numbers only
  {
    array_push($errors,'Mobile must be 10 digits only');
    $mobile_bad = true;
  }
  
  if($mobile_bad == false){
    foreach($existing_mobiles as $row){
      if($row['mobile_num'] == $_POST['mobile']){
        array_push($errors,'That mobile already exists, you may only register once with the same mobile');
      }
    }
  }

  if ($_POST['firstName'] == '' || !ctype_alpha($_POST['firstName']))
  {
    array_push($errors,'You must have a first name of only letters.');
  }
  
 
  if ($_POST['lastName'] == '' || !ctype_alpha($_POST['lastName']))//check if the last name is only letters
  {
    array_push($errors,'You must have a last name of only letters.');
  }
  

  if ($_POST['pword'] == '' || strlen($_POST['pword']) < 5 )//check if the password is at least 5 characters
  {
    array_push($errors,'Password must be at least 5 characters.');
  }
  
 
  if ($_POST['pword'] != $_POST['pword_conf'])//check if the password and confirmation match
  {
    array_push($errors,'Password does not match confirmation');
  }
  
  
  if (empty($_POST['dob']))
  {
    array_push($errors,'Date of Birth cannot be empty');
  }

  
 
  if ($errors)
  { 
    foreach ($errors as $error)//loop through the errors and display them
    {
      echo '<p>'.$error.'</p>';
    }
   
    echo '<a href="javascript: window.history.back()">Return to form</a>';
  }
  else
  { 
    $hash_pword = password_hash($_POST['pword'], PASSWORD_DEFAULT);

	  $stmt = $db->prepare("INSERT INTO attendee (mobile_num, first_name, last_name, dob, password) VALUES (?, ?, ?, ?, ?)");//prepare the statement to insert the attendee into the database
	  $result = $stmt->execute([$_POST['mobile'], $_POST['firstName'], $_POST['lastName'], $_POST['dob'], $hash_pword]);//execute the statement
	
	if($result){
		header("Location: Index.php");
    log_event($db, "New Attendee Account Created", "new user", "User created with mobile_num: " . htmlentities($_POST['mobile'], ENT_QUOTES, 'UTF-8')." Name of : " . htmlentities($_POST['firstName'], ENT_QUOTES, 'UTF-8')." " . htmlentities($_POST['lastName'], ENT_QUOTES, 'UTF-8')." DOB: " . htmlentities($_POST['dob'], ENT_QUOTES, 'UTF-8'));
	}
	else{
    $sqlerror = $stmt->errorCode();
		echo $sqlerror;
		log_event($db, "Failed to create new attendee", "public user", "New Attendee failed to be created with errorcode: " .$sqlerror);
		echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
	}
	
  }
}
else
{ 
  echo 'Please submit the <a href="register_att.php">form</a>.';
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Registration Handler</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to handle a new attendee registration" />
	<link rel="stylesheet" href="assignment_style1.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  </head>
  <body>
  
  </body>