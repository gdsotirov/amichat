<?php
  session_start();

  include("error.inc.php");

  if ( !isset($_SESSION['USR_ID']) ) {
    PrintError(103);
    exit;
  }

  if ( isset($_POST['Submit']) ) {
    $_SESSION['USR_ROOMID'] = $_POST['Room'];
  }

  include("common.inc.php");
  include("passwd.inc.php");
  if ( $lnk = @mysqli_connect("p:" . DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
    if ( @mysqli_select_db($lnk, DB_NAME) ) {
      $query  = "SELECT rooms.RoomID,rooms.RoomName";
      $query .= "  FROM rooms";
      $res = @mysqli_query($query, $lnk);
    }
    else {
      PrintError(202);
      exit;
    }
  }
  else {
    PrintError(201);
    exit;
  }

  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title><?php echo CHAT_NAME ?> - Menu</title>
<link rel="stylesheet" type="text/css" href="chat.css" />
</head>

<body class="User" style="padding: 5px 0px 0px 5px;">
<table width="100%">
<tr><td align="left">
<?php
  $USR_NICK = $_SESSION['USR_NICK'];
  print("Псевдоним: <b>$USR_NICK</b>&nbsp;");
  $USR_ROOMNAME = "няма";
  while ( $RoomDetails = @mysqli_fetch_array($res, MYSQL_ASSOC) ) {
    if ( $_SESSION['USR_ROOMID'] == $RoomDetails['RoomID'] ) {
      $USR_ROOMNAME = $RoomDetails['RoomName'];
    }
  } // while
  print("Стая: <b>$USR_ROOMNAME</b>\n");

  if ( mysqli_stmt_data_seek($res, 0) ) {
    if ( @mysqli_num_rows($res) > 0 ) {
      print("<form action=\"\" class=\"inline\" method=\"post\" name=\"RoomPost\">");
      print("<label for=\"Room\">Нова стая:&nbsp;</label>");
      print("<select id=\"Room\" name=\"Room\">\n");
      while ( $RoomDetails = @mysqli_fetch_array($res, MYSQL_ASSOC) ) {
        print("<option value=\"".$RoomDetails['RoomID']."\"");
        if ( $_SESSION['USR_ROOMID'] == $RoomDetails['RoomID'] ) {
          print(" selected=\"selected\">\n");
        }
        else {
          print(">\n");
        }
        print($RoomDetails['RoomName']."</option>\n");
      } // while
      print("</select>\n");
      print("<input type=\"submit\" name=\"Submit\" value=\"Смяна\" />\n");
      print("</form>\n");
    }
  }

  @mysqli_free_result($res);
?>
</td>
<td align="right">
<a class="aButton" href="usr_pref.php" target="_top">Настройки</a>
<a class="aButton" href="logout.php" target="_top">Изход</a></td></tr>
</table>
</body>

</html>

