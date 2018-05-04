<!-- Codice per lînserimento di prodotti-->
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aggiungi prodotti Magazino SAMT">
        <meta name="author" content="Angelo Sanker">

        <title>Aggiungi Prodotti</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/gestion.css" rel="stylesheet">
        <link href="css/prodotti.css" rel="stylesheet">

        <link rel="icon" type="image/png" href="img/favicon.png"> 
    </head>
    <body>
        <div class="container">
            <?php
            ob_start();
            error_reporting(E_ALL ^ E_NOTICE);
            session_start();
            if (!(isset($_SESSION['logged'])) || $_SESSION['power'] < 1) {
                echo "<h1>Area riservata, accesso negato.</h1>";
                echo "Per tornare alla home clicca <a href='index.php'><font color='blue'>qui</font></a>";
                die;
            }
            $username = $_SESSION['username'];
            $name = $_SESSION['name'];
            $logged = $_SESSION['logged'];
            $power = $_SESSION['power'];

            include 'mysqlcon.php';
            $result = mysqli_query($con, "SELECT ID_Utente FROM utenti WHERE username = '$username';"); //query per select dell'id del utente loggato
            $row = mysqli_fetch_array($result);
            $idutente = $row['ID_Utente'];
            mysqli_close($con);

            include 'menu.php';
            ?>
            <div class="page-header">
                <h1>Aggiungi prodotti</h1>
            </div>
            <?php
            if ($_GET['added']) {
                echo '<center><div id="successmsg" class="alert alert-success">Prodotto aggiunto con successo</div></center>';
            }
            if ($_GET['nameerr']) {
                echo '<center><div id="successmsg" class="alert alert-warning back-link">Il numero di serire inserito è già stato usato</div></center>';
            }
            if ($_GET['exist']) {
                echo '<center><div id="successmsg" class="alert alert-warning back-link">L\'immagine con il nome ' . $_GET['exist'] . ' esiste già</div></center>';
            }
            ?>
            <form role="form" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control input-lg" placeholder="Nome prodotto" name="nomeProdotto" maxlength="50" required autofocus>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group select-editable">
                            <?php
                            include 'mysqlcon.php'; //connessione al db

                            //$result = mysqli_query($con, "SELECT * FROM categorie ORDER BY NomeC;"); //query per select
                            $result = mysqli_query($con, "SELECT * FROM categorie_padre ORDER BY NomeCP;");
                            ?>
                            <select onchange="this.nextElementSibling.value = this.options[this.selectedIndex].text;" class="input-lg form-control">
                                <?php
                                echo "<option value='' style='display:none;' disabled selected>Seleziona una categoria</option>";

                                // while ($row = mysqli_fetch_array($result)) {
                                //     echo "<option value=$row[ID_Categoria]>$row[NomeC]</option>"; //stampo opzione della selezione
                                // }

                                while ($row = mysqli_fetch_array($result)) {
                                   echo "<option disabled>&HorizontalLine;&HorizontalLine; $row[NomeCP] &HorizontalLine;&HorizontalLine;</option>";
                                   $padre = mysqli_query($con, "SELECT * FROM Categorie WHERE Categoria_padre = ".$row['ID_categoria_padre']." ORDER BY NomeC;");
                                   while ($figli = mysqli_fetch_array($padre)) {
                                       echo "<option value= $figli[ID_Categoria] >$figli[NomeC]</option>";
                                    }
                                }

                                echo "</select>";
                                ?>
                                <input name="category" type="text" class="form-control input-lg" value="" required>
                                </div>
                                </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <span class="help-block">Carica un file pdf riguardate il prodotto</span>
                                            <input type="file" class="form-control input-lg" placeholder="File_pdf" name="file_pdf" id="file_pdf">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-7 col-sm-7 col-md-7">
                                        <div class="form-group">
                                            <span class="help-block">Carica una immagine del prodotto. (consigliate immagini con altezza = larghezza)</span>
                                            <input type="file" class="form-control input-lg" placeholder="Icona" name="icona" id="icona">
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2">
                                        <div class="form-group">
                                            <span class="help-block">&nbsp;</span>
                                            <div class="input-group">
                                                <input type="number" class="form-control input-lg" placeholder="Pezzi" name="quantita" min="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <span class="help-block">&nbsp;</span>
                                            <div class="input-group">
                                                <span class="input-group-addon">CHF</span>
                                                <input type="number" class="form-control input-lg" placeholder="Prezzo" name="prezzo" min="0" required>
                                                <span class="input-group-addon">.-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control input-lg" placeholder="Numero di serie" name="nDiSerie" maxlength="50" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control input-lg" placeholder="Modello" name="modello" maxlength="50">
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control input-lg" placeholder="Aula" name="aula" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2">
                                        <div class="form-group">
                                            <input type="checkbox" name="disponibile" value="1"> Diposnibile<br/>
                                            <input type="checkbox" name="portabile" value="1"> Portabile a casa
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control input-lg" placeholder="Descrizione" name="descrizione" maxlength="65535" style="resize: vertical;"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" name="submit" class="btn btn-lg btn-success btn-block"><span class="glyphicon glyphicon-plus"></span> Aggiungi</button>

                                </form>
                                <div>
                                    <a href="itemsG.php">Gestione prodotti</a>
                                    |
                                    <a href="category.php">Aggiungi categorie</a>
                                </div>
                        </div>
                        <?php
                        include 'mysqlcon.php'; //connessione database
                        //assegno i valori e controllo che non ci siano caratteri problematici
                        $nomeProdotto = mysqli_real_escape_string($con, $_POST['nomeProdotto']);
                        $categoria = mysqli_real_escape_string($con, $_POST['category']);
                        $nDiSerie = mysqli_real_escape_string($con, $_POST['nDiSerie']);

                        $modello = mysqli_real_escape_string($con, $_POST['modello']);
                        $disponibile = mysqli_real_escape_string($con, $_POST['disponibile']);
                        $portabile = mysqli_real_escape_string($con, $_POST['portabile']);
                        $aula = mysqli_real_escape_string($con, $_POST['aula']);
                        $descrizione = mysqli_real_escape_string($con, $_POST['descrizione']);
                        $prezzo = mysqli_real_escape_string($con, $_POST['prezzo']);
                        $quantita = mysqli_real_escape_string($con, $_POST['quantita']);

                        //metto un valore alle variabili vuote
                        if (empty($modello)) {
                            $modello = "-";
                        }
                        if (empty($disponibile)) {
                            $disponibile = 0;
                        }
                        if (empty($portabile)) {
                            $portabile = 0;
                        }
                        if (empty($aula)) {
                            $aula = "-";
                        }
                        if (empty($descrizione)) {
                            $descrizione = "Al momento non c\'è una descrizione disponibile per questo prodotto.";
                        }
                        if (empty($quantita)) {
                            $quantita = 1;
                        }

                        //prendo i dati del file
                        $icona_dir = "img/Items/";
                        $icona_name = basename($_FILES["icona"]["name"]);
                        $icona = $icona_dir . $icona_name;

                        //se il nome è uguale a solo quello della cartella vuol dire che il campo file era vuoto e metto l'immagine di base.
                        if ($icona == $icona_dir) {
                            $icona = "img/Items/noicon.png";
                        } else {
                            //rendo l'immagine con nome univoco
                            $time = microtime();
                            $icona = $icona_dir . $time . $icona_name;

                            // Controllo se il file esiste già
                            if (file_exists($icona)) {
                                //errore il file esiste
                                header("location: items.php?exist=" . $icona_name);
                                DIE;
                            } else {
                                //sposto il file
                                move_uploaded_file($_FILES["icona"]["tmp_name"], $icona);
                            }
                        }

                        //prendo i dati del file pdf
                        $pdf_dir = "pdf/";
                        $pdf_name = basename($_FILES["file_pdf"]["name"]);
                        $pdf = $pdf_dir . $pdf_name;

                        //se il nome è diverso della cartella vuol dire che il campo file non è vuoto quindi assegno il alore.
                        if ($pdf != $pdf_dir) {
                            //rendo il file con nome univoco
                            $time = microtime();
                            $pdf = $pdf_dir . $time . $pdf_name;
                            $pdf = str_replace(' ', '_', $pdf);
                            // Controllo se il file esiste già
                            if (file_exists($pdf)) {
                                //errore il file esiste
                                header("location: items.php?exist=" . $pdf_name);
                                DIE;
                            } else {
                                //sposto il file
                                move_uploaded_file($_FILES["file_pdf"]["tmp_name"], $pdf);
                            }
                        } else {
                            $pdf = "NULL";
                        }


                        //controllo che non siano vuoti
                        if (!empty($categoria)) {
                            if (!mysqli_query($con, "INSERT INTO categorie(NomeC) VALUES ('$categoria')")) { //query INSERT
                                //in caso di errore faccio nulla
                            }
                            mysqli_close($con); //chiudo connessione
                        }

                        include 'mysqlcon.php';
                        if (!empty($nomeProdotto) && !empty($categoria) && !empty($nDiSerie)) {
                            //INSERT INTO prodotti VALUES (NULL, '$nomeProdotto', '$categoria', '$modello', '$nDiSerie', '$disponibile', '$portabile', '$icona_name', '$aula', NOW())

                            if ($quantita > 1) {
                                //query con select dall'altra tabella dell'ID del nome della categoria e riservato da responsabile
                                if (!mysqli_query($con, "INSERT INTO prodotti (ID_Prodotto, NomeP, Categoria, Modello, `Numero di serie`, Disponibile, Portabile, Icona, File_PDF, Aula, DateTime, Descrizione, Riservato, Responsabile, Prezzo, Quantita)
					SELECT NULL, '$nomeProdotto', ID_Categoria, '$modello', '$nDiSerie', '$disponibile', '$portabile', '$icona', '$pdf', '$aula', NOW(), '$descrizione', '$idutente', '$idutente', '$prezzo', '$quantita' FROM categorie WHERE NomeC = '$categoria'")) {
                                    die(header("location: items.php?nameerr=true")); //errore
                                }
                            } else {
                                //query con select dall'altra tabella dell'ID del nome della categoria
                                if (!mysqli_query($con, "INSERT INTO prodotti (ID_Prodotto, NomeP, Categoria, Modello, `Numero di serie`, Disponibile, Portabile, Icona, File_PDF, Aula, DateTime, Descrizione, Responsabile, Prezzo, Quantita)
					SELECT NULL, '$nomeProdotto', ID_Categoria, '$modello', '$nDiSerie', '$disponibile', '$portabile', '$icona', '$pdf', '$aula', NOW(), '$descrizione', '$idutente', '$prezzo', '$quantita' FROM categorie WHERE NomeC = '$categoria'")) {
                                    die(header("location: items.php?nameerr=true")); //errore
                                }
                            }

                            mysqli_close($con);
                            header("location: items.php?added=true"); //fatto
                        }
                        ?>
                        <script src="js/jquery.js"></script>
                        <script src="js/bootstrap.min.js"></script>
                        <script>
                                //nascondo il messaggio dopo 2 secondi
                                $(document).ready(function () {
                                    $("#successmsg").delay(2000).hide("slow");
                                });
                        </script>
                        </body>
                        </html>