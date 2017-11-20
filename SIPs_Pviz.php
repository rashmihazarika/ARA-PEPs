<DOCTYPE html>
<html lang="en">
<head>

<!-- pviz protein feature viewer -->       
<link rel="stylesheet" type="text/css" href="pviz-master/examples/deps/pviz-core.css">
<script src="pviz-master/examples/deps/pviz-bundle.min.js"></script>
<!-- END -->

</head>
<body>

<?php
include 'menu.html';
include 'color_sequence.php';

if (!isset($_GET['SIP_ID'])) die("No peptide information to show");
$sipid = $_GET['SIP_ID'];

$servername = "127.0.0.1"; $username = "root"; $password = "";
$dbhandle = mysql_connect($servername, $username, $password) 
or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not find database");

$query = ("SELECT * FROM SIP_info WHERE SIP_ID = '$sipid'");
$result = mysql_query($query) or die ("Error attempting query"); 
while($row = mysql_fetch_assoc($result)) {
    $pep = $row ['peptide_sequence'];
}

echo '<h3><font color=CC9900>Protein feature visualization</font></h3>';
$query = ("SELECT * FROM TMdomains_info WHERE SIP_ID = '$sipid'");
$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
    echo '<pre>No features to show</pre>';
    mysql_close($dbhandle);
    include 'footer.html';
    return;
}
while($row = mysql_fetch_assoc($result)) {
    $a = $row ['Inside1_start'];
    $b = $row ['Inside1_stop'];
    $c = $row ['TM_helix1_start'];
    $d = $row ['TM_helix1_stop'];
    $e = $row ['Outside1_start'];
    $f = $row ['Outside1_stop'];
    $g = $row ['TM_helix2_start'];
    $h = $row ['TM_helix2_stop'];
    $i = $row ['Inside2_start'];
    $j = $row ['Inside2_stop'];
    $k = $row ['TM_helix3_start'];
    $l = $row ['TM_helix3_stop'];
    $m = $row ['Outside2_start'];
    $n = $row ['Outside2_stop'];
}
?>

<html>
<head>                 
<script src="pviz-master/examples/deps/pviz-bundle.min.js"></script>
<!-- just a few lines of javscript to decorate the page -->
<style type="text/css" media="screen" class="example">
g.feature.TMDomains rect.feature {fill: red; fill-opacity: 0.8;}
g.feature.TMDomains:hover rect.feature {fill: black;fill-opacity: 0.8;}
g.feature.TMDomains text {font-weight: bold;fill: white;}
g.feature.TMDomains.inside rect.feature {fill: green;fill-opacity: 0.8;}
g.feature.TMDomains.inside:hover rect.feature {fill: black;fill-opacity: 0.8;}
g.feature.TMDomains.inside text {font-weight: bold;fill: white;}
g.feature.TMDomains.outside rect.feature {fill: green;fill-opacity: 0.8;}
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
var fts = [["<?php echo $a; ?>","<?php echo $b; ?>",'inside'],["<?php echo $c; ?>", "<?php echo $d; ?>", 'TM1'],
["<?php echo $e; ?>","<?php echo $f; ?>",'outside'],["<?php echo $g; ?>", "<?php echo $h; ?>",'TM2'],
["<?php echo $i; ?>","<?php echo $j; ?>",'inside'],["<?php echo $k; ?>", "<?php echo $l; ?>",'TM3'],
["<?php echo $m; ?>","<?php echo $n; ?>",'outside']];

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

<?php
mysql_close($dbhandle);
include 'footer.html';
?>

