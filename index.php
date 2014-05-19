<?php
$today = date("Y-m-d H:i:s") ;
	echo "Fucking Server. $today";
	$file = fopen("./fuck.txt","wr");
	fwrite($file, "fucking");
?>
