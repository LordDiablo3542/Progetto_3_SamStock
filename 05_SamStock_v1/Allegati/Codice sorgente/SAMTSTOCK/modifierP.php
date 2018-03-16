<?php
	session_start(); //inizio della sessione
	error_reporting(E_ALL ^ E_NOTICE);
	
	//include della connessione al database
	include 'mysqlcon.php';
		
	//prendo le variabili dal post del form
	$id = mysqli_real_escape_string($con, $_POST['ID']);
	$nome = mysqli_real_escape_string($con, $_POST['nomeProdotto']);
	$serie = mysqli_real_escape_string($con, $_POST['nDiSerie']);
	$categoria = mysqli_real_escape_string($con, $_POST['categoriaProdotto']);
	$modello = mysqli_real_escape_string($con, $_POST['modello']);
	$aula = mysqli_real_escape_string($con, $_POST['aula']);
	$disponibile = mysqli_real_escape_string($con, $_POST['disponibile']);
	$portabile = mysqli_real_escape_string($con, $_POST['portabile']);
	$descrizione = mysqli_real_escape_string($con,$_POST['descrizione']);
	$prezzo = mysqli_real_escape_string($con, $_POST['prezzo']);
	$quantita = mysqli_real_escape_string($con, $_POST['quantita']);
	
	if(empty($disponibile)){
		$disponibile = 0;
	}
	if(empty($portabile)){
		$portabile = 0;
	}
	
	if (!mysqli_query($con,"UPDATE prodotti SET NomeP = '{$nome}', Categoria = '{$categoria}', Modello = '{$modello}', `Numero di serie` = '{$serie}', Disponibile = '{$disponibile}', Portabile = '{$portabile}', Aula = '{$aula}', Descrizione = '{$descrizione}', Prezzo = '{$prezzo}', Quantita = '{$quantita}' WHERE ID_Prodotto = '{$id}'")) { //update
		die(header("location: itemsG.php?usenameerr=true")); //se c'è un errore redirect con errore
	}
	mysqli_close($con); //chiudo connessione
	header("location: itemsG.php?update=true"); //redirect con messaggio di conferma
?>
