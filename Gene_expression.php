<DOCTYPE html>
<html lang="en">
<head>


<?php 
include 'header.html'; 
include 'menu.html'; 


if (!isset($_GET['Gene_id'])) die("No LOCUS to show");
$Gene_id = $_GET['Gene_id'];
$tarid = $_GET['TAR_ID'];

//include 'color_sequence.php';

$servername = "127.0.0.1"; $username = "root"; $password = "";
$dbhandle = mysql_connect($servername, $username, $password) 
or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysql_select_db("OSIP",$dbhandle) or die("Could not find database");

echo '<h1>TAR ID: <font color="blue">' . $tarid . '</font></h1>';
echo '<h1>Gene_id/Locus: <font color="blue">' . $Gene_id . '</font></h1>';
echo '<h5 class="bg-success text-uppercase">RNA sequencing expression data</font></h5>';

//------------------------------------------------------------------------------------------------------------------------------

echo '<h5><font color="brown">Differential expression</font></h5>';

$query= "SELECT * FROM RNAseq_info LEFT JOIN Genes_FPKM ON RNAseq_info.Gene_id = Genes_FPKM.Gene_id WHERE RNAseq_info.Gene_id='$Gene_id' AND RNAseq_info.TAR_ID='$tarid';";

$result = mysql_query($query) or die ("Error attempting query");
$nhits = mysql_num_rows($result);
if ($nhits == 0) {
echo '<pre>None</pre>';
} else {
    echo "<table class='table table-hover table-condensed table-bordered table-striped' style='font-size: 15px'>";
    echo "<tr><th>TAR</th><th>Treatments</th><th>FPKM</th><th>FPKM_conf_hi</th><th>FPKM_conf_lo</th><th>Standard deviation</th></tr>";
    while($row = mysql_fetch_array($result)) {
        //$Gene_id = $row['Gene_id'];
        $TAR = $row['TAR_ID'];
        $sample = $row['sample_name'];
        $fpkm = $row['fpkm'];
        $conf_hi = $row['conf_hi'];
        $conf_lo = $row['conf_lo'];
        $stdev = $row['stdev'];
	
        echo '<tr>';
        echo '<td>'.$TAR.'</td>';
        echo '<td>'.$sample.'</td>';
	echo '<td>'.$fpkm.'</td>';
        echo '<td>'.$conf_hi.'</td>';
        echo '<td>'.$conf_lo.'</td>';
	echo '<td>'.$stdev.'</td>';
        echo '</tr>';
    }
    echo "</table>";
}

//-------------------- RNAseq expression Bar chart-------------------------------------------------------------------------------------------

$query= "SELECT * FROM RNAseq_info LEFT JOIN Genes_FPKM ON RNAseq_info.Gene_id = Genes_FPKM.Gene_id WHERE RNAseq_info.Gene_id='$Gene_id' AND RNAseq_info.TAR_ID='$tarid';";

$result = mysql_query($query) or die ("Error attempting query");
$fpkm_data = '';
while($row=mysql_fetch_assoc($result)){   
    $fpkm_data[] = $row['fpkm'];
}
$fpkm_vals = implode(",",$fpkm_data);

echo '<div id="container" style="min-width: 200px; height: ' . $chart_height . 'px; margin: 0 auto"></div>';

?>

<!-- Highcharts -->  
<script src="highcharts/jquery-1.8.2.min.js"></script>
<script src="highcharts/highcharts.js"></script>
<script src="highcharts/highcharts-more.js"></script>
<script src="highcharts/modules/exporting.js"></script>
<!-- END -->


<script>
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Expression Barplot'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
                'BC',
                'MOCK_BC',
                'MOCK_PQ',
                'PQ',
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'FPKM'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} FPKM</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.1,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Treatments',
            data: [ <?php echo $fpkm_vals; ?> ]

        }]
    });
});
</script>
<?php
mysql_close($dbhandle);
include 'footer.html';
?>
