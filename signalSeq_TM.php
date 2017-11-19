<?php

include 'headpager.html'; 
include 'menu_headpager.html'; 
include 'color_sequence.php';

$maxpeplen = 50; // maximum peptide length to display on the screen
$minlen = '';    // minimum peptide length to select
$maxlen = '';    // maximum peptide length to select
$dnds = '';      // if non-empty, select only peptides with dN/dS < 1 but not 0
$sigseq = '';    // if non-empty, select only peptides with signal sequence
$TM = ''; // if non empty, select only peptides with TM domains
$all = ''; // if non empty, select only peptides with TM domains


if (isset($_GET['feature'])) $feature = $_GET['feature'];
if (isset($_GET['min_length'])) $minlen = $_GET["min_length"];
if (isset($_GET['max_length'])) $maxlen = $_GET["max_length"];
if (isset($_GET['dnds'])) $dnds = $_GET["dnds"];
if (isset($_GET['sigseq'])) $sigseq = $_GET["sigseq"];
if (isset($_GET['TM'])) $TM = $_GET["TM"];
if (isset($_GET['all'])) $TM = $_GET["all"];

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

if ($dnds != '') echo '; <b>conserved peptides</b> (dN/dS < 1)';
//if ($sigseq != '') echo '; peptides with <b>signal sequence</b> shown';
//if ($TM != '') echo '; peptides with <b>TM domains</b> shown';
//if ($all != '') echo '; all peptides </b> shown';

echo '</p><p>';

//------------------------------------------------------------------------------------------------------------------------------------

$servername = "127.0.0.1"; $username = "root"; $password = "AraDB7168#"; $database = "OSIP";

$dbhandle = mysql_connect($servername, $username, $password) 
or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysql_select_db($database,$dbhandle) or die("Could not find database");

$union = " UNION ";
$head1 = "select sORF_ID, sORF_pep, sORF_pep_len, dNdS from sORF_pep_info";
$head2 = "select SIP_ID, peptide_sequence, length_of_peptide, dNdS from SIP_info";
$head3 = "select LW_ID, Preproprotein, pep_length, dNdS from LeaseWalker_peptide_info";

$head_sigP1 = "select sORF_ID, sORF_pep, sORF_pep_len, dNdS from sORF_pep_info p join signal_peptides_info s on p.sORF_ID = s.ID ";
$head_sigP2 = "select SIP_ID, peptide_sequence, length_of_peptide, dNdS from SIP_info p join signal_peptides_info s on p.SIP_ID = s.ID";
$head_sigP3 = "select LW_ID, Preproprotein, pep_length, dNdS from LeaseWalker_peptide_info p join signal_peptides_info s on p.LW_ID = s.ID";

$head_TM_P1 = "select sORF_ID, sORF_pep, sORF_pep_len, dNdS from sORF_pep_info p join TMdomains_info s on p.sORF_ID = s.ID";
$head_TM_P2 = "select SIP_ID, peptide_sequence, length_of_peptide, dNdS from SIP_info p join TMdomains_info s on p.SIP_ID = s.ID";
$head_TM_P3 = "select LW_ID, Preproprotein, pep_length, dNdS from LeaseWalker_peptide_info p join TMdomains_info s on p.LW_ID = s.ID";


#condition1 for filtering by length
$condition1 = '';
if ($minlen != '') {
  $condition1 = " WHERE sORF_pep_len >=$minlen ";
  if ($maxlen != '') {
    $condition1= "$condition1 AND sORF_pep_len <= $maxlen ";
  } 

#condition2 for filtering by length
$condition2 = '';
if ($minlen != '') {
  $condition2 = " WHERE length_of_peptide >= $minlen ";
  if ($maxlen != '') {
    $condition2 = "$condition2 AND length_of_peptide <= $maxlen ";
  }

#condition3 for filtering by length
$condition3 = '';
if ($minlen != '') {
  $condition3 = " WHERE pep_length >= $minlen ";
  if ($maxlen != '') {
    $condition3 = "$condition3 AND pep_length <= $maxlen";
  }

#condition for dN/dS ratio 
if ($dnds != '') {
    $condition1 = "$condition1 AND dNdS < 1";
  }
} elseif ($dnds != '') {
    $condition1 = " WHERE dNdS < 1";
}
if ($dnds != '') {
    $condition2 = "$condition2 AND dNdS < 1";
  }
} elseif ($dnds != '') {
    $condition2 = " WHERE dNdS < 1";
}
if ($dnds != '') {
    $condition3 = "$condition3 AND dNdS < 1 ";
  }
} elseif ($dnds != '') {
    $condition3 = " WHERE dNdS < 1 ";
}

//--------------------------------------------------------------------------------------------------------------------------------
#build the query
$tail = ";";

switch ($feature) {
  case 'sigseq':
    $query = "$head_sigP1$condition1$union$head_sigP2$condition2$union$head_sigP3$condition3$tail";
    echo 'showing peptides with <b>signal sequence</b>';
    break;

  case 'TM':
    $query = "$head_TM_P1$condition1$union$head_TM_P2$condition2$union$head_TM_P3$condition3$tail";
    echo ' showing peptides with <b>TM domains</b>';
    break;

  case 'all':
    $query = "$head1$condition1$union$head2$condition2$union$head3$condition3$tail";
    echo 'showing all peptides';
    break;
}

//if ($sigseq != '') {
//    $query = "$head_sigP1$condition1$union$head_sigP2$condition2$union$head_sigP3$condition3$tail";
//}
 //   else if ($TM != '') {
//    $query = "$head_TM_P1$condition1$union$head_TM_P2$condition2$union$head_TM_P3$condition3$tail";
//} 
//    else {
//    $query = "$head1$condition1$union$head2$condition2$union$head3$condition3$tail";
//} 

//echo '<p>'.$query.'</p>';

$result = mysql_query($query) or die ("Error attempting query. Please select at least one of the options !");
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
//echo "<div>\n";
//echo '<tr><td><button id="btn-export">export <span class="glyphicon glyphicon-download-alt"></button></span></td></tr>';
echo "</div>";
echo '<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-condensed table-striped table-bordered"' . $tableid . '>';
echo '<thead>';

echo "<tr><th>ID</th><th>peptide sequence</th><th>peptide length</th><th>dN/dS</th></tr>";
echo '</thead>';
echo '<tbody>';

while($row = mysql_fetch_array($result)) {
    $f = $row ['dNdS'];
    if ($dnds == '' || is_numeric($f) && $f < 1.0 && $f != 0) {
      $b1 = $row ['sORF_ID'];
      if (preg_match("/ath_mu/", $b1, $matches)){
            $link_Annot = "<a href=LW.php?LW_ID=$b1 target='_blank'>$b1</a>";
        } elseif (preg_match("/sORF/", $b1, $matches)) {
            $link_Annot = "<a href=Hanada.php?sORF_ID=$b1 target='_blank'>$b1</a>";
        } else {
            $link_Annot = "<a href=SIPs.php?SIP_ID=$b1 target='_blank'>$b1</a>"; 
	}
      $s1 = $row ['sORF_pep'];
      $s3 = $row ['sORF_pep_len'];
      #$l = $row ['dNdS'];
      //if (strlen($s1) > $maxpeplen)  {
        //$s1 = substr($s1, 0, $maxpeplen).'...';
      //}

      $s1 = colorAminoAcids($s1);
      echo '<tr>';
      echo "<td> $link_Annot </td>";
      #echo "<td> $b2 </td>";
      #echo "<td> $b3 </td>";
      #echo "<td> $s1 </td>";
      echo "<td> $s1 </td>";
      echo "<td> $s3 </td>";
      #echo "<td> $l </td>";
      echo "<td> $f </td>";
    }
}
echo '</tbody>';
echo '</table>';

mysql_close($dbhandle);

include 'footer.html';

?>  

