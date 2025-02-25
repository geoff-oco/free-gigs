<?php
  require 'gigs_connect.php';
  
  redirect_attendee();
  admin_redirect_public();
  welcome_admin();

?>
<!DOCTYPE html>
<html>
  <head>
    <title>List Concerts</title>
    <meta name="author" content="Geoff Oconnell" />
    <meta name="description" content="List concerts page of free gigs" />
    <link rel="stylesheet" type="text/css" href="assignment_styles1.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script>
        function validateForm() {
            //create a new form object to store the new concert form
            var form = document.forms["new_concert"];
            var band = form.band_name;
            var venue = form.venue_name;
            var concert = form.concert_date;
            var errorString = "";
            var validation = true;
            
            //reset the background color of the form
            band.style.backgroundColor = '';
            venue.style.backgroundColor = '';
            concert.style.backgroundColor = '';
            
            if (band.value == "") {
                errorString += "Aint no show with no band.\n";
                band.style.backgroundColor = '#FFC8C8';
                validation = false;
            }
				
            if (venue.value == "") {
                errorString += "The band needs a place to play.\n";
                venue.style.backgroundColor = '#FFC8C8';
                validation = false;
            }
				
            if (concert.value == "") {
                errorString += "You must select a date.\n";
                concert.style.backgroundColor = '#FFC8C8';
                validation = false;
            }
            
            if (!validation) {
                alert(errorString);
                return false;
            }
            return true; 
        }
    </script>
  </head>

  <body>
  	<div class="layout-container">
	<aside class="sidebar">
    <p><a href="list_bands.php">Bands</a> | <a href="list_venues.php">Venues</a></p>
    </br>
    <p><a href="view_logs.php">Event Log</a></p>
    </br>
	    <form name="new_concert" method="post" action="new_concert.php" onsubmit="return validateForm()">
        <p><strong>Select Band:</strong><br />
            <select name="band_name">
                <option value="" selected disabled>Select a Band</option>
                <?php  
            
					$result = $db->query("SELECT * FROM band ORDER BY band_id");
      
            
					foreach($result as $row)
					{
						echo '<option value="'.$row['band_id'].'">'.$row['band_name'].'</option>';
					}
          ?>
            </select>
        </br>
		        <p><strong>Select Venue:</strong></br>
            <select name="venue_name">
                <option value="" selected disabled>Select a Venue</option>
                <?php  
            
					$result = $db->query("SELECT * FROM venue ORDER BY venue_id");
      
           
					foreach($result as $row)
					{
						echo '<option value="'.$row['venue_id'].'">'.$row['venue_name'].'</option>';
					}
          ?>
            </select>
            </br>
            <p><strong>Select A Date:</strong><br />
			<input type="datetime-local" name="concert_date">
      <label>
        <input type="checkbox" id="adult" name="adult">
        Is the concert over 18?
    </label>
        <p>
            <input type="submit" name="submit"/>
        </p>
    </form>
    </br>
    <a href="logout.php">Logout</a>
	</aside>
    <?php
      $stmt = $db->prepare("SELECT c.concert_id, b.band_id, b.band_name, v.venue_id, v.venue_name, DATE_FORMAT(c.concert_date, '%M %d %Y %r') AS formatted_date   
                      FROM concert c
                      INNER JOIN band b ON c.band_id = b.band_id
                      INNER JOIN venue v ON c.venue_id = v.venue_id");
      $stmt->execute();
      $result_data = $stmt->fetchAll();
	  
	  if (count($result_data) > 0)
      {    
        //create a new array to store the upcoming concerts
		    $upcoming_concerts = [];
        //create a new array to store the past concerts
		    $past_concerts = [];
	  }
  //loop through the result data and check if the concert date is in the past or upcoming
		foreach($result_data as $row) {
			$sql_datetime = new DateTime($row['formatted_date']);
			$current_datetime = new DateTime();

			if($sql_datetime >= $current_datetime) {
				$upcoming_concerts[] = $row;
			}
			else {
				$past_concerts[] = $row;
			}
		}
      ?>
	  <main class="content">
	  <h1>Past Concerts</h1>
	  <?php
	if (count($past_concerts) > 0)
      {
		    echo '<ul>';
        //loop through the past concerts and display the band name, venue name, and formatted date
        foreach($past_concerts as $row)
        {
				echo '<li>'.$row['band_name'].' played at '.$row['venue_name'].' on '.$row['formatted_date'];
        }
		    echo '</ul>';
		    $pastconcertcount = count($past_concerts);
		    if ($pastconcertcount == 1){
			    echo '<p>There is '.$pastconcertcount.' listed concert that has played in the past.</p>';
		    }
		    else{
			echo '<p>There are '.$pastconcertcount.' listed concerts that have played in the past.</p>';
		}
	  }
      else
      {
        echo '<p>No concerts on the roster that have played in the past.</p>';
      }
	  ?>
	  <h1>Upcoming Concerts</h1>
	  <?php
      //if the upcoming concerts array is not empty, display the concerts
      if (count($upcoming_concerts) > 0)
      {
		    echo '<ul>';
        //loop through the upcoming concerts and display the band name, venue name, and formatted date
        foreach($upcoming_concerts as $row)
        {
				   echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'];
        }
		    echo '</ul>';
		    $upcomingconcertcount = count($upcoming_concerts);
		    if ($upcomingconcertcount == 1){
			    echo '<p>There is '.$upcomingconcertcount.' listed concert in our roster.</p>';
		    }
		    else{
			    echo '<p>There are '.$upcomingconcertcount.' listed concerts in our roster.</p>';
		    }
	  }
      else
      {
        echo '<p>No concerts on the roster.</p>';
      }
    ?>
	</main>
	</div>
  </body>
</html>

