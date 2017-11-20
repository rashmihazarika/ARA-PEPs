<?php 
include 'header.html'; 
include 'menu.html'; 

if (!isset($_GET['TAR_ID'])) die("No TAR information to show");
$tarid = $_GET['TAR_ID'];

include 'color_sequence.php';

$servername = "127.0.0.1"; $username = "root"; $password = "";
$dbhandle = mysql_connect($servername, $username, $password) 
or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not find database");

$query = "SELECT * FROM TAR_info WHERE TAR_ID = '$tarid' ; ";
$query2 = "SELECT * FROM SIP_info WHERE TAR_ID = '$tarid' ; ";
$query3= "SELECT * FROM Peptide_positions_info p INNER JOIN SIP_info s ON s.SIP_ID = p.SIP_ID WHERE TAR_ID = '$tarid' ; ";
//echo '<p>'.$query.'</p>';
$query4 = "SELECT * FROM Mapped_Annotations_info WHERE TAR_ID ='$tarid'";

$result = mysql_query($query) or die ("Error attempting query $query");
$result2 = mysql_query($query2) or die ("Error attempting query $query2");
$result3 = mysql_query($query3) or die ("Error attempting query $query3");
$result4 = mysql_query($query4) or die ("Error attempting query $query4"); 

echo '<h1>TAR ID: <font color="blue">' . $tarid . '</font></h1>';
//----------------------------------------------------------------------------------------------------------------------
echo '<table class="table table-hover table-condensed table-bordered table-striped">';

while ($row = mysql_fetch_assoc($result)) {
   #$a = $row ['TAR_ID'];
   $a = $row ['source'];
   $seq = $row ['Nucleotide']; // extract the nucleotide sequence
   $b = $row ['BC'];
   $bc_expr = $row ['BC_expression'];
   $bc_chr = $row ['BC_Chr'];
   $bc_start = $row ['BC_Chr_start'];
   $bc_stop = $row ['BC_Chr_stop'];
   $g = $row ['PQ'];
   $pq_expr = $row ['PQ_expression'];
   $pq_chr = $row ['PQ_Chr'];
   $pq_start = $row ['PQ_Chr_start'];
   $pq_stop = $row ['PQ_Chr_stop'];

   echo '<h5 class="bg-success text-uppercase">Source</font></h5>';
   echo "<p>$a </p>";
   echo '<h5 class="bg-success text-uppercase">Chromosomal co-ordinates</font></h5>';
   if (preg_match("/RNA-seq/", $a)) {
	echo '<pre>Not available</pre>';
   } else {
	echo '<tr><th>TilingArrays (Biotic stress)</th>
		  <th>log2 fold ratio</th>
                  <th>Chr</th>
                  <th>Start</th>
                  <th>Stop</th>
                  <th>TilingArrays (Abiotic stress)</th>
                  <th>log2 fold ratio</th>
                  <th>Chr</th>
                  <th>Start</th>
                  <th>Stop</th></tr>';
	echo '<tr align = "left">';
   	//echo '<td>'.$a.'</td>';
   	echo '<td>'.$b.'</td>';
   	echo '<td>'.$bc_expr.'</td>';
   	echo '<td>'.$bc_chr.'</td>';
   	echo '<td>'.$bc_start.'</td>';
   	echo '<td>'.$bc_stop.'</td>';
   	echo '<td>'.$g.'</td>';
   	echo '<td>'.$pq_expr.'</td>';
   	echo '<td>'.$pq_chr.'</td>';
   	echo '<td>'.$pq_start.'</td>';
   	echo '<td>'.$pq_stop.'</td>';
	}
	echo '</table>';
}

//---------------------------------------------------------------------------------------------------------------------------
#$inhouse='None';
#$Cuffcompare='None';
#$Cuffcompare_class='None';
#$Cuffmerge='None';
#$Cuffmerge_class='None';
$t10an='None';
$plnc='None';
$tu='None';

while ($row = mysql_fetch_assoc($result4)) {
#   $inhouse = $row['RNAseq_inhouse'];
#   $Cuffcompare = $row['Cuffcompare'];
#   $Cuffcompare_class=$row['Cuffcompare_class'];
#   $Cuffmerge=$row['Cuffmerge'];
#   $Cuffmerge_class=$row['Cuffmerge_class'];
   $t10an   = $row ['TAIR10_annotation'];
   $plnc    = $row ['PLncDB_annotation'];
   $tu      = $row['TU'];
}

//------------------------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">Other available annotations</font></h5>';
echo '<table class="table table-hover table-condensed table-bordered table-striped">';
echo "<tr><th>TAIR10 Annotation</th><th>Annotation in PLncDB</th><th>Type of Transcription Unit (PLncDB)</th></tr>";
//$find  = 'intergenic';
//$pos = strpos($t10an, $find);
//if ($pos === false) {
if (preg_match("/AT/", $t10an)){
   $p_links = array();
   $protsArray = explode(',',$t10an);
        foreach($protsArray as $t10an) {
        #$p_links[] = "<a href='http://plants.ensembl.org/Arabidopsis_thaliana/Gene/Summary?g=". trim($t10an) ."'target='_blank'>". trim($t10an) ."</a>";
        $p_links[] ="<a href='https://www.araport.org/search/thalemine/". trim($t10an) ."'target='_blank'>". trim($t10an) ."</a>";
	}
        $links_prots = implode(", ", $p_links);
 
    	$annot = array();
    	$plncdb = explode(',',$plnc);
    	foreach($plncdb as $plnc) {
    	$plnc_links[] = $plnc;
    	}
    	$links_plnc = implode(", ", $plnc_links);

    	$annot_tu = array();
    	$plncdb_tu = explode(',',$tu);
    	foreach($plncdb_tu as $tu) {
    	$tu_links[] = $tu;
    	}
    	$links_tu = implode(",", $tu_links);

    	echo "<tr><td>$links_prots</td><td>$links_plnc</td><td>$links_tu</td></tr></table>";
	} else {
	$annot = array();
    	$plncdb = explode(',',$plnc);
    	foreach($plncdb as $plnc) {
    	$plnc_links[] = $plnc;
    	}
    	$links_plnc = implode(", ", $plnc_links);

    	$annot_tu = array();
    	$plncdb_tu = explode(',',$tu);
    	foreach($plncdb_tu as $tu) {
    	$tu_links[] = $tu;
    	}
    	$links_tu = implode(",", $tu_links);
    	echo "<tr><td>$t10an</td><td>$links_plnc</td><td>$links_tu</td></tr></table>";
}

//------------------------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">RNA sequencing expression data</font></h5>';
//echo "<pre><strong>RNAseq <i>in house</i> data:</strong> $inhouse </pre>";
//echo "<pre>$inhouse  ($Cuffcompare,$Cuffcompare_class),($Cuffmerge,$Cuffmerge_class)</pre>";

//------------------------------------------------------------------------------------------------------------------------------
$query = "SELECT * FROM RNAseq_info LEFT JOIN Cuffcmp_loci ON RNAseq_info.Gene_id = Cuffcmp_loci.Gene_id WHERE RNAseq_info.TAR_ID ='$tarid'; ";
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
echo '<pre>no assembled transcripts</pre>';
} else {
    echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
    echo "<tr><th>Gene locus</th><th>Chr position</th><th>TAIR10 genes</th></tr>";
    while($row = mysql_fetch_array($result)) {
        $Gene_id = $row['Gene_id'];
	$Gene_id = "<a href=http://www.biw.kuleuven.be/CSB/ARA-PEPs/Gene_expression.php?Gene_id=$Gene_id&TAR_ID=$tarid>$Gene_id</a>";
        $Chr_pos = $row['Chr_pos'];
        $TAIR10_id = $row['TAIR10_id'];
        #$Isoform_id = $row['Isoform_id'];

        echo '<tr>';
        //echo '<td>'.$tarid.'</td>';
        echo '<td>'.$Gene_id.'</td>';
        echo '<td>'.$Chr_pos.'</td>';
	$TAIR10_links = array();
        $TAIR10Array = explode(',',$TAIR10_id);
        foreach($TAIR10Array as $TAIR10_id) {
        $TAIR10_links[] = trim($TAIR10_id);
        }
        $links_TAIR10 = implode(", ", $TAIR10_links);
	echo '<td>'.$links_TAIR10.'</td>';

	#$p_links = array();
        #$protsArray = explode(',',$Isoform_id);
        #foreach($protsArray as $Isoform_id) {
        #$p_links[] = trim($Isoform_id);
        #}
        #$links_prots = implode(", ", $p_links);
	#echo '<td>'.$links_prots.'</td>';

   }
    echo "</table>";
}

//------------------------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">Nucleotide sequence of TAR</font></h5>';
$seq = preg_replace('/\s+/', '', $seq);
$seq = colorNucleotides($seq);
$seq = wordwrap($seq, 150, "\n", true);
echo "<p>$seq </p>";

//-------------------------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">Putative peptides encoded by TAR</font></h5>';
echo '<table class="table table-hover table-condensed table-bordered table-striped">';
//echo '<tr><th>SIP ID</th><th>Strand</th><th>Other Annotations</th><th>Annotation Source</th><th>Peptide sequence</th><th>Peptide length (AA)</th></tr>';
echo '<tr><th>SIP ID</th><th>Strand</th><th>Annotations</th><th>Annotation source</th><th>Sequence</th><th>Length (AA)</th></tr>';

while ($row = mysql_fetch_array($result2)) {
   echo '<tr>';
   $sipid = $row ['SIP_ID'];
   //$sipid = "<a href=peptide_info.php?SIP_id=$sipid>$sipid</a>";
   //$sipid = "<a href=sipx.php?SIP_ID=$sipid target='_blank'>$sipid</a>";
   $sipid = "<a href=http://www.biw.kuleuven.be/CSB/ARA-PEPs/SIPs.php?SIP_ID=$sipid>$sipid</a>";
   $sseq = $row ['peptide_sequence'];
   $sseq = trim($sseq); //preg_replace('/\s+/', '', $sseq);
   $sseq = colorAminoAcids($sseq);
   $sseq = wordwrap($sseq, 20, "\n", true);

   $b = $row ['Strand'];
   $c = $row ['Other_Annotations']; //$c = trim($c);
   $g = $row['Dataset']; //$g = trim($g);
   $i = $row['length_of_peptide'];

   echo "<td>$sipid:  &nbsp; </td>";
   echo '<td align="center">'.$b.'</td>';
   echo "<td>$c</td>";
   //echo '<td><a href="HANADAwiki.php?sORF_ID='.$c.'">'.$c.'</td>';
   echo '<td>'.$g.'</td>';
   echo "<td>$sseq</td>";
   echo '<td>'.$i.'</td>';
   echo '</tr>';
}
echo '</table>';

//-------------------- TAR/SIP chart-------------------------------------------------------------------------------------------

$chart_title = "'$tarid and putative peptides encoded from it'";

$SIP_data = '';
$Chr_data = '';
//$chrn_data = '';
$chart_nrows = 0;
$min_start = -1;
$max_stop = -1;
$sep='';
#if ($bc_expr > 0 && $pq_expr > 0) {
if (!empty($bc_expr) && !empty($pq_expr)) {
  $tar_start = min($bc_start, $pq_start);
  $tar_stop = max($bc_stop, $pq_stop);
  $SIP_data = "\"$tarid\"";
  $Chr_data = "{ low: $tar_start, high: $tar_stop, color: \"blue\" }";
  $sep=',';
  $tarvec = explode("/", $tarid);
  $bc_tar = $tarvec[0];
  $pq_tar = $tarvec[1];
  //echo "<p>tar1=$bc_tar, tar2=$pq_tar";
  $chart_nrows ++;
  $min_start = $tar_start;
  $max_stop = $tar_stop;
} else {
  $bc_tar = $tarid;
  $pq_tar = $tarid;
}

#if ($bc_expr > 0) {
if (!empty($bc_expr)) {
  $SIP_data = "$SIP_data$sep \"$bc_tar\"";
  //$Chr_data = "$Chr_data$sep [ $bc_start, $bc_stop ]";
  $Chr_data = "$Chr_data$sep { low: $bc_start, high: $bc_stop, color: \"green\" }";
  $sep=',';
  $chart_nrows ++;
  if ($min_start < 0 || $min_start > $bc_start) $min_start = $bc_start;
  if ($max_stop < 0 || $max_stop < $bc_stop) $max_stop = $bc_stop;
}
#if ($pq_expr > 0) {
if (!empty($pq_expr)) {
  $SIP_data = "$SIP_data$sep \"$pq_tar\"";
  //$Chr_data = "$Chr_data$sep [ $pq_start, $pq_stop ]";
  $Chr_data = "$Chr_data$sep { low: $pq_start, high: $pq_stop, color: \"green\" }";
  $sep=',';
  $chart_nrows ++;
  if ($min_start < 0 || $min_start > $pq_start) $min_start = $pq_start;
  if ($max_stop < 0 || $max_stop < $pq_stop) $max_stop = $pq_stop;
}

while($row3 = mysql_fetch_array($result3)) {
  //echo '<tr>';
  $sipid3 = $row3 ['SIP_ID'];
  $chrn = $row3 ['peptide_chr'];
  $SIP_data = "$SIP_data$sep " . '"' . $sipid3 . '"';
  //$sipid3 = "<a href=Pep_pos.php?SIP_id=$sipid3>$sipid3</a>";
  $chrstart = $row3 ['peptide_Chr_start'];
  $chrend = $row3 ['peptide_chr_end'];
  //$Chr_data = "$Chr_data$sep [ $chrstart, $chrend ]";
  //$chrn_data = "$chrn_data$sep $chrn";
  $sep=',';
  $chart_nrows ++;
  if ($chrstart < $chrend) {
    if ($min_start < 0 || $min_start > $chrstart) $min_start = $chrstart;
    if ($max_stop < 0 || $max_stop < $chrend) $max_stop = $chrend;
    $Chr_data = "$Chr_data$sep { low: $chrstart, high: $chrend, dataLabels: { format: '{y} >>>' } }";
  } else {
    if ($min_start < 0 || $min_start > $chrend) $min_start = $chrend;
    if ($max_stop < 0 || $max_stop < $chrstart) $max_stop = $chrstart;
    $Chr_data = "$Chr_data$sep { low: $chrstart, high: $chrend, dataLabels: { format: '<<< {y}' } }";
  }
}

$chrlabel = "'$chrn'"; // assume all SIPs for this TAR are expressed from same chromosome number
$chrn = preg_replace('/[^0-9]+/', '', $chrn);
$chrtitle = "'Positions in Chromosome $chrn'";

//echo "<p>$SIP_data";
//echo "<p>$Chr_data";
//echo "<p>$chrn_data";
//echo "<p>nrows=$chart_nrows";

// PENDING: indicate strand direction and TAR position (TAR PQ, TAR BC and total)

$chart_height = 170 + $chart_nrows * 30;
$chart_height = min($chart_height, 600);

echo '<div id="container" style="min-width: 200px; height: ' . $chart_height . 'px; margin: 0 auto"></div>';
echo '<p class="text-center"><a href="http://www.biw.kuleuven.be/CSB/ARA-PEPs/JBrowse-1.12.0/index.html?data=data&loc=' . $chrn. '%3A' . $min_start . '..' . $max_stop . '" target="_blank">  Visualize this region in JBrowse-1.12.0</a></p>';
?>
<html>
<head>
<!-- Highcharts -->  
<script src="highcharts/jquery-1.8.2.min.js"></script>
<script src="highcharts/highcharts.js"></script>
<script src="highcharts/highcharts-more.js"></script>
<script src="highcharts/modules/exporting.js"></script>
<!-- END -->
</head>
<body>
       
<script>
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'columnrange',
            inverted: true
        },
        title: {
            text: [ <?php echo $chart_title; ?> ]
        },
        //subtitle: {
        //    text: 'xxx'
        //},
        xAxis: {
            categories: [ <?php echo $SIP_data; ?> ]
        },
        yAxis: {
            title: {
                text: <?php echo $chrtitle; ?>
            }
        },
        plotOptions: {
            columnrange: {
                dataLabels: {
                    enabled: true
                    //formatter: function () {
                    //    return this.y + '>>>';
                    //}
                }
            }
        },
        legend: {
            enabled: false
        },
        series: [{
            name: <?php echo $chrlabel; ?>,
	    color: Highcharts.getOptions().colors[3],
            data: [ <?php echo $Chr_data; ?> ]
        }]
    });
});

</script>

<?php
mysql_close($dbhandle);
include 'footer.html';
?>


