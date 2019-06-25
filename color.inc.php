<?php
  function PrependZero($strlen, $str) {
    while ( strlen($str) < $strlen ) {
      $str = "0".$str;
    }
    return $str;
  }

  function MakeTriplet($Red, $Green, $Blue) {
    return "#".PrependZero(2, dechex($Red))
              .PrependZero(2, dechex($Green))
              .PrependZero(2, dechex($Blue));
  }
?>