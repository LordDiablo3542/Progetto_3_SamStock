<?php
	//stringa di connessione, parametri: host, username, password, database
	$con = mysqli_connect("localhost","root","","samtstock","33003");
	
	//se c'è un errore nella connessione lo stampo
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	//invio dati UTF8 al server
	mysqli_query($con,"SET NAMES 'utf8'");
?>
