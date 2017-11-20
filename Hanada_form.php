<?php 
include 'header.html'; 
include 'menu.html'; 
?>

<h2 align="center">sORFs-DB by Hanada et al., 2013</h2>


<?php
$servername = "127.0.0.1"; $username = "root"; $password = "";
$table = 'Hanada_sORF_info';
$dbhandle = mysql_connect($servername, $username, $password) or die("Unable to connect to MySQL");
$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not select database");
$query = "SELECT COUNT('sORF_ID') FROM $table";
$result = mysql_query($query) or die ("Error attempting query $query");
$nrecords = mysql_result($result, 0);
echo "<p> Contains $nrecords records.</p>";
mysql_close($dbhandle);
?>

<h4>Reference:</h4>
<ul><li>
Hanada K, Higuchi-Takeuchi M, Okamoto M, Yoshizumi T, Shimizu M, Nakaminami K,
Nishi R, Ohashi C, Iida K, Tanaka M, Horii Y, Kawashima M, Matsui K, Toyoda T,
Shinozaki K, Seki M, Matsui M.
<a href="http://www.ncbi.nlm.nih.gov/pubmed/23341627" target="_blank">
Small open reading frames associated with morphogenesis are hidden in plant genomes.</a>
Proc Natl Acad Sci U S A. 2013 Feb 5;110(6):2395-400. 
</li></ul>
<ul><li>
<a href="http://evolver.psc.riken.jp/seiken/" target="_blank">HanaDB-AT</a
</li></ul>

<!-- search form for Hanada db -->
<div class="well">
    <FORM METHOD=GET ACTION="Hanada.php">
       <div class="form-group">
         <h3>Search specific chromosomal positions:</h3>
         <p>
            <b>Chromosome: </b><input type="number" name="Chromosome" placeholder="1"><br>
            <b>Chromosome start: </b><input type="number" name="Chr_start" placeholder="10000000"><font color="grey">(relative to TAIR10)</font><br>
            <b>Chromosome stop: </b><input type="number" name="Chr_stop" placeholder="20000000">
            <button type="submit" class="btn btn-primary name="submit">submit</button>
            <button type="reset" class="btn btn-default">clear</button>
            </div>
            </FORM>
        </div> 
    </body>
</html>

<?php 
include 'footer.html';
?>

