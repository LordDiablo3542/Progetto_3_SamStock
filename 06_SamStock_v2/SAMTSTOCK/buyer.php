<?php
	/*
	 * Codice per la riservazione da parte di un utente.
	 */

	session_start(); //inizio della sessione
	
	$username = $_SESSION['username']; //prendo il nome utente
	$email = $_SESSION['email']; //prendo e-mail utente
	
	//prendo le variabili dal post del form
	$id = $_GET['ID'];
	
	include 'mysqlcon.php'; //connessione db
	$result = mysqli_query($con,"SELECT * FROM utenti WHERE username = '$username';"); //query per select
	$row = mysqli_fetch_array($result);
	$idU = $row['ID_Utente']; //prendo id utente
	$nomeU = $row['Nome']; //prendo nome
	$cognU = $row['Cognome']; //prendo cognome
	mysqli_close($con); //chiudo connessione
	
	include 'mysqlcon.php'; //connessione db
	$result = mysqli_query($con,"SELECT * FROM prodotti, utenti WHERE ID_Prodotto = $id AND utenti.ID_Utente = prodotti.Responsabile;"); //query per select
	$row = mysqli_fetch_array($result);
	$emailR = $row['EMail']; //prendo email responsabile
	$nspro = $row['Numero di serie']; //numero di serie
	$nopro = $row['NomeP']; //nome prodotto
	mysqli_close($con); //chiudo connessione
	
	//se non sono vuote
	if(!empty($id)){
		//include della connessione al database
		include 'mysqlcon.php';
		mysqli_query($con,"UPDATE prodotti SET Disponibile = 0, Comprato = '{$idU}' WHERE ID_Prodotto = '{$id}'");//update
		mysqli_close($con); //chiudo connessione
		
		//invio e-mail
		$to = $emailR;
		$subject = "Compera - " . $nomeU . " " . $cognU;
		$message = "Vorrei comprare il seguente prodotto: ". $nopro . " - " . $nspro;
		$header = "From: ". $email ." \r\n";
		
		mail($to, $subject, $message, $header); //invio e-mail
		
		//risultato per l'ajax (mesaggio per utente)
		echo "<br><div class='text-center alert alert-success'><span class='glyphicon glyphicon-exclamation-sign' style='color: red; font-size: 20px;'></span> Il prodotto &egrave; stato comprato con successo, vai a ritirarlo portando i soldi dal responsabile oppure contattalo.</div>";
	}
?>
