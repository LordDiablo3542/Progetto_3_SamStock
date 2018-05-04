<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE);
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $name = $_SESSION['name'];
    $logged = $_SESSION['logged'];
    $power = $_SESSION['power'];
}
?>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <title>Welcome!  <?php if ($logged) {
    echo $name . "!";
} ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Magazino SAMT">
        <meta name="author" content="Angelo Sanker">
        <link rel="shortcut icon" href="img/favicon.ico">

        <!-- stili -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/index.css" rel="stylesheet">

        <link rel="icon" type="image/png" href="img/favicon.png"> 
    </head>
    <body>
        <!-- menu -->
<?php include 'menu.php'; ?>

        <!-- contenuto pagina -->
        <div class="container">
            <center>
                <form method="post" role="form" class="input-group searchForm">
                    <div class="col-lg-8">
                        <input type="text" class="form-control input-lg" placeholder="Cerca il prodotto per nome" name="item">
                    </div>
                    <?php
                    include 'mysqlcon.php';

                    $result = mysqli_query($con, "SELECT * FROM categorie_padre ORDER BY NomeCP;"); //selezioni categorie e ordino per nome
                    //creo un select prendendo le opzioni dal database
                    echo "<div class='col-lg-4'>";
                    echo "<div class='input-group'>";
//----------------------------------------------MODIFICA: Aggiunta dello style per allargare il select
                    echo "<select name='category' class='input-lg form-control categoryForm' style='width: 215px;'>
						<option value='' style='display:none;' disabled selected>Seleziona categoria</option>";

                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option disabled>&HorizontalLine;&HorizontalLine; $row[NomeCP] &HorizontalLine;&HorizontalLine;</option>";
                        $padre = mysqli_query($con, "SELECT * FROM Categorie WHERE Categoria_padre = " . $row['ID_categoria_padre'] . " ORDER BY NomeC;");
                        while ($figli = mysqli_fetch_array($padre)) {
                            echo "<option value= $figli[ID_Categoria] >$figli[NomeC]</option>";
                        }
                    }
                    echo "</select>";
                    echo "<span class='input-group-btn'>
								<button class='btn btn-default input-lg categoryForm' type='submit'>CERCA</button>
							</span></div></div>";
                    mysqli_close($con);
                    ?>

                </form>
            </center>

            <?php
            include 'mysqlcon.php'; //connessione al database
            //recupero dati dai form 
            $item = $_POST['item'];
            $category = $_POST['category'];

            //if per prodotto e categoria
            if (!empty($item) and ! empty($category)) {//controllo che siano tutti e due riempiti 
                $result = mysqli_query($con, "SELECT *, SUM(if(Disponibile = 1, 1, 0)) AS Totale FROM (SELECT * FROM prodotti, categorie  WHERE prodotti.Categoria = categorie.ID_Categoria AND (Categoria = " . $category . ") AND (NomeP LIKE '%" . $item . "%') ORDER BY Disponibile DESC) AS tbFilter GROUP BY NomeP ORDER BY Disponibile DESC"); //querry di ricerca
                $result2 = mysqli_query($con, "SELECT NomeC FROM categorie WHERE categorie.ID_Categoria = $category");

                $cName = mysqli_fetch_array($result2);

                $trovati = mysqli_num_rows($result); //conto le righe trovate

                if ($trovati > 0) {//controllo che le righe trovate siano almeno 1
                    echo "<h4 class='back-link'>Trovate $trovati voci per il termine <b>" . stripslashes($item) . "</b> nella categoria <b>" . stripslashes($cName['NomeC']) . "</b></h4>\n"; //stampo quante righe ho trovato
                    printTable($result);
                } else {
                    echo "<h4 class='back-link'>Al momento non ci sono articoli che contengano i termini cercati.</h4>"; //in caso siano state 0 righe trovate stampo un messaggio
                }
                echo '<style>';
                echo '#homeContainer { display: none; }';
                echo '</style>';

                //if per solo categoria
            } else if (empty($item) and ! empty($category)) {//controllo che siano tutti e due riempiti 
                $result = mysqli_query($con, "SELECT *, SUM(if(Disponibile = 1, 1, 0)) AS Totale FROM (SELECT * FROM prodotti, categorie  WHERE (prodotti.Categoria = categorie.ID_Categoria) AND (Categoria = " . $category . ") AND (NomeP LIKE '%" . $item . "%') ORDER BY Disponibile DESC) AS tbFilter GROUP BY NomeP ORDER BY Disponibile DESC"); //querry di ricerca
                $result2 = mysqli_query($con, "SELECT NomeC FROM categorie WHERE categorie.ID_Categoria = $category");

                $cName = mysqli_fetch_array($result2);

                $trovati = mysqli_num_rows($result); //conto le righe trovate

                if ($trovati > 0) {//controllo che le righe trovate siano almeno 1
                    echo "<h4 class='back-link'>Trovate $trovati voci per la categoria <b>" . stripslashes($cName['NomeC']) . "</b>\n"; //stampo quante righe ho trovato
//----------------------------------------------MODIFICA: Aggiunta dell'icona che porta alla visualizzazione semplice
                    echo "<a target='_blank' href='simpleViewCategoria.php?categoria=" . stripslashes($cName['NomeC']) . "'><img src='img/Items/view.ico' width='40' height='40'/></a></h4>";
                    printTable($result);
                } else {
                    echo "<h4 class='back-link'>Al momento non ci sono articoli che contengano i termini cercati.</h4>"; //in caso siano state 0 righe trovate stampo un messaggio
                }
                echo '<style>';
                echo '#homeContainer { display: none; }';
                echo '</style>';

                //if per solo prodotto
            } else if (!empty($item) and empty($category)) {//controllo che siano tutti e due riempiti 
                $result = mysqli_query($con, "SELECT *, SUM(if(Disponibile = 1, 1, 0)) AS Totale FROM (SELECT * FROM prodotti, categorie WHERE prodotti.Categoria = categorie.ID_Categoria AND (NomeP LIKE '%" . $item . "%') ORDER BY Disponibile DESC) AS tbFilter GROUP BY NomeP ORDER BY Disponibile DESC"); //querry di ricerca

                $trovati = mysqli_num_rows($result); //conto le righe trovate

                if ($trovati > 0) {//controllo che le righe trovate siano almeno 1
                    echo "<h4 class='back-link'>Trovate $trovati voci per il termine <b>" . stripslashes($item) . "</b></h4>\n"; //stampo quante righe ho trovato
                    printTable($result);
                } else {
                    echo "<h4 class='back-link'>Al momento non ci sono articoli che contengano i termini cercati.</h4>"; //in caso siano state 0 righe trovate stampo un messaggio
                }
                echo '<style>';
                echo '#homeContainer { display: none; }';
                echo '</style>';

                //if per nulla
            } else if (empty($item) and empty($category)) {
                
            }

            mysqli_close($con); //chiudo la connessione
            ?>

            <div id="homeContainer">
                <center>
                    <div id="slideshow" class="carousel slide">
                        <!-- Indicatori di posizione -->
                        <ol class="carousel-indicators">
                            <li data-target="#slideshow" data-slide-to="0" class="active"></li>
                            <li data-target="#slideshow" data-slide-to="1"></li>
                            <li data-target="#slideshow" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <?php
                            include 'mysqlcon.php';

                            $result = mysqli_query($con, "SELECT * FROM prodotti, categorie WHERE prodotti.Categoria = categorie.ID_Categoria GROUP BY NomeP ORDER BY RAND() LIMIT 3;"); //selezioni prodotti per lo slideshow raggruppati per nome in modo da non avere due volte lo stesso prodotto

                            $i = 0;
                            while ($row = mysqli_fetch_array($result)) {
                                if ($i == 0) {
                                    ?>
                                    <div class="item active">
                                        <img src="<?php echo $row['Icona'] ?>">
                                        <div class="carousel-caption">
                                            <h3 class="carouselTitle"><a class="carrLink" href="product.php?name=<?php echo $row['NomeP'] . "&id=" . $row['ID_Prodotto'] ?>"><?php echo $row['NomeP'] ?></a></h3>
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                } else {
                                    ?>
                                    <div class="item">
                                        <img src="<?php echo $row['Icona'] ?>" >
                                        <div class="carousel-caption">
                                            <h3 class="carouselTitle"><a class="carrLink" href="product.php?name=<?php echo $row['NomeP'] . "&id=" . $row['ID_Prodotto'] ?>"><?php echo $row['NomeP'] ?></a></h3>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            mysqli_close($con);
                            ?>
                        </div>
                    </div>
                </center>

                <div class="page-header">
                    <h1>Ultimi inseriti!</h1>
                </div>
                <?php
                include 'mysqlcon.php';

                $result = mysqli_query($con, "SELECT *, COUNT(NomeP) as Totale FROM prodotti, categorie WHERE prodotti.Categoria = categorie.ID_Categoria AND Disponibile <> 0 GROUP BY NomeP ORDER BY DateTime DESC LIMIT 5;"); //selezione ultimi 5 prodotti aggiunti ragruppati per nome se sono disponibili, ordino per la data e limito i risultati a 5

                printTable($result);

                mysqli_close($con);

                //funzione per la creazione della tabella
                function printTable($result) {
                    echo '<table class="table table-bordered table-striped">';
                    echo '<tr class="info">';
                    echo '<th>Nome</th>';
                    echo '<th>Categoria</th>';
                    echo '<th>Modello</th>';
                    echo '<th>Aula</th>';
                    echo '<th style="width: 100px;">Disponibili</th>';
                    echo '<th style="width: 150px;">Prezzo</th>';
                    echo '<th style="width: 100px;">Pezzi</th>';
                    echo '</tr>';

                    while ($row = mysqli_fetch_array($result)) {
                        //stampa dei nomi in rosso se non disponibile
                        if ($row['Disponibile'] == 1) {
                            //stampa dei nomi in verde se quantità maggiore 1
                            if ($row['Quantita'] > 1) {
                                echo "<tr class='tableRow' style='color: green;'>";
                                echo "<td style='vertical-align: middle; width: 400px;' >
										<a style='color: green;' href='product.php?name=" . $row['NomeP'] . "&id=" . $row['ID_Prodotto'] . "' id='name'>" . $row['NomeP'] . "</a>
										<img src='" . $row['Icona'] . "' class='itemImg' /></td>";
                            } else {
                                echo "<tr class='tableRow'>";
                                echo "<td style='vertical-align: middle; width: 400px;' >
										<a href='product.php?name=" . $row['NomeP'] . "&id=" . $row['ID_Prodotto'] . "' id='name'>" . $row['NomeP'] . "</a>
										<img src='" . $row['Icona'] . "' class='itemImg' /></td>";
                            }
                        } else {
                            echo "<tr class='tableRow' style='color: red;'>";
                            echo "<td style='vertical-align: middle; width: 400px;' >
									<a style='color: red;' href='product.php?name=" . $row['NomeP'] . "&id=" . $row['ID_Prodotto'] . "' id='name'>" . $row['NomeP'] . "</a>
									<img src='" . $row['Icona'] . "' class='itemImg' /></td>";
                        }

                        echo "<td style='vertical-align: middle;'>" . $row['NomeC'] . "</td>";
                        echo "<td style='vertical-align: middle;'>" . $row['Modello'] . "</td>";
                        echo "<td style='vertical-align: middle;'>" . $row['Aula'] . "</td>";
                        echo "<td style='vertical-align: middle;text-align:center'>" . $row['Totale'] . "</td>";
                        if ($row['Prezzo'] == 0) {
                            echo "<td style='vertical-align: middle;text-align:right;width: 150px;'>Non in vendita</td>";
                        } else {
                            echo "<td style='vertical-align: middle;text-align:right;width: 150px;'>CHF " . $row['Prezzo'] . ".-</td>";
                        }
                        echo "<td style='vertical-align: middle;text-align:center'>" . $row['Quantita'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                ?>
            </div>
        </div>

        <!-- footer -->
        <div class="footer">
            <div class="container">
                <p class="text-muted">Powered by Angelo Sanker Copyright &copy; 2014 SAMT.</p>
            </div>
        </div>

        <!-- javascript -->
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.carousel').carousel({
                    interval: 3500,
                    cycle: true
                })
            });
        </script>
    </body>
</html>