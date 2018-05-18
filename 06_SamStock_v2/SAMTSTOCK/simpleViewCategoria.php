<?php 
$categoria = $_GET['categoria'];
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- stili -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/index.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="img/favicon.png"> 
        <script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <title>Prodotti di <?php echo $categoria; ?></title>
    </head>
    <style>        
        img {
            max-width: 100%;
            min-width: 90px;
            width: 200px;
            height: auto;
            margin: auto;
        }
        
        h3 {
            font-size: 35px;
            text-align: center;
            margin: auto;
        }
        
        h1 {
            margin: 10px; 
            text-align: center;
            background: #f2f2f2;
            border: gray 1px solid;
            min-height: 43px;
        }
        
        p {
            text-align: center;
        }
        
        .row:nth-of-type(odd) {
            background: #edfeff;
        }
        .row:nth-of-type(even) {
           background: #FFFFFF;
        }
        
        .row {
            text-align: center;
            border: 1px gray solid;
            margin-left: 10px;
            margin-right: 10px;
            height: auto;
        }
        
        .cell {
            display: table-cell;
            text-align: center;
            display: flex;
            height: 35%;
            align-items: center;
            margin: auto;
        }
        
        .footer {
            margin-top: auto;
            height: auto;
        }
        button {
            float: left;
            max-height: 40px;
            vertical-align: middle;
        }
        
        i {
            height: auto;
            width: auto;
            vertical-align: middle;
        }
    </style>
    <body id="bodyId">
        <?php
        include 'mysqlcon.php';
        include 'barcodeReader/barcode.php';
       
        $id_cat = mysqli_query($con, "SELECT ID_Categoria FROM categorie WHERE NomeC LIKE '" . $categoria . "';");

        $barcodeGenerator = new barcode_generator();
        $symbolUPC = 'ean-13';
        $symbolQR = 'qr';
        $counter = 0;
//        echo "<div class='cont' id='cont'><table>";
//        echo "<th colspan=4><button id='printButton' onclick='printPage()'><i class='glyphicon glyphicon-print'></i></button><h1 id='titolo'>Categoria: " . $categoria . "</h1></th>";
//        while ($row1 = mysqli_fetch_array($id_cat)) {
//            $result = mysqli_query($con, "SELECT * FROM prodotti WHERE Categoria LIKE '" . $row1["ID_Categoria"] . "';");
//
//            while ($row2 = mysqli_fetch_array($result)) {
//                $data = $row2['Numero di serie'];
//                $img = $row2["Icona"];
//
//                $imagePathUPC = 'img/barcodeGeneratedUPC' . $counter . '.png';
//                $imagePathQR = 'img/barcode_GeneratedQR' . $counter . '.png';
//
//                $imageUPC = $barcodeGenerator->render_image($symbolUPC, $data, '');
//                $imageQR = $barcodeGenerator->render_image($symbolQR, $data, '');
//
//                imagepng($imageUPC, $imagePathUPC);
//                imagepng($imageQR, $imagePathQR);
//                imagedestroy($imageUPC);
//                imagedestroy($imageQR);             
//                
//                echo "<tr>";
//                        echo "<td><h3>" . $row2['NomeP'] . "</h3></td>";
//                        echo "<td><img src='$img' /></td>";
//                        echo "<td><img src='$imagePathUPC' /></td>";
//                        echo "<td><img src='$imagePathQR' /></td>";
//                echo "</tr>";
//                $counter++;
//            }
//        }
//        echo "</table></div>";
        
        echo "<form method='post' action='printSimpleViewCategoria.php/?categoria=".$categoria."'><h1><button id='printButton'><i class='glyphicon glyphicon-print'></i></button>Prodotti di ".$categoria."</h1></form>";
        
        while ($row1 = mysqli_fetch_array($id_cat))
        {
            $result = mysqli_query($con, "SELECT * FROM prodotti WHERE Categoria LIKE '" . $row1["ID_Categoria"] . "';");
        
            while($row2 = mysqli_fetch_array($result)) 
            {
                $data = $row2['Numero di serie'];
                $link = "localhost:3600/aggiungiprodotto.php?idprod=".$row2["ID_Prodotto"];
                $img = $row2['Icona'];
                
                $imagePathUPC = 'img/barcodeGeneratedUPC' . $counter . '.png';
                $imagePathQR = 'img/barcode_GeneratedQR' . $counter . '.png';
                
                $imageUPC = $barcodeGenerator->render_image($symbolUPC, $data, '');
                $imageQR = $barcodeGenerator->render_image($symbolQR, $link, '');

                imagepng($imageUPC, $imagePathUPC);
                imagepng($imageQR, $imagePathQR);
                imagedestroy($imageUPC);
                imagedestroy($imageQR);
                
                echo '<div class="row">';
                    echo '<div class="col-md-3 col-xs-6 divTableCell cell"><h3>'.$row2['NomeP'].'</h3></div>';
                    echo '<div class="col-md-3 col-xs-6 divTableCell cell"><img src="'.$img.'" /></div>';
                    echo '<div class="col-md-3 col-xs-6 divTableCell cell"><img src="'.$imagePathUPC.'" /></div>';
                    echo '<div class="col-md-3 col-xs-6 divTableCell cell"><img src="'.$imagePathQR.'" /></div>';
                echo '</div>';
                $counter++;
            }
        }
        ?>
        <div id="editor"></div>
        <!-- footer -->
        <div class="footer">
            <div class="container">
                <p class="text-muted">Powered by Angelo Sanker Copyright &copy; 2014 & Luca Rausa - Elia Manassero &copy; 2018 SAMT.</p>
            </div>
        </div>
    </body>
</html>