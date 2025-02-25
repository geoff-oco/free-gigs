<?php
  require 'gigs_connect.php';
  
  redirect_attendee();
  admin_redirect_public();
  welcome_admin();
  
?>
<!DOCTYPE html>
<html>
  <head>
    <title>List Bands</title>
    <meta name="author" content="Geoff OConnell" />
    <meta name="description" content="List bands page of free gigs" />
    <link rel="stylesheet" href="assignment_styles1.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
	<script>
        function validateForm() {
            //create a new form object to store the new band form
            var form = document.forms["new_band"];
            var name = form.band_name;
            var errorString = "";
            var validation = true;
            //reset the background color of the form
            name.style.backgroundColor = '';
            
            //if the band name is empty, display an error message
            if (name.value.trim() == "") {
                errorString += "The band needs a name, dingus.\n";
                name.style.backgroundColor = '#FFC8C8';
                validation = false;
            }
            
            //if the validation is not valid, display an error message
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
    <p><a href="list_venues.php">Venues</a> | <a href="list_concerts.php">Concerts</a></p>
    </br>
    <p><a href="view_logs.php">Event Log</a></p>
    </br>
	<form name="new_band" method="post" action="new_band.php" onsubmit="return validateForm()">
        <p><strong>Add New Band:</strong><br />
            <textarea name="band_name" style="width: 300px; height: 25px"></textarea>
        </p>
        </br>
        <p>
            <input type="submit" name="submit" value="Add Band"/>
        </p>
      </form>
      </br>
    <a href="logout.php">Logout</a>
	</aside>
	<main class="content">
	<h4>Bands</h4>
    <?php
    //prepare the statement to select the bands
      $stmt = $db->prepare("SELECT * 
                      FROM band");
      $stmt->execute();
      $result_data = $stmt->fetchAll();
	  //prepare the statement to select the concerts
      $stmtx = $db->prepare("SELECT band_id, concert_date 
                      FROM concert");
      $stmtx->execute();
      $players = $stmtx->fetchAll();
      
      //if the result data is not empty, display the bands
      if (count($result_data) > 0)
      {
        $current_datetime = new DateTime();//create a new datetime object to store the current date and time
		    echo '<ul>';
		    $band_is_performing = false;//create a variable to store the band is performing
        //loop through the result data and display the bands
        foreach($result_data as $row)
        {
				  echo '<li>'.$row['band_name'].' <a href="edit_band_form.php?band_id='.$row['band_id'].'">Edit</a>';
				//loop through the players and check if the band is performing
				  foreach($players as $performing){
					  $concert_date = new DateTime($performing['concert_date']);
					//if the band is performing, display the edit and delete links
					  if($performing['band_id']==$row['band_id'] && $concert_date > $current_datetime){
						  $band_is_performing = true;
					  }
				  }
				//if the band is not performing, display the delete link
				  if(!$band_is_performing){
					  echo '| <a onclick="return confirm(\'Are you sure you want to delete this band?\')" href="delete_band.php?band_id='.$row['band_id'].'">Delete</a></li>';
				  }
				//if the band is performing, display the edit link
				  else{
					  echo '</li>';
					  $band_is_performing = false;
				}
        }
		echo '</ul>';
		$bandcount = count($result_data);
		if ($bandcount == 1){
			echo '<p>There is '.$bandcount.' listed band in our roster.</p>';
		}
		else{
			echo '<p>There are '.$bandcount.' listed bands in our roster.</p>';
		}
	  }
      else
      {
        echo '<p>No bands on the roster.</p>';
      }
    ?>
	</main>
	</div>
  </body>
</html>
