<?php 
include 'headpager.html';
include 'menu_headpager.html'; 
include 'color_sequence.php';


$maxpeplen = 50; // maximum peptide length to display on the screen
$minlen = '';    // minimum peptide length to select
$maxlen = '';    // maximum peptide length to select

if (!isset($_GET['min_length']) && !isset($_GET['max_length'])) {
  echo '<h2 align="center">Overview</h2>';
  echo '<h4>Hanada et al., 2007, 2013</h4>';
}

$servername = "127.0.0.1"; $username = "root"; $password = "";
$dbhandle = mysql_connect($servername, $username, $password) or die("Unable to connect to MySQL");
$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not select database");

$query = "SELECT * FROM sORF_pep_info LEFT JOIN Hanada_dNdS_info ON sORF_pep_info.sORF_ID = Hanada_dNdS_info.ID" ;
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

//echo '<tr><td><button id="btn-export">export <span class="glyphicon glyphicon-download-alt"></button></span></td></tr>';
//echo '<br/>';
echo '<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-condensed table-striped table-bordered"' . $tableid . '>';
echo '<thead>';
echo '<tr><th>sORF ID</th><th>Other Annotations</th><th>Peptide sequence</th><th>Length</th><th>dN/dS</th><th>p-value(dN/dS)</th></tr>';
echo '</thead>';
echo '<tbody>';

//select * from sORF_pep_info JOIN Hanada_dNdS_info ON Hanada_dNdS_info.sORF_ID = sORF_pep_info.sORF_ID;

while($row = mysql_fetch_assoc($result)) {
    $a = $row ['sORF_ID'];
    $b = $row ['sORF_otherAnnot'];
if (preg_match("/ath_mu/", $b, $matches)){
            $link_Annot = "<a href=LW.php?LW_ID=$b target='_blank'>$b</a>";
        } elseif (preg_match("/sORF/", $b, $matches)) {
            $link_Annot = "<a href=Hanada.php?sORF_ID=$b target='_blank'>$b</a>";
        } else {
            $link_Annot = "<a href=SIPs.php?SIP_ID=$b target='_blank'>$b</a>"; 
} 
    $c = $row ['sORF_pep'];
	//if (strlen($c) > $maxpeplen) {
        	//$c = substr($c, 0, $maxpeplen).'...';
         //}
      	$c = colorAminoAcids($c);
    $d = $row ['sORF_pep_len'];
    $e = $row ['dNdS'];
    $f = $row ['Pval'];
                            
    echo '<tr>';
    echo '<td><a href="Hanada.php?sORF_ID='.$a.'">'.$a.'</td>'; 
    echo '<td>'.$link_Annot.'</td>';
    echo '<td>'.$c.'</td>';
    echo '<td>'.$d.'</td>';
    echo '<td>'.$e.'</td>';
    echo '<td>'.$f.'</td>';
   
}
echo '</tbody>';
echo '</table>';

mysql_close($dbhandle);

include 'footer.html';

?>
