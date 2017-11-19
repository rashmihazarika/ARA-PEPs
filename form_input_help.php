<?php 
include 'header.html'; 
include 'menu.html'; 
?>

<section class="span12">  
<h1 class="page-header">User guide</font></h1>
<h2><a id="Getting started with ARA-PEPs"></a><span style="text-decoration: underline;">Getting started with ARA-PEPs</span></h2>
<p>ARA-PEPs offers quick and easy browsing.<br />
<br />

<h2><a id="Search"></a><span style="text-decoration: underline;">Search</span> <span class="glyphicon glyphicon-arrow-right"> </span></h2>

<p>Clicking on <code>Datasets</code> tab will list the 3 datasets within ARA-PEPs.<br/></p>
<div style="text-align: center;"><img class="img-responsive img-thumbnail" alt="Search" src="searchDatasets.png" /><br/></div>

<p><br/><br/>
Choose any one of the Dataset. Each dataset will open up an overview page. Clicking on Stress-induced Peptides  will give an overview of all
 the currently available TARs. Next, click on any desired TAR or SIP to obtain more information about it.<br/></p>
<div style="text-align: center;"><img class="img-responsive img-thumbnail" src="SIPs-DB.png" alt="categories" /><br/></div>

<p><br/><br/>
<code>An example</code>: Clicking on BtTAR109/PQTAR106 will yield detailed information. Now click on the hyperlinks for more information.<br /></p>
<div style="text-align: center;"><img class="img-responsive img-thumbnail" alt="search" src="TARwiki.png" /><br/></div>

<p><br/><br/>
<code>Another example</code>: Clicking on XLOC_019305 shows the differential expression of the locus.<br/></p>
<div style="text-align: center;"><img class="img-responsive img-thumbnail" src="RNAseq.png" alt="categories" /><br/></div>

<p><br/><br/>
<p>Clicking on any of the SIPs from the previous page will reveal more information about the putative peptide.<br/></p>
<div style="text-align: center;"><img class="img-responsive img-thumbnail" alt="search" src="SIPswiki.png" /><br/></div>

<p><br/><br/>
Search results can be filtered by Tiling Array expression/RNAseq expression levels and chromosomal co-ordinates.
 Users can build their own query to do a search here. If one or more conditions are left blank, the query fields will
 be ignored and all the available TARs will be listed. The form can be cleared by clicking on the 'Clear' button.
 Similarly, you can search the whole the ARA-PEPs database as below.<br/></p>
<div style="text-align: center;"><img class="img-responsive img-thumbnail" src="search_form1.png" alt="categories" /><br/></div>

<p><br/><br/>
Clicking on "Tiling Arrays" will display the expression levels of the TARs filtered by chromosomal positions from Tiling Arrays.
If one or more conditions are left blank, the query fields will be ignored and all the available TARs will be listed. The user 
can select the desired TAR from the table and view the details.<br/></p>
<div style="text-align: center;"><img class="img-responsive img-thumbnail" src="TARexplvel.png" alt="categories" /><br/></div>

<p><br/><br/>
It is possible to search the whole ARA-PEPs database. Simply enter the chromosomal positions. Enter at least one field and 
if any of the conditions are left blank, the query fields will be ignored and all available sequences within that criteria will be listed.
It is also possible to search ARA-PEPs for sequences with TM domains or signal sequences.
Input the desired peptide criteria: minimum and/or maximum length, with a signal sequence or with TM domain and those with 
evolutionary conservation (dN/dS less than 1). If both signal sequence and evolutionary conservation boxes are checked, only the peptides
 that are conserved and have a signal sequence will be listed. The user needs to fill in at least one field. <br/></p>
<div style="text-align: center;"><img class="img-responsive img-thumbnail" src="search_form2.png" alt="categories" /><br/></div>

<p><br/><br/>
Paste your nucleotide or peptide sequence in the corresponding field to do a blast  search against all the sequences in ARA-PEPs. 
A formatted list of matching nucleotides or peptides will be shown. The user can alternatively click on the raw output option.<br /></p>
<div style="text-align: center;"><img class="img-responsive img-thumbnail" src="Blast_page.png" alt="categories" /><br/></div>

<p><br/><br/>
<h2><a id="JBrowse"></a><span style="text-decoration: underline;">JBrowse</span> <span class="glyphicon glyphicon-arrow-right"> </span></h2>
<p>Clicking on JBrowse will open a new tab in your browser with a window that allows the user to visualize the stress induced TARs 
and SIPs together with other tracks, mapped to the reference genome TAIR10. Click on the checkbox on the left panel to view the desired tracks.<br/></p>

<p>You can move across the tracks by clicking and dragging your mouse inside the track window or by using the navigation tools. 
Center the view at a point by clicking on either the track scale bar on top of the webpage or by shift-clicking in the track area. 
Zoom in and out by clicking zoom buttons in the navigation bar or by pressing the up and down arrow keys + shift. <br/></p>

<div style="text-align: center;"><img class="img-responsive img-thumbnail" src="JBrowse.png" alt="categories" /><br/></div>
<p><br/><br/>

<?php include 'footer.html'; ?>

