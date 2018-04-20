<?php
	/*
	 *	Codice php per la creazione di un nuovo utente
	 */

	include 'mysqlcon.php';
	
	//regex per il controllo dei dati inserirti
	$regexpass = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/';
	//$regexusername = '/^[a-zA-Z]+[\.][a-zA-Z]+$/';
	$regexname = '/^[a-zA-Z]+$/';
	$regexemail = '/^[a-zA-Z0-9._-]+[@][a-zA-Z0-9-]+[\.][a-zA-Z]+$/';
	
	//variabili flag
	$bpass = false;
	$bpassconf = false;
	$bnome = false;
	$bcognome = false;
	$bpwuguali = false;
	$bemail = false;
	
	//prendo dati
	$nome = $_POST['nome'];
	$cognome = $_POST['cognome'];
	$password = $_POST['password'];
	$passwordconf = $_POST['passwordconf'];
	$email = $_POST['email'];
	
	//se sono settati
	if(isset($_POST['nome']) && isset($_POST['password']) && isset($_POST['passwordconf']) && isset($_POST['cognome']) && isset($_POST['email'])) {
		//se matcha con il regex
		if (preg_match($regexname, $nome)) {
			//setto la variabile a true
			$bnome = true;
		} else {header("location: login.php?regerr=true");}
		
		//se matcha con il regex
		if(preg_match($regexpass, $password)){
			//setto la variabile a true
			$bpass = true;
		}else {header("location: login.php?regerr=true");}
		
		//se matcha con il regex
		if(preg_match($regexpass, $passwordconf)){
			//setto la variabile a true
			$bpassconf = true;
		}else {header("location: login.php?regerr=true");}
		
		//se matcha con il regex
		if(preg_match($regexname, $cognome)){
			//setto la variabile a true
			$bcognome = true;
		}else {header("location: login.php?regerr=true");}
		
		//se le password sono uguali
		if($password == $passwordconf){
			//setto la variabile a true
			$bpwuguali = true;
		}else {header("location: login.php?regerr=true");}
		
		//se matcha con il regex
		if(preg_match($regexemail, $email)){
			//setto la variabile a true
			$bemail = true;
		}else {header("location: login.php?regerr=true");}
		
		//se le variabili sono a true
		if($bnome && $bpass && $bpassconf && $bcognome && $bpwuguali && $bemail){
			//controllo che non sia SQL injection
			$nome = mysqli_real_escape_string($con, $nome);
			$password = mysqli_real_escape_string($con, $password);
			$cognome = mysqli_real_escape_string($con, $cognome);
			$email = mysqli_real_escape_string($con, $email);
			
			//metto minuscolo
			$nomea = strtolower($nome);
			$cognomea = strtolower($cognome);
			
			//creo nome utente
			$username = $nomea. "." .$cognomea;
			
			//insert dei dati
			$query = "INSERT INTO utenti(Nome, Cognome, Username, Password, EMail) VALUES ('$nome', '$cognome', '$username', SHA1('$password'), '$email')";
			if (!mysqli_query($con,$query)) {
				//errore
				die(header("location: login.php?nameerr=true"));
			}
			//redirect login
			header("location: login.php?registered=true");
		}
	}
?>