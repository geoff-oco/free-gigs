<?php
  require 'gigs_connect.php';
  
  redirect_attendee();
  admin_redirect_public();
  welcome_admin();
  
  if (!isset($_GET['venue_id']) || !ctype_digit($_GET['venue_id']))//check if the venue id is set and is a number
  { 
    header("Location: list_venues.php");
    exit;
  }
  
  $stmt = $db->prepare("SELECT * FROM venue WHERE venue_id = ?");//prepare the statement to select the venue
  $stmt->execute( [$_GET['venue_id']] );//execute the statement to select the venue
  $venue = $stmt->fetch();//fetch the venue
  
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Venue</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to edit a venue" />
	<link rel="stylesheet" href="assignment_styles1.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script>
        function validateForm() {
            //get the form and the venue name
            var form = document.forms["edit_venue"];
            var name = form.venue_name;
            var capacity = form.capacity;
            var isNumeric = /^[0-9]*$/.test(capacity.value);
            var errorString = "";
            
            //reset the background color
            name.style.backgroundColor = '';
            capacity.style.backgroundColor = '';
            
            if (name.value.trim() == "") {
                errorString += "The venue needs a name, dingus.\n";
                name.style.backgroundColor = '#FFC8C8';
                validation = false;
            }

            if (!isNumeric || capacity.value.trim() == "") {
                errorString += "The venue needs a capacity.\n";
                capacity.style.backgroundColor = '#FFC8C8';
                validation = false;
            }
            
            //validate the form
            if (!validation) {
                alert(errorString);
                return false;
            }
            return true; 
        }
    </script>

</head>

<body>

    <h1>Edit Venue</h1>
	<p><a href="javascript:history.back()">Back</a>
    </br>
    <p>Lets change up that venue name, yeeeow!</p>
    
    <form name="edit_venue" method="post" action="edit_venue.php" onsubmit="return validateForm()">
        <p><strong>Band Name:</strong></br>
            <textarea name="venue_name" style="width: 600px; height: 50px"><?= htmlspecialchars($venue['venue_name']) ?></textarea>
        </p>
        <p><strong>Venue Capacity:</strong><br />
            <textarea name="capacity" style="width: 50px; height: 25px"><?= htmlspecialchars($venue['capacity']) ?></textarea>
      </p>
		</br>
		<input type="hidden" id="venue_id" name="venue_id" value="<?= $venue['venue_id'] ?>">
        </br>
        <p>
            <input type="submit" name="submit"/>
        </p>
    </form>
    </br>  
    <a href="logout.php">Logout</a>

</body>
</html>