<?php
	session_start(); //inizio della sessione
	
	//include della connessione al database
	include 'mysqlcon.php';
		
	//prendo le variabili dal post del form
	$id = mysqli_real_escape_string($con, $_POST['ID']);
	$limite = mysqli_real_escape_string($con, $_POST['limite']);
	
	//se non sono vuote
	if(!empty($id) && isset($limite)){
		if (!mysqli_query($con,"UPDATE prodotti SET Limite = '{$limite}' WHERE ID_Prodotto = '{$id}'")) { //update
			die(header("location: limiter.php?error=true")); //se c'è un errore redirect con errore
		}
		mysqli_close($con); //chiudo connessione
		header("location: limiter.php?update=true"); //redirect con messaggio di conferma
	}
?>
