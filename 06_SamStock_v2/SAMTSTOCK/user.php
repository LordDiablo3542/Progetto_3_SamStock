<html lang="it">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Aggiungi categorie Magazino SAMT">
		<meta name="author" content="Angelo Sanker">

		<title>Gestione Utenti</title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/user.css" rel="stylesheet">
		
		<link rel="icon" type="image/png" href="img/favicon.png"> 
	</head>
	<body>
		<?php
			error_reporting(E_ALL ^ E_NOTICE);
			session_start();
			//se non sei loggato e non hai abbastanza potere non puoi entrare qui
			if(!(isset($_SESSION['logged'])) || $_SESSION['power'] < 2) {
				echo '<div class="container">';
				echo "<h1>Area riservata, accesso negato.</h1>";
				echo "Per tornare alla home clicca <a href='index.php'><font color='blue'>qui</font></a>";
				echo '</div>';
				die;
			}
			$username = $_SESSION['username'];
			$name = $_SESSION['name'];
			$logged = $_SESSION['logged'];
			$power = $_SESSION['power'];
		?>
		
		<?php include 'menu.php'; ?>
		
		<div class="container">
			<div class="page-header">
				<h1>Gestione Utenti!</h1>
			</div>
			<form action="user.php" method="post" role="form" class="input-group searchForm">
				<div class="col-lg-12">
					<div class="input-group">
						<input type="text" class="form-control input-lg searchInput" placeholder="Cerca l'utente, tramite il suo nome" name="user">
						<span class="input-group-btn">
							<button class="btn btn-default input-lg searchInput" type="submit">CERCA</button>
						</span>
					</div>
				</div>
			</form>
			<?php
				//errori e messaggi
				if($_GET['deleted']){
					echo '<center><div id="successmsg" class="alert alert-success">Utente cancellato con successo</div></center>';
				}
				if($_GET['deleteall']){
					echo '<center><div id="successmsg" class="alert alert-success">Tutti gli utenti selezionati sono stati cancellati con successo</div></center>';
				}
				if($_GET['update']){
					echo '<center><div id="successmsg" class="alert alert-success">L\'utente è stato modificato con successo</div></center>';
				}
				if($_GET['usenameerr']){
					echo '<center><div id="successmsg" class="alert alert-warning">Il nome utente da te scelto esiste già, le modifiche non sono potute essere fatte</div></center>';
				}
			?>
			<!--<form action="deleter.php" id="dForm" method="post">-->
				<?php
					include 'mysqlcon.php'; //connessione al database

					//recupero dati dai form 
					$user = $_POST['user'];
					
					//if per controllo se non è vuoto
					if (!empty($user)) {
						$result = mysqli_query($con,"SELECT * FROM utenti  WHERE (Nome LIKE '%" . $user . "%')"); //querry di ricerca
						$search = true;

						$trovati = mysqli_num_rows($result);//conto le righe trovate
						
						if($trovati > 0){//controllo che le righe trovate siano almeno 1
							echo "<h4 class='back-link'>Trovati $trovati utenti per la parola chiave <b>".stripslashes($user)."</b></h4>\n";//stampo quante righe ho trovato
							printTable($result, $search);
						} else{
							echo "<h4 class='back-link'>Al momento non ci sono utenti che contengano i termini cercati come nome.</h4>";//in caso siano state 0 righe trovate stampo un messaggio
						}
						echo '<style>';
						echo '#allusers { display: none; }';
						echo '</style>';
					}
					mysqli_close($con);//chiudo la connessione
				?>
				<div id="allusers">
					<?php
						include 'mysqlcon.php';
							
						$result = mysqli_query($con,"SELECT * FROM utenti ORDER BY Nome ASC;"); //prendo tutti gli utenti
						$search = false;
								
						printTable($result, $search);
							
						mysqli_close($con);
					?>
				</div>
				<button type="submit" class="btn btn-lg btn-danger" id="deleteButt" >DELETE</button>
			<!--</form>-->
		</div>
		
		<?php
			//stampo tabella
			function printTable($result, $search){
				echo '<table class="table table-bordered table-striped table-hover">';
				echo '<tr class="info">';
					if($search){
						echo '<th class="sizedTD"><input type="checkbox" id="selecctallSearch"></th>';
					}else{
						echo '<th class="sizedTD"><input type="checkbox" id="selecctall"></th>';
					}
					echo '<th>ID</th>';
					echo '<th>Nome</th>';
					echo '<th>Cognome</th>';
					echo '<th>Username</th>';
					//echo '<th>Password</th>';
					echo '<th>Power</th>';
					echo '<th class="sizedTD">Azioni</th>';
				echo '</tr>';
				
				while($row = mysqli_fetch_array($result)) {
					echo "<tr>";
						if($row['Power'] < 2){
							if($search){
								echo '<td class="sizedTD"><input class="checkbox2" type="checkbox" name="cbArray[]" value="'. $row['ID_Utente'] .'"></td>';
							}else{
								echo '<td class="sizedTD"><input class="checkbox1" type="checkbox" name="cbArray[]" value="'. $row['ID_Utente'] .'"></td>';
							}
						}else{
							echo '<td class="sizedTD"><input type="checkbox" value="" disabled></td>';
						}
						echo "<td class='sizedTD'>" . $row['ID_Utente'] . "</td>";
						echo "<td>" . $row['Nome'] . "</td>";
						echo "<td>" . $row['Cognome'] . "</td>";
						echo "<td>" . $row['Username'] . "</td>";
						//echo "<td>" . $row['Password'] . "</td>";
						//echo "<td>" . $row['Power'] . "</td>";
						echo "<td>";
						//a dipendenza del potere faccio visualizzare un nome
						switch($row['Power']){
							case "0":
								echo "Utente";
								break;
							case "1":
								echo "Responsabile";
								break;
							case "2":
								echo "Amministratore";
								break;
						}
						echo "</td>";
						//se admin non posso fare azioni
						if($row['Power'] < 2){
							echo '<td class="sizedTD">
									<a href="#Modal'. $row['ID_Utente'] .'" data-toggle="modal" data-target="#Modal'. $row['ID_Utente'] .'" class="actionLinks" title="Modifica">
										<i class="glyphicon glyphicon-edit"></i>
									</a>
									<a class="actionLinks" title="Cancella" onclick="deleterFunction('. $row['ID_Utente'] .')"> 
										<i class="glyphicon glyphicon-remove"></i>
									</a>
								</td>';
						}else{
							echo '<td class="sizedTD"><img src="img/NO.png" width="22px;" height="22px;" /></td>';
						}
					echo "</tr>";
		?>
					
					<div class="modal fade" id="<?php echo 'Modal'. $row['ID_Utente']; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'Modal'. $row['ID_Utente']; ?>" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="myModalLabel">Modica dell'utente <?php echo $row['Nome'] . " " . $row['Cognome']; ?></h4>
								</div>
								<div class="modal-body">
									<form action="modifier.php" method="post" name="modalForm">
										<div class="row">
											<div class="col-xs-6 col-sm-6 col-md-6">
												<div class="form-group">
													<input type="text" style="display: none;" name="ID" value="<?php echo $row['ID_Utente']; ?>" required>
													<input type="text" class="form-control input-lg" placeholder="Nome" name="Nome" maxlength="50" value="<?php echo $row['Nome']; ?>" required>
												</div>
											</div>
											<div class="col-xs-6 col-sm-6 col-md-6">
												<div class="form-group">
													<input type="text" class="form-control input-lg" placeholder="Cognome" name="Cognome" maxlength="50" value="<?php echo $row['Cognome']; ?>" required>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12">
												<div class="form-group">
													<input type="text" class="form-control input-lg" placeholder="Username" name="Username" maxlength="101" value="<?php echo $row['Username']; ?>" required>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-8 col-sm-8 col-md-8">
												<div class="form-group">
													<input type="text" class="form-control input-lg" placeholder="Nuova password" name="Password" maxlength="255" required>
												</div>
											</div>
											<div class="col-xs-4 col-sm-4 col-md-4">
												<div class="form-group">
													<select class="form-control input-lg" name="Power" required>
														<option value="0">Utente</option>
														<option value="1">Responsabile</option>
														<option value="2">Amministratore</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12">
												<div class="form-group">
													<input type="email" class="form-control input-lg" placeholder="E-Mail" name="Email" maxlength="255" value="<?php echo $row['EMail']; ?>" required>
												</div>
											</div>
										</div>
										<button type="submit" name="submit" class="btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-edit"></span> Modifica</button>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
								</div>
							</div>
						</div>
					</div>
		<?php }
				echo "</table>";
			}
		?>
		
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function() {
				$( "#successmsg" ).delay(2000).hide("slow"); //al caricamento della pagina aspetto 2 secondi e poi nascondo lentamente il messaggio
				
				
				
				$('#selecctall').click(function(event) {  //nel cliccare 
					if(this.checked) { //controlli lo stato
						$('.checkbox1').each(function() { //loop che passa per ogni checkboy con la classe checkbox1
							this.checked = true;  //seleziona tutti i checkbox con la class "checkbox1"               
						});
					}else{
						$('.checkbox1').each(function() { //loop che passa per ogni checkboy con la classe checkbox1
							this.checked = false; //deseleziona tutti i checkbox con la class "checkbox1"                       
						});         
					}
				});
				
				
				
				$('#selecctallSearch').click(function(event) {  //nel cliccare 
					if(this.checked) { //controlli lo stato
						$('.checkbox2').each(function() { //loop che passa per ogni checkboy con la classe checkbox2
							this.checked = true;  //seleziona tutti i checkbox con la class "checkbox2"               
						});
					}else{
						$('.checkbox2').each(function() { //loop che passa per ogni checkboy con la classe checkbox2
							this.checked = false; //deseleziona tutti i checkbox con la class "checkbox2"                       
						});         
					}
				});
				
				
				
				$('#deleteButt').click(function(event) { //al click del bottono
					var cbArray = []; //creo array
					
					$("input[name^=cbArray]:checked").each(function() { //per ogni checkbox checkkato con il nome cbArray
						cbArray.push($(this).val()); //faccio il push dei dati nell'array
					});
					
					if (confirm("Sei sicuro di voler cancellare tutti gli utenti selezionati?") == true) { //chiede se sei sicuro
						$.post('deleter.php', {'cbArray': cbArray}); //faccio il post per il file deleter.php
						window.location.href = "user.php?deleteall=true"; //redirect con messaggio di conferma
					}
				});
				
				
				
				/*$('#dForm').submit(function(event) { //nel fare il submit
					if (confirm("Sei sicuro di voler cancellare tutti gli utenti selezionati?") == false) { //chiede se sei sicuro
						event.preventDefault(); //in caso di no anulla l'evento
					}
				})*/
			});
			
			function deleterFunction(id) {
				if (confirm("Sei sicuro di voler cancellare l'utente con l'id " + id + "?") == true) { //chiede se sei sicuro
					window.location.href = "deleter.php?delete="+id; //fa il redirect
				}
			}
		</script>
	</body>
</html>