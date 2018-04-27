<!--Lista dei prodotti-->
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aggiungi categorie Magazino SAMT">
        <meta name="author" content="Angelo Sanker">

        <title>Gestione Prodotti</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/user.css" rel="stylesheet">

        <link rel="icon" type="image/png" href="img/favicon.png"> 
    </head>
    <body>
        <?php
        error_reporting(E_ALL ^ E_NOTICE);
        session_start();
        // controllo dei permessi
        if (!(isset($_SESSION['logged'])) || $_SESSION['power'] < 1) {
            echo '<div class="container">';
            echo "<h1>Area riservata, accesso negato.</h1>";
            echo "Per tornare alla home clicca <a href='index.php'><font color='blue'>qui</font></a>";
            echo '</div>';
            die;
        }
        $username = $_SESSION['username'];
        $name = $_SESSION['name'];
        $logged = $_SESSION['logged'];
        $power = $_SESSION['power'];
        ?>

        <?php include 'menu.php'; ?>

        <div class="container">
            <div class="page-header">
                <h1>Gestione prodotti</h1>
            </div>
            <!--form per la ricerca-->
            <form action="itemsG.php" method="post" role="form" class="input-group searchForm">
                <div class="col-lg-12">
                    <div class="input-group">
                        <input type="text" class="form-control input-lg searchInput" placeholder="Cerca il prodotto, tramite il suo nome" name="item">
                        <span class="input-group-btn">
                            <button class="btn btn-default input-lg searchInput" type="submit">CERCA</button>
                        </span>
                    </div>
                </div>
            </form>
            <?php
            //errori
            if ($_GET['deleted']) {
                echo '<center><div id="successmsg" class="alert alert-success">Prodotto cancellato con successo</div></center>';
            }
            if ($_GET['deleteall']) {
                echo '<center><div id="successmsg" class="alert alert-success">Tutti i prodotti selezionati sono stati cancellati con successo</div></center>';
            }
            if ($_GET['update']) {
                echo '<center><div id="successmsg" class="alert alert-success">Il prodotto è stato modificato con successo</div></center>';
            }
            if ($_GET['usenameerr']) {
                echo '<center><div id="successmsg" class="alert alert-warning">Il numero di serie da te scelto esiste già, le modifiche non sono potute essere fatte</div></center>';
            }
            ?>
            <?php
            include 'mysqlcon.php'; //connessione al database
            //recupero dati dai form 
            $item = $_POST['item'];

            //if per controllo se non è vuoto
            if (!empty($item)) {
                $result = mysqli_query($con, "SELECT * FROM prodotti  WHERE (NomeP LIKE '%" . $item . "%')"); //querry di ricerca
                $search = true;

                $trovati = mysqli_num_rows($result); //conto le righe trovate

                if ($trovati > 0) {//controllo che le righe trovate siano almeno 1
                    echo "<h4 class='back-link'>Trovati $trovati prodotti per la parola chiave <b>" . stripslashes($item) . "</b></h4>\n"; //stampo quante righe ho trovato
                    printTable($result, $search);
                } else {
                    echo "<h4 class='back-link'>Al momento non ci sono articoli che contengano i termini cercati.</h4>"; //in caso siano state 0 righe trovate stampo un messaggio
                }
                echo '<style>';
                echo '#allitems { display: none; }';
                echo '</style>';
            }
            mysqli_close($con); //chiudo la connessione
            ?>
            <div id="allitems">
                <?php
                include 'mysqlcon.php';
                // selezione dei prodotti nel database
                $result = mysqli_query($con, "SELECT ID_Prodotto, NomeP, NomeC, Modello, `Numero di serie`, Disponibile, Portabile, Aula, Descrizione, Prezzo, Quantita, File_PDF FROM prodotti p JOIN categorie c ON p.Categoria = c.ID_Categoria ORDER BY NomeP ASC;");
                $search = false;

                printTable($result, $search);

                mysqli_close($con);
                ?>
            </div>
            <button type="submit" class="btn btn-lg btn-danger" id="deleteButt" >DELETE</button>
            <a href="limiter.php" class="btn btn-lg btn-warning"><span class="glyphicon glyphicon-tasks"></span> Limiti prodotti</a>
            <a href="items.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-plus"></span> Prodotti</a>
            <a href="category.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-plus"></span> Categoria</a>
        </div>

        <?php

        //stampo tabella
        function printTable($result, $search) {
            echo '<table class="table table-bordered table-striped table-hover">';
            echo '<tr class="info">';
            if ($search) {
                echo '<th class="sizedTD"><input type="checkbox" id="selecctallSearch"></th>';
            } else {
                echo '<th class="sizedTD"><input type="checkbox" id="selecctall"></th>';
            }
            echo '<th>Nome</th>';
            echo '<th>Categoria</th>';
            echo '<th>Modello</th>';
            echo '<th>Numero di serie</th>';
            echo '<th class="sizedTD">Disponibile</th>';
            echo '<th class="sizedTD">Portabile</th>';
            echo '<th>Aula</th>';
            echo '<th>Prezzo</th>';
            echo '<th>Pezzi</th>';
            echo '<th class="sizedTD">Azioni</th>';
            echo '<th>PDF</th>';
            echo '</tr>';

            while ($row = mysqli_fetch_array($result)) {
                echo '';
                echo "<tr>";
                if ($search) {
                    echo '<td class="sizedTD"><input class="checkbox2" type="checkbox" name="cbArray[]" value="' . $row['ID_Prodotto'] . '"></td>';
                } else {
                    echo '<td class="sizedTD"><input class="checkbox1" type="checkbox" name="cbArray[]" value="' . $row['ID_Prodotto'] . '"></td>';
                }
//                echo "<td class='sizedTD'>" . $row['ID_Prodotto'] . "</td>";
                echo "<td>" . $row['NomeP'] . "</td>";
                echo "<td>" . $row['NomeC'] . "</td>";
                echo "<td>" . $row['Modello'] . "</td>";
                echo "<td>" . $row['Numero di serie'] . "</td>";
                echo "<td class='sizedTD'>" . ($row['Disponibile'] == 1 ? "Sì" : "No") . "</td>";
                echo "<td class='sizedTD'>" . ($row['Portabile'] == 1 ? "Sì" : "No") . "</td>";
                echo "<td>" . $row['Aula'] . "</td>";
                echo "<td style='text-align:right'>" . $row['Prezzo'] . ".-</td>";
                echo "<td style='text-align:center'>" . $row['Quantita'] . "</td>";
                echo '<td class="sizedTD">
								<a href="#Modal' . $row['ID_Prodotto'] . '" data-toggle="modal" data-target="#Modal' . $row['ID_Prodotto'] . '" class="actionLinks" title="Modifica">
									<i class="glyphicon glyphicon-edit"></i>
								</a>
								<a class="actionLinks" title="Cancella" onclick="deleterFunction(' . $row['ID_Prodotto'] . ')"> 
									<i class="glyphicon glyphicon-remove"></i>
								</a>
							</td>';
                if ($row['File_PDF'] != "NULL") {
                    echo "<td style='vertical-align: middle;'>
								<a href= " . $row['File_PDF'] . ">
									<img src='./img/pdf.png' whidth='22px' height='22px'>
								</a>
							</td>";
                } else {
                    echo "<td style='vertical-align: middle;'><img src='./img/NO.png' whidth='20px' height='20px'></td>";
                }
                echo "</tr>";
                ?>

                <div class="modal fade" id="<?php echo 'Modal' . $row['ID_Prodotto']; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'Modal' . $row['ID_Prodotto']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel">Modifica del prodotto <?php echo $row['NomeP']; ?></h4>
                            </div>
                            <div class="modal-body">
                                <form action="modifierP.php" method="post" name="modalForm">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <input type="text" style="display: none;" name="ID" value="<?php echo $row['Nome']; ?>" required>
                                                <input type="text" class="form-control input-lg" placeholder="Nome prodotto" name="nomeProdotto" maxlength="50" value="<?php echo $row['NomeP']; ?>" required autofocus>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="form-group select-editable">
                                                <?php
                                                include 'mysqlcon.php';
                                                $resultIN = mysqli_query($con, "SELECT * FROM categorie ORDER BY NomeC;"); //query per select
                                                ?>
                                                <select class="input-lg form-control" name="categoriaProdotto" required >
                                                    <?php
                                                    echo "<option value='' style='display:none;' disabled selected>Seleziona una categoria</option>";

                                                    while ($rowIN = mysqli_fetch_array($resultIN)) {
                                                        echo "<option value=$rowIN[ID_Categoria]>$rowIN[NomeC]</option>"; //stampo opzione della selezione
                                                    }
                                                    mysqli_close($con);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-5 col-sm-5 col-md-5">
                                            <div class="form-group">
                                                <input type="text" class="form-control input-lg" placeholder="Numero di serie" name="nDiSerie" maxlength="50" value="<?php echo $row['Numero di serie']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <input type="number" class="form-control input-lg" placeholder="Pezzi" name="quantita" min="1" value="<?php echo $row['Quantita']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">CHF</span>
                                                    <input type="number" class="form-control input-lg" placeholder="Prezzo" name="prezzo" min="0" value="<?php echo $row['Prezzo']; ?>">
                                                    <span class="input-group-addon">.-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-3 col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control input-lg" placeholder="Modello" name="modello" maxlength="50" value="<?php echo $row['Modello']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control input-lg" placeholder="Aula" name="aula" maxlength="10" value="<?php echo $row['Aula']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <input type="checkbox" name="disponibile" value="1" <?php echo ($row['Disponibile'] == 1 ? "checked" : "") ?>> &Egrave; diposnibile?<br/>
                                                <input type="checkbox" name="portabile" value="1" <?php echo ($row['Portabile'] == 1 ? "checked" : "") ?>> &Egrave; portabile a casa?
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <textarea class="form-control input-lg" placeholder="Descrizione" name="descrizione" maxlength="65535" style="resize: vertical;"><?php echo $row['Descrizione']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-edit"></span> Modifica</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            echo "</table>";
        }
        ?>

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            // Metodi per animazioni e azioni
            $(document).ready(function () {
                $("#successmsg").delay(2000).hide("slow"); //al caricamento della pagina aspetto 2 secondi e poi nascondo lentamente il messaggio



                $('#selecctall').click(function (event) {  //nel cliccare 
                    if (this.checked) { //controlli lo stato
                        $('.checkbox1').each(function () { //loop che passa per ogni checkboy con la classe checkbox1
                            this.checked = true;  //seleziona tutti i checkbox con la class "checkbox1"               
                        });
                    } else {
                        $('.checkbox1').each(function () { //loop che passa per ogni checkboy con la classe checkbox1
                            this.checked = false; //deseleziona tutti i checkbox con la class "checkbox1"                       
                        });
                    }
                });



                $('#selecctallSearch').click(function (event) {  //nel cliccare 
                    if (this.checked) { //controlli lo stato
                        $('.checkbox2').each(function () { //loop che passa per ogni checkboy con la classe checkbox2
                            this.checked = true;  //seleziona tutti i checkbox con la class "checkbox2"               
                        });
                    } else {
                        $('.checkbox2').each(function () { //loop che passa per ogni checkboy con la classe checkbox2
                            this.checked = false; //deseleziona tutti i checkbox con la class "checkbox2"                       
                        });
                    }
                });



                $('#deleteButt').click(function (event) { //al click del bottono
                    var cbArray = []; //creo array

                    $("input[name^=cbArray]:checked").each(function () { //per ogni checkbox checkkato con il nome cbArray
                        cbArray.push($(this).val()); //faccio il push dei dati nell'array
                    });

                    if (confirm("Sei sicuro di voler cancellare tutti i prodotti selezionati?") == true) { //chiede se sei sicuro
                        $.post('deleterP.php', {'cbArray': cbArray}); //faccio il post per il file deleter.php
                        window.location.href = "itemsG.php?deleteall=true"; //redirect con messaggio di conferma
                    }
                });
            });

            function deleterFunction(id) {
                if (confirm("Sei sicuro di voler cancellare questo prodotto?") == true) { //chiede se sei sicuro
                    window.location.href = "deleterP.php?delete=" + id; //fa il redirect
                }
            }
        </script>
    </body>
</html>