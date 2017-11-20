<?php 
include 'headpager.html';
include 'menu_headpager.html'; 
include 'color_sequence.php';


$maxpeplen = 20; // maximum peptide length to display on the screen
$minlen = '';    // minimum peptide length to select
$maxlen = '';    // maximum peptide length to select

if (!isset($_GET['min_length']) && !isset($_GET['max_length'])) {
  //echo '<h2 align="center">Overview</h2>';
}

$chrnumber = '';
$chrstart = '';
$chrstop = '';

if (isset($_GET['Chromosome'])) $chrnumber = $_GET['Chromosome'];
if (isset($_GET['Chr_start'])) $chrstart = $_GET['Chr_start'];
if (isset($_GET['Chr_stop'])) $chrstop = $_GET['Chr_stop'];
if ($chrstart == '') $chrstart = 0;

$condition = '';
if ($chrnumber != '') {
  $condition = "WHERE Chrm = $chrnumber AND Chr_start >= $chrstart";
  echo '<p><b> Query results for chromosome position:</b> ';
  echo $chrnumber . ':' . $chrstart . '-';
  if ($chrstop != '') {
    echo $chrstop;
    $condition = "$condition AND Chr_stop <= $chrstop";
  }
  echo ' relative to TAIR10';
} else {
  die ("Error attempting query, please enter at least one field!" );
  //echo 'all';
}

$servername = "127.0.0.1"; $username = "root"; $password = "";
$table = 'merged_database';
$dbhandle = mysql_connect($servername, $username, $password) or die("Unable to connect to MySQL");
$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not select database");
$query = "SELECT * FROM $table $condition";
$result = mysql_query($query) or die ("Error attempting query $query");
$nhits = mysql_num_rows($result);

if ($nhits == 0) {
  echo "<ul><li>no hits.</li></ul>";
  mysql_close($dbhandle);
  include 'footer.html';
  return;
} elseif ($nhits == 1) {
  echo "<ul><li>only 1 hit:</li></ul>";
} else {
  echo "<ul><li>$nhits hits:</li></ul>";
}

if ($nhits > 10) {
  $tableid = 'id="example"';
} else {
  $tableid='';
}

$matches='';
//echo '<tr><td><button id="btn-export">export <span class="glyphicon glyphicon-download-alt"></button></span></td></tr>';
echo '<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-condensed table-striped table-bordered"' . $tableid . '>';
echo '<thead>';
echo '<tr><th>Peptide ID</th><th>Source</th><th>Chr</th><th>Strand</th><th>Chr start</th><th>Chr stop</th><th>Peptide sequence</th><th>Other Annotations</th></tr>';
echo '</thead>';
echo '<tbody>';
while($row = mysql_fetch_assoc($result)) {
    $a = $row ['Peptide_ID'];
    if (preg_match("/ath_mu/", $a, $matches)){
            $link = "<a href=LW.php?LW_ID=$a target='_blank'>$a</a>";
        } elseif (preg_match("/sORF/", $a, $matches)) {
            $link = "<a href=Hanada.php?sORF_ID=$a target='_blank'>$a</a>";
        } else {
            $link = "<a href=SIPs.php?SIP_ID=$a target='_blank'>$a</a>"; 
} 
    $b = $row ['Chrm'];
    $c = $row ['strand'];
    $d = $row ['Chr_start'];
    $e = $row ['Chr_stop'];
    $f = $row ['Peptide'];
    //if (strlen($f) > $maxpeplen) {$f = substr($f, 0, $maxpeplen).'...';}
    $f = colorAminoAcids($f);
    $g = $row ['Annot'];
if (preg_match("/ath_mu/", $g, $matches)){
            $link_Annot = "<a href=LW.php?LW_ID=$g target='_blank'>$g</a>";
        } elseif (preg_match("/sORF/", $g, $matches)) {
            $link_Annot = "<a href=Hanada.php?sORF_ID=$g target='_blank'>$g</a>";
        } else {
            $link_Annot = "<a href=SIPs.php?SIP_ID=$g target='_blank'>$g</a>"; 
} 
     $h = $row ['Source'];
                        
    echo '<tr>';
    echo '<td>'.$link.'</td>';
    echo '<td>'.$h.'</td>'; 
    echo '<td>'.$b.'</td>';
    echo '<td align="center">'.$c.'</td>';
    echo '<td>'.$d.'</td>';
    echo '<td>'.$e.'</td>';
    echo '<td>'.$f.'</td>';
    echo '<td>'.$link_Annot.'</td>';
    
}
echo '</tbody>';
echo '</table>';

mysql_close($dbhandle);
include 'footer.html';
?>


