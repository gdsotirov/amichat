<?php
  session_start();

  include("common.inc.php");
  include("error.inc.php");

  if ( !isset($_SESSION['ADM_ID']) )
    Redirect("index.php?admin=1");

  include("admin.inc.php");

  Headers();

  echo "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=windows-1251" />
<title><?php echo CHAT_NAME ?> ��������������� ��������</title>
<link rel="stylesheet" type="text/css" href="chat.css" />
</head>

<body class="Admin">
<p align="center" class="midhdr">��������������� ��������</p>
<p align="center" class="smlhdr">����� �����
<?php echo $_SESSION['ADM_NAME'] ?>!</p>
<p align="center" class="text">�� �������� ��� ������� ��
<?php
  print("<b>".$_SESSION['ADM_LLDATE']." ".$_SESSION['ADM_LLTIME']."</b> �� <b>");
  print(gethostbyaddr($_SESSION['ADM_LLHOST']));
  print(" (".$_SESSION['ADM_LLHOST'].")</b>");
?>
</p>
<table align="center" class="tbThinBorder" cellspacing="0" width="100%">
<?php PrintTabs(''); ?>
<tr><td colspan="6" class="tdData">
����, �������� ����� �� ������� ������.<br /><br />
<b>��������������</b> - ��������, ��������� � �������� �� ��������������.<br />
<b>�����������</b> - �������� �� ����, ��������� � �������� �� ������������ �����������.<br />
<b>�������</b> - �������������� �� ��������������� �������.<br />
<b>����</b> - ��������, ��������� � �������� �� ����.<br />
</td></tr></table>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center"><a href="http://validator.w3.org/check/referer">
<img border="0" src="valid-xhtml10.gif" alt="Valid XHTML 1.0!"
height="31" width="88" /></a>
<a class="right" href="http://jigsaw.w3.org/css-validator/">
<img alt="Valid CSS!" border="0" height="31" src="valid-css.png" width="88" />
</a></p>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center" class="copyright">����� &copy; 2003 <a href="mailto: <?php
echo CHAT_CONTACT ?>" class="aCopyright"><?php echo CHAT_AUTHOR ?></a></p>
</body>

</html>

