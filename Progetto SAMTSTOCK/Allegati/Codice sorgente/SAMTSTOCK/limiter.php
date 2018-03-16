<html lang="it">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Aggiungi categorie Magazino SAMT">
		<meta name="author" content="Angelo Sanker">

		<title>Limiti prodotti</title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/user.css" rel="stylesheet">
		
		<link rel="icon" type="image/png" href="img/favicon.png"> 
	</head>
	<body>
		<?php
			error_reporting(E_ALL ^ E_NOTICE);
			session_start();
			if(!(isset($_SESSION['logged'])) || $_SESSION['power'] < 1) {
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
				<h1>Limiti Prodotti!</h1>
			</div>
			<form action="limiter.php" method="post" role="form" class="input-group searchForm">
				<div class="col-lg-12">
					<div class="input-group">
						<input type="text" class="form-control input-lg searchInput" placeholder="Cerca il prodotto, tramite il suo nome" name="item">
						<span class="input-group-btn">
							<button class="btn btn-default input-lg searchInput" type="submit">CERCA</button>
						</span>
					</div>
				</div>
			</form>
			<?php
				//errori e messaggi
				if($_GET['update']){
					echo '<center><div id="successmsg" class="alert alert-success">Il prodotto è stato modificato con successo</div></center>';
				}
				if($_GET['error']){
					echo '<center><div id="successmsg" class="alert alert-danger">Error!</div></center>';
				}
			?>
				<?php
					include 'mysqlcon.php'; //connessione al database

					//recupero dati dai form 
					$item = $_POST['item'];
					
					//if per controllo se non è vuoto
					if (!empty($item)) {
						$result = mysqli_query($con,"SELECT * FROM prodotti  WHERE (NomeP LIKE '%" . $item . "%') AND Quantita > 1"); //querry di ricerca

						$trovati = mysqli_num_rows($result);//conto le righe trovate
						
						if($trovati > 0){//controllo che le righe trovate siano almeno 1
							echo "<h4 class='back-link'>Trovati $trovati prodotti per la parola chiave <b>".stripslashes($item)."</b></h4>\n";//stampo quante righe ho trovato
							printTable($result);
						} else{
							echo "<h4 class='back-link'>Al momento non ci sono articoli che contengano i termini cercati.</h4>";//in caso siano state 0 righe trovate stampo un messaggio
						}
						echo '<style>';
						echo '#allitems { display: none; }';
						echo '</style>';
					}
					mysqli_close($con);//chiudo la connessione
				?>
				<div id="allitems">
					<?php
						include 'mysqlcon.php';
							
						$result = mysqli_query($con,"SELECT ID_Prodotto, NomeP, NomeC, Modello, `Numero di serie`, Disponibile, Portabile, Aula, Descrizione, Prezzo, Quantita, Limite FROM prodotti p JOIN categorie c ON p.Categoria = c.ID_Categoria WHERE Quantita > 1 ORDER BY NomeP ASC");
								
						printTable($result);
							
						mysqli_close($con);
					?>
				</div>
				<a href="itemsG.php" class="btn btn-lg btn-warning"><span class="glyphicon glyphicon-dashboard"></span> Gestione prodotti</a>
				<a href="items.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-plus"></span> Prodotti</a>
				<a href="category.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-plus"></span> Categoria</a>
		</div>
		
		<?php
			//stampo tabella
			function printTable($result){
				echo '<table class="table table-bordered table-striped table-hover">';
				echo '<tr class="info">';
					echo '<th>Nome</th>';
					echo '<th>Categoria</th>';
					echo '<th>Modello</th>';
					echo '<th>Numero di serie</th>';
					echo '<th class="sizedTD">Disponibile</th>';
					echo '<th class="sizedTD">Portabile</th>';
					echo '<th>Aula</th>';
					echo '<th>Prezzo</th>';
					echo '<th>Pezzi</th>';
					echo '<th class="sizedTD">Limiter</th>';
				echo '</tr>';
				
				while($row = mysqli_fetch_array($result)) {
					if($row['Quantita'] < $row['Limite']){
						echo "<tr style='color: red;'>";
					}else {
						echo "<tr>";
					}
						echo "<td>" . $row['NomeP'] . "</td>";
						echo "<td>" . $row['NomeC'] . "</td>";
						echo "<td>" . $row['Modello'] . "</td>";
						echo "<td>" . $row['Numero di serie'] . "</td>";
						echo "<td class='sizedTD'>" . ($row['Disponibile'] == 1 ? "Sì" : "No") . "</td>";
						echo "<td class='sizedTD'>" . ($row['Portabile'] == 1 ? "Sì" : "No") . "</td>";
						echo "<td>" . $row['Aula'] . "</td>";
						echo "<td style='text-align:right'>" . $row['Prezzo'] . ".-</td>";
						echo "<td style='text-align:center'>" . $row['Quantita'] . "</td>";
						echo '<td class="sizedTD">
								<a href="#Modal'. $row['ID_Prodotto'] .'" data-toggle="modal" data-target="#Modal'. $row['ID_Prodotto'] .'" class="actionLinks" style="font-size: 22px;" title="Modifica">
									<i class="glyphicon glyphicon-resize-horizontal"></i>
								</a>
							</td>';
					echo "</tr>";
		?>
					
					<div class="modal fade" id="<?php echo 'Modal'. $row['ID_Prodotto']; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'Modal'. $row['ID_Prodotto']; ?>" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="myModalLabel">Modifica del limitatore per il prodotto <?php echo $row['NomeP']; ?></h4>
								</div>
								<div class="modal-body">
									<form action="limiterupdater.php" method="post" name="modalForm">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12">
												<div class="form-group">
													<input type="text" style="display: none;" name="ID" value="<?php echo $row['ID_Prodotto']; ?>" required>
													
													<center>
														<output style="font-size: 45px; for="limite" id="<?php echo 'pezzi'. $row['ID_Prodotto']; ?>"><?php echo $row['Limite']." pezzi"; ?></output>
													</center>
													
													<label for="limite" style="margin-left: 18px;">0</label>
													<label for="limite" class="pull-right" style="margin-right: 18px;"><?php echo $row['Quantita'] ?></label>
													<input type="range" class="input-lg" name="limite" id="<?php echo 'Modal'. $row['ID_Prodotto']; ?>" maxlength="50" min="0" max="<?php echo $row['Quantita']; ?>" step="1" oninput="outputUpdate(value, <?php echo $row['ID_Prodotto']; ?>)" list="persett<?php echo $row['ID_Prodotto']; ?>" value="<?php echo $row['Limite']; ?>" autofocus>
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
					
					if (confirm("Sei sicuro di voler cancellare tutti i prodotti selezionati?") == true) { //chiede se sei sicuro
						$.post('deleterP.php', {'cbArray': cbArray}); //faccio il post per il file deleter.php
						window.location.href = "itemsG.php?deleteall=true"; //redirect con messaggio di conferma
					}
				});
			});
			
			function deleterFunction(id) {
				if (confirm("Sei sicuro di voler cancellare il prodotto con l'id " + id + "?") == true) { //chiede se sei sicuro
					window.location.href = "deleterP.php?delete="+id; //fa il redirect
				}
			}
			
			function outputUpdate(limite, id) { //prendo id e value dello slider
				var idlim = "#pezzi" + id;
				document.querySelector(idlim).value = limite + " pezzi"; //setto l'output al numero del value
			}
			
			function ticks(element) {
				if (element.hasOwnProperty('list') && element.hasOwnProperty('min') && element.hasOwnProperty('max') && element.hasOwnProperty('step')) { //cerco elementi con quelle property
					//creao datalist per i trattini nello slider
					var datalist = document.createElement('datalist'),
					
					//prendo dati
					minimum = parseInt(element.getAttribute('min')),
					step = parseInt(element.getAttribute('step')),
					maximum = parseInt(element.getAttribute('max'));
					datalist.id = element.getAttribute('list');
					
					//creo trattini
					for (var i = minimum; i < maximum+step; i = i + step) {
						datalist.innerHTML +="<option value="+i+"></option>";
					}
					element.parentNode.insertBefore(datalist, element.nextSibling);
				} 
			}
			var lists = document.querySelectorAll("input[type=range][list]"),
			arr = Array.prototype.slice.call(lists);
			arr.forEach(ticks);
		</script>
	</body>
</html>