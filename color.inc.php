<?php
  function PrependZero($strlen, $str) {
    while ( strlen($str) < $strlen ) $str = "0".$str;
    return $str;
  }

  function MakeTriplet($Red, $Green, $Blue) {
    $res  = "#".PrependZero(2, dechex($Red));
    $res .= PrependZero(2, dechex($Green));
    $res .= PrependZero(2, dechex($Blue));
    return $res;
  }
?>