<html lang="it">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Aggiungi prodotti Magazino SAMT">
		<meta name="author" content="Angelo Sanker">

		<title>Riservazioni</title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/user.css" rel="stylesheet">
		
		<link rel="icon" type="image/png" href="img/favicon.png"> 
	</head>
	<body>
		<div class="container">
			<?php
				ob_start();
				error_reporting(E_ALL ^ E_NOTICE);
				session_start();
				if(!(isset($_SESSION['logged'])) || $_SESSION['power'] < 1) {
					echo "<h1>Area riservata, accesso negato.</h1>";
					echo "Per tornare alla home clicca <a href='index.php'><font color='blue'>qui</font></a>";
					die;
				}
				$username = $_SESSION['username'];
				$name = $_SESSION['name'];
				$logged = $_SESSION['logged'];
				$power = $_SESSION['power'];
				$email = $_SESSION['email'];
				
				include 'mysqlcon.php';
				$result = mysqli_query($con,"SELECT ID_Utente FROM utenti WHERE username = '$username';"); //query per select id utente loggato
				$row = mysqli_fetch_array($result);
				$idutente = $row['ID_Utente'];
				mysqli_close($con);
				
				include 'menu.php';
			?>
			<div class="page-header">
				<h1>Riservazioni</h1>
			</div>
			
			<?php
				//errori
				if($_GET['deleted']){
					echo '<center><div id="successmsg" class="alert alert-success">Riservazione annullata con successo</div></center>';
				}
				if($_GET['send']){
					echo '<center><div id="successmsg" class="alert alert-success">E-Mail inviata con successo</div></center>';
				}
				if($_GET['nosend']){
					echo '<center><div id="successmsg" class="alert alert-danger">L\'e-mail non è stata inviata!</div></center>';
				}
				
				include 'mysqlcon.php';
					
				$result = mysqli_query($con,"SELECT * FROM prodotti, categorie, utenti WHERE prodotti.Categoria = categorie.ID_Categoria AND prodotti.Comprato = utenti.ID_Utente AND Responsabile = '$idutente' AND Comprato IS NOT NULL"); //seleziono i prodotti di mia responsabilità riservati
					
				printTable($result);
				
				mysqli_close($con);
				
				//stampo tabella
				function printTable($result){
					echo '<table class="table table-bordered table-striped">';
					echo '<tr class="info">';
						echo '<th>Nome</th>';
						echo '<th>Categoria</th>';
						echo '<th>Modello</th>';
						echo '<th>Numero di serie</th>';
						echo '<th>Aula</th>';
						echo '<th>Riservato</th>';
						echo '<th class="sizedTDL">Azioni</th>';
					echo '</tr>';
					
					while($row = mysqli_fetch_array($result)) {
						//se la quantia è maggiore di 1 coloro in verde
						if($row['Quantita'] > 1){
							echo "<tr style='color: green;'>";
							echo "<td style='vertical-align: middle; width: 400px;' >
							<a style='color: green;' href='product.php?name=" . $row['NomeP'] . "&id=" . $row['ID_Prodotto'] . "' id='name'>" . $row['NomeP'] . "</a></td>";
						} else{
							echo '<tr>';
							echo "<td style='vertical-align: middle; width: 400px;' >
							<a href='product.php?name=" . $row['NomeP'] . "&id=" . $row['ID_Prodotto'] . "' id='name'>" . $row['NomeP'] . "</a></td>";
						}
							echo "<td style='vertical-align: middle;'>" . $row['NomeC'] . "</td>";
							echo "<td style='vertical-align: middle;'>" . $row['Modello'] . "</td>";
							echo "<td style='vertical-align: middle;'>" . $row['Numero di serie'] . "</td>";
							echo "<td style='vertical-align: middle;'>" . $row['Aula'] . "</td>";
							echo "<td style='vertical-align: middle;'>" . $row['Username'] . "</td>";
							echo '<td class="sizedTD">
									<a href="reservation.php?ok=true&nomep='. $row['NomeP'] .'&emailu='. $row['EMail'] .'" class="actionLinks" title="Invia conferma riservazione"> 
										<i class="glyphicon glyphicon-ok"></i>
									</a>
									<a class="actionLinks" title="Anulla compera" onclick="deleterFunction('. $row['ID_Prodotto'] .')"> 
										<i class="glyphicon glyphicon-remove"></i>
									</a>
								</td>';
						echo "</tr>";
					}
					echo "</table>";
				}
			?>
		</div>
		<?php
			//se ok è settato setto l'e-mail in vieni a ritarare
			if (isset($_GET['ok'])) {
				$nomep = $_GET['nomep'];
				$to = $_GET['emailu'];
				$subject = "Ritiro: ". $nomep;
				$message = "La tua richiesta di prestito è stata accettata.\r\nPassa a ritirare il prodotto (" . $nomep .") entro la fine di questa settimana.\r\n\r\n" . $name;
				
				emailSender($to, $subject, $message, $email);
			}
			
			//funzione per l'invio delle e-mail
			function emailSender($to, $subject, $message, $email) {
				$header = "From: ". $email ." \r\n".
						  "Content-Type: text/plain; charset=utf-8"; //informazione di chi lo invia, cioè utente e dico anche il tipo del contesto, testo utf-8
					
				$retval = mail($to, $subject, $message, $header); //invio e-mail
					
				if($retval == true) {
					header("location: bought.php?send=true"); //redirect giusto
				}else {
					header("location: bought.php?nosend=true"); //redirect errore
				}
			}
		?>
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function() {
			  $( "#successmsg" ).delay(2000).hide("slow");
			});
			
			function deleterFunction(id) {
				if (confirm("Sei sicuro di voler cancellare questa compera?") == true) { //chiede se sei sicuro
					window.location.href = "deleterR.php?delete="+id; //fa il redirect
				}
			}
		</script>
	</body>
</html>