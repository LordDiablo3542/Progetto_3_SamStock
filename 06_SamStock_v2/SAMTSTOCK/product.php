<?php 
	ob_start();
	session_start();
	error_reporting(E_ALL ^ E_NOTICE);
	if (isset($_SESSION['username'])){
		$username = $_SESSION['username'];
		$name = $_SESSION['name'];
		$logged = $_SESSION['logged'];
		$power = $_SESSION['power'];
		$email = $_SESSION['email'];
	}
	$nameI = $_GET['name'];
	$idI = $_GET['id'];
?>
<html lang="it">
	<head>
		<meta charset="utf-8">
		<title><?php echo  $nameI; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Magazino SAMT">
		<meta name="author" content="Angelo Sanker">
		<link rel="shortcut icon" href="img/favicon.ico">

		<!-- stili -->  
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/index.css" rel="stylesheet">

		<link rel="icon" type="image/png" href="img/favicon.png">
		
		<script>
			function ajaxRiservator(id, riserva){
				var xmlhttp;
				// code for IE7+, Firefox, Chrome, Opera, Safari
				if (window.XMLHttpRequest){
					xmlhttp = new XMLHttpRequest();
				}
				// code for IE6, IE5
				else{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				
				if(riserva){
					xmlhttp.open("GET","riservator.php?ID=" + id,true);
				} else{
					xmlhttp.open("GET","buyer.php?ID=" + id,true);
				}
				xmlhttp.send();
				
				xmlhttp.onreadystatechange = function(){
					if (xmlhttp.status == 200 && xmlhttp.readyState == 4){
						document.getElementById("riservationDiv").innerHTML = xmlhttp.responseText;
					}
				}
			}
		</script>
	</head>
	<body>
		<!-- menu -->
		<?php include 'menu.php'; ?>

		<!-- contenuto pagina -->
		<div class="container">
			<h1><?php 
                        echo  $nameI; 
                        if($power >= 1){
                            ?> 
				<a href="labels.php?<?php echo 'prod='.$nameI.'&code=img/QR'.$idI.'.png'; ?>" target="_blank" style="font-size: 22px;" title="Stampa etichetta"> <i class="glyphicon glyphicon-print"></i></a>
			<?php } ?></h1>
			
			<?php
				if($_GET['send']){
					echo '<center><div id="successmsg" class="alert alert-success">E-Mail inviata con successo</div></center>';
				}
				if($_GET['nosend']){
					echo '<center><div id="successmsg" class="alert alert-danger">L\'e-mail non è stata inviata!</div></center>';
				}
			
				include 'mysqlcon.php';
								
				$result = mysqli_query($con,"SELECT * FROM prodotti, utenti, categorie WHERE ID_Prodotto = $idI AND utenti.ID_Utente = prodotti.Responsabile AND prodotti.Categoria = categorie.ID_Categoria"); //query dettagli 
				$row = mysqli_fetch_array($result);
				
				$idProp = $row['Responsabile'];
			?>		
			
			<center><img class="sizedImage" src="<?php echo $row['Icona'] ?>"></center>
			<div class="text-right" id="riservationDiv">
				<?php
					if(isset($logged)){
						if($row['Disponibile'] == 1){ //se è disponibile il prodotto
							if($row['Prezzo'] > 0){
								echo '<h3>Prezzo: CHF '. $row['Prezzo'] .'.-</h3>'; //stampo prezzo
								echo '<button class="btn btn-success" onclick="ajaxRiservator('.$idI.', false)">COMPRA</button> '; //stampo bottone compra
							}
							if(empty($row['Riservato'])){ //se non riservato
								echo '<button class="btn btn-primary" onclick="ajaxRiservator('.$idI.', true)">RISERVA</button> '; //stampo bottone riserva
							}
						}else{
							echo'<br><div class="text-center alert alert-warning">Il prodotto è già stato riservato/venduto.</div>'; //stampo che è giàriservato
						}
					}
				?>
			</div><br />
			
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#descrizione" role="tab" data-toggle="tab">Descrizione</a></li>
				<li role="presentation"><a href="#dettagli" role="tab" data-toggle="tab">Dettagli</a></li>
				<li role="presentation"><a href="#simili" role="tab" data-toggle="tab">Simili</a></li>
				<?php if($logged){ ?>
					<li role="presentation"><a href="#contatta" role="tab" data-toggle="tab">Contatta</a></li>
				<?php } ?>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active tabDiv descDiv" id="descrizione">
					<?php echo $row['Descrizione'] ?>
					<br />
					<br />
					<br />
					<?php echo $row['Portabile'] == 0 ? "Nota: Questo prodotto non può essere portato a casa" : "Nota: Questo prodotto può essere portato a casa" ?>
				</div>
				<div role="tabpanel" class="tab-pane tabDiv" id="simili">
					<div class="row">
						<?php
							$resultSimili = mysqli_query($con,"SELECT * FROM prodotti WHERE Categoria = " . $row['Categoria'] . " AND ID_Prodotto <> " . $idI . "  ORDER BY RAND() LIMIT 4"); //query per perodotti simili
							while($rowSimili = mysqli_fetch_array($resultSimili)){
						?>
								<div class="col-lg-3">
									<a href="product.php?name=<?php echo $rowSimili['NomeP'] . "&id=" . $rowSimili['ID_Prodotto'] ?>" class="thumbnail">
										<img src="<?php echo $rowSimili['Icona'] ?>" alt="<?php echo $rowSimili['NomeP'] ?>" title="<?php echo $rowSimili['NomeP'] ?>" style="max-height: 100px; min-height: 100px;">
										<div class="caption">
											<center>
												<h4><?php echo $rowSimili['NomeP'] ?></h4>
											</center>
										</div>
									</a>
								</div>
						<?php
							}
						?>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane tabDiv" id="dettagli">
					<table class="table table-bordered table-striped">
						<tr>
							<td class="sizedTD">Nome </td>
							<td><?php echo $row['NomeP'] ?></td>
						</tr>
						<tr>
							<td class="sizedTD">Categoria </td>
							<td><?php echo $row['NomeC'] ?></td>
						</tr>
						<tr>
							<td class="sizedTD">Modello </td>
							<td><?php echo $row['Modello'] ?></td>
						</tr>
						<tr>
							<td class="sizedTD">Numero di serie </td>
							<td><?php echo $row['Numero di serie'] ?></td>
						</tr>
						<tr>
							<td class="sizedTD">Disponibile </td>
							<td><?php echo ($row['Disponibile'] == 1 ? 'Si' : 'No') ?></td>
						</tr>
						<tr>
							<td class="sizedTD">Portabile </td>
							<td><?php echo ($row['Portabile'] == 1 ? 'Sì' : 'No') ?></td>
						</tr>
						<tr>
							<td class="sizedTD">Ubicazione </td>
							<td><?php echo $row['Aula'] ?></td>
						</tr>
						<tr>
							<td class="sizedTD">Pezzi </td>
							<td><?php echo $row['Quantita'] ?></td>
						</tr>
<!----------------------------------------------MODIFICA: Aggiunta della lettura del file pdf-->
                                                <tr>
                                                    <td class="sizedTD">PDF </td>
                                                        <?php
                                                        if ($row['File_PDF'] != "NULL") {
                                                            echo "<td style='vertical-align: middle;'>"
                                                            . "<a href= " . $row['File_PDF'] . ">
                                                                <img src='./img/pdf.png' whidth='22px' height='22px'>
                                                                </a>
                                                                </td>";
                                                            
                                                        } else {
                                                            echo "<td style='vertical-align: middle;'><img src='./img/NO.png' whidth='20px' height='20px'></td>";
                                                        }
                                                        ?>
                                                </tr>
<!----------------------------------------------MODIFICA: Aggiunta della creazione e della visualizzazione del codice a barre e del QRCode-->
                                                <tr>
                                                        <td class="sizedTD">Codice a barre </td>
                                                        <td>
                                                            <?php
                                                            include 'barcodeReader/barcode.php';
                                                            
                                                            //Creazione dell'oggetto barcode_generator
                                                            $barcodeGenerator = new barcode_generator();
                                                            $symbolUPC = 'ean-13';
                                                            $symbolQR = 'qr';
                                                            $data = $row['Numero di serie'];
                                                            
                                                            //Creazione delle immagini dei relativi codice a barre e QrCode
                                                            $imageUPC = $barcodeGenerator->render_image($symbolUPC, $data, '');
                                                            $imageQR = $barcodeGenerator->render_image($symbolQR, $data, '');
                                                            
                                                            //Salvataggio delle immagini
                                                            $imagePathUPC = 'img/UPC'.$row['ID_Prodotto'].'.png';
                                                            $imagePathQR = 'img/QR'.$row['ID_Prodotto'].'.png';
                                                            imagepng($imageUPC, $imagePathUPC);
                                                            imagepng($imageQR, $imagePathQR);
                                                            imagedestroy($imageUPC);
                                                            imagedestroy($imageQR);
                                                            ?>
                                                            <!-- Visualizzazione delle immagini tramite HTML -->
                                                            <img src="<?php echo $imagePathUPC; ?>" width="200" height="150" />
                                                            <img src="<?php echo $imagePathQR; ?>" width="200" height="200" />
                                                        </td>
                                                </tr>
					</table>
				</div>
				<div role="tabpanel" class="tab-pane tabDiv" id="contatta">
					<form method="post" role="form">
						<?php echo "Invia una E-Mail al responsabile di questo prodotto: <b>".$row['Nome']." ".$row['Cognome']."</b>"?>
						<textarea class="form-control input-lg"  name="mailtext" id="mailtext" style="resize: vertical;"></textarea>
						<br />
						<button class="btn btn-lg btn-primary" id="send" disabled="disabled">INVIA</button>
					</form>
				</div>
			</div>
			
			<?php
				$emailG = $row['EMail']; //prendo email gestore
				$nprodotto = $row['NomeP'] . " - " . $row['Numero di serie']; //predo dai nome e numero di serie prodotto
				$mailtext = $_POST['mailtext']; //prendo dati dalla textarea
				
				mysqli_close($con);
				
				//controllo che non sia vuoto
				if(!empty($mailtext)){
					$to = $emailG; //invio a chi
					$subject = $nprodotto; //oggetto
					$message = $mailtext; //messaggio
					$header = "From: ". $email ." \r\n".
							  "Content-Type: text/plain; charset=utf-8"; //informazione di chi lo invia, cioè utente e dico anche il tipo del contesto, testo utf-8
					
					$retval = mail($to, $subject, $message, $header); //invio e-mail
					
					if($retval == true) {
						header("location: product.php?name=$nameI&id=$idI&send=true"); //redirect giusto
					}else {
						header("location: product.php?name=$nameI&id=$idI&nosend=true"); //redirect errore
					}
				}
			?>
		</div>
		
		<!-- footer -->
		<div class="footer">
			<div class="container">
				<p class="text-muted">Powered by Angelo Sanker Copyright &copy; 2014 SAMT.</p>
			</div>
		</div>

		<!-- javascript -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function() {
				$( "#successmsg" ).delay(2000).hide("slow"); //nascondo i messaggi dopo 2 secondi
				
				//se il textbox è vuoto non posso fare invia
				$('#mailtext').keyup(function() {
					var empty = false;
					$('#mailtext').each(function() {
						if ($(this).val().length == 0) {
							empty = true;
						}
					});

					if (empty) {
						$('#send').attr('disabled', 'disabled');
					} else {
						$('#send').removeAttr('disabled');
					}
				});
			  
			});
		</script>
	</body>
</html>