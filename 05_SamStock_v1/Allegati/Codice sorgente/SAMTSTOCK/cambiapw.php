<html lang="it">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Cambia password Magazino SAMT">
		<meta name="author" content="Angelo Sanker">

		<title>Cambia password</title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/login.css" rel="stylesheet">
		
		<link rel="icon" type="image/png" href="img/favicon.png"> 
	</head>
	<body>
		<div class="container">
			<?php
				error_reporting(E_ALL ^ E_NOTICE);
				session_start();
				//se non sei loggato non puoi entrare qui
				if(!(isset($_SESSION['logged']))) {
					echo "<h1>Area riservata, accesso negato.</h1>";
					echo "Per effettuare il login clicca <a href='login.php'><font color='blue'>qui</font></a>";
					die;
				}
			?>
			<form action="changer.php" method="post" class="form-signin" role="form">
				<center>
					<h2 class="form-signin-heading">
						<img src="img/LogoSAMTSTOCK.png" />
					</h2>
					<?php
						//errori
						if($_GET['change']){
							echo '<div class="alert alert-danger">Errore! Le due password non corrispondono!</div>';
						}
						if($_GET['pass']){
							echo '<div class="alert alert-danger">Errore! La password non soddisfa i requisiti minimi!</div>';
						}
					?>
				</center>
				<input type="password" name="password1" class="form-control" placeholder="Nuova Password" maxlength="50" required autofocus>
				<input type="password" name="password2" class="form-control" placeholder="Ripeti Password" maxlength="50" required>
				<a id="pop" class="help botMarg" data-toggle="popover" data-placement="left" data-html="true" title="Aiuto alla registrazione" 
								data-content="
									<font color='red'>Password:</font> Deve avere 1 simbolo, 1 numero, 1 carattere maiuscolo, 1 carattere minuscolo e deve essere lunga almeno 8 caratteri.<br />">Help</a>
				<a href="index.php" class="pull-right botMarg">Torna all'index<span class="glyphicon glyphicon-chevron-right" aria-hidden="true" style="font-size: 12px"></span></a>
				<button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Cambia</button>
			</form>
		</div>
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
			//popover sugerimenti
			$(function(){
			   $('#pop').popover();
			});
		</script>
	</body>
</html>