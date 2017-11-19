<?php

// color the nucleotides of a DNA or RNA sequence, using the NAD convention from
// http://www.biomedcentral.com/1471-2105/13/S8/S2/figure/F2?highres=y
function colorNucleotides($str) {
  $ncolors['A'] = "red";
  $ncolors['T'] = "blue";
  $ncolors['U'] = "blue";
  $ncolors['C'] = "green";
  $ncolors['G'] = "orange"; // "yellow" ?
  $cstr = '';
  for ($i=0; $i < strlen($str); $i++) {
    $c = $str[$i];
    if (stripos('ATUCG', $c) !== FALSE) {
      $color = $ncolors[strtoupper($c)];
      $c = '<font color="' . $color . '">' . $c . '</font>';
    }
    $cstr = $cstr . $c;
  }
  $cstr = '<div class="biopolseq"><code>' . $cstr . '</code></div>';
  return $cstr;
}

// color amino acid sequences, using the ClustaW convention from
// http://www.biomedcentral.com/1471-2105/13/S8/S2/figure/F2?highres=y
function colorAminoAcids($str) {
  $cstr = '';
  for ($i=0; $i < strlen($str); $i++) {
    $c = $str[$i];
    if (stripos('NDQEAC', $c) !== FALSE) {
      $c = '<font color="magenta">'. $c . '</font>';
    }
    elseif (stripos('PSGT', $c) !== FALSE) {
      $c = '<font color="orange">'. $c . '</font>';
      //$c = '<font color="yellow">'. $c . '</font>';
    }
    elseif (stripos('RKH', $c) !== FALSE) {
      $c = '<font color="red">'. $c . '</font>';
    }
    elseif (stripos('MILV', $c) !== FALSE) {
      $c = '<font color="green">'. $c . '</font>';
    }
    elseif (stripos('FYW', $c) !== FALSE) {
      $c = '<font color="blue">'. $c . '</font>';
    }
    $cstr = $cstr . $c;
  }
  $cstr = '<div class="biopolseq"><code>' . $cstr . '</code></div>';
  return $cstr;
}

?>

