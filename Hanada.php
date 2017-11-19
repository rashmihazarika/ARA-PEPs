<DOCTYPE html>
<html lang="en">

<?php
include 'header.html'; 
include 'menu.html'; 
include 'color_sequence.php';

$servername = "127.0.0.1"; $username = "root"; $password = "AraDB7168#";

$dbhandle = mysql_connect($servername, $username, $password) 
or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not select stress_peptides");

if (isset($_GET['sORF_ID'])) {$id = $_GET['sORF_ID'];} else { die("Invalid input"); } 

$output= NULL;
echo '<h1>sORF ID: <font color=blue>'.$id.'</font></h1>';
echo '<h5 class="bg-success text-uppercase">General information</h5>';
//Selecting the data from table but with limit
 
$query = ("SELECT * FROM Hanada_sORF_info WHERE sORF_ID ='$id'"); 
$result = mysql_query($query) or die ("Error attempting query");

echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo "<tr><th>sORF ID</th><th>Chr</th><th>Strand</th><th>Chr left (TAIR8)</th><th>Chr right (TAIR8)</th>
<th>Chr left (TAIR10)</th><th>Chr right (TAIR10)</th></tr>";

while($row = mysql_fetch_assoc($result)) {
    $a = $row ['sORF_ID'];
    $b = $row ['Chrm'];
    $c = $row ['strand'];
    $d = $row ['chr_leftTAIR8'];
    $d_links = array();
    $d_linksArray = explode(',', $d);
    foreach($d_linksArray as $d) {
    $d_links[] = "$d";
        }
    $d_links_str = implode(", ", $d_links);

    $e = $row ['chr_rightTAIR8'];
    $e_links = array();
    $e_linksArray = explode(',', $e);
    foreach($e_linksArray as $e) {
    $e_links[] = "$e";
        }
    $e_links_str = implode(", ", $e_links);

    $f = $row ['chr_leftTAIR10'];
    $f_links = array();
    $f_linksArray = explode(',', $f);
    foreach($f_linksArray as $f) {
    $f_links[] = "$f";
        }
    $f_links_str = implode(", ", $f_links);

    $g = $row ['chr_rightTAIR10'];
    $g_links = array();
    $g_linksArray = explode(',', $g);
    foreach($g_linksArray as $g) {
    $g_links[] = "$g";
        }
    $g_links_str = implode(", ", $g_links);
                        
    echo '<tr>';
    echo '<td>'.$a.'</td>';
    echo '<td>'.$b.'</td>';
    echo '<td align="center">'.$c.'</td>';
    echo '<td>'.$d_links_str.'</td>';
    echo '<td>'.$e_links_str.'</td>';
    echo '<td>'.$f_links_str.'</td>';
    echo '<td>'.$g_links_str.'</td>';
}
echo "</table>";
$whereis  = 'nd';
$pos = strpos($f_links_str, $whereis);
if ($pos === false) {
echo '<p><i><a href="http://www.biw.kuleuven.be/CSB/ARA-PEPs/JBrowse-1.12.0/index.html?data=data&loc=' . $b. '%3A' . $f_links_str . '..' . $g_links_str . '" target="_blank">  Visualize this region in JBrowse-1.12.0</a></i></p>';
}

//-----------------------------------------------------------------------------------------------------------------------
//$output= NULL;
echo '<h5 class="bg-success text-uppercase">Nucleotide sequence</h5>';
$query = ("SELECT * FROM sORF_nucl_info WHERE sORF_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query"); 
//echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
//echo "<tr><th>Nucleotide</th></tr>";


while($row = mysql_fetch_assoc($result)) {
    $h = $row ['sORF_nucl'];
    $h = preg_replace('/\s+/', '', $h);
    $h = colorNucleotides($h);
    $h = wordwrap($h, 150, "\n", true);
    echo "<p>$h </p>";
}
//-----------------------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">Peptide sequence</h5>';
$query = ("SELECT * FROM sORF_pep_info LEFT JOIN Hanada_sORF_info ON sORF_pep_info.sORF_ID = Hanada_sORF_info.sORF_ID where sORF_pep_info.sORF_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query"); 
while($row = mysql_fetch_assoc($result)) {
    $h = $row ['sORF_pep'];
    $h = preg_replace('/\s+/', '', $h);
    $h = colorNucleotides($h);
    $h = wordwrap($h, 150, "\n", true);
    echo "<p>$h </p>";
}

//--------------------------------------------------------------------------------------------------------------------------------------
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo '<tr><th>Peptide length</th><th>dN/dS</th><th>p-value</th></tr>';
$query = "SELECT * FROM sORF_pep_info LEFT JOIN Hanada_dNdS_info ON sORF_pep_info.sORF_ID = Hanada_dNdS_info.ID WHERE sORF_pep_info.sORF_ID = '$id'" ;
$result = mysql_query($query) or die ("Error attempting query $query");

while($row = mysql_fetch_assoc($result)) {
    $d = $row ['sORF_pep_len'];
    $e = $row ['dNdS'];
    $f = $row ['Pval'];
                            
    echo '<tr>';
    echo '<td>'.$d.'</td>';
    echo '<td>'.$e.'</td>';
    echo '<td>'.$f.'</td>';
   
}
echo '</tbody>';
echo '</table>';
echo '<p><font size="2">* Note: <i>dN/dS</i> score were obtained from Hanada et al., 2007. The genomes of <i>Brassica oleracea</i>,<i> Oryza sativa </i>subsp.<i> japonica</i>,<i> Populus trichocarpa</i>,<i> Medicago truncatula</i> and <i>Lotus corniculatus</i> var. <i>japonicus</i> were used to assess the conservation of novel sORFs. Check article below.</font></p>';
echo "<p><font size=2><i><a href=https://www.ncbi.nlm.nih.gov/pubmed/17395691>PubMed</a></i></font></p>";
//----------------------------------------------------------------------------------------------------------------------------------
echo '<h5 class="bg-success text-uppercase">Other Annotations</h5>';
$query = ("SELECT sORF_otherAnnot FROM sORF_pep_info where sORF_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query");
#$nhits = mysql_num_rows($result);
while($row = mysql_fetch_array($result)) {
        $h = $row ['sORF_otherAnnot'];
        if (preg_match("/ath_mu/", $h, $matches)){
            $link_Annot = "<a href=LW.php?LW_ID=$h target='_blank'>$h</a>";
        } elseif (preg_match("/sORF/", $h, $matches)) {
            $link_Annot = "<a href=Hanada.php?sORF_ID=$h target='_blank'>$h</a>";
        } elseif (preg_match("/BIP/", $h, $matches)) {
            $link_Annot = "<a href=SIPs.php?SIP_ID=$h target='_blank'>$h</a>";
        } elseif (preg_match("/OSIP/", $h, $matches)) {
            $link_Annot = "<a href=SIPs.php?SIP_ID=$h target='_blank'>$h</a>";
        } else {
            $link_Annot = "None";
        }
            echo "<pre>$link_Annot </pre>";

}


//---------------------------------------------------------------------------------------------------------------------------------------------------------------
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


#SELECT Cluster_name, GROUP_CONCAT(ID SEPARATOR ', ') FROM Cluster_Annotation where Cluster_name = 1 group by 'all';
#SELECT Cluster_name, GROUP_CONCAT(ID SEPARATOR ', ') FROM Cluster_Annotation GROUP BY Cluster_name;
//-------------------------------------------------------------------------------------------------------------------------

echo '<h5 class="bg-success text-uppercase">Secretion signals<font size="2"> (SignalP-4.1)</font></h5>';
$query = "SELECT * FROM signal_peptides_info WHERE ID ='$id'";
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
//----------------------------------------------------------------------------------------------------------------------------------------

echo '<h5 class="bg-success text-uppercase">Putative functional annotations <font size="2"></font></h5>';

$query = "SELECT * FROM Functional_Annotations_sORFs WHERE sORF_ID = '$id'"; 
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

//-----------------------------------------------------------------------------------------------------------------------------------------------------
//$output= NULL;
echo '<h5 class="bg-success text-uppercase">Details</h5>';
$query = ("SELECT * FROM Hanada_sORF_info WHERE sORF_ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query"); 
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
echo "<tr><th>Probe ID</th><th>No of expression organs/conditions</th><th>FLcDNAs</th><th>Translational evidence</th>
<th>Type of Homology</th><th>No of Homologs</th><th>Gene Family ID</th><th>TAIR10 Annotation</th></tr>";

while($row = mysql_fetch_assoc($result)) {
    $h = $row ['Probe_ID'];
    $i = $row ['No_of_expression'];
    $j = $row ['FLcDNA'];
    $k = $row ['Translational_evidence'];
    //$l = $row ['TypeofHomology '];
    $l = $row ['TypeofHomology'];
    $m = $row ['NoOFHomologs'];
    $n = $row ['genefamilyID'];
    $o = $row ['TAIR10_Annotation'];
    $link_TAIR10 = '<a href="https://www.araport.org/search/thalemine/' . $o . '"' . "target='_blank'>$o</a>";
    $link_ATRIKEN = '<a href="http://evolver.psc.riken.jp/seiken/probe.cgi?' . $h . '"' . "target='_blank'>$h</a>";
    //$link_ATRIKEN = "<a href=http://evolver.psc.riken.jp/seiken/probe.cgi?$h target='_blank'>$h</a>";
    //$link_ATRIKEN = "<a href=http://evolver.psc.riken.jp/seiken/probe.cgi?ATRIKEN30282 target='_blank'>$h</a>";
    //RIKEN link not working
    echo '<tr>';
    echo '<td>'.$link_ATRIKEN.'</td>';
    echo '<td>'.$i.'</td>';
    echo '<td>'.$j.'</td>';
    echo '<td>'.$k.'</td>';
    echo '<td>'.$l.'</td>';
    echo '<td>'.$m.'</td>';
    echo '<td>'.$n.'</td>';
    echo '<td>'.$link_TAIR10.'</td>';
}
echo "</table>";

//---------------------------------------------------------------------------------------------------------------------------------------------

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
    echo '<h5 class="bg-success text-uppercase">Protein feature visualization</h5>';

}

$query = $query = ("SELECT Pep_seq FROM TMdomains_info WHERE ID ='$id'");
$result = mysql_query($query) or die ("Error attempting query"); 
while($row = mysql_fetch_assoc($result)) {
    $pep = $row ['Pep_seq'];
    $pep = chop($pep,"*");
    //echo $pep;
}

#echo '<h5 class="bg-success text-uppercase">Protein feature visualization</h5>';
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
echo "<tr><th>loop start</th><th>loop stop</th><th>TM1 start</th><th>TM1 stop</th><th>loop start</th><th>loop stop</th>
<th>TM2 start</th><th>TM2 stop</th><th>loop start</th><th>loop stop</th><th>TM3 start</th>
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



