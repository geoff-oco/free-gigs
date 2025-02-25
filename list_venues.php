<?php
  require 'gigs_connect.php';
  
  redirect_attendee();
  admin_redirect_public();
  welcome_admin();
  
?>
<!DOCTYPE html>
<html>
  <head>
    <title>List Venues</title>
    <meta name="author" content="Greg OConnell" />
    <meta name="description" content="List venues page of free gigs" />
    <link rel="stylesheet" href="assignment_styles1.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script>
        function validateForm() {
            //create a new form object to store the new venue form
            var form = document.forms["new_venue"];
            var name = form.venue_name;
            var capacity = form.capacity;
            var errorString = "";
            var validation = true;
            var isNumeric = /^[0-9]*$/.test(capacity.value);
            //reset the background color of the form
            name.style.backgroundColor = '';
            capacity.style.backgroundColor = '';

            //check if the venue name is empty
            if (name.value.trim() == "") {
                errorString += "The venue needs a name.\n";
                name.style.backgroundColor = '#FFC8C8';
                validation = false;
            }

            if (!isNumeric || capacity.value.trim() == "") {
                errorString += "The venue needs a capacity.\n";
                capacity.style.backgroundColor = '#FFC8C8';
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
    <p><a href="list_bands.php">Bands</a> | <a href="list_concerts.php">Concerts</a></p>
    </br>
    <p><a href="view_logs.php">Event Log</a></p>
    </br>
	<form name="new_venue" method="post" action="new_venue.php" onsubmit="return validateForm()">
        <p><strong>Venue Name:</strong><br />
            <textarea name="venue_name" style="width: 300px; height: 25px"></textarea>
      </p>
      <p><strong>Venue Capacity:</strong><br />
            <textarea name="capacity" style="width: 50px; height: 25px"></textarea>
      </p>
        </br>
        <p>
            <input type="submit" name="submit"/>
        </p>
    </form>
    </br>
    <a href="logout.php">Logout</a>
	</aside>
	<main class="content">
	<h4>Venues</h4>
    <?php
      $stmt = $db->prepare("SELECT * 
                      FROM venue");
      $stmt->execute();
      $result_data = $stmt->fetchAll();
      
      $stmtx = $db->prepare("SELECT venue_id, concert_date 
                      FROM concert");
      $stmtx->execute();
      $venues = $stmtx->fetchAll();
      
      
      if (count($result_data) > 0)
      {
		    echo '<ul>';
        //create a new datetime object to store the current date and time
        $current_datetime = new DateTime();
        
		    $venue_is_booked = false;
        //loop through the result data and display the venue name, edit link, and delete link
        foreach($result_data as $row)
        {
				   echo '<li>'.$row['venue_name'].' Capacity: '.$row['capacity'].' <a href="edit_venue_form.php?venue_id='.$row['venue_id'].'">Edit</a>';
				   //loop through the venues and check if the venue is booked
				   foreach($venues as $booked){
            //create a new datetime object to store the concert date
            $concert_date = new DateTime($booked['concert_date']);
            //check if the venue is booked and the concert date is in the future
						if($booked['venue_id']==$row['venue_id']){
              if($concert_date > $current_datetime){
                $venue_is_booked = true;
              }
						}
					}
					//if the venue is not booked, display the delete link
					if(!$venue_is_booked){
						echo '| <a onclick="return confirm(\'Are you sure you want to delete this venue?\')" href="delete_venue.php?venue_id='.$row['venue_id'].'">Delete</a></li>';
					}
					else{
						echo '</li>';
						$venue_is_booked = false;
					}
        }
		    echo '</ul>';
		    $venuecount = count($result_data);
		    if ($venuecount == 1){
			    echo '<p>There is '.$venuecount.' listed venue in our roster.</p>';
		    }
		    else{
			    echo '<p>There are '.$venuecount.' listed venues in our roster.</p>';
		    }
	  }
      else
      {
        echo '<p>No venues on the roster.</p>';
      }
    ?>
	</main>
	</div>
  </body>
</html>

