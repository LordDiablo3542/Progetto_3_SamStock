<?php
	session_start();
	
	include 'mysqlcon.php'; //connessione al db
	
	if (isset($_GET['nomeutente'])){ //se ci sono nomeutente e passsword
		//prendo i dati
		$username = $_GET['nomeutente'];
		
		//controllo che non sia SQL injection
		$username = mysqli_real_escape_string($con, $username);
		
		//faccio la select
		$query = "SELECT * FROM utenti WHERE username = '{$username}' LIMIT 1";
		$result = mysqli_query($con, $query)or die(mysql_error());
		
		//se i risualti non sono 1
		if (!$result->num_rows == 1) {
			//errore
			header("location: login.php?notex=true");
		} else {
			//prendo dati
			$row = mysqli_fetch_array($result);
			
			$newpass = randomPW();
			mysqli_query($con,"UPDATE utenti SET password = SHA1('{$newpass}') WHERE username = '{$username}'");
			
			$to = $row['EMail']; //invio a chi
			$subject = "Recupero password - " . $row['Nome'] . " " . $row['Cognome']; //oggetto
			$message = "La tua nuova password è: <b>" . $newpass . "</b>"; //messaggio
			$header = "Content-Type: text/html; charset=utf-8"; //testo/html utf-8
					  
			//chiudo connessione
			mysqli_close($con);
			
			$retval = mail($to, $subject, $message, $header); //invio e-mail
			
			if($retval == true) {
				header("location: login.php?send=true"); //redirect giusto
			}else {
				header("location: login.php?nosend=true"); //redirect errore
			}
		}
	}
	
	//funzione che crea una password random
	function randomPW(){
		$lunghezza = 12;
		$caratteri_disponibili = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$codice = "";
		for($i = 0; $i<$lunghezza; $i++){
			$codice = $codice.substr($caratteri_disponibili,rand(0,strlen($caratteri_disponibili)-1),1);
		}
		return $codice;
	}
?>
