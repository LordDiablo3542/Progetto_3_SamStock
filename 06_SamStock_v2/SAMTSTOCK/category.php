<!--Pagina per la gestione delle categorie-->
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aggiungi categorie Magazino SAMT">
        <meta name="author" content="Angelo Sanker">

        <title>Aggiungi Categorie</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/gestion.css" rel="stylesheet">

        <link rel="icon" type="image/png" href="img/favicon.png"> 
    </head>
    <body>

        <div class="container">
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
            include 'menu.php';
            ?>
            <div class="page-header">
                <h1>Aggiungi categorie</h1>
            </div>
            <?php
            // Avvisi
            if ($_GET['added']) {
                echo '<div id="successmsg" class="alert alert-success back-link">Categoria aggiunta con successo.</div>';
            }
            if ($_GET['nameerr']) {
                echo '<div id="successmsg" class="alert alert-warning back-link">La categoria esiste già.</div>';
            }
            if ($_GET['deleted']) {
                echo '<div id="successmsg" class="alert alert-success back-link">Categoria cancellata con successo.</div>';
            }
            if ($_GET['catdelerr']) {
                echo '<div id="successmsg" class="alert alert-warning back-link">La categoria non è stata cancellata perché contiene dei prodotti al suo interno.</div>';
            }
            ?>
            <!--Form di inserimento-->
            <form method="post" role="form">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div style="margin-bottom: 10px;">
                            <input type="text" class="form-control input-lg" placeholder="Nome categoria" name="categoryName" required autofocus>
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group" style="margin-bottom: 10px;">
                                <?php
                                include 'mysqlcon.php'; //connessione al db

                                $result = mysqli_query($con, "SELECT * FROM categorie_padre ORDER BY NomeCP;"); //query per select
                                ?>
                                <select onchange="this.nextElementSibling.value = this.options[this.selectedIndex].text;" class="input-lg form-control"
                                        placeholder="Nome categoria padres" name="categoryPadreName" required autofocus>
                                <?php
                                    echo "<option value='' style='display:none;' disabled selected>Categoria padre</option>";
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<option value=$row[ID_categoria_padre]>$row[NomeCP]</option>"; //stampo opzione della selezione
                                    }
                                    echo "</select>";                                    
                                ?>
                        </div>
                    </div>

                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div style="margin-bottom: 10px;">
                            <button class="btn btn-success input-lg" type="submit" style="width: 100%">
                                <span class="glyphicon glyphicon-plus"></span> 
                                Aggiungi
                            </button>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <input type="checkbox" name="isPadre" onchange="changePadre()" value="1"> Padre<br/>
                        </div>
                    </div>
                </div>
            </form>
            <div class="back-link">
                <a href="itemsG.php">Gestione prodotti</a>
                |
                <a href="items.php">Aggiungi prodotto</a>
            </div><br />
            <br />
            <?php
            // Selezione degli elementi nella tabella database

            include 'mysqlcon.php';

            $result = mysqli_query($con, "SELECT * FROM Categorie_padre ORDER BY NomeCP;"); //prendo tutti gli utenti

            echo '<h1>Elimina categorie</h1>';

            printTable($result, $con);

            //stampo tabella
            function printTable($result, $con) {
                echo '<table class="table table-bordered table-striped table-hover">';
                echo '<tr class="info">';
                echo '<th>Nome</th>';
                echo '<th class="sizedTD">Azioni</th>';
                echo '</tr>';
                
                while ($row = mysqli_fetch_array($result)) {
                    
                    echo "<tr Style='background-color: white;'>";
                    echo "<td>" . $row['NomeCP'] . "</td>";
                    echo "<td class='sizedTD'>
                        <a class='actionLinks' title='Cancella' onclick='deleterPFunction(" . $row['ID_categoria_padre'] . ")'> 
                            <i class='glyphicon glyphicon-remove' style='font-size: 22px;'></i>
                        </a>
                    </td>";
                    echo "</tr>";
                    $padre = mysqli_query($con, "SELECT * FROM Categorie WHERE Categoria_padre = ".$row['ID_categoria_padre']." ORDER BY NomeC;");
                    
                    while ($figli = mysqli_fetch_array($padre)) {
                        echo "<tr Style='background-color: #f9f9f9;'>";
                        echo "<td Style='padding-left: 30px'>" . $figli['NomeC'] . "</td>";
                        echo "<td class='sizedTD'>
                            <a class='actionLinks' title='Cancella' onclick='deleterFunction(" . $figli['ID_Categoria'] . ")'> 
                                <i class='glyphicon glyphicon-remove' style='font-size: 22px;'></i>
                            </a>
                        </td>";
                        echo "</tr>";
                    }
                    
                }
                echo "</table>";
            }
            mysqli_close($con);
            ?>
        </div>

        <?php
        include 'mysqlcon.php'; //connessione database

        $categoryName = mysqli_real_escape_string($con, $_POST['categoryName']); //prendo dati
        
        $ispadre = $_POST['isPadre'];
        if (empty($ispadre)) {
            $isadre = 0;
        }
        //controllo che non siano vuoti
        if (!empty($categoryName)) {
            // inserimento della categoria
            if($ispadre == 0){
                $categoryPadreName = $_POST['categoryPadreName'];
                if (!mysqli_query($con, "INSERT INTO categorie(NomeC,Categoria_padre) VALUES ('$categoryName',".$categoryPadreName.")")) { //query INSERT
                    die(header("location: category.php?nameerr=true")); //errore
                }
            }
            else{
                if (!mysqli_query($con, "INSERT INTO categorie_padre(NomeCP) VALUES ('$categoryName')")) { //query INSERT
                    die(header("location: category.php?nameerr=true")); //errore
                }
            }
            mysqli_close($con); //chiudo connessione
            header("location: category.php?added=true"); //redirect
        }

        if (isset($_GET['delete'])) {
            $id = $_GET['delete']; //prendo dato
            // Cancellazione della categoria
            if (!mysqli_query($con, "DELETE FROM categorie WHERE ID_Categoria='$id'")) { //cancello
                die(header("location: category.php?catdelerr=true")); //errore
            }
            mysqli_close($con);

            header("location: category.php?deleted=true"); //redirect
        }
        
        if (isset($_GET['deleteP'])) {
            $id = $_GET['deleteP']; //prendo dato
            // Cancellazione della categoria
            if (!mysqli_query($con, "DELETE FROM Categorie_padre WHERE ID_categoria_padre='$id'")
                    || !mysqli_query($con, "DELETE FROM categorie WHERE Categoria_padre='$id'")) { //cancello
                die(header("location: category.php?catdelerr=true")); //errore
            }
            mysqli_close($con);

            header("location: category.php?deleted=true"); //redirect
        }
        ?>

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#successmsg").delay(2000).hide("slow");
            });

            function deleterFunction(id) {
                if (confirm("Sei sicuro di voler cancellare questa categoria?") == true) { //chiede se sei sicuro
                    window.location.href = "category.php?delete=" + id; //fa il redirect
                }
            }
            
            function deleterPFunction(id) {
                if (confirm("Sei sicuro di voler cancellare questa categoria e le sue categorie derivanti?") == true) { //chiede se sei sicuro
                    window.location.href = "category.php?deleteP=" + id; //fa il redirect
                }
            }
            
            function changePadre(){
                var c = document.getElementsByName("isPadre")[0];
                var s = document.getElementsByName("categoryPadreName")[0];
                
                if(c.checked == true){
                    s.disabled = true;
                }
                else{
                    s.disabled = false;
                }
            }
        </script>
    </body>
</html>