<?php 
include 'header.html'; 
include 'menu.html'; 
?>

<h2 align="center">SecretedPeptides-DB by Lease and Walker, 2006</h2>


<?php
$servername = "127.0.0.1"; $username = "root"; $password = "AraDB7168#";
$table = 'LeaseWalker_peptide_info';
$dbhandle = mysql_connect($servername, $username, $password) or die("Unable to connect to MySQL");
$selected = mysql_select_db("OSIP", $dbhandle) or die("Could not select database");
$result = mysql_query("SELECT COUNT('LW_ID') FROM {$table}");
$nrecords = mysql_result($result, 0);
echo "<p> Contains $nrecords records.</p>";
mysql_close($dbhandle);
?>

<h4>Reference:</h4>
<ul><li>Kevin A. Lease and John C. Walker, 
<a href="http://www.plantphysiol.org/content/142/3/831.full.pdf" target="_blank">
The Arabidopsis Unannotated Secreted Peptide Database, a Resource for Plant Peptidomics</a>,
Plant Physiology, November 2006, Vol. 142, pp. 831-838.</li></ul>

<!-- search form for Lease & Walker db -->
<div class="well">

    <FORM METHOD=GET ACTION="LeaseWalker.php">
       <div class="form-group">
        
         <h3>Search specific chromosomal positions:</h3>
	    <p>
            <b>Chromosome: </b><input type="number" name="Chromosome" placeholder="1">
            <br>
            <b>Chromosome start: </b><input type="number" name="Chr_start" placeholder="10000000">
            <font color="grey">(relative to TAIR10)</font><br>
            <b>Chromosome stop: </b><input type="number" name="Chr_stop" placeholder="20000000">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-default">Clear</button>
        </div>         
     </FORM>
</div>


<?php 
include 'footer.html';
?>

