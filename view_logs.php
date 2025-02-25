
<?php
	require 'gigs_connect.php';

  redirect_attendee();
  admin_redirect_public();
  welcome_admin();
	
		
        echo '<h1>Event Logs</h1>';
        echo '<p><a href="list_concerts.php">Concerts</a> | <a href="list_venues.php">Venues</a> | <a href="list_bands.php">Venues</a></p>';
        echo '<p><a href="logout.php">Logout</a></p>';
        echo '<table>';
        echo '<tr><th>log ID</th><th>Date</th><th>Event Type</th><th>Username</th><th>IP Address</th><th>Event Details</th></tr>';
        $stmt = $db->prepare("SELECT * FROM event_log");
        $stmt->execute();
        $logs = $stmt->fetchAll();
        foreach($logs as $log){
            echo '<tr><td>'.$log['log_id'].'</td><td>'.$log['log_date'].'</td><td>'.$log['event_type'].'</td><td>'.$log['username'].'</td><td>'.$log['ip_address'].'</td><td>'.$log['event_details'].'</td></tr>';
        }
        echo '</table>';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>View System Logs</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form for admins to view the system logs" />
	<link rel="stylesheet" href="assignment_styles1.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  </head>
  <body>
  
  </body>