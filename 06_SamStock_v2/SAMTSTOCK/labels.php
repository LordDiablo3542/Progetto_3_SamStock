<?php
	error_reporting(E_ALL ^ E_NOTICE);
	
	if(isset($_GET['id'])){
		include 'mysqlcon.php'; //connessione database
		
		//prendo valori
		$nameI = $_GET['name'];
		$idI = $_GET['id'];
		
		//select per prendere dati del prodotto
		$result = mysqli_query($con,"SELECT * FROM prodotti, utenti WHERE ID_Prodotto = $idI AND utenti.ID_Utente = prodotti.Responsabile");
		$row = mysqli_fetch_array($result);
		
		//prendo i dati dal database
		$modello = $row['Modello'];
		$ndserie = $row['Numero di serie'];
		
		mysqli_close($con);
		
		//richiedo mc_tables che è una estenzione di FDPF
		require('FPDF/mc_table.php');
		
		//creo il mio PDF
		$pdf = new PDF_MC_Table();
		$pdf -> AddPage();
		$pdf -> SetFont('Arial','B',48);
		
		//stampo titolo
		$pdf -> SetXY(68,20);
		$pdf -> Cell(100,10,"Etichette");

		$pdf -> SetFont('Arial','',11);
		
		$pdf -> ln(30);
		
		//setto larghezze colonne e allineamento
		$pdf -> SetWidths(array(63, 63, 63));
		$pdf -> SetAligns(array('C', 'C', 'C'));
		
		//stampo righe
		for($i = 0; $i < 9; $i++){
			$pdf->Row(array("\n".$nameI." - ".$modello."\n\n".$ndserie."\n\n", "\n".$nameI." - ".$modello."\n\n".$ndserie."\n\n", "\n".$nameI." - ".$modello."\n\n".$ndserie."\n\n")); //stampo dettagli del prodotto
		}
		
		$pdf -> Output('etichette.pdf', I);
	}
?>
