<?php
	session_start(); //inizio della sessione
	
	//include della connessione al database
	include 'mysqlcon.php';
		
	//prendo le variabili dal post del form
	$id = mysqli_real_escape_string($con, $_POST['ID']);
	$nome = mysqli_real_escape_string($con, $_POST['Nome']);
	$cognome = mysqli_real_escape_string($con, $_POST['Cognome']);
	$username = mysqli_real_escape_string($con, $_POST['Username']);
	$password = mysqli_real_escape_string($con, $_POST['Password']);
	$power = mysqli_real_escape_string($con, $_POST['Power']);
	$email = mysqli_real_escape_string($con, $_POST['Email']);
	
	//se non sono vuote
	if(!empty($id) && !empty($nome) && !empty($cognome) && !empty($username) && !empty($password) && !empty($email)){
		if (!mysqli_query($con,"UPDATE utenti SET Nome = '{$nome}', Cognome = '{$cognome}', Username = '{$username}', Password = SHA1('{$password}'), Power = '{$power}', EMail ='{$email}' WHERE ID_Utente = '{$id}'")) { //upadate
			die(header("location: user.php?usenameerr=true")); //se c'è un errore redirect con errore
		}
		mysqli_close($con); //chiudo connessione
		header("location: user.php?update=true"); //redirect con messaggio di conferma
	}
?>
