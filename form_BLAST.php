<?php 
include 'header.html'; 
include 'menu.html'; 
?>
<br>
<div class="well" style="background-color:  #c9d5d5;">
     <FORM METHOD=POST ACTION="blast.php">
       <div class="form-group">
	  <h2>Do a quick BLAST search against ARA-PEPs:</h2>
            <h5><font color="#663300">BLASTN</font></h5>
            <h6><font color="#663300">Paste your nucleotide sequence here...</font></h6>
            <p>
	    <input type="hidden" name="qtype" value="TAR/ORF">
            <textarea rows="4" cols="80" value="" name="sequence">
            </textarea>
            <br>Display raw blast output:
            <input type="checkbox" name="raw" value="y"><br>
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-default">Clear</button>
       </div>
     </FORM>

     <FORM METHOD=POST ACTION="blast.php">
       <div class="form-group">
            <h5><font color="#663300">BLASTP</font></h5>
            <h6><font color="#663300">Paste your peptide sequence here...</font></h6>
            <p>
	    <input type="hidden" name="qtype" value="Peptide">
            <textarea rows="2" cols="80" value="" name="sequence">
            </textarea>
            <br>Display raw blast output:
            <input type="checkbox" name="raw" value="y"><br>
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-default">Clear</button>
       </div>
     </FORM>
</div>


<?php include 'footer.html'; ?>
