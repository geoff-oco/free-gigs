<?php
  require 'gigs_connect.php';
  
  redirect_attendee();
  admin_redirect_public();
  welcome_admin();
  
  if (!isset($_GET['band_id']) || !ctype_digit($_GET['band_id']))//check if the band id is set and is a number
  { 
    header("Location: list_bands.php");
    exit;
  }
  
  $stmt = $db->prepare("SELECT * FROM band WHERE band_id = ?");
  $stmt->execute( [$_GET['band_id']] );
  $band = $stmt->fetch();
  
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Band</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to edit a new band" />
	<link rel="stylesheet" href="assignment_styles1.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script>
        function validateForm() {
            //validate the form
            var form = document.forms["edit_band"];
            var name = form.band_name;
            var errorString = "";
            
            
            title.style.backgroundColor = '';
            content.style.backgroundColor = '';
            forum.style.backgroundColor = '';
            //check if the band name is empty
            if (title.value.trim() == "") {
                errorString += "The band needs a name, dingus.\n";
                title.style.backgroundColor = '#FFC8C8';
                validation = false;
            }
            
            //if the form is not valid, show the error message
            if (!validation) {
                alert(errorString);
                return false;
            }
            return true; 
        }
    </script>

</head>

<body>

    <h1>Edit Band</h1>
	<p><a href="javascript:history.back()">Back</a>
    </br>
    <p>Time to change that band name, yeeeow!</p>
    
    <form name="edit_band" method="post" action="edit_band.php" onsubmit="return validateForm()">
        <p><strong>Band Name:</strong></br>
            <textarea name="band_name" style="width: 600px; height: 50px"><?= htmlspecialchars($band['band_name']) ?></textarea>
        </p>
		
		<input type="hidden" id="band_id" name="band_id" value="<?= $band['band_id'] ?>">

        <p>
            <input type="submit" name="submit"/>
        </p>
    </form>
    </br>
    <a href="logout.php">Logout</a>

</body>
</html>