<?php
  session_start();

  include("common.inc.php");
  include("error.inc.php");

  if ( !isset($_SESSION['ADM_ID']) )
    Redirect("index.php?admin=1");

  include("admin.inc.php");

  Headers();

  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title><?php echo CHAT_NAME ?> Административни страници</title>
<link rel="stylesheet" type="text/css" href="chat.css" />
</head>

<body class="Admin">
<p align="center" class="midhdr">Административни страници</p>
<p align="center" class="smlhdr">Добре дошъл
<?php echo $_SESSION['ADM_NAME'] ?>!</p>
<p align="center" class="text">За последно сте влизали на
<?php
  print("<b>".$_SESSION['ADM_LLDATE']." ".$_SESSION['ADM_LLTIME']."</b> от <b>");
  print(gethostbyaddr($_SESSION['ADM_LLHOST']));
  print(" (".$_SESSION['ADM_LLHOST'].")</b>");
?>
</p>
<table align="center" class="tbThinBorder" cellspacing="0" width="100%">
<?php PrintTabs(''); ?>
<tr><td colspan="6" class="tdData">
Моля, изберете бутон от списъка отгоре.<br /><br />
<b>Администратори</b> - добавяне, изтриване и редакция на администратори.<br />
<b>Потребители</b> - добавяне на нови, изтриване и редакция на съществуващи потребители.<br />
<b>Цветове</b> - администриране на потребителските цветове.<br />
<b>Стаи</b> - добавяне, изтриване и редакция на стаи.<br />
</td></tr></table>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center"><a href="http://validator.w3.org/check/referer">
<img border="0" src="valid-xhtml10.gif" alt="Valid XHTML 1.0!"
height="31" width="88" /></a>
<a class="right" href="http://jigsaw.w3.org/css-validator/check/referer">
<img alt="Valid CSS!" border="0" height="31" src="valid-css.png" width="88" />
</a></p>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center" class="copyright">Автор &copy; 2003-2004 <a href="mailto: <?php
echo CHAT_CONTACT ?>" class="aCopyright"><?php echo CHAT_AUTHOR ?></a></p>
</body>

</html>

