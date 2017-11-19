<?php 
include 'header.html'; 
include 'menu.html'; 
?>

<!-- Forms -->
<div class="well well-lg" style="background-color: #e1d7c4;">

    <FORM METHOD=GET ACTION="search_SIPs.php" target="_blank">
       <div class="form-group">
            <h2>Search biotic/abiotic stress-induced Transcriptionally-Active Regions(TARs):</h2>
             <h6><font color=#221133>Customize your query by selecting at least one option:</font></h6>
             <p>
	    <!--<h6><font color="gray"> Click any of the options below:</font></h6>-->
              <input type="radio" name="expressionType" value="tiling"> <b>Tiling Arrays</b> (log2fold ratio):
              <select name="conditionTiling">
              <option value="top">the highest</option>
              <option value=">">higher than</option>
              <option value="<">lower than</option>
              <option value="bottom">the lowest</option>
              <input type="number" name="levelTiling" step="1" min="1" max="145" placeholder="10"><br>
              <input type="radio" name="expressionType" value="RNA"> <b>RNA-Seq</b> (FPKMs):
              <select name="conditionRNA">
              <option value="top">the highest</option>
              <option value="bottom">the lowest</option>
              <input type="number" name="levelRNA" step="1" min="1" placeholder="10"><br>
              <input type="radio" name="expressionType" value="both"> <b>all TARs expressed in both Tiling Arrays & RNA-Seq experiments</b> <br>
              <br>
              <b>AND</b>
              <br>         
	      <br>
              <b>Chromosome: </b><input type="number" name="Chromosome" step="1" min="1" max="5" placeholder="1"><br>
              <b>Chromosome start: </b><input type="number" name="Chr_start" step="1" min="1" placeholder="10000000"><font color="grey"></font><br>
              <b>Chromosome stop: </b><input type="number" name="Chr_stop" step="1" min="1" placeholder="20000000"> 
              <button type="submit" class="btn btn-primary">Submit</button>
              <button type="reset" class="btn btn-default">Clear</button>
        </div>         
     </FORM>
</div>

<!-- search form for merged_peps_info_n -->
<div class="well well-lg" style="background-color:  #c9d5d5;">
    <FORM METHOD=GET ACTION="search_ARAPEPs_full.php" target="_blank">
       <div class="form-group">
         <h2>Search the whole ARA-PEPs database:</h2>
         <h6><font color=#221133>Filter by chromosomal position:</font></h6>
         <p>
            <b>Chromosome: </b><input type="number" name="Chromosome" step="1" min="1" max-"5" placeholder="1"><br>
            <b>Chromosome start: </b><input type="number" name="Chr_start" step="1" min="1" placeholder="10000000"><font color="grey"></font><br>
            <b>Chromosome stop: </b><input type="number" name="Chr_stop" step="1" min="1" placeholder="20000000">
            <button type="submit" class="btn btn-primary name="submit">submit</button>
            <button type="reset" class="btn btn-default">clear</button>
            </div>
            </FORM>
        </div> 
    </body>
</html>


<div class="well well-lg" style="background-color: #f0efe0;">
    <FORM METHOD=GET ACTION="signalSeq_TM.php" target="_blank">
       <div class="form-group">
	  <h2>Search ARA-PEPs for peptides with TM domains or secretion signals:</h2>
	    <h6><font color=#221133> Customize your query by selecting at least one option:</font></h6>
	    <p>
	    <b>Enter peptide length: </b>
            <br>
            <b>Minimum: </b><input type="number" step="1" min="1" max="250" name="min_length" placeholder="1">
            <b>Maximum: </b><input type="number" step="1" min="1" max="250" name="max_length" placeholder="200">
            <br>
            <input type="radio" name="feature" value="sigseq"> with <b> Signal sequence: </b>
            <br>
            <input type="radio" name="feature" value="TM"> with <b> TM Domains: </b>
            <br>
            <input type="radio" name="feature" value="all"> all peptides: </b>
            <br>
            AND
	    <br> <input type="checkbox" name="dnds" value="y"><b> purifying selection (dN/dS<1): </b>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-default">Clear</button>
            <br>
            <br>
            <p>
       </div>
    </FORM>
</div>

<?php include 'footer.html'; ?>
