<?php

// check if sequence is a nucleotide sequence
function isNucleotideSequence($str) {
  $alphabet = 'ACTGU';
  for ($i=0; $i < strlen($str); $i++) {
    if (stripos($alphabet, $str[$i]) === false) return false;
  }
  return true;
}

// check if sequence is a peptide sequence
function isAminoAcidSequence($str) {
  $alphabet = 'NDQEACPSGTRKHMILVFYW';
  for ($i=0; $i < strlen($str); $i++) {
    if (stripos($alphabet, $str[$i]) === false) return false;
  }
  return true;
}

?>

