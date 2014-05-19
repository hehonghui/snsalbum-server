<?php 
    session_start();
    echo $_SESSION['temp'];
	session_destroy();
?>
