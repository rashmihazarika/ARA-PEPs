<?php 
include 'headpager.html';
include 'menu_headpager.html'; 
include 'color_sequence.php';


$maxpeplen = 50; // maximum peptide length to display on the screen
$minlen = '';    // minimum peptide length to select
$maxlen = '';    // maximum peptide length to select

if (!isset($_GET['min_length']) && !isset($_GET['max_length'])) {
  echo '<h2 align="center">Overview</h2>';
  echo '<h4>Lease & Walker, 2006</h4>';
}


$servername = "127.0.0.1"; $username = "root"; $password = "";
$table = 'LW_pep_info';
$dbhandle = mysql_connect($servername, $username, $password) or die("Unable to connect to MySQL");
$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not select database");
$query = "SELECT * FROM $table" ;
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
echo '<tr><th>LW ID</th><th>Other Annotations</th><th>Peptide sequence</th><th>Length</th>';
echo '</thead>';
echo '<tbody>';

while($row = mysql_fetch_assoc($result)) {
    $a = $row ['LW_ID'];
    $b = $row ['LW_OtherAnnot'];
if (preg_match("/ath_mu/", $b, $matches)){
            $link_Annot = "<a href=LW.php?LW_ID=$b target='_blank'>$b</a>";
        } elseif (preg_match("/sORF/", $b, $matches)) {
            $link_Annot = "<a href=Hanada.php?sORF_ID=$b target='_blank'>$b</a>";
        } else {
            $link_Annot = "<a href=SIPs.php?SIP_ID=$b target='_blank'>$b</a>"; 
} 
    $c = $row ['LW_pep'];
	//if (strlen($c) > $maxpeplen) {
        	//$c = substr($c, 0, $maxpeplen).'...';
         //}
      	$c = colorAminoAcids($c);
    $d = $row ['LW_pep_len'];
                            
    echo '<tr>';
    echo '<td><a href="LW.php?LW_ID='.$a.'">'.$a.'</td>'; 
    echo '<td>'.$link_Annot.'</td>';
    echo '<td>'.$c.'</td>';
    echo '<td>'.$d.'</td>';
   
}
echo '</tbody>';
echo '</table>';

mysql_close($dbhandle);

include 'footer.html';

?>
