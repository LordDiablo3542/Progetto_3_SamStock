<?php
	/*
	 * Codice per cambiare la password
	 */

	session_start(); //inizio della sessione
	
	//include della connessione al database
	include 'mysqlcon.php';
	
	//regex password: Lunghezza 8 caratteri, un numero, una miniuscola, una maiuscula e un simbolo
	$regexpass = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/';
	
	//prendo le variabili dal post del from e la variabile di sessione che ho salvato al momento del log in
	$password1 = mysqli_real_escape_string($con, $_POST['password1']);
	$password2 = mysqli_real_escape_string($con, $_POST['password2']);
	$username = $_SESSION['username'];
	
	//controllo se la password rispetta i requisti minimi
	if(preg_match($regexpass, $password1)){
		if((strcmp($password1, $password2) == 0)){
			//query per l'update della password dell'utente
			mysqli_query($con,"UPDATE utenti SET password = SHA1('{$password1}') WHERE username = '{$username}'");
			mysqli_close($con);
			header("location: logout.php?change=true"); //stampo messaggio di cambio password fatto
		}else{
			header("location: cambiapw.php?change=false"); //stampo errore di password non uguali
		}
	}else {header("location: cambiapw.php?pass=false");} //stampo errore per la password con formatazione sbagliata
?>
