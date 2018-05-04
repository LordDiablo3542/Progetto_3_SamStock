<html>
    <head>
        <!-- stili -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/index.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="img/favicon.png"> 
    </head>
    <style>
        td {
            padding: 30px;
        }
        
        tr:nth-child(odd)
        {
            background-color: #F9F9F9;
        }
        
        th {
            border: #d3d3d3 1px solid;
            background-color: #F0FFFF; 
        }
    </style>
    <body>
        <?php
        include 'mysqlcon.php';
        include 'barcodeReader/barcode.php';

        $categoria = $_GET['categoria'];
       
        $id_cat = mysqli_query($con, "SELECT ID_Categoria FROM categorie WHERE NomeC LIKE '" . $categoria . "';");

        $barcodeGenerator = new barcode_generator();
        $symbolUPC = 'ean-13';
        $symbolQR = 'qr';
        $counter = 0;
        echo "<table style='text-align: center; margin: auto; width: 96%; margin-left: 2%; margin-right: 2%px; border-collapse: collapse;'>";
        echo "<th colspan=4><h1 style='margin: auto; text-align: center;'>Categoria: " . $categoria . "</h1></th>";
        while ($row1 = mysqli_fetch_array($id_cat)) {
            $result = mysqli_query($con, "SELECT * FROM prodotti WHERE Categoria LIKE '" . $row1["ID_Categoria"] . "';");

            while ($row2 = mysqli_fetch_array($result)) {
                $data = $row2['Numero di serie'];
                $img = $row2["Icona"];

                $imagePathUPC = 'img/barcodeGeneratedUPC' . $counter . '.png';
                $imagePathQR = 'img/barcode_GeneratedQR' . $counter . '.png';

                $imageUPC = $barcodeGenerator->render_image($symbolUPC, $data, '');
                $imageQR = $barcodeGenerator->render_image($symbolQR, $data, '');

                imagepng($imageUPC, $imagePathUPC);
                imagepng($imageQR, $imagePathQR);
                imagedestroy($imageUPC);
                imagedestroy($imageQR);

                echo "<tr style='margin: auto; border-bottom: 1px solid #d3d3d3;'>";
                echo "<td style='width:25%;'><h3 style='font-size: 30px;'>" . $row2['NomeP'] . "</h3></td>";
                echo "<td style='width:25%;'><img src='$imagePathUPC' width='200' height='150' /></td>";
                echo "<td style='width:25%;'><img src='$imagePathQR' width='150' height='150' /></td>";
                echo "<td style='width:25%;'><img src='$img'  width='150' height='150' /></td>";
                echo "</tr>";
                $counter++;
            }
        }
        echo "</table>";
        ?>
        <!-- footer -->
        <div class="footer">
            <div class="container">
                <p class="text-muted">Powered by Angelo Sanker Copyright &copy; 2014 SAMT.</p>
            </div>
        </div>
        <script>
            $(document).ready( function()
            {
                $("tr:odd").css({
                    "background-color":"#000";
                    "color":"#fff";
                });
            });
        </script>
    </body>
</html>