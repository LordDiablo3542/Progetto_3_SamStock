<?php
	session_start();
	
	include 'mysqlcon.php'; //connessione al db
	
	if (isset($_POST['username']) and isset($_POST['password'])){ //se ci sono nomeutente e passsword
		//prendo i dati
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		//controllo che non sia SQL injection
		$username = mysqli_real_escape_string($con, $username);
		$password = mysqli_real_escape_string($con, $password);
		
		//faccio la select
		$query = "SELECT * from utenti WHERE username = '{$username}' AND password = SHA1('{$password}') LIMIT 1";
		$result = mysqli_query($con, $query)or die(mysql_error());
		
		//se i risualti non sono 1
		if (!$result->num_rows == 1) {
			//errore
			header("location: login.php?login=false");
		} else {
			//prendo dati
			$row = mysqli_fetch_array($result);
			//sessione utente
			$_SESSION['username'] = $username;
			//sessione nome
			$_SESSION['name'] = $row['Nome'];
			//potere per le pagine (admin, gestore, user)
			$_SESSION['power'] = $row['Power'];
			//potere per le pagine
			$_SESSION['email'] = $row['EMail'];
			//utente loggato
			$_SESSION['logged'] = true;
			
			//chiudo connessione
			mysqli_close($con);
			
			//redirect index
			header("location: index.php");
		}
	}
?>
