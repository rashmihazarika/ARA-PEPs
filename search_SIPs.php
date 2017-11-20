<?php 
include 'headpager.html'; 
include 'menu_headpager.html'; 
include 'color_sequence.php';
  
$exprcond='';
$exprlevel='';
$chrnumber = '';
$chrstart = '';
$chrstop = '';

if (isset($_GET['expressionType'])) {
  $exprtype = $_GET['expressionType'];
  //echo "<p> exprtype=$exprtype";
  switch ($exprtype) {
  case 'RNA':
      //echo '<p>RNA';
      if (isset($_GET['conditionRNA'])) $exprcond = $_GET['conditionRNA'];
      if (isset($_GET['levelRNA'])) $exprlevel = $_GET['levelRNA'];
      $table = 'TAR_RNAseq_info';
      $fieldBCexpr = 'TAR_RNAseq_info.BC'; 
      $fieldBCchr = 'RNAseq_info.Chrm';
      $fieldBCchrstart = 'RNAseq_info.Chr_start';
      $fieldBCchrstop = 'RNAseq_info.Chr_stop';
      $fieldPQexpr = 'TAR_RNAseq_info.PQ'; 
      $tableheader = '<tr><th>TAR ID</th><th>GENE ID</th><th>BC</th><th>MOCK BC</th><th>MOCK PQ</th><th>PQ</th><th>Chr</th><th>start</th><th>stop</th>';
      $plottitle = '"Levels of mRNA expression (in FPKMs)"';
      $plotseries = '{
            name: "Botrytis cinerea (BC)",
            data: levelsBotrytis
        }, {
            name: "MOCK_BC",
            data: levels_WT_Botrytis
        }, {
            name: "MOCK_PQ",
            data: levels_WT_Paraquat
        }, {
            name: "Paraquat (PQ)",
            data: levelsParaquat
        }';
      break;

      case 'tiling':
      //echo '<p>tiling';
      if (isset($_GET['conditionTiling'])) $exprcond = $_GET['conditionTiling'];
      if (isset($_GET['levelTiling'])) $exprlevel = $_GET['levelTiling'];
      $table = 'TAR_TilingArray_info';
      //$table = 'TAR_info';
      $fieldBCexpr = 'BC_expression'; 
      $fieldBCchr = 'BC_Chr';
      $fieldBCchrstart = 'BC_Chr_start';
      $fieldBCchrstop = 'BC_Chr_stop';
      $fieldPQexpr = 'PQ_expression'; 
      $tableheader = '<tr><th>TAR ID</th><th>BC Stress</th><th>Chr</th> <th>Chr Start</th> <th>Chr Stop</th><th>BC expression</th><th>PQ Stress</th><th>Chr</th><th>Chr Start</th><th>Chr Stop</th><th>PQ expression</th></tr>';
      $plottitle = '"Levels of mRNA expression (log2 fold ratio) "';
      $plotseries = '{
            name: "Botrytis cinerea (BC)",
            data: levelsBotrytis
        }, {
            name: "Paraquat (PQ)",
            data: levelsParaquat
        }';
      break;

    case 'both':
      //echo '<p>both';
      if (isset($_GET['conditionRNA'])) $exprcond = $_GET['conditionRNA'];
      $table = 'TAR_TilingArray_info';
      $fieldBCchr = 'BC_Chr';
      $fieldBCchrstart = 'BC_Chr_start';
      $fieldBCchrstop = 'BC_Chr_stop';
      $tableheader = '<tr><th>TAR ID</th><th>GENE ID</th><th>Chr (BC)</th><th>Start (BC)</th><th>Stop (BC)</th><th>Chr (PQ)</th> <th>Start (PQ)</th> <th>Stop (PQ)</th></tr>';
      break;
  }
}

if (isset($_GET['Chromosome'])) $chrnumber = $_GET['Chromosome'];
if (isset($_GET['Chr_start'])) $chrstart = $_GET['Chr_start'];
if (isset($_GET['Chr_stop'])) $chrstop = $_GET['Chr_stop'];

//-----------expression levels-------------------------------------

$useE = ($exprlevel != '');

$exprop = ''; // operator '>', or '<', in 'WHERE' condition_TilingArray
if ($exprcond != 'top' && $exprcond != 'bottom') {
  $exprop = $exprcond;
  switch ($exprop) {
    case '>': $textop = 'higher than'; break;
    case '<': $textop = 'lower than'; break;
  }
}

// make sure only expressed TARs will be shown for the selected stress(es)
$textcond = '';
$cond0=''; 
if ($useE && is_numeric($exprlevel) && $exprop != '') {
  $cond0 = "$fieldBCexpr $exprop $exprlevel";
  $textcond = "TARs with expression levels $textop $exprlevel under $org";
}

echo '<p><b> Chromosome position:</b> ';
$chrcond='';
if ($chrnumber != '') {
  echo "$chrnumber: ";
  $chrcond = "$fieldBCchr = $chrnumber";
  if ($chrstart != '') {
    $chrcond = "$chrcond AND $fieldBCchrstart >= $chrstart AND";
  } else {
    echo "0-";
  }
  if ($chrstop != '') {
    $chrcond = "$chrcond $fieldBCchrstop <= $chrstop";
    echo "$chrstart-"."$chrstop";
  }
} else {
  echo 'any';
}

$org = 'biotic/abiotic stress induced by either <i>Botrytis cinerea</i> or <i>Paraquat</i> or by both stress conditions';

//----------------------------------------------------------------------------------------------------------------------------------

$tail = '';
if ($useE) {
  $tail =" ORDER BY greatest ($fieldBCexpr, $fieldPQexpr)";
  if ($exprcond == 'top' || $exprcond == '>'){ 
    $tail="$tail DESC";
  }
  if ($exprcond == 'top' || $exprcond == 'bottom') {
    $n = intval($exprlevel);
    $tail = "$tail LIMIT $n";
    $adj  = ($exprcond == 'top' ? 'highest' : 'lowest');
    $textcond = "Searching for $n $adj expression levels under $org";
  }
}

//-----------------------------------------------------------------------------------------------------------------------------------

$condition = '';
if ($cond0 != '' || $chrcond != '') {
  $cond1 = '';
  $sep='';
  if ($cond0 != '') {
    $cond1=$cond0;
    $sep=' AND ';
  }
  if ($chrcond != '') $cond1="$cond1$sep$chrcond";
  $cond1 = "( $cond1 )";
  $cond2 = $cond1;
  //$cond2 = preg_replace('/TAR_RNAseq_info.BC/', 'TAR_RNAseq_info.PQ', $cond2);
  $cond2 = preg_replace('/BC/', 'PQ', $cond2);
  $cond3 = "$cond1 OR $cond2";
  $condition = "WHERE $cond3";
}

//echo "<p>Chrcond $chrcond </p>";
//echo "<p>COND1 $cond1 </p>";
//echo "<p>COND2 $cond2 </p>";
//echo "<p>COND3 $cond3 </p>";
//echo "<p>CONDITION $condition </p>";
//echo "<p>TAIL $tail </p>";

//-----------------------------------------------------------------------------------------------------------------------------------

$servername = "127.0.0.1"; $username = "root"; $password = "";
$dbhandle = mysql_connect($servername, $username, $password) 
or die("Unable to connect to MySQL");

$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not find database");

switch ($exprtype) {
  case 'RNA':
    $query = "SELECT * FROM $table JOIN RNAseq_info ON RNAseq_info.Gene_id = TAR_RNAseq_info.Gene_id $condition $tail; ";
    break;

  case 'tiling':
    $query = "SELECT * FROM $table $condition $tail ;";
    break;

  case 'both':
    $query = "SELECT * FROM $table JOIN RNAseq_info ON RNAseq_info.TAR_ID = TAR_TilingArray_info.TAR_ID JOIN TAR_RNAseq_info ON RNAseq_info.Gene_id = TAR_RNAseq_info.Gene_id $condition $tail; ";
    break;
}

//echo "<p>QUERY $query </p>";

if ($textcond == '') {
  $textcond = "all expressed TARs under $org";
}
echo "<p>$textcond :</p>";

//echo "<p>nhits=$nhits</p>";

$result = mysql_query($query) or die ("Error attempting query, please select at least one of the options!");
$nhits = mysql_num_rows($result);

if ($nhits == 0) {
  echo "<ul><li>no hits.</li></ul>";
  mysql_close($dbhandle);
  include 'footer.html';
  return;
}

//--------------------------------------------------donot display highcharts for the 'both' option-----------------------------------------------------
if ($exprtype != "both") {
$chart_nrows = min($nhits, 10);
if ($chart_nrows < $nhits) echo "<p><ul><li>Chart of the first $chart_nrows out of $nhits hits: (scroll down to see full list of hits)</li></ul><p>";
$chart_height = 20 + $chart_nrows * 180;
$chart_height = min($chart_height, 500);
echo '<div id="container" style="width:100%; height:' . $chart_height . 'px;"></div>';
}

echo "<ul><li>$nhits hits found</li></ul>";

if ($nhits > 10) {
  $tableid = 'id="example"';
} else {
  $tableid='';
}

//echo '<tr><td><button id="btn-export">export <span class="glyphicon glyphicon-download-alt"></button></span></td></tr>';
echo '<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-condensed table-striped table-bordered"' . $tableid . '>';
echo '<thead>';
echo $tableheader;
echo '</thead>';
echo '<tbody>';

$maxtarlen = 5; // maximum TAR length to display on the screen

$sep='';
$tar_data = '';
$bc_data = '';
$pq_data = '';
$wt_bc_data = '';
$wt_pq_data = '';
$nfetched = 0;

while($row = mysql_fetch_array($result)) {
    $nfetched ++;
    $tar = $row ['TAR_ID'];
    //if ($tar == '') continue;
    $a = $tar;
    //$a = "<a href=TARinfotracks.php?TAR_ID=$a>$a</a>";
    $a = "<a href=TAR.php?TAR_ID=$a>$a</a>";
    $bcval = $row ['BC'];
    $pqval = $row ['PQ'];

    switch ($exprtype) {
      case 'RNA':
        $c = $row ['WT_BC'];
        $d = $row ['WT_PQ'];
        $f = $row ['Chrm'];
        $g = $row ['Chr_start'];
        $h = $row ['Chr_stop'];
        $geneid = $row [ 'Gene_id' ];
        $geneid = "<a href=Gene_expression.php?Gene_id=$geneid&TAR_ID=$tar>$geneid</a>";
        echo '<tr>';       
        echo '<td> &nbsp;'.$a.'</td>';
        echo '<td> &nbsp;'.$geneid.'</td>';
        echo '<td> &nbsp;'.$bcval.'</td>';
        echo '<td> &nbsp;'.$c.'</td>';
        echo '<td> &nbsp;'.$d.'</td>';
        echo '<td> &nbsp;'.$pqval.'</td>';
        echo '<td> &nbsp;'.$f.'</td>';
        echo '<td> &nbsp;'.$g.'</td>';
        echo '<td> &nbsp;'.$h.'</td>';
        break;
        case 'tiling':
        $d = $row ['BC_Chr'];
        $e = $row ['BC_Chr_start'];
        $f = $row ['BC_Chr_stop'];
        $i = $row ['PQ_Chr'];
        $j = $row ['PQ_Chr_start'];
        $k = $row ['PQ_Chr_stop'];
        //$l = $row ['Len_TAR'];
        //$s = $row ['Nucleotide']; // sequence
        //if (strlen($s) > $maxtarlen) {
        // $s = substr($s, 0, $maxtarlen).'...';
        //}
        //$s = colorNucleotides($s);
        $bcexpr = $row ['BC_expression'];
        $pqexpr = $row ['PQ_expression'];
        echo '<tr>';
        echo '<td> &nbsp;'.$a.'</td>';
        echo '<td> &nbsp;'.$bcval.'</td>';
        echo '<td> &nbsp;'.$d.'</td>';
        echo '<td  align="right"> '.$e.'&nbsp; </td>';
        echo '<td  align="right"> '.$f.'&nbsp; </td>';
        echo '<td> &nbsp;'.$bcexpr.'</td>';
        echo '<td> &nbsp;'.$pqval.'</td>';
        echo '<td> &nbsp;'.$i.' </td>';
        echo '<td align="right"> '.$j.'&nbsp; </td>';
        echo '<td align="right"> '.$k.'&nbsp; </td>';
        echo '<td> &nbsp;'.$pqexpr.'</td>';
        //echo '<td align="right"> '.$l.'&nbsp; </td>';
        //echo '<td><div id="biopolseq"><code>' . $s . '</code></div></td>';
        //echo "<td>$s</td>";    
        break;
        case 'both':
        $geneid_both = $row [ 'Gene_id' ];
        $geneid_both = "<a href=Gene_expression.php?Gene_id=$geneid_both&TAR_ID=$tar>$geneid_both</a>";
        $BC_Chr = $row ['BC_Chr'];
        $BC_start = $row ['BC_Chr_start'];
        $BC_stop = $row ['BC_Chr_stop'];
        $PQ_Chr = $row ['PQ_Chr'];
        $PQ_start = $row ['PQ_Chr_start'];
        $PQ_stop = $row ['PQ_Chr_stop'];
        echo '<tr>';
        echo '<td> &nbsp;'.$a.'</td>';
        echo '<td> &nbsp;'.$geneid_both.'</td>';
        echo '<td> &nbsp;'.$BC_Chr.'</td>';
        echo '<td> &nbsp;'.$BC_start.'</td>';
        echo '<td> &nbsp;'.$BC_stop.'</td>';
        echo '<td> &nbsp;'.$PQ_Chr.'</td>';
        echo '<td> &nbsp;'.$PQ_start.'</td>';
        echo '<td> &nbsp;'.$PQ_stop.'</td>';
        break;
    }
    if ($nfetched <= $chart_nrows) {
      $tar_data = "$tar_data$sep '$tar'";
      switch ($exprtype) {
        case 'RNA':
	  if ($bcval == '') $bcval = 0;
	  if ($pqval == '') $pqval = 0;
	  if ($c == '') $c = 0;
	  if ($d == '') $d = 0;
          $bc_data = "$bc_data$sep $bcval";
          $pq_data = "$pq_data$sep $pqval";
          $wt_bc_data = "$wt_bc_data$sep $c";
          $wt_pq_data = "$wt_pq_data$sep $d";
          break;
        case 'tiling':
	  if ($bcexpr == '') $bcexpr = 0;
	  if ($pqexpr == '') $pqexpr = 0;
          $bc_data = "$bc_data$sep $bcexpr";
          $pq_data = "$pq_data$sep $pqexpr";
          break;
      }
      $sep=',';
    }
    //echo "<tr><td>$tar</td>";
    //echo "<td>$bcexpr</td>";
    //echo "<td>$pqexpr</td></tr>";

}
echo '</tbody>';
echo '</table>';
//echo "<p>BC=$bc_data";
//echo "<p>PQ=$pq_data";



//echo "<p>tar=$tar_data";
//echo "<p>bc=$bc_data";
//echo "<p>pq=$pq_data";

mysql_close($dbhandle);
?>

<!--- don't include jquery if already included from bootstrap pager:
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js">
      </script>-->
<!-- Highcharts -->  

<script src="highcharts/highcharts.js"></script>
<script>

var levelsBotrytis = [<?php echo $bc_data; ?>]
var levelsParaquat = [<?php echo $pq_data; ?>]
var levels_WT_Botrytis = [<?php echo $wt_bc_data; ?>]
var levels_WT_Paraquat = [<?php echo $wt_pq_data; ?>]

$(function () { 
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Transcriptionally active regions'
        },
        xAxis: {
            categories: [ <?php echo $tar_data; ?> ]
        },
        yAxis: {
            title: {
                text: <?php echo $plottitle; ?>
            }
        },
        series: [ <?php echo $plotseries; ?> ]
    });
});
</script>

<?php include 'footer.html'; ?>
