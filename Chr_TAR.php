<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Page title - Ath-PeptidesDB</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Description" lang="en" content="ADD SITE DESCRIPTION">
        <meta name="author" content="ADD AUTHOR INFORMATION">
        <meta name="robots" content="index, follow">

        <!-- Bootstrap Core CSS file -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">

        <!-- Override CSS file - add your own CSS rules -->
        <link rel="stylesheet" href="assets/css/styles.css">

    </head>
    <body>

        <!-- Navigation -->
        <nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
            <div class="container-fluid">

                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><b><font color=#E0E0E0>Ath-PeptidesDB</font></b></a>
                </div>
                <!-- /.navbar-header -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="TAR.php">StressPeps</a></li>
                        <li><a href="Hanada.php">sORFs</a></li>
                        <li><a href="LeaseWalker.php">SecretedPeptides</a></li>
                        <li><a href="#">Download</a></li>
                        <li><a href="#">Help</a></li>
                        <li><a href="contact_us.html">Contact us</a></li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
        <!-- /.navbar -->

        <!-- Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">

                    <!-- Page breadcrumb -->
                    <ol class="breadcrumb">
                        <li><a href="coverpage_peptides.html">Home</a></li>
                        <li><a href="">GBrowse</a></li>
                        <li><a href="form_input.html">Start searching</a></li>
                    </ol>

                    <!-- Heading levels -->

                    <h5 align="center">OVERVIEW</h5>
                    <hr>

                    <?php
                    $servername = "127.0.0.1"; $username = "root"; $password = "hello123";

                    //connection to the database
                    $conn = mysqli_connect($servername, $username, $password);

                    // Check connection
                    if ($conn -> connect_error) {
                        die("Connection failed: " . $conn -> connect_error);
                    } 
                    $dbhandle = mysql_connect($servername, $username, $password) or die("Unable to connect to MySQL");
                    //echo "Successfully connected to MySQL server <br>";

                    //select a database to work with
                    $selected = mysql_select_db("stress_peptides",$dbhandle) or die("Could not select stress_peptides");

                    if (isset($_POST['BC_Chromosome']) && isset($_POST['BC_ChrStart']) && isset($_POST['BC_ChrStop'])) {
                        $BC_id = $_POST['BC_Chromosome'];
                        $BC_id_start = $_POST['BC_ChrStart'];
                        $BC_id_stop = $_POST['BC_ChrStop'];
                        $PQ_id = $_POST['BC_Chromosome'];
                        $PQ_id_start = $_POST['BC_ChrStart'];
                        $PQ_id_stop = $_POST['BC_ChrStop'];
                    } else {
                        die("Invalid input");
                    }

                    $query = ("SELECT * FROM TAR_info WHERE BC_Chromosome = '$BC_id' AND BC_ChrStart >= '$BC_id_start' AND BC_ChrStop <= '$BC_id_stop' OR 
                        PQ_Chromosome = '$PQ_id' AND PQ_ChrStart >= '$PQ_id_start' AND PQ_ChrStop <= '$PQ_id_stop'");

                    $result = mysql_query($query) or die ("Error attempting query"); 

                    echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
                    echo "<tr><th>TAR ID</th><th>Biotic stress(BC)</th><th>log2 fold ratio</th><th>Chr</th><th>Chr Start</th><th>Chr Stop</th>
                        <th>Abiotic stress(PQ)</th><th>log2 fold ratio</th><th>Chr</th><th>Chr Start</th><th>Chr Stop</th></tr>";

                        while($row = mysql_fetch_assoc($result)) {
                        $a = $row ['TAR_ID'];
                        $b = $row ['BC'];
                        $c = $row ['BC_expression'];
                        $d = $row ['BC_Chromosome'];
                        $e = $row ['BC_ChrStart'];
                        $f = $row ['BC_ChrStop'];
                        $g = $row ['PQ'];
                        $h = $row ['PQ_expression'];
                        $i = $row ['PQ_Chromosome'];
                        $j = $row ['PQ_ChrStart'];
                        $k = $row ['PQ_ChrStop'];

                        echo '<tr align = "left">';
                        echo '<td><a href="http://localhost/TARwiki.php?TAR_ID='.$a.'">'.$a.'</td>';
                        echo '<td>'.$b.'</td>';
                        echo '<td>'.$c.'</td>';
                        echo '<td>'.$d.'</td>';
                        echo '<td>'.$e.'</td>';
                        echo '<td>'.$f.'</td>';
                        echo '<td>'.$g.'</td>';
                        echo '<td>'.$h.'</td>';
                        echo '<td>'.$i.'</td>';
                        echo '<td>'.$j.'</td>';
                        echo '<td>'.$k.'</td>';
                }
                echo "</table>";

                mysqli_close($conn);
                ?> 

