<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="utf-8">
		<title>Log in</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Log-in Magazino SAMT">
		<meta name="author" content="Angelo Sanker">

		<!-- page styles -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/login.css" rel="stylesheet">
	
		<link rel="icon" type="image/png" href="img/favicon.png"> 
	</head>

	<body>
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-4 col-md-offset-4">
				
					<div class="account-wall">
						<center>
							<img src="img/LogoSAMTSTOCK.png" />
							<?php
								error_reporting(E_ALL ^ E_NOTICE); //nascondo le notice di php
								
								//se ricevo la variabile stampo il div con il messaggio
								if($_GET['login']){
									echo '<div class="alert alert-danger">Errore! Nome utente o Password sbagliati!</div>';
								}
								if($_GET['change']){
									echo '<div class="alert alert-success">Password cambiata con successo!</div>';
								}
								if($_GET['registered']){
									echo '<div class="alert alert-success">Registrazione effettuata con successo, il tuo Nome untente è <i>nome.cognome</i> tutto in minuscolo!</div>';
								}
								if($_GET['loggedout']){
									echo '<div class="alert alert-success">Log out effettuato con successo!</div>';
								}
								if($_GET['send']){
									echo '<div class="alert alert-success">Password inviata con successo!</div>';
								}
								if($_GET['nosend']){
									echo '<div class="alert alert-danger">L\'e-mail non è stata inviata!</div>';
								}
							?>
						</center>
						<form class="form-signin" action="logincontroller.php" method="post">
							<input type="text" class="form-control" placeholder="Nome utente" name="username" required autofocus>
							<input type="password" class="form-control" placeholder="Password" name="password" required>
							<button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
						</form>
					</div>
					<div class="new-account">
						<a href="#signUpModal" data-toggle="modal" data-target="#signUpModal">Sign up</a>
						|
						<a href="#pwLost" data-toggle="modal" data-target="#pwLost">Recupera password</a>
						<a href="index.php" class="pull-right">Back to home<span class="glyphicon glyphicon-chevron-right" style="font-size: 12px"></span></a>
					</div>
				</div>
				
				<div class="modal fade" id="signUpModal" tabindex="-1" role="dialog" aria-labelledby="signUpModal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel">Modulo di registrazione</h4>
							</div>
							<div class="modal-body">
								<center>
									<img src="img/LogoSAMTSTOCK.png" />
									<?php
										if($_GET['regerr']){
											echo '<div class="alert alert-warning">I campi della registrazione non sono stati compilati nel modo giusto, cosulta il link <i>Help</i>!</div>';
										}
										if($_GET['nameerr']){
											echo '<div class="alert alert-warning">Il nome utente esiste già, prova a fare qualche modifica al nome o al cognome!</div>';
										}
									?>
								</center>
								<form class="form-signup" action="signup.php" method="post">
									<input type="text" class="form-control" placeholder="Nome" name="nome" maxlength="50" required autofocus>
									<input type="text" class="form-control" placeholder="Cognome" name="cognome" maxlength="50" required>
									<input type="password" class="form-control" placeholder="Password" name="password" maxlength="255" required>
									<input type="password" class="form-control" placeholder="Ripeti password" name="passwordconf" maxlength="255" required>
									<input type="email" class="form-control" placeholder="E-Mail" name="email" maxlength="255" required>
									<button class="btn btn-lg btn-success btn-block" type="submit">Sign up</button>
								</form>
							</div>
							<div class="modal-footer">
								<a id="pop" class="help" data-toggle="popover" data-placement="left" data-html="true" title="Aiuto alla registrazione" 
								data-content="
									<font color='red'>Nome:</font> Caratteri A-z<br />
									<font color='red'>Cognome:</font> Caratteri A-z<br />
									<font color='red'>Password:</font> Deve avere 1 simbolo, 1 numero, 1 carattere maiuscolo e minuscolo e deve essere lunga almeno 8 caratteri.<br />
									<font color='red'>E-Mail:</font>Caratteri per una e-mail: <br />[a-Z, 0-9 e -_.]@[a-Z, 0-9 e -].[a-Z]<br />
									<hr />
									<b>Esempio:</b><br />
									<font color='red'>Nome:</font> Pinco<br />
									<font color='red'>Cognome:</font> Pallino<br />
									<font color='red'>Password:</font> Pallino0-<br />
									<font color='red'>Ripeti password:</font> Pallino0-<br />
									<font color='red'>E-Mail:</font> pinco.pallino@samt.ch<br />
									<br />
									In fine il tuo nome utente sarà <font color='red'>nome.cognome:</font>
									<font color='green'>pinco.pallino</font>
								">Help</a>
								<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal fade" id="pwLost" tabindex="-1" role="dialog" aria-labelledby="pwLost" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel">Recupero password</h4>
							</div>
							<div class="modal-body">
								<center>
									<img src="img/LogoSAMTSTOCK.png" />
									<?php
										if($_GET['notex']){
											echo '<div class="alert alert-warning">Il nome utente da te inserito non esiste nel nostro database!</div>';
										}
									?>
								</center>
								<form class="form-signup" action="pwrecovery.php" method="get">
									<p>La password verrà inviata all'utente via E-Mail.</p>
									<input type="text" class="form-control" placeholder="Nome utente" name="nomeutente" maxlength="101" required autofocus><br />
									
									<button class="btn btn-lg btn-success btn-block" type="submit">INVIA <span class="glyphicon glyphicon-envelope"  style="font-size: 15px"></span></button>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- javascript -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
			//funzione per l'apertura del popover
			$(function(){
			   $('#pop').popover();
			});
		</script>
		<?php
			//aprire il form si registrazione quando c'è uno di quei errori
			if($_GET['regerr'] || $_GET['nameerr']){
		?>
				<script>
					$(window).load(function(){
						$('#signUpModal').modal('show');
					});
				</script>
		<?php
			}
		?>
		
		<?php
			//aprire il form di recupero password se l'utente non esiste
			if($_GET['notex']){
		?>
				<script>
					$(window).load(function(){
						$('#pwLost').modal('show');
					});
				</script>
		<?php
			}
		?>
	</body>
</html>
