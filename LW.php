<DOCTYPE html>
<html lang="en">

<?php
include 'header.html'; 
include 'menu.html';
include 'color_sequence.php';
 
$servername = "127.0.0.1"; $username = "root"; $password = "";

//connection to the database
//$conn = mysqli_connect($servername, $username, $password);

// Check connection
//if ($conn->connect_error) {
//    die("Connection failed: " . $conn -> connect_error);
//} 
$dbhandle = mysql_connect($servername, $username, $password) or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not select database");

if (isset($_GET['LW_ID'])) {$id = $_GET['LW_ID'];} else { die("Invalid input"); }

 
echo '<h1>SecretedPeps ID: <font color=blue>'.$id.'</font></h1>';
//$output= NULL;
//Selecting the data from table but with limit
echo '<h5 class="bg-success text-uppercase">General information</h5>';
$query = ("SELECT * FROM LeaseWalker_peptide_info WHERE LW_ID ='$id'"); 
$result = mysql_query($query) or die ("Error attempting query");
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo "<tr><th>Pep ID</th><th>Chr</th><th>Strand</th><th> Chr Start (TAIR6)</th><th>Chr Stop (TAIR6)</th>
<th> Chr Start (TAIR10)</th><th>Chr Stop (TAIR10)</th></tr>";
while($row = mysql_fetch_assoc($result)) {
    $a = $row ['LW_ID'];
    $b = $row ['Chrm'];
    $e = $row ['Strand'];
    $c = $row ['Start_TAIR6'];
    $d = $row ['Stop_TAIR6'];
    $g = $row ['Start_TAIR10'];
    $f = $row ['Stop_TAIR10'];
    
    echo '<tr>';
    echo '<td>'.$a.'</td>';
    echo '<td>'.$b.'</td>';
    echo '<td>'.$e.'</td>';
    echo '<td>'.$c.'</td>';
    echo '<td>'.$d.'</td>';
    echo '<td>'.$g.'</td>';
    echo '<td>'.$f.'</td>';
    
}
echo "</table>";
echo '<p><i><a href="http://www.biw.kuleuven.be/CSB/ARA-PEPs/JBrowse-1.12.0/index.html?data=data&loc=' . $b. '%3A' . $g . '..' . $f . '" target="_blank">  Visualize this region in JBrowse-1.12.0</a></i></p>';
//-----------------------------------------------------------------------------------------------------------------------
include 'check_sequence.php';

// display Salk Tiling array data in table format
function displaySalk($data) {
    $slist = explode(',', $data);
    echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
    echo "<tr><th>Nucleotide probe</th><th>Expression</th>";
    $val = '';
    $opentable = false;
    foreach ($slist as $field) {
        if (isNucleotideSequence($field)) {
	    if ($opentable) echo "</td></tr>";
	    else $opentable = true;
            $seq = colorNucleotides($field);
	    echo "<tr><td>$seq</td><td>";
	    $sep='';
        } elseif (is_numeric($field)) {
            $val = "$field";
        } else {
	    echo "$sep$field: $val";
	    $val = '';
	    $sep = ', ';
 	}
    }
    if ($opentable) echo "</td></tr>";
    echo "</table>";
}


//$output= NULL;
echo '<h5 class="bg-success text-uppercase">Salk Tiling Array analysis</h5>';
$query = ("SELECT * FROM LeaseWalker_Salktiling WHERE LW_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
    echo '<pre>None</pre>';
} else {
while($row = mysql_fetch_assoc($result)) {
    //echo '<pre>';
    $LW_salktiling = $row ['LW_salktiling'];
    //echo " $LW_salktiling"; // lidia
    $LW_new = displaySalk($LW_salktiling); // lidia
    //echo " $LW_new";
}
//echo '</pre>';
}

//-----------------------------------------------------------------------------------------------------------------------
//$output= NULL;
echo '<h5 class="bg-success text-uppercase">Nucleotide sequence</h5>';
$query = ("SELECT * FROM LeaseWalker_peptide_info WHERE LW_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query"); 
//echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
//echo "<tr><th>Nucleotide</th></tr>";

while($row = mysql_fetch_assoc($result)) {
    $h = $row ['nucleotide'];
    $h = preg_replace('/\s+/', '', $h);
    $h = colorNucleotides($h);
    $h = wordwrap($h, 150, "\n", true);
    echo "<p>$h </p>";
}
//-----------------------------------------------------------------------------------------------------------------------
//$output= NULL;
echo '<h5 class="bg-success text-uppercase">Peptide sequence</h5>';
$query = ("SELECT * FROM LeaseWalker_peptide_info WHERE LW_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query"); 
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo "<tr><th>Preproprotein</th><th>Signal peptide</th><th>Proprotein</th>";
while($row = mysql_fetch_assoc($result)) {
    $h = $row ['Preproprotein'];
    $new_h = wordwrap($h, 50, "\n", true);
    $new_h = colorAminoAcids($new_h);
    $i = $row ['signalPeptide'];
    $new_i = wordwrap($i, 50, "\n", true);
    $new_i = colorAminoAcids($new_i);
    $j = $row ['Proprotein'];
    $new_j = wordwrap($j, 50, "\n", true);
    $new_j = colorAminoAcids($new_j);

    echo '<tr>';
    echo '<td>'.$new_h.'</td>';
    echo '<td>'.$new_i.'</td>';
    echo '<td>'.$new_j.'</td>';
}
echo "</table>";
//-----------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">Other Annotations</h5>';
$query = ("SELECT LW_OtherAnnot FROM LW_pep_info where LW_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query");
#$nhits = mysql_num_rows($result);
while($row = mysql_fetch_array($result)) {
        $h = $row ['LW_OtherAnnot'];
        if (preg_match("/ath_mu/", $h, $matches)){
            $link_Annot = "<a href=LW.php?LW_ID=$h>$h</a>";
        } elseif (preg_match("/sORF/", $h, $matches)) {
            $link_Annot = "<a href=Hanada.php?sORF_ID=$h>$h</a>";
        } elseif (preg_match("/BIP/", $h, $matches)) {
            $link_Annot = "<a href=SIPs.php?SIP_ID=$h>$h</a>";
        } elseif (preg_match("/OSIP/", $h, $matches)) {
            $link_Annot = "<a href=SIPs.php?SIP_ID=$h>$h</a>";
        } else {
            $link_Annot = "None";
        }
            echo "<pre>$link_Annot </pre>";

}



//------------------------------------------------------------------------------------------------------------------
//$output= NULL;
echo '<h5 class="bg-success text-uppercase">tBLASTn against the Rice genome(TIGR v0.4)</h5>';
$query = ("SELECT * FROM LeaseWalker_tblastn_Rice WHERE LW_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
    echo '<pre>None</pre>';
} else { 
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo "<tr><th>Rice Chr</th><th>Significance</th><th>HSP start</th><th>HSP stop</th><th>% identity</th>";
while($row = mysql_fetch_assoc($result)) {
    $h = $row ['rice_chr'];
    $i = $row ['significance'];
    $j = $row ['HSP_start'];
    $k = $row ['HSP_end'];
    $l = $row ['Identity'];
  
    echo '<tr>';
    echo '<td>'.$h.'</td>';
    echo '<td>'.$i.'</td>';
    echo '<td>'.$j.'</td>';
    echo '<td>'.$k.'</td>';
    echo '<td>'.$l.'</td>';
}
echo "</table>";
}

//------------------------------------------------------------------------------------------------------------------
//$output= NULL;
#echo '<h5 class="bg-success text-uppercase">Clusters(BLASTCLUST)</h5>';
#$query = ("SELECT * FROM LeaseWalker_clusters WHERE LW_ID ='$id'");
#$result = mysql_query($query) or die ("Error attempting query");
#$nhits = mysql_num_rows($result);
#if ($nhits == 0) {
#    echo '<pre>None</pre>';
#} else { 
#echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
##while($row = mysql_fetch_assoc($result)) {
#    $LW_cluster = $row ['LW_cluster'];   
#    echo '<tr>';
#    echo '<td>'.$LW_cluster.'</td>';
#}
#echo "</table>";
#}
//-----------------------------------------------------------------------------------------------------------------

echo '<h5 class="bg-success text-uppercase ">Putative Peptide families</h5>';
#$query = ("SELECT * FROM SIPs_clusters INNER JOIN Clusters ON SIPs_clusters.Cluster_name=Clusters.Cluster_name WHERE SIPs_clusters.SIP_ID='$sipid'");
$query = ("SELECT * FROM Cluster_Annotation INNER JOIN Clusters ON Cluster_Annotation.Cluster_name=Clusters.Cluster_name WHERE Cluster_Annotation.ID='$id'");
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
echo '<pre>None</pre>';
} else {
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo "<tr><th>Cluster ID</th><th>Other peptides within the cluster</th></tr>";
while($row = mysql_fetch_array($result)) {
$SIP_ID = $row['ID'];
$ClusterName = $row['Cluster_name'];
$ClusterPeps = $row['peptides'];
$c_links = array();
$cArray = explode(',',$ClusterPeps);
foreach($cArray as $ClusterPeps) {
if (preg_match("/sORF/", $ClusterPeps)){
$c_links[] = "<a href='Hanada.php?sORF_ID=". trim($ClusterPeps) ."'>". trim($ClusterPeps) ."</a>";
}
else if 
(preg_match("/BIP/", $ClusterPeps)){
$c_links[] = "<a href='SIPs.php?SIP_ID=". trim($ClusterPeps) ."'>". trim($ClusterPeps) ."</a>";
}
else if 
(preg_match("/OSIP/", $ClusterPeps)){
$c_links[] = "<a href='SIPs.php?SIP_ID=". trim($ClusterPeps) ."'>". trim($ClusterPeps) ."</a>";
}
else if
(preg_match("/ath_mu/", $ClusterPeps)){
$c_links[] = "<a href='LW.php?LW_ID=". trim($ClusterPeps) ."'>". trim($ClusterPeps) ."</a>";
}
} 
$new_links = implode(", ", $c_links);
echo '<tr>';
echo '<td>'.$ClusterName.'</td>';

echo '<td>'.$new_links.'</td>';
}
echo "</table>";
echo '<p><font size="2"><i>* Note: Markov Cluster Algorithm(MCL) was used to group all peptides in the ARA-PEPs database into putative families.</i></font></p>';
}

//------------------------------------------------------------------------------------------------------------------
//$output= NULL;

echo '<h5 class="bg-success text-uppercase">BLASTP against <i>A. thaliana</i> proteins</h5>';
$query = ("SELECT LeaseWalker_blastp_plants.LW_ID, GROUP_CONCAT(LeaseWalker_blastp_plants.LW_Homolog) FROM LeaseWalker_blastp_plants WHERE LW_ID = '$id' GROUP BY LeaseWalker_blastp_plants.LW_ID");

//$query = ("SELECT * FROM LeaseWalker_blastp_plants WHERE LW_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
    echo '<pre>None</pre>';
} else {
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
while($row = mysql_fetch_array($result)) {
    	$LW_blastp_plant = $row ['GROUP_CONCAT(LeaseWalker_blastp_plants.LW_Homolog)'];
	$p_links = array();
        $protsArray = explode(',',$LW_blastp_plant);
        foreach($protsArray as $LW_blastp_plant) {
        $p_links[] = "<a href='https://www.araport.org/search/thalemine/". trim($LW_blastp_plant) ."'target='_blank'>". trim($LW_blastp_plant) ."</a>";
        }
        $links_prots = implode(", ", $p_links);
    echo '<tr>';
    echo '<td>'.$links_prots.'</td>';
}
echo "</table>";
}

//SELECT  SIP_ID, Coverage FROM Homologs_info WHERE SIP_ID = 'BIP0_5';
//-----------------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">Putative functional annotations <font size="2"></font></h5>';

$query = "SELECT * FROM Functional_Annotations_LW WHERE LW_ID = '$id'"; 
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
    echo '<pre>None</pre>';
} else {
    echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
    echo "<tr><th>Domain name</th><th>PFAM ID</th><th>Significance (E-value)</th><th>GO:Term (Pfam2GO)</th><th>GO ID</th></tr>";
    while($row = mysql_fetch_array($result)) {
        $PFAM_domain_name = $row['domain_name'];
        $PFAM_ID = $row['PFAM_ID'];
        $HMMER_signf = $row['HMMER_signf'];
	$PFAM2GO = $row['PFAM2GO'];
        $PFAM_GO_ID = $row['PFAM_GO'];
        if (preg_match("/N.A/", $PFAM_ID)){
        echo "<tr><td>$PFAM_domain_name</td><td>$PFAM_ID</td><td>$HMMER_signf</td><td>$PFAM2GO</td><td>$PFAM_GO_ID</td></tr>";
        } else {
        echo '<tr>';
        echo '<td>'.$PFAM_domain_name.'</td>';
        if ($PFAM_ID != '') {
            $url = "http://pfam.xfam.org/family/$PFAM_ID";
            echo ' <td><a href="' . $url . '" target="_blank">'. $PFAM_ID . '</a></td>';
        }

	$pfamGO_term = array();
        $PFAM_GOterm_Array = explode(',',$PFAM2GO);
        foreach($PFAM_GOterm_Array as $PFAM2GO) {
        //$pfamGO_term[] = "<li>".$PFAM2GO."</li>";
        $pfamGO_term[] = $PFAM2GO;
        }
        $links_PFAM_GOterm = implode(" ", $pfamGO_term);

        $pfamGO_links = array();
        $PFAM_GO_Array = explode(',',$PFAM_GO_ID);
        foreach($PFAM_GO_Array as $PFAM_GO_ID) {
        $pfamGO_links[] = "<a href='https://www.ebi.ac.uk/QuickGO/GTerm?id=". trim($PFAM_GO_ID) ."'target='_blank'>". trim($PFAM_GO_ID) ."</a>";
        }
        $links_PFAM_GO = implode(", ", $pfamGO_links);

        echo '<td>'.$HMMER_signf.'</td>';
        echo '<td>'.$links_PFAM_GOterm.'</td>';
        echo '<td>'.$links_PFAM_GO.'</td>';
    }
}
    echo '</table>';
    echo '<p><font size="2"><i>* Note: Putative functions or terms have been assigned using HMMSEARCH against the PFAM database and 
    Cysteine Rich Proteins (Silverstein et al.,2007).</i></font></p>';
}


//--------------------------------------------------------------------------------------------------------------------

// display MPSS data in table format
function displayMPSS($data) {
    $slist = explode(',', $data);
    echo "<tr><th>Nucleotide probe</th><th>Expression</th>";
    $opentable = false;
    foreach ($slist as $field) {
        if (isNucleotideSequence($field)) {
	    if ($opentable) echo "</td></tr>";
	    else $opentable = true;
            $seq = colorNucleotides($field);
	    echo "<tr><td>$seq</td><td>";
	    $sep='';
        } else {
	    echo "$sep$field";
	    $sep = ', ';
 	}
    }
    if ($opentable) echo "</td></tr>";
}

//$output= NULL;
echo '<h5 class="bg-success text-uppercase">Massively Parallel Signature Sequencing (MPSS) analysis</h5>';
$query = ("SELECT * FROM LeaseWalker_mpss WHERE LW_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result); 
if ($nhits == 0) {
    echo '<pre>None</pre>';
} else {
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
while($row = mysql_fetch_assoc($result)) {
    $LW_mpss = $row ['LW_mpss'];
    //echo '<tr>';
    //echo '<td>'.$LW_mpss.'</td>'; // lidia
    displayMPSS($LW_mpss);
}
echo "</table>";
}

echo '<h5 class="bg-success text-uppercase">Transmembrane (TM) topology<font size="2"> (TMHMM Server v. 2.0)</font></h5>';
$query = "SELECT * FROM TMdomains_info WHERE ID = '$id'";
$result = mysql_query($query) or die ("Error attempting query $query");

$nhits = mysql_num_rows($result);

if ($nhits == 0) {
    echo '<pre>No predicted TM domains</pre>';
} else {
    echo '<pre>No. of TM domains:';
    while($row = mysql_fetch_array($result)) {
        $b = $row ['Predicted_TMHs'];
        echo " $b";
    }
    echo '</pre>';
}

$query = $query = ("SELECT Pep_seq FROM TMdomains_info WHERE ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query"); 
while($row = mysql_fetch_assoc($result)) {
    $pep = $row ['Pep_seq'];
    $pep = chop($pep,"*");
    //echo $pep;
}

$query = $query = ("SELECT Pep_seq FROM TMdomains_info WHERE ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query"); 
while($row = mysql_fetch_assoc($result)) {
    $pep = $row ['Pep_seq'];
    $pep = chop($pep,"*");
    //echo $pep;
}

//echo '<h5 class="bg-success text-uppercase">Protein feature visualization</h5>';
$query = ("SELECT * FROM TMdomains_info WHERE ID = '$id'");
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
    //echo '<pre>None</pre>';
    mysql_close($dbhandle);
    include 'footer.html';
    return;
} else {
while($row = mysql_fetch_assoc($result)) {
    $TM = $row ['Predicted_TMHs'];
    $a = $row ['start1'];
    $b = $row ['stop1'];
    $c = $row ['TM_helix1_start'];
    $d = $row ['TM_helix1_stop'];
    $e = $row ['start2'];
    $f = $row ['stop2'];
    $g = $row ['TM_helix2_start'];
    $h = $row ['TM_helix2_stop'];
    $i = $row ['start3'];
    $j = $row ['stop3'];
    $k = $row ['TM_helix3_start'];
    $l = $row ['TM_helix3_stop'];
    $m = $row ['start4'];
    $n = $row ['stop4'];
if ($TM == 1){
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo "<tr><th>loop start</th><th>loop stop</th><th>TM1 start</th><th>TM1 stop</th><th>loop start</th><th>loop stop</th></tr>"; 
echo '<tr>';
    echo '<td>'.$a.'</td>';
    echo '<td>'.$b.'</td>';
    echo '<td>'.$c.'</td>';
    echo '<td>'.$d.'</td>';
    echo '<td>'.$e.'</td>';
    echo '<td>'.$f.'</td>';
} else if ($TM == 2) {
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo "<tr><th>loop start</th><th>loop stop</th><th>TM1 start</th><th>TM1 stop</th><th>loop start</th><th>loop stop</th><th>TM2 start</th><th>TM2 stop</th><th>loop start</th><th>loop stop</th></tr>";
echo '<tr>';
    echo '<td>'.$a.'</td>';
    echo '<td>'.$b.'</td>';
    echo '<td>'.$c.'</td>';
    echo '<td>'.$d.'</td>';
    echo '<td>'.$e.'</td>';
    echo '<td>'.$f.'</td>';
    echo '<td>'.$g.'</td>';
    echo '<td>'.$h.'</td>';
    echo '<td>'.$i.'</td>';
    echo '<td>'.$j.'</td>';
} else if ($TM == 3) {
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo "<tr><th>loop start</th><th>loop stop</th><th>TM1 start</th><th>TM1 stop</th><th>loop start</th><th>loop stop</th><th>TM2 start</th><th>TM2 stop</th><th>loop start</th><th>loop stop</th><th>TM3 start</th>
<th>TM3 stop</th><th>loop start</th><th>loop stop</th></tr>";
echo '<tr>';
    echo '<td>'.$a.'</td>';
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
    echo '<td>'.$l.'</td>';
    echo '<td>'.$m.'</td>';
    echo '<td>'.$n.'</td>';
}
}
echo "</table>";
}
?>



<html>
<head> 
 
<!-- pviz protein feature viewer -->       
<link rel="stylesheet" type="text/css" href="pviz-master/examples/deps/pviz-core.css">
<script src="pviz-master/examples/deps/pviz-bundle.min.js"></script>
<!-- END -->

</head>
<body>
                
<script src="pviz-master/examples/deps/pviz-bundle.min.js"></script>
<!-- just a few lines of javscript to decorate the page -->
<style type="text/css" media="screen" class="example">

g.feature.TMDomains rect.feature {fill: red; fill-opacity: 0.8;}
g.feature.TMDomains:hover rect.feature {fill: black;fill-opacity: 0.8;}
g.feature.TMDomains text {font-weight: bold;fill: white;}

g.feature.TMDomains.TM1 rect.feature {fill: red;fill-opacity: 0.8;}
g.feature.TMDomains.TM1:hover rect.feature {fill: black;fill-opacity: 0.8;}
g.feature.TMDomains.TM1 text {font-weight: bold;fill: white;}
g.feature.TMDomains.TM2 rect.feature {fill: red;fill-opacity: 0.8;}
g.feature.TMDomains.TM2:hover rect.feature {fill: black;fill-opacity: 0.8;}
g.feature.TMDomains.TM2 text {font-weight: bold;fill: white;}
g.feature.TMDomains.TM3 rect.feature {fill: red;fill-opacity: 0.8;}
g.feature.TMDomains.TM3:hover rect.feature {fill: black;fill-opacity: 0.8;}
g.feature.TMDomains.TM3 text {font-weight: bold;fill: white;}

g.feature.TMDomains.loop rect.feature {fill: green;fill-opacity: 0.8;}
g.feature.TMDomains.blue:hover rect.feature {fill: black;fill-opacity: 0.8;}
g.feature.TMDomains.blue text {font-weight: bold;fill: white;}

</style>
<div id="main" class="row"></div>
<script class="example">
var pviz = this.pviz;
var seq = "<?php echo $pep; ?>";
//var seq = 'CGACATTCAATATTTTCCCGCCAAAAAGAT';
var seqEntry = new pviz.SeqEntry({
    sequence : seq
});

new pviz.SeqEntryAnnotInteractiveView({
    model : seqEntry,
    el : '#main'
}).render();


<?php
if ($TM == 1) {
  echo "var fts = [
    [ \"$a\", \"$b\", 'loop'],
    [ \"$c\", \"$d\", 'TM1'],
    [ \"$e\", \"$f\", 'loop']];" ;
} else if ($TM == 2) {
  echo "var fts = [
    [ \"$a\", \"$b\", 'loop'],
    [ \"$c\", \"$d\", 'TM1'],
    [ \"$e\", \"$f\", 'loop'],
    [ \"$g\", \"$h\", 'TM2'],
    [ \"$i\", \"$j\", 'loop']];" ;
} else if ($TM == 3) {
  echo "var fts = [
    [ \"$a\", \"$b\", 'loop'],
    [ \"$c\", \"$d\", 'TM1'],
    [ \"$e\", \"$f\", 'loop'],
    [ \"$g\", \"$h\", 'TM2'],
    [ \"$i\", \"$j\", 'loop'],
    [ \"$k\", \"$l\", 'TM3'],
    [ \"$m\", \"$n\", 'loop']];" ;
}
?>

var ftsOLD = 
[["<?php echo $a; ?>","<?php echo $b; ?>",'inside1'],
["<?php echo $c; ?>", "<?php echo $d; ?>", 'TM1'],
["<?php echo $e; ?>","<?php echo $f; ?>",'outside1']
["<?php echo $g; ?>", "<?php echo $h; ?>", 'TM2'],
["<?php echo $i; ?>","<?php echo $j; ?>",'outside1'],
["<?php echo $k; ?>", "<?php echo $l; ?>", 'TM3'],
["<?php echo $m; ?>","<?php echo $n; ?>",'outside1']
];

seqEntry.addFeatures(fts.map(function(ft) {
    return {
        category : 'TMDomains',
        type : ft[2],
        start : ft[0],
        end : ft[1],
        text : ft[0] + '-' + ft[1] + '/' + ft[2]
    }
}))

//seqEntry.addFeatures([{
//    category : 'secondary structure',
//    type : 'beta_strand',
//    start : 0,
//    end : 0
//}, {
//    category : 'secondary structure',
//    type : 'helix',
//    start : 0,
//    end : 0
//}]);
</script>
</body>
</html>
<br>

<?php
echo '<p><font size="1">Powered by pViz.js: a dynamic JavaScript & SVG library for visualization of protein sequence features</font></p>';
mysql_close($dbhandle);
include 'footer.html';
?>






