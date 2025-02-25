<?php
	require 'gigs_connect.php';
	
	redirect_attendee();

	if(isset($_POST['submit'])){
		$stmt = $db->prepare("SELECT * FROM admin WHERE username=?");
		$stmt->execute([$_POST['uname']]);
		$user = $stmt->fetch();//fetch the user from the database
		//if the user exists, set the session variables and redirect to list_concerts.php
		if($user && password_verify($_POST['pword'], $user['password'])){
			$_SESSION['username'] = $user['username'];
			$_SESSION['admin_id'] = $user['admin_id'];
			log_event($db, "Admin Login", htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'), "Admin logged in with username: " . htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'));
			header('Location: list_concerts.php');
			exit;
		}
		else{//if the user does not exist, display an error message
			echo 'invalid credentials, try again.';
			log_event($db, "Admin Failed Login","Username doesnt exist", "Admin failed log in with username: " . htmlentities($_POST['uname'], ENT_QUOTES, 'UTF-8'));
		}
	}



?>

<!DOCTYPE html>
<html>
	<head>
		<title>Admin Login</title>
		<meta name="author" content="Geoffrey O'Connell" />
		<meta name="description" content="A form to login for admin" />
		<link rel="stylesheet" href="assignment_styles1.css">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
	</head>
	<body>
	<header>
        <h1>Admin Login</h1>
    </br>
    <p><a href="Index.php">Back to Home</a></p>
    </header>
		<form name="login_form" method="post" action="admin_login.php">
			<p><input type="text" name="uname" placeholder="Username" title="Username" /></p>
			<p><input type="password" name="pword" placeholder="Password" title="Password" /></p>
			<p><input type="submit" name="submit"></p>
		</form>
	</body>

</html>