<?php
  require 'gigs_connect.php';
  
  redirect_admin();
  attendee_redirect_public();
  welcome_attendee();
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo htmlentities($_SESSION['first_name'].' '.$_SESSION['last_name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="author" content="G OConnell" />
    <meta name="description" content="View attendee profile of gigs" />
    <link rel="stylesheet" href="assignment_styles1.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  </head>
  
  
    <body>
		<div class="layout-container">
		<aside class="sidebar">
		<?php
			$dob = new DateTime(htmlentities($_SESSION['dob']));//create a new date time object to store the date of birth
			$age = date_diff($dob, date_create('today'))->y; //trying out datediff function and converting to year
			//display the attendee's name, date of birth, mobile number, and a link to logout
      		echo '<h4>'.htmlentities($_SESSION['first_name'].' '.$_SESSION['last_name']).'</h4>';//display the attendee's name
	  		echo '</br>';
	  		echo '<p>Born in: '.htmlentities($dob->format('d/m/Y')).'</br>';//format the date of birth to dd/mm/yyyy
	  		echo '</br>';
			echo '<p>Age: '.$age.'</p>';
			echo '</br>';
	  		echo '<p>Mobile Number: '.htmlentities($_SESSION['mobile_num']).'</br>';//display the mobile number
	  		echo '</br>';
	  		echo '<a href="logout.php">Logout</a>';
	  		
	  		  $stmt = $db->prepare("SELECT c.concert_id, c.band_id, c.venue_id, b.band_name, v.venue_name, c.adult, v.capacity, DATE_FORMAT(c.concert_date, '%M %d %Y %r') AS formatted_date
                      FROM concert c
                      INNER JOIN band b on c.band_id = b.band_id
					  INNER JOIN venue v on c.venue_id = v.venue_id
                      ORDER BY c.concert_date DESC");

      
      
	  $stmt->execute();
      $result_data = $stmt->fetchAll();//fetch all the concerts from the database
	  
	  $stmtb = $db->prepare("SELECT concert_id
                                    FROM bookings
                                    WHERE mobile_num = ?");//prepare the statement to fetch the concerts the attendee is booked into

					  
	  $stmtb->execute([$_SESSION['mobile_num']]);
	  $bookings = $stmtb->fetchAll();//fetch all the concerts the attendee is booked into

	  $stmtc = $db->prepare("SELECT concert_id, COUNT(*) AS booking_count
                                    FROM bookings
                                    GROUP BY concert_id");
	  $stmtc->execute();
	  $total_bookings = $stmtc->fetchAll();
      
	

      
      if (count($result_data) > 0)
      {    
		//create an array to store the upcoming concerts
		$upcoming_concerts = [];
		$past_concerts = [];
	  }
	  	//loop through the concerts and add them to the upcoming or past concerts array
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
		if (count($result_data) > 0)
		{ 
		  $bookings_count = 0;
		  $max_attendance = false;
		  foreach($bookings as $book){
			  foreach($upcoming_concerts as $upcoming){
				  if($book['concert_id']==$upcoming['concert_id']){
					  $bookings_count++;
					  if($bookings_count >= 2){
						  $max_attendance = true;
					  }
				  }
			  }
		  }
		  if($max_attendance == true){
			  echo '<h2>You are already attending 2 concerts, you cannot attend any more!</h2>';
		  }
    ?>
	</aside>
	<main class="content">
		<h1>Our Upcoming Concerts:</h1>
		<?php
		echo '<ul>';
        //loop through the upcoming concerts and check if the attendee is booked into them
        foreach($upcoming_concerts as $row)
        {	
			$booked_in = false;
			$current_bookings = 0;
			foreach ($total_bookings as $count){
				if($row['concert_id'] == $count['concert_id']){
					$current_bookings = $count['booking_count'];
				}
			}
			foreach($bookings as $gig){
				if($row['concert_id']==$gig['concert_id']){
					$booked_in = true;
				}
			}
			if($max_attendance == true){
				if($row['adult'] == 1){
					if($current_bookings == $row['capacity']){
						echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].' This concert is over 18 only! This concert is fully booked</li>';
						$current_bookings = 0;
						$booked_in = false;
					}
					else{
						echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].' Capacity: ('.$current_bookings.'|'.$row['capacity'].') This concert is over 18 only!</li>';
						$booked_in = false;
						$current_bookings = 0;
					}
				}
				else{
					if($current_bookings == $row['capacity']){
						echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].' This concert is fully booked</li>';
						$booked_in = false;
						$current_bookings = 0;
					}
					else{
						echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].' Capacity: ('.$current_bookings.'|'.$row['capacity'].') </li>';
						$booked_in = false;
						$current_bookings = 0;
					}
				}

			}
			else{
				if($row['adult'] == 1){
					if($current_bookings == $row['capacity']){
						echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].' This concert is over 18 only! Unfortunately this concert is fully booked</li>';
						$booked_in = false;
						$current_bookings = 0;
					}
					else{
						if(!$booked_in){
							echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].' Capacity: ('.$current_bookings.'|'.$row['capacity'].') This concert is over 18 only!<em> <a onclick="return confirm(\'Are you sure you want to make a booking for this gig?\')" href="process_booking.php?concert_id='.$row['concert_id'].'&date='.$row['formatted_date'].'&adult='.$row['adult'].'">Book Now</a></li>';
							$current_bookings = 0;
						}		
						else{
							echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].' Capacity: ('.$current_bookings.'|'.$row['capacity'].') This concert is over 18 only!</li>';
							$booked_in = false;
							$current_bookings = 0;
						}
					}
				}
				else{
					if($current_bookings == $row['capacity']){
						echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].' Unfortunately this concert is fully booked</li>';
						$booked_in = false;
						$current_bookings = 0;
					}
					else{
						if(!$booked_in){
							echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].' Capacity: ('.$current_bookings.'|'.$row['capacity'].') <em> <a onclick="return confirm(\'Are you sure you want to make a booking for this gig?\')" href="process_booking.php?concert_id='.$row['concert_id'].'&date='.$row['formatted_date'].'&adult='.$row['adult'].'">Book Now</a></li>';
							$current_bookings = 0;
						}		
						else{
							echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].' Capacity: ('.$current_bookings.'|'.$row['capacity'].') </li>';
							$booked_in = false;
							$current_bookings = 0;
					}
				}

				}
			}
		}
		echo '</ul></ br>';
		//display the number of upcoming concerts
		$gigcount = count($upcoming_concerts);
		if ($gigcount == 1){
			echo '<p><strong>There is '.$gigcount.' upcoming concert.</strong></p>';
		}
		else{
			echo '<p><strong>There are '.$gigcount.' upcoming concerts.</strong></p>';
		}
	  }
      else
      {
        echo '<p><strong>No concerts coming up.</strong></p>';
      }
    ?>
	<h1>Concerts you are attending:</h1>
	
	<?php
	//prepare the statement to fetch the concerts the attendee is booked into
		$stmt = $db->prepare("SELECT z.booking_id, z.mobile_num, c.concert_id,  b.band_name, v.venue_name, DATE_FORMAT(c.concert_date, '%M %d %Y %r') AS formatted_date
                    FROM bookings z
                    INNER JOIN concert c ON z.concert_id = c.concert_id
                    INNER JOIN band b ON c.band_id = b.band_id
                    INNER JOIN venue v ON c.venue_id = v.venue_id
                    WHERE z.mobile_num = ?
                    ORDER BY c.concert_date DESC");

      
      
	  $stmt->execute([$_SESSION['mobile_num']]);
      $concerts = $stmt->fetchAll();
	
	if (count($concerts) > 0)
      {     
		$bookedgigcount = 0;
		$been_and_gone = false;//create a variable to store if the concert has already played
		echo '<ul>';
        //loop through the concerts and check if the attendee is booked into them
        foreach($concerts as $row)
        {	//
			foreach($past_concerts as $past){//loop through the past concerts and check if it has passed
				if($row['concert_id']==$past['concert_id']){
					$been_and_gone = true;
				}
			}
			if(!$been_and_gone){
				echo '<li>'.$row['band_name'].' playing at '.$row['venue_name'].' on '.$row['formatted_date'].'<em> <a onclick="return confirm(\'Are you sure you want to cancel your attendance at this gig?\')" href="delete_booking.php?booking_id='.$row['booking_id'].'">Cancel</a></li>';
				$bookedgigcount++;
			}
			else{
				$been_and_gone = false;
			}
		}
		echo '</ul></ br>';
		if ($bookedgigcount == 1){
			echo '<p><strong>You are attending '.$bookedgigcount.' upcoming concert.</strong></p>';
		}
		else{
			echo '<p><strong>You are attending '.$bookedgigcount.' upcoming concerts.</strong></p>';
		}
	  }
      else
      {
        echo '<p><strong>No concerts booked.</strong></p>';
      }
	?>
	</main>
	</div>
  </body>
</html>
