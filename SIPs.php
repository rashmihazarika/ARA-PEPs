<DOCTYPE html>
<html lang="en">
<head>
 

<?php
include 'header.html'; 
include 'menu.html';
include 'color_sequence.php';

if (!isset($_GET['SIP_ID'])) die("No peptide information to show");
$sipid = $_GET['SIP_ID'];

$servername = "127.0.0.1"; $username = "root"; $password = "AraDB7168#";
$dbhandle = mysql_connect($servername, $username, $password) or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not find database");

$table = 'SIP_info';
$query = "SELECT * FROM $table WHERE SIP_ID = '$sipid' ; ";

//echo '<p>'.$query.'</p>';

$result = mysql_query($query) or die ("Error attempting query $query");

echo '<h1>SIP ID: <font color=blue>'.$sipid.'</font></h1>';
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
  echo "<p>no hits.</p>";
  mysql_close($dbhandle);
  include 'footer.html';
  return;
}
while($row = mysql_fetch_array($result)) {
    $TAR_ID = $row ['TAR_ID'];
    $Strand = $row ['Strand'];
    $SIP_ID = $row ['SIP_ID'];
    $Other_Annotations = $row ['Other_Annotations'];
    $Dataset = $row ['Dataset'];
    $peptide_sequence = $row ['peptide_sequence'];
    $peptide_sequence= trim($peptide_sequence); //preg_replace('/\s+/', '', $sseq);
    $peptide_sequence = colorAminoAcids($peptide_sequence);
    $peptide_sequence = wordwrap($peptide_sequence, 20, "\n", true);
    $length_of_peptide = $row ['length_of_peptide'];
    $Homologs = $row ['Homologs'];
    $MeanAlnScore = $row ['MeanAlnScore'];
    $dNdS = $row ['dNdS'];
}

//-----------------------------------------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">General information</h5>';
echo '<table class="table table-hover table-condensed table-bordered table-striped">';
//echo '<tr><th>Strand</th><th>Other Annotations</th><th>Annotation Source</th><th>Peptide sequence</th><th>Peptide length</th></tr>';
echo '<tr><th>TAR ID</th><th>Strand</th><th>Peptide sequence</th><th>Length (AA)</th></tr>';
echo "<tr><td><a href=TAR.php?TAR_ID=$TAR_ID>$TAR_ID</a></td>";
echo '<td align="center">'.$Strand.'</td>';
//echo '<td><a href="Hanada.php?sORF_ID='.$d.'">'.$d.'</td>';
echo "<td>$peptide_sequence</td>";
echo "<td>$length_of_peptide</td></tr>";
echo '</table>';

//------------------------------------------------------------------------------------------------------------------------------------------------
$query = ("SELECT * FROM SIP_Annotations_info WHERE SIP_ID = '$sipid'");
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
} else {
    echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
    echo "<tr><th>Other Annotations</th><th>Source</th></tr>";
    while($row = mysql_fetch_array($result)) {
        $Other_Annotations = $row ['Other_Annotations'];
        $Dataset = $row ['Dataset'];
        if (preg_match("/ath_mu/", $Other_Annotations, $matches)){
            $link = "<a href=LW.php?LW_ID=$Other_Annotations target='_blank'>$Other_Annotations</a>";
            echo "<tr><td>$link</td><td>$Dataset</td></tr></table>";
        } else {
            $links = array();
            $linksArray = explode(',', $Other_Annotations);
            foreach($linksArray as $Other_Annotations) {
                $links[] = "<a href='Hanada.php?sORF_ID=". trim($Other_Annotations) ."'>". trim($Other_Annotations) ."</a>"; 
            }
            $links_str = implode(", ", $links);
            echo "<tr><td>$links_str</td><td>$Dataset</td></tr></table>";
        }
    }
    echo "</table>";
} 

//-----------------------------------------------------------------------------------------------------------------------------------------------

echo '<h5 class="bg-success text-uppercase">Available literature</h5>';

$query = ("SELECT * FROM SIP_info WHERE SIP_ID = '$sipid'");
$new_sipid = str_replace('/', '_', $sipid);
$newname=$_SERVER['DOCUMENT_ROOT'].'/CSB/ARA-PEPs/references/'. $new_sipid . ".txt";
if (file_exists($newname)) {
$refs = explode("\n", file_get_contents($newname));
foreach($refs as $v){
         echo "<font size=2>".$v."</font><br/>";
}
} else {
    echo "<pre>None</pre>";
}

//--------------------------------------------------------------------------------------------------------------------------------------------------

echo '<h5 class="bg-success text-uppercase">Assessment of coding potentiality<font size="2"> (scored against 8 plant genomes using tBLASTn, E-value set as 0.001)</font></h5>';
$query = ("SELECT * FROM SIP_info WHERE SIP_ID = '$sipid'");
$result = mysql_query($query) or die ("Error attempting query"); 
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo "<tr><th>Homologous species </th></tr>";
while($row = mysql_fetch_assoc($result)) {
    $SIP_ID = $row ['SIP_ID'];
    $Homologs = $row ['Homologs'];

    echo '<tr>';
    //echo '<td>'.$SIP_ID.'</td>';
    echo '<td><i>'.$Homologs.'</i></td>';  
    
}
echo "</table>";
//------------------------------------------------------------------------------------------------------------------------------------------------

$query = ("SELECT COUNT(SIP_ID), SIP_ID FROM Homologs_info WHERE SIP_ID = '$sipid'");
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
} else {
    echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
    echo "<tr><th>Total hits (tBLASTn)</th></tr>";
    while($row = mysql_fetch_array($result)) {
        $SIP_ID = $row['SIP_ID'];
        $No_of_tBLASTn_hits = $row['COUNT(SIP_ID)'];
        
        echo '<tr>';
        echo '<td>'.$No_of_tBLASTn_hits.'</td>';
        
    }
    echo "</table>";
} 
//------------------------------------------------------------------------------------------------------------------------------------------------

$query = ("SELECT * FROM SIP_info WHERE SIP_ID = '$sipid'");
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
} else {
    echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
    echo "<tr><th>Mean pairwise alignment score</th><th>dN/dS</th></tr>";
    while($row = mysql_fetch_array($result)) {
        $SIP_ID = $row['SIP_ID'];
        $MeanAlnScore = $row['MeanAlnScore'];
        $dNdS = $row['dNdS'];
        echo '<tr>';
        echo '<td>'.$MeanAlnScore.'</td>';
        echo '<td>'.$dNdS.'</td>';
    }
    echo "</table>";
    echo '<p><font size="2"><i>* Note: Only top tBLASTn hits and sequences without in-frame start/stop codons were extracted out to construct and score alignments. dN/dS of an alignment is indicated as 0 when dN/dS could not be estimated due to dS=0.</i></font></p>';
} 

//---------------------------------------------------------------------------------------------------------------------------------------------------

$query = ("SELECT * FROM SIP_info WHERE SIP_ID = '$sipid'");
$new_sipid = str_replace('/', '_', $sipid);
$newname=$_SERVER['DOCUMENT_ROOT'].'/CSB/ARA-PEPs/MSA/'. $new_sipid . ".pep.fasta.aln_trimmed.aln";
if (file_exists($newname)) {
$f = "<a href=JSAV/JSAV-master/P_alignments.php?SIP_ID=$SIP_ID target='_blank'><font size=2>View alignment here</font></a>";
//$f = "<a href=JSAV/JSAV-master/index.html>View raw alignment</a>";
echo "$f"; 
} else {
   // echo "<pre>No alignment to show.</pre>";
}

//---------------------------------------------------------------------------------------------------------------------------------------------------

echo '<h5 class="bg-success text-uppercase">Putative functional annotations <font size="2"></font></h5>';
$query = "SELECT * FROM Functional_Annotations_info2 WHERE SIP_ID = '$sipid'"; 
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
        //echo '<td>'.$PFAM2GO.'</td>';
        echo '<td>'.$links_PFAM_GO.'</td>';
        //echo '<td>'.$PFAM_GO_ID.'</td>';
    }
}
    echo '</table>';
    echo '<p><font size="2"><i>* Note: Putative functions or terms have been assigned using hmmsearch(HMMER3) against several datasets such as the PFAM database,
Cysteine Rich Peptides (CRPs), Defensin-like (DEFL) genes (Silverstein et al., 2005, 2007; Giacomelli et al., 2012; Zhou et al., 2013).</i></font></p>';
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------

echo '<h5 class="bg-success text-uppercase">BLASTP against <i>A. thaliana</i> proteins</h5>';
$query = "SELECT * FROM Functional_Annotations_info1 WHERE SIP_ID = '$sipid'"; 
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
echo '<pre>None</pre>';
} else {
    echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
    echo "<tr><th>Matching proteins</th><th>GO:Term</th><th>GO ID</th><th>P value</th></tr>";
    while($row = mysql_fetch_array($result)) {
        $Matching_prots = $row['Matching_prots'];
        $GO_Func_description = $row['GO_Func_description'];
        $GO_Term = $row['GO_Term'];
        $p_value = $row['p_value'];
        if (preg_match("/N.A/", $Matching_prots)){
        echo "<tr><td>$Matching_prots</td><td>$GO_Func_description</td><td>$GO_Term</td><td>$p_value</td></tr></table>";
        } else {
        $p_links = array();
        $protsArray = explode(',',$Matching_prots);
        foreach($protsArray as $Matching_prots) {
        $p_links[] = "<a href='https://www.araport.org/search/thalemine/". trim($Matching_prots) ."'target='_blank'>". trim($Matching_prots) ."</a>";
        }
        $links_prots = implode(", ", $p_links);

        $links = array();
        $linksArray = explode(',', $GO_Term);
        foreach($linksArray as $GO_Term) {
        $links[] = "<a href='https://www.ebi.ac.uk/QuickGO/GTerm?id=". trim($GO_Term) ."'target='_blank'>". trim($GO_Term) ."</a>";
        }
        $links_str = implode(", ", $links);

	//$link = "<a href=https://www.ebi.ac.uk/QuickGO/GTerm?id=$tags target='_blank'>$tags</a>";
        echo '<tr>';
        //if ($c != '') {$url = "https://www.ebi.ac.uk/QuickGO/GTerm?id=$c";echo ' <td><a href="' . $url . '" target="_blank">'. $c . '</a></td>';}
        echo '<td>'.$links_prots.'</td>';
        echo '<td>'.$GO_Func_description.'</td>';
        echo '<td>'.$links_str.'</td>';
        echo '<td>'.$p_value.'</td>';
    }
}
    echo '</table>';
    echo '<p><font size="2"><i>* Note:Homologs(>50% sequence identity) were identified using BLASTP</i></font></p>';  
}

//------------------------------------------------------------------------------------------------------------------------------------------------

echo '<h5 class="bg-success text-uppercase ">Putative Peptide families</h5>';
#$query = ("SELECT * FROM SIPs_clusters INNER JOIN Clusters ON SIPs_clusters.Cluster_name=Clusters.Cluster_name WHERE SIPs_clusters.SIP_ID='$sipid'");
$query = ("SELECT * FROM Cluster_Annotation INNER JOIN Clusters ON Cluster_Annotation.Cluster_name=Clusters.Cluster_name WHERE Cluster_Annotation.ID='$sipid'");
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
echo '<p><font size="2"><i>* Note: Markov Cluster Algorithm(mcl) was used to group all peptides in the ARA-PEPs database into putative families.</i></font></p>';
}


//---------------------------------------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">Secretion signals<font size="2"> (SignalP-4.1)</font></h5>';
$query = "SELECT * FROM signal_peptides_info WHERE ID = '$sipid'";
$result = mysql_query($query) or die ("Error attempting query $query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
    echo '<pre>None</pre>';
} else {
    echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
    echo "<tr><th>D Score</th><th>Signal start</th><th>Signal stop</th><th>Prepropeptide</th><th>Signal sequence</th><th>Propeptide</th></tr>";
    while($row = mysql_fetch_array($result)) {
        $DScore = $row ['DScore'];
        $Signal_start = $row ['Signal_start'];
        $Signal_stop = $row ['Signal_stop'];
        $Prepropeptide = $row ['Prepropeptide'];
        $Prepropeptide = trim($Prepropeptide); //preg_replace('/\s+/', '', $sseq);
        $Prepropeptide = colorAminoAcids($Prepropeptide);
        $Prepropeptide = wordwrap($Prepropeptide, 20, "\n", true);
        $Signal_sequence = $row ['Signal_sequence'];
        $Signal_sequence = trim($Signal_sequence); //preg_replace('/\s+/', '', $sseq);
        $Signal_sequence = colorAminoAcids($Signal_sequence);
        $Signal_sequence = wordwrap($Signal_sequence, 20, "\n", true);
        $Propeptide = $row ['Propeptide'];
        $Propeptide = trim($Propeptide); //preg_replace('/\s+/', '', $sseq);
        $Propeptide = colorAminoAcids($Propeptide);
        $Propeptide = wordwrap($Propeptide, 20, "\n", true);
        echo '<tr>';
        echo '<td>'.$DScore.'</td>';
        echo '<td>'.$Signal_start.'</td>';
        echo '<td>'.$Signal_stop.'</td>';
        echo '<td>'.$Prepropeptide.'</td>';
        echo '<td>'.$Signal_sequence.'</td>';
        echo '<td>'.$Propeptide.'</td>';
    }
    echo '</table>';
}

//----------------------------------------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">Transmembrane (TM) topology<font size="2"> (TMHMM Server v. 2.0)</font></h5>';
$query = "SELECT * FROM TMdomains_info WHERE ID = '$sipid'";
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

//---------------------------------------------------------------------------------------------------------------------------------------------------------
$query = ("SELECT * FROM SIP_info WHERE SIP_ID = '$sipid'");
$result = mysql_query($query) or die ("Error attempting query"); 
while($row = mysql_fetch_assoc($result)) {
    $pep = $row ['peptide_sequence'];
    $pep = chop($pep,"*");
    //echo $pep;
}

//echo '<h5 class="bg-success text-uppercase">Protein feature visualization</h5>';
$query = ("SELECT * FROM TMdomains_info WHERE ID = '$sipid'");
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
                
<!-- just a few lines of javscript to decorate the page -->
<script src="pviz-master/examples/deps/pviz-bundle.min.js"></script>
<style type="text/css" media="screen" class="example">


g.feature.TMDomains rect.feature {fill: red; fill-opacity: 0.8;}
g.feature.TMDomains:hover rect.feature {fill: black;fill-opacity: 0.8;}
g.feature.TMDomains text {font-weight: bold;fill: white;}

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

seqEntry.addFeatures(fts.map(function(ft) {
    return {
        category : 'TMDomains',
        type : ft[2],
        start : ft[0],
        end : ft[1],
        text : ft[0] + '-' + ft[1] + '/' + ft[2]
    }
}))

</script>
</body>
</html>
<br>

<?php
echo '<p><font size="1">Powered by pViz.js: a dynamic JavaScript & SVG library for visualization of protein sequence features</font></p>';
mysql_close($dbhandle);
include 'footer.html';
?>

