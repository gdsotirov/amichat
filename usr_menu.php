<?php
  session_start();

  include("error.inc.php");

  if ( !isset($_SESSION['USR_ID']) ) {
    PrintError(103);
    exit;
  }

  include("common.inc.php");

  echo "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=windows-1251" />
<title><?php echo CHAT_NAME ?> - Menu</title>
<link rel="stylesheet" type="text/css" href="chat.css" />
</head>

<body class="User" style="padding: 5px 0px 0px 5px;">
<table width="100%">
<tr><td align="left">
<?php
  $USR_NICK = $_SESSION['USR_NICK'];
  print("Псевдоним: <b>$USR_NICK</b>&nbsp;");
  print("Стая: <b>Обща</b>\n");
?>
</td>
<td align="right">
<a class="aButton" href="usr_pref.php" target="_top">Настройки</a>
<a class="aButton" href="logout.php" target="_top">Изход</a></td></tr>
</table>
</body>

</html>

