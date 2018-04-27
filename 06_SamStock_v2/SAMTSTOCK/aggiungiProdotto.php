<!--Pagina per la gestione delle categorie-->
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aggiungi categorie Magazino SAMT">
        <meta name="author" content="Angelo Sanker">

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/gestion.css" rel="stylesheet">

        <link rel="icon" type="image/png" href="img/favicon.png"> 
    </head>
    <body>
        <?php
        ob_start();
        error_reporting(E_ALL ^ E_NOTICE);
        session_start();
        // Controllo dei permessi dell'utente
        if (!(isset($_SESSION['logged'])) || $_SESSION['power'] < 1) {
            echo "<h1>Area riservata, accesso negato.</h1>";
            echo "Per tornare alla home clicca <a href='index.php'><font color='blue'>qui</font></a>";
            die;
        }
        $username = $_SESSION['username'];
        $name = $_SESSION['name'];
        $logged = $_SESSION['logged'];
        $power = $_SESSION['power'];
        
        $id = $_GET['idprod'];
        
        include 'mysqlcon.php';
        if(!mysqli_query($con, "UPDATE prodotti SET quantita = quantita+1 WHERE ID_Prodotto= $id")){
            header("location: itemsG.php?updateerr=true");
        }else{
            header("location: itemsG.php?update=true");
        }
        ?>
    </body>
</html>