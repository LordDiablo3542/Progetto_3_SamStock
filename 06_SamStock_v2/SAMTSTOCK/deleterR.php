<?php
	// Codice per una cancellazione di un prodotto

	session_start();
	
	include 'mysqlcon.php';
	
	if (isset($_GET['delete'])){
		$id = $_GET['delete']; //prendo dato

		mysqli_query($con,"UPDATE prodotti SET Disponibile = 1, Riservato = NULL WHERE ID_Prodotto = '{$id}'"); //update
		mysqli_close($con); //chiudo connessione
			
		header("location: reservation.php?deleted=true"); //redirect
	}
?>
