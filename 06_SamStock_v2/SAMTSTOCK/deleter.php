<?php
	/*
	 * Codice per la cancellazione degli utentis
	 */

	session_start();
	
	include 'mysqlcon.php';
	
	if (!empty($_GET['nodelete'])){
		header("location: user.php");
	}else{
		//Cancellazione di un singolo utente
		if (isset($_GET['delete'])){
			$id = $_GET['delete']; //prendo dato

			mysqli_query($con,"DELETE FROM utenti WHERE ID_Utente='$id'"); //cancello
			mysqli_close($con);
			
			header("location: user.php?deleted=true"); //redirect
		}
		
		//Cancellazione degli utenti selezoinati
		if (isset($_POST['cbArray'])){
			
			$cbArray = $_POST['cbArray']; //prendo array
		
			for ($i=0; $i < count($cbArray);$i++) //for che va fino alla grandezza dell'array
			{
				mysqli_query($con,"DELETE FROM utenti WHERE ID_Utente='".$cbArray[$i]."'"); //cancello dal database
			}
			mysqli_close($con); //chiudo connessione
			header("location: user.php?deleteall=true"); //redirect
		}
	}
	//header("location: user.php");
?>
