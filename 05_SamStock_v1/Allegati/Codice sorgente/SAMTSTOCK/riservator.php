<?php
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
		mysqli_query($con,"UPDATE prodotti SET Disponibile = 0, Riservato = '{$idU}' WHERE ID_Prodotto = '{$id}'");//update
		mysqli_close($con); //chiudo connessione
		
		//invio e-mail
		$to = $emailR;
		$subject = "Riservazione - " . $nomeU . " " . $cognU;
		$message = "Vorrei riservare il seguente prodotto: ". $nopro . " - " . $nspro;
		$header = "From: ". $email ." \r\n";
		
		mail($to, $subject, $message, $header); //invio e-mail
		
		echo'<br><div class="text-center alert alert-success">Il prodotto &egrave; stato riservato con successo.</div>'; //risultato per l'ajax
	}
?>
