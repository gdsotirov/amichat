<?php
  if ( isset($_GET['admin']) )
     $ADMLOGIN = $_GET['admin'];

  include("common.inc.php");

  $TITLE = CHAT_NAME;
  if ( isset($ADMLOGIN) )
    $TITLE = $TITLE." - Вход за администратори ";
  else $TITLE = $TITLE." - Вход за потребители ";

  print("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title><?php echo $TITLE ?></title>
<meta name="Author" content="Georgi D. Sotirov" />
<meta name="Contact" content="gdsotirov@dir.bg" />
<link href="chat.css" rel="stylesheet" type="text/css" />
<script src="common.js" type="text/javascript" defer="defer"></script>
</head>

<body onload="javascript: focusFirst(); return true">
<p align="center" class="midhdr"><?php echo $TITLE ?></p>
<form action="login.php" name="LoginForm" method="post">
<table align="center">
<tr>
<td>Потребителско име</td>
<td><input type="text" name="Username" size="32" maxlength="32" /></td>
</tr>
<tr>
<td>Парола</td>
<td><input type="password" name="Password" size="32" maxlength="32" /></td>
</tr>
<tr>
<td align="center" colspan="2">
<input type="submit" name="Submit" value="Вход" />
<input type="reset" name="Reset" value="Изчисти" />
<?php
  if ( isset($ADMLOGIN) )
    print("<input type=\"hidden\" name=\"Admin\" value=\"1\" />");
?>
</td>
</tr>
<tr>
<td align="left" colspan="2">
<?php
  print("<a href=\"".$_SERVER['PHP_SELF']);
  if ( !isset($ADMLOGIN) )
    print("?admin=1\">Вход за администратори");
  else print("?\">Вход за потребители");
  print("</a><br />\n");
?>
</td></tr></table></form>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center"><a href="http://validator.w3.org/check/referer">
<img border="0" src="valid-xhtml10.gif" alt="Valid XHTML 1.0!"
height="31" width="88" /></a>
<a class="right" href="http://jigsaw.w3.org/css-validator/check/referer">
<img alt="Valid CSS!" border="0" height="31" src="valid-css.png" width="88" />
</a></p>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center" class="copyright">Автор &copy; 2003-2004
<a href="mailto: <?php echo CHAT_CONTACT ?>" class="aCopyright">
<?php echo CHAT_AUTHOR ?></a></p>
</body>

</html>

