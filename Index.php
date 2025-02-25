<?php
  require 'gigs_connect.php';
  
	redirect_admin();
	redirect_attendee();

	if(isset($_POST['submit'])){
		$stmt = $db->prepare("SELECT * FROM attendee WHERE mobile_num=?");//prepare the statement to select the attendee
		$stmt->execute([$_POST['mobile']]);//execute the statement to select the attendee
		$user = $stmt->fetch();//fetch the attendee
		
		if($user && password_verify($_POST['pword'], $user['password'])){//if the attendee is found
			$_SESSION['mobile_num'] = $user['mobile_num'];//set the session variable to the attendee's mobile number
			$_SESSION['first_name'] = $user['first_name'];//set the session variable to the attendee's first name
			$_SESSION['last_name'] = $user['last_name'];//set the session variable to the attendee's last name
			$_SESSION['dob'] = $user['dob'];//set the session variable to the attendee's date of birth
			log_event($db, "Attendee Login", htmlentities($_SESSION['mobile_num'], ENT_QUOTES, 'UTF-8'), "Attendee logged in with mobile number: " . htmlentities($_SESSION['mobile_num'], ENT_QUOTES, 'UTF-8'));
			header('Location: attendee_home.php');//redirect the attendee to the attendee home page
			exit;
		}
		else{
			echo '<p class="error-message">invalid credentials, try again.</p>';
			log_event($db, "Attendee Failed Login","Attendee doesnt exist", "Attendee failed log in with mobile number: " . htmlentities($_POST['mobile'], ENT_QUOTES, 'UTF-8'));
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Free-Gigs Homepage</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A homepage for free gigs" />
	    <link rel="stylesheet" href="assignment_styles1.css">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

    <h1>Welcome to Free-Gigs</h1>

	<div class="layout-container">
	<aside class="sidebar">
    <form method="post" name="login_form" action="Index.php">
        <fieldset><legend>Login</legend>
			<em>Log in here to start booking your attendance to our amazing free concerts and events!</em>
            <p><input type="text" name="mobile" placeholder="Mobile Phone" title="Mobile Phone" /></p>
            <p><input type="password" name="pword" placeholder="Password" title="Password" /></p>
			<p><input type="submit" name="submit" value= "Log In"></p>
			<p>Click <a href="register_att.php">here</a> to register</p>
			</br>
			<a href="admin_login.php">Admin Login</a>
        </fieldset>
    </form>
	</aside>

	<main class="content">
	<h1>Our Upcoming Concerts:</h1>
	   <?php
      //prepare the statement to select the concerts
        $stmt = $db->prepare("SELECT c.concert_id, c.band_id, c.venue_id, b.band_name, v.venue_name, DATE_FORMAT(c.concert_date, '%M %d %Y %r') AS formatted_date
                      FROM concert c
                      INNER JOIN band b on c.band_id = b.band_id
					  INNER JOIN venue v on c.venue_id = v.venue_id
                      ORDER BY c.concert_date DESC");

      
      
	  $stmt->execute();
      $result_data = $stmt->fetchAll();
	  
	  if (count($result_data) > 0)
      {    
		$upcoming_concerts = [];//create an array to store the upcoming concerts
	  }

  //loop through the result data and add the upcoming concerts to the array
		foreach($result_data as $row) {
			$sql_datetime = new DateTime($row['formatted_date']);
			$current_datetime = new DateTime();

			if($sql_datetime >= $current_datetime) {
				$upcoming_concerts[] = $row;
			}
		}
      
      //if the upcoming concerts array is not empty, display the concerts
      if (count($upcoming_concerts) > 0)
      {      
		echo '<ul>';
        
        foreach($upcoming_concerts as $row)
        {
				echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].'</li>';

        }
		echo '</ul></ br>';
		$gigcount = count($upcoming_concerts);
		if ($gigcount == 1){
			echo '<p><strong>There is '.$gigcount.' upcoming concert.</strong></p>';
		}
		else{
			echo '<p><strong>There are '.$gigcount.' upcoming concerts.</strong></p>';
		}
	  }
      //if the upcoming concerts array is empty, display a message
      else
      {
        echo '<p><strong>No concerts coming up.</strong></p>';
      }
    ?>
	</main>
	</div>
</body>
</html>