<html lang="it">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Aggiungi categorie Magazino SAMT">
		<meta name="author" content="Angelo Sanker">

		<title>Aggiungi Categorie</title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/gestion.css" rel="stylesheet">
		
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
				include 'menu.php';
			?>
			<div class="page-header">
				<h1>Aggiungi categorie!</h1>
			</div>
			<?php
				if($_GET['added']){
					echo '<div id="successmsg" class="alert alert-success back-link">Categoria aggiunta con successo.</div>';
				}
				if($_GET['nameerr']){
					echo '<div id="successmsg" class="alert alert-warning back-link">La categoria esiste già.</div>';
				}
				if($_GET['deleted']){
					echo '<div id="successmsg" class="alert alert-success back-link">Categoria cancellata con successo.</div>';
				}
				if($_GET['catdelerr']){
					echo '<div id="successmsg" class="alert alert-warning back-link">La categoria non è stata cancellata perché contiene dei prodotti al suo interno.</div>';
				}
			?>
			<form method="post" role="form">
				<div class="col-lg-12">
					<div class="input-group" style="margin-bottom: 10px;">
						<input type="text" class="form-control input-lg" placeholder="Nome categoria" name="categoryName" required autofocus>
						<span class="input-group-btn">
							<button class="btn btn-success input-lg" type="submit"><span class="glyphicon glyphicon-plus"></span> Aggiungi</button>
						</span>
					</div>
				</div>
			</form>
			<div class="back-link">
				<a href="itemsG.php">Gestione prodotti</a>
				|
				<a href="items.php">Aggiungi prodotto</a>
			</div><br />
			<br />
			<?php
				include 'mysqlcon.php';
							
				$result = mysqli_query($con,"SELECT * FROM categorie ORDER BY ID_Categoria DESC;"); //prendo tutti gli utenti
				$search = false;
						
				echo '<h1>Elimina categorie!</h1>';
				
				printTable($result, $search);
					
				mysqli_close($con);
				
				//stampo tabella
				function printTable($result, $search){
					echo '<table class="table table-bordered table-striped table-hover">';
					echo '<tr class="info">';
						echo '<th class="sizedTD">ID</th>';
						echo '<th>Nome</th>';
						echo '<th class="sizedTD">Azioni</th>';
					echo '</tr>';
					
					while($row = mysqli_fetch_array($result)) {
						echo "<tr>";
							echo "<td><center>" . $row['ID_Categoria'] . "</center></td>";
							echo "<td>" . $row['NomeC'] . "</td>";
							echo "<td class='sizedTD'>
										<a class='actionLinks' title='Cancella' onclick='deleterFunction(". $row['ID_Categoria'] .")'> 
											<i class='glyphicon glyphicon-remove' style='font-size: 22px;'></i>
										</a>
									</td>";
						echo "</tr>";
					}
					echo "</table>";
				}
			?>
		</div>
		
		<?php
			include 'mysqlcon.php'; //connessione database
			
			$categoryName = mysqli_real_escape_string($con, $_POST['categoryName']); //prendo dati
			
			//controllo che non siano vuoti
			if(!empty($categoryName)){
				if (!mysqli_query($con,"INSERT INTO categorie(NomeC) VALUES ('$categoryName')")) { //query INSERT
					die(header("location: category.php?nameerr=true")); //errore
				}
				mysqli_close($con); //chiudo connessione
				header("location: category.php?added=true"); //redirect
			}
			
			if (isset($_GET['delete'])){
				$id = $_GET['delete']; //prendo dato

				if (!mysqli_query($con,"DELETE FROM categorie WHERE ID_Categoria='$id'")){ //cancello
					die(header("location: category.php?catdelerr=true")); //errore
				}
				mysqli_close($con);
				
				header("location: category.php?deleted=true"); //redirect
			}
		?>
		
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function() {
			  $( "#successmsg" ).delay(2000).hide("slow");
			});
			
			function deleterFunction(id) {
				if (confirm("Sei sicuro di voler cancellare la categoria " + id + "?") == true) { //chiede se sei sicuro
					window.location.href = "category.php?delete="+id; //fa il redirect
				}
			}
		</script>
	</body>
</html>