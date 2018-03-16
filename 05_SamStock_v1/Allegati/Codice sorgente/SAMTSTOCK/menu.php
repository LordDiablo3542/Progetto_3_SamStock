<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php"><img class="navbar-brand-logo" src="img/LogoSAMTSTOCK.png" /></a>
		</div>
		<div class="collapse navbar-collapse" id="navbar">
			<ul class="nav navbar-nav">
				<li><a href="index.php">Home</a></li>
				<!-- se l'utente è gestore o amministratore-->
				<?php if($power >= 1){ ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Prodotti 
							<?php
								include 'mysqlcon.php';
								$result = mysqli_query($con,"SELECT * FROM prodotti WHERE Quantita < Limite;"); //query per select prodotti sotto limite									
								$num_righe = mysqli_num_rows($result); //conto quante righe
								
								//se il numero di righe è magiore di 0 stampo
								if($num_righe > 0){
									echo '<span class="badge"> '. $num_righe .'</span>'; //stampo il numero di righe, cioè il numero di prodotti sotto limite
								}
								
								mysqli_close($con);
							?>
						<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="itemsG.php"><span class="glyphicon glyphicon-dashboard"></span> Gestione prodotti</a></li>
							<li><a href="limiter.php"><span class="glyphicon glyphicon-tasks"></span> Limiti prodotti
								<?php
									//se il numero di righe è magiore di 0 stampo
									if($num_righe > 0){
										echo '<span class="badge"> '. $num_righe .'</span>'; //stampo il numero di righe, cioè il numero di prodotti sotto limite
									}
								?>
							</a></li>
							<li><a href="items.php"><span class="glyphicon glyphicon-plus"></span> Prodotti</a></li>
							<li><a href="category.php"><span class="glyphicon glyphicon-plus"></span> Categorie</a></li>
						</ul>
					</li>
					<li><a href="reservation.php">Riservazioni
							<?php
								include 'mysqlcon.php';
								$result = mysqli_query($con,"SELECT ID_Utente FROM utenti WHERE username = '$username';"); //query per select ID utente loggato con power >=1
								$row = mysqli_fetch_array($result);
								$idutente = $row['ID_Utente'];
								
								$result2 = mysqli_query($con,"SELECT * FROM prodotti, utenti WHERE prodotti.Riservato = utenti.ID_Utente AND Responsabile = '$idutente' AND Riservato IS NOT NULL"); //seleziono tutte le riservazioni fatte ai prodotti di cui l'utente è responsbile
								$num_righe = mysqli_num_rows($result2); //conto quante righe
								
								//se il numero di righe è magiore di 0 stampo
								if($num_righe > 0){
									echo '<span class="badge"> '. $num_righe .'</span>'; //stampo il numero di righe, cioè il numero di riservazioni
								}
								
								mysqli_close($con);
							?>
						</a>
					</li>
				<?php } ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if($logged){ ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo  $name ?> <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<!-- se l'utente è amministratore -->
							<?php if($power == 2){ ?>
								<li><a href="user.php"><span class="glyphicon glyphicon-user"></span> Gestione Utenti</a></li>
								<hr />
							<?php } ?>
							<li><a href="cambiapw.php"><span class="glyphicon glyphicon-wrench"></span> Cambia password</a></li>
							<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>
						</ul>
					</li>
				<?php } else{ ?>
					<li><a href="login.php">Log in</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>