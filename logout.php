<?php
	//start the session	
	session_start();
	//destroy the session
	session_destroy();
	header('Location: Index.php');
?>