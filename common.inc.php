<?php
  // constants
  define("CHAT_NAME", "AMI Chat");

  define("CHAT_AUTHOR", "Георги Д. Сотиров");
  define("CHAT_CONTACT", "gdsotirov@dir.bg");

  define("CHAT_VER_MAJOR", 0);
  define("CHAT_VER_MINOR", 3);
  define("CHAT_VER_PATCH", 4);
  define("CHAT_VERSTR", CHAT_VER_MAJOR.".".CHAT_VER_MINOR.".".CHAT_VER_PATCH);

  define("DB_SERVER", "localhost");
  define("DB_NAME", "chat");

  define("SUPERUSER_ID", 1);
  define("DEF_MSGCOUNT", 20);
  define("DEF_REFRESH", 10);
  define("DEF_COLORID", 1);

  // Common functions
  function Redirect($file) {
    $location = "Location: http://";
    $location .= $_SERVER['HTTP_HOST'];
    $location .= dirname($_SERVER['PHP_SELF']);
    $location .= "/$file";
    header($location);
    exit;
  }

  function Headers() {
    $now = gmdate("D, d M Y H:i:s")." GMT";
    header("Date: $now");
    header("Expires: $now");
    header("Last-Modified: $now");
    header("Pragma: no-cache");
    header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
    header("Content-Type: text/html");
  }

  function PrintBool($Bool) {
    $res = "";

    if ( !isset($Bool) || $Bool == '' ) {
      $res = "n/a";
    }
    else if ( $Bool == 1 || $Bool == '1' || strtolower($Bool) == 'yes' || strtolower($Bool) == 'y' ) {
      $res = "Да";
    }
    else if ( $Bool == 0 || $Bool == '0' || strtolower($Bool) == 'no' || strtolower($Bool) == 'n' ) {
      $res = "Не";
    }

    return $res;
  }

  function CheckValidChars($String) {
    $i = 0;
    while ( $i < strlen($String) ) {
      if ( !preg_match("/[_a-zA-z0-9]/", $String[$i]) ) {
        return false;
      }
      $i++;
    }
    return true;
  }

  function CheckStringField(&$Error, $Value, $Min, $Max, $CheckCorrectInput = false) {
    $FieldErr = "";
    if ( isset($Value) && strlen($Value) > 0 ) {
      if ( $CheckCorrectInput && !CheckValidChars($Value) ) {
        $FieldErr = "<span class=\"error\">Полето съдържа неразрешени ";
        $FieldErr .= "символи! Разрешените символи са _ (долно тире), ";
        $FieldErr .= "малки и големи английски букви (a-z, A-Z) и арабските ";
        $FieldErr .= "цифри (0-9).</span><br />";
        $Error = TRUE;
      }
      if ( strlen($Value) < $Min || strlen($Value) > $Max ) {
        $FieldErr .= "<span class=\"error\">Дължината на това поле трябва";
        $FieldErr .= " да бъде между $Min и $Max символа.</span>";
        $Error = TRUE;
      }
    }
    else {
      $FieldErr = "<span class=\"error\">Моля, попълнете това поле!</span>";
      $Error = TRUE;
    }
    return $FieldErr;
  }

  /* TODO: Check here for valid numeric value in $Value */
  function CheckNumField(&$Error, $Value, $Min, $Max, $Unit) {
    $FieldErr = "";
    if ( isset($Value) ) {
      if ( $Value < $Min || $Value > $Max ) {
        $FieldErr = "<span class=\"error\">Стйността в това поле трябва";
        $FieldErr .= " да бъде между $Min и $Max $Unit.</span>";
        $Error = TRUE;
      }
    }
    else {
      $FieldErr = "<span class=\"error\">Моля, попълнете това поле!</span>";
      $Error = TRUE;
    }
    return $FieldErr;
  }
?>
