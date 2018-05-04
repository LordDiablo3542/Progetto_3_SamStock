<?php
	session_start();
	
	include 'mysqlcon.php';
	
	if (!empty($_GET['nodelete'])){
		header("location: itemsG.php");
	}else{
		if (isset($_GET['delete'])){
			$id = $_GET['delete']; //prendo dato

			mysqli_query($con,"DELETE FROM prodotti WHERE ID_Prodotto='$id'"); //cancello
			mysqli_close($con);
			
			header("location: itemsG.php?deleted=true"); //redirect
		}
		
		if (isset($_POST['cbArray'])){
			
			$cbArray = $_POST['cbArray']; //prendo array
		
			for ($i=0; $i < count($cbArray);$i++) //for che va fino alla grandezza dell'array
			{
				mysqli_query($con,"DELETE FROM prodotti WHERE ID_Prodotto='".$cbArray[$i]."'"); //cancello dal database
			}
			mysqli_close($con); //chiudo connessione
			header("location: itemsG.php?deleteall=true"); //redirect
		}
	}
?>
