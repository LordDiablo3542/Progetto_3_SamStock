<?php
	session_start(); //starto la sessione
	session_destroy(); //la chiudo
	
	//se password è stata cmabiata o se si è fatto il login (varibili per stampa messaggio nel login)
	if($_GET['change']){
		header("location: login.php?change=true");
	}else {
		header("location: login.php?loggedout=true");
	}
?>