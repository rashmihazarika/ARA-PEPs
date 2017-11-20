<?php
include 'headpager.html'; 
include 'menu_headpager.html'; 
include 'color_sequence.php';
?>

<?php
$maxpeplen =30; // maximum peptide length to display on the screen
$minlen = '';    // minimum peptide length to select
$maxlen = '';    // maximum peptide length to select
$dnds = '';      // if non-empty, select only peptides with dN/dS < 1 but not 0
$sigseq = '';    // if non-empty, select only peptides with signal sequence
$TM = ''; // if non empty, select only peptides with TM domains

if (!isset($_GET['min_length']) && !isset($_GET['max_length'])) {
  echo '<h2 align="center">Overview</h2>';
  echo '<h4>De Coninck et al., 2013 and Hazarika et al., 2017</h4>';
}

if (isset($_GET['min_length'])) $minlen = $_GET["min_length"];
if (isset($_GET['max_length'])) $maxlen = $_GET["max_length"];
if (isset($_GET['dnds'])) $dnds = $_GET["dnds"];
if (isset($_GET['sigseq'])) $sigseq = $_GET["sigseq"];
if (isset($_GET['TM'])) $TM = $_GET["TM"];

if ($minlen != '' && ! is_numeric($minlen) || $maxlen != '' && ! is_numeric($maxlen)) {
  die("invalid peptide lengths: " . $minlen . ", " . $maxlen);
}

echo '<p> <b>Peptide length:</b> ';
if ($minlen == '' && $maxlen == '') {
  echo 'any';
} elseif ($maxlen == '') {
  echo 'minimum ' . $minlen . ' amino acids';
} elseif ($minlen == '') {
  $minlen = '1';
  echo 'maximum ' . $maxlen . ' amino acids';
} else {
  echo ' between ' . $minlen . ' and ' . $maxlen;
  echo ' amino acids';
}

if ($dnds != '') echo '; <b>conserved peptides</b> shown (dN/dS < 1)';
if ($sigseq != '') echo '; peptides with <b>signal sequence</b> shown';
if ($TM != '') echo '; peptides with <b>TM domains</b> shown';

echo '</p><p>';

//----------------------------------------------------------------------------------------------
$servername = "127.0.0.1"; $username = "root"; $password = ""; $database = "OSIP";

$dbhandle = mysql_connect($servername, $username, $password) 
or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysql_select_db($database,$dbhandle) or die("Could not find database");

$table1 = 'SIP_info';
$head = "SELECT TAR_ID, Other_Annotations, Source, SIP_ID, peptide_sequence, length_of_peptide, dNdS FROM $table1";

#extracting peptides with secretion signals 
$table_sigP = 'signal_peptides_info';
$head_sigP = "SELECT p.TAR_ID, p.SIP_ID, p.peptide_sequence, p.length_of_peptide, dNdS";
$head_sigP = "$head_sigP FROM $table1 p INNER JOIN $table_sigP s ON s.SIP_ID = p.SIP_ID";

#extracting peptides with TM Domains
$table_TM = 'TMdomains_info';
$head_TM = "SELECT p.TAR_ID, p.SIP_ID, p.peptide_sequence, p.length_of_peptide, dNdS";
$head_TM = "$head_TM FROM $table1 p INNER JOIN $table_TM s ON s.SIP_ID = p.SIP_ID";

#condition for filtering by length
$condition = '';
if ($minlen != '') {
  $condition = " WHERE length_of_peptide >= $minlen ";
  if ($maxlen != '') {
    $condition = "$condition AND length_of_peptide <= $maxlen ";
  }

#condition for dN/dS ratio 
if ($dnds != '') {
    $condition = "$condition AND dNdS < 1 ";
  }
} elseif ($dnds != '') {
    $condition = " WHERE dNdS < 1 ";
}

#build the query
$tail = ";";
if ($sigseq != '') {
    $query = "$head_sigP$condition$tail";
}
    else if ($TM != '') {
    $query = "$head_TM$condition$tail";
} 
    else {
    $query = "$head$condition$tail";
} 

//echo '<p>'.$query.'</p>';

$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);

if ($nhits == 0) {
  echo "<ul><li>no records.</li></ul>";
  mysql_close($dbhandle);
  include 'footer.html';
  return;
} elseif ($nhits == 1) {
  echo "<ul><li>only 1 record</li></ul>";
} 
//else {echo "<ul><li>$nhits records</li></ul>";}

if ($nhits > 10) {
  $tableid = 'id="example"';
} else {
  $tableid='';
}

$matches='';
//echo '<tr><td><button id="btn-export">export <span class="glyphicon glyphicon-download-alt"></button></span></td></tr>';
echo '<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-condensed table-striped table-bordered"' . $tableid . '>';
echo '<thead>';

echo "<tr> <th>TAR ID</th> <th>SIP ID</th> <th>Other Annotations</th><th>peptide sequence</th> <th>peptide length</th> <th>dN/dS</th></tr>";
echo '</thead>';
echo '<tbody>';

while($row = mysql_fetch_array($result)) {
    $f = $row ['dNdS'];
    if ($dnds == '' || is_numeric($f) && $f < 1.0 && $f != 0) {
      $a = $row ['TAR_ID'];
      //$a = "<a href=TARinfotracks.php?TAR_ID=$a>$a</a>";
      //$a = "<a href=TAR/$a>$a</a>";
      $a = "<a href=TAR.php?TAR_ID=$a>$a</a>";
      $b = $row ['SIP_ID'];
      //$c = "<a href=peptide_info.php?SIP_id=$b>$b</a>";
      $c = "<a href=SIPs.php?SIP_ID=$b>$b</a>";
      $annot = $row ['Other_Annotations'];
       if (preg_match("/ath_mu/", $annot, $matches)){
            $link_Annot = "<a href=LW.php?LW_ID=$annot target='_blank'>$annot</a>";
        } elseif (preg_match("/sORF/", $annot, $matches)) {
            $link_Annot = "<a href=Hanada.php?sORF_ID=$annot target='_blank'>$annot</a>";
        } else {
            $link_Annot = "<a href=SIPs.php?SIP_ID=$annot target='_blank'>$annot</a>"; 
	}
      #$src = $row ['Source'];
      $s = $row ['peptide_sequence'];
      $l = $row ['length_of_peptide'];
      //if (strlen($s) > $maxpeplen) {
        //$s = substr($s, 0, $maxpeplen).'...';
      //}
      $s = colorAminoAcids($s);
      echo '<tr>';
      echo '<td> &nbsp;'.$a.'</td>';
      echo '<td> &nbsp;'.$c.'</td>';
      echo "<td> $link_Annot </td>";
      #echo "<td> $src </td>";
      echo '<td>'. $s. '</td>';
      echo '<td align="right">'.$l.'&nbsp; </td>';
      echo '<td> &nbsp;' . $f . '</td>';
    }
}
echo '</tbody>';
echo '</table>';

mysql_close($dbhandle);

include 'footer.html';

?>  

