<?php
  session_start();

  include("common.inc.php");
  
  if ( !isset($_SESSION['USR_ID']) ) {
    Redirect("index.php");
  }

  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title><?php echo CHAT_NAME ?></title>
<link rel="stylesheet" type="text/css" href="chat.css" />
</head>

<frameset frameborder="no" framespacing="0" rows="45, *">
<frame frameborder="0" name="Header" noresize="noresize" scrolling="no"
src="usr_header.php" />
<frameset frameborder="no" framespacing="0" cols="*, 20%">
<frameset frameborder="no" framespacing="0" rows="35, *, 40">
<frame frameborder="0" name="Menu" noresize="noresize" scrolling="no"
src="usr_menu.php" />
<frameset frameborder="no" framespacing="0" cols="5, *">
<frame frameborder="0" name="Offset" noresize="noresize" scrolling="no"
src="usr_offset.php" />
<frame frameborder="0" name="Output" noresize="noresize" scrolling="auto"
src="usr_output.php" />
</frameset>
<frame frameborder="0" name="Input" noresize="noresize" scrolling="no"
src="usr_input.php" />
</frameset>
<frame frameborder="0" name="Online" noresize="noresize" scrolling="no"
src="usr_online.php" />
</frameset>
<noframes>
<body><p class="center">Изглежда, че вашата търсачка не поддържа (или
поддръжката е изключена) рамки. Ако искате да използвате тази стая за
разговори моля надградете (инсталирайте нова версия) вашата търсачка или
разрешете поддръжката на рамки.<br /><br />
<a href="logout.php">Exit</a></p></body></noframes>
</frameset>

</html>

