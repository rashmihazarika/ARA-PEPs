<?php 
//include 'headpapger.html'; 
include 'header.html'; 
include 'menu.html'; 
include 'color_sequence.php';
include 'check_sequence.php';

$qtype = '';
$raw = '';
$seq = '';
$maxseqlen = 2000; // how much is a reasonable limit?

if (isset($_POST['qtype'])) $qtype = $_POST['qtype'];
if (isset($_POST['raw'])) $raw = $_POST['raw'];
if (isset($_POST['sequence'])) $seq = $_POST['sequence'];
if (strlen($seq) > $maxseqlen) {
  die("<p>maximum sequence length exceeded: should be at most $maxseqlen");
}

$seq = trim($seq);
$seq = preg_replace('/\s+/', '', $seq); // remove *all* blanks from seq
if ($seq == '') {
  die("<p>empty $qtype sequence");
}

if ($qtype == 'TAR/ORF') {
  if (! isNucleotideSequence($seq)) {
    die("<p>incorrect nucleotide sequence<code>$seq</code>");
  }
  $prog='blastn';
  $db='ARAPEPS_nucleotides';
  $colorseq = colorNucleotides($seq);
} else {
  if (! isAminoAcidSequence($seq)) {
    die("<p>incorrect amino acid sequence <code>$seq</code>");
  }
  if (isNucleotideSequence($seq)) {
    echo '<p><font color="red">WARNING:</font> ';
    echo 'Your sequence looks more like a nucleotide sequence!';
    echo '<p>Are you sure this is the query you intended?';
    echo '<p>If not, please go back and resubmit your query.<hr>';
  }
  $prog='blastp';
  $db='ARAPEPS_peptides';
  $colorseq = colorAminoAcids($seq);
}

$query='/var/tmp/query.fa';
$queryfile = fopen($query, 'w') or die('Unable to create query file');
fwrite($queryfile, ">myquery\n$seq\n");
fclose($queryfile);

$prpath='/usr/bin';
$dbpath='/var/www/html/CSB/ARA-PEPs/blastdb';

if ($raw != '') {
  $options = '-html';
  $sortcmd = '';
} else {
  $options = "-outfmt '6 std qseq sseq' ";
  $sortcmd = '| sort -g -k 11,11'; // sort per increasing e-value
}
$cmd = "$prpath/$prog -db $dbpath/$db -query $query $options $sortcmd";
//echo "CMD = $cmd";

$output = shell_exec($cmd);
if ($raw != '') {
  echo "<pre>$output</pre>";
  exit();
}

//echo "<p>BLAST search results for $qtype sequence: $colorseq </p>";
//echo "<p>$qtype input sequence: $colorseq </p>";
echo "<p>Search with default parameters: </p>";
echo "<pre>Input sequence: $colorseq </pre>";
//echo "OUTPUT: <pre>$output</pre>END";
$lines = explode("\n", $output);

//nhits = sizeof($lines) - 1;
if (sizeof($lines) < 2) {
  echo '<p>No hits.</p>';
  //exit();
  goto end;
}

echo "<p>Hits:</p>";
echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
//echo "<table width='100%' border='2' cellspacing='1' cellpadding='3'>";
$names = array("$qtype id", "% identity",
//  "alignment<br>length", "mismatches", "gap<br>openings",
  "alignment length", "mismatches", "gap opens",
  "query start", "query end", "subject start", "subject end", "e-value", "score");

echo '<tr>';
$i = 0;
foreach ($names as $name) {
  $i ++;
  if ($i != 1 && $i != 10) {
    $align = ' align="right"';
  } else {
    $align = '';
  }
  echo "<td$align><strong>$name</strong></td>";
}
echo '</tr>';

//-------------------------------------------------------------------------------------
// print table of hits
$h = 0; // hit number
foreach ($lines as $line) {
  echo '<tr>';
  $field = explode("\t", $line);
  $nfields = sizeof($field);
  if ($nfields < 3) {
    continue;
  }
  $sid[$h] = $field[1]; // sequence id of hit
  $qseq[$h] = $field[$nfields - 2]; // aligned part of query sequence
  $sseq[$h] = $field[$nfields - 1]; // aligned part of hit sequence
  $h ++;
  for ($i=1; $i < $nfields - 2; $i++) {
    $f = $field[$i];
    $align = '';
    if ($i == 1) {
      if (preg_match("/ath_mu/", $f, $matches)){
            $f = "<a href=LW.php?LW_ID=$f target='_blank'>$f</a>";  
        } elseif (preg_match("/sORF/", $f, $matches)) {
            $f = "<a href=Hanada.php?sORF_ID=$f target='_blank'>$f</a>";
        } elseif (preg_match("/IP/", $f, $matches)){    
            $f = "<a href=SIPs.php?SIP_ID=$f target='_blank'>$f</a>";
        } elseif (preg_match("/TAR/", $f, $matches)){
            $f = "<a href=TAR.php?TAR_ID=$f target='_blank'>$f</a>";
	} 
    } elseif ($i != 10) {
      $align = ' align="right"';
    }
    echo "<td$align>$f</td>";
  }
  echo '</tr>';
}
echo '</table>';

//-----------------------------------------------------------------------------------
// print details about each hit
echo "<p>The high scoring pairs:</p>";
for ($i=0; $i < $h; $i++) {
  $title = $sid[$i];
  if ($qtype == 'Peptide') {
    //$title = "<a href=peptide_info.php?SIP_id=$title>$title</a>";
    // $title = "<a href=SIPSwiki.php?SIP_ID=$title>$title</a>";
    $s1 = colorAminoAcids($qseq[$i]);
    $s2 = colorAminoAcids($sseq[$i]);
  } else {
    //$title = "<a href=TARinfotracks.php?TAR_ID=$title>$title</a>";
    //$title = "<a href=TARwiki.php?TAR_ID=$title>$title</a>";
    $s1 = colorNucleotides($qseq[$i]);
   $s2 = colorNucleotides($sseq[$i]);
  }
  $n = $i+1;
  echo "<p><a name=hit$n>$title</a></p>";
  echo "<pre> Query:$s1 Subj:$s2</pre>";
  //echo $s1;
  //echo $s2;
  echo "<p>";
}

end:

?>

<hr>

<p>Search performed by
<b><a href="http://www.ncbi.nlm.nih.gov/news/06-16-2015-blast-plus-update/" target="_blank">NCBI BLAST+ standalone version 2.2.31</a></b>


<?php include 'footer.html'; ?>
