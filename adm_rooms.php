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
<title><?php echo CHAT_NAME ?> Административни страници: Стаи</title>
<link href="chat.css" rel="stylesheet" type="text/css" />
<script defer="defer" src="common.js" type="text/javascript"></script>
</head>

<body class="Admin" onload="javascript: focusFirst(); return true">
<p align="center" class="midhdr">Административни страници: Стаи</p>
<table align="center" class="tbThinBorder" cellspacing="0" width="100%">
<?php PrintTabs('rooms'); ?>
<tr><td colspan="6" class="tdData">
<p align="center" class="smlhdr">Записани стаи</p>
<form action="adm_roomsmod.php" method="post">
<table align="center" cellspacing="0" class="tbInfo" width="100%">
<tr>
<th>Избор</th>
<th>Име</th>
<th>Описание</th>
<th>Записана</th>
<th>Записана от</th>
</tr>
<?php
  include("passwd.inc.php");
  if ( $lnk = @mysql_pconnect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
    if ( @mysql_select_db(DB_NAME, $lnk) ) {
      $query  = "SELECT rooms.RoomID,rooms.RoomName,rooms.Descr,";
      $query .= "rooms.ModDate,rooms.ModTime,";
      $query .= "administrators.Username AS ModByName";
      $query .= " FROM rooms,administrators";
      $query .= " WHERE rooms.AdminID=administrators.AdminID";
      $res = @mysql_query($query, $lnk);
      if ( @mysql_num_rows($res) > 0 ) {
        $RowNum = 1;
        while ( $RoomDetails = @mysql_fetch_array($res, MYSQL_ASSOC) ) {
          if ( $RowNum++ % 2 )
            print("<tr class=\"trOdd\">\n");
          else print("<tr>\n");
          print("<td><input name=\"RoomIds[]\" type=\"checkbox\" value=\"");
          print($RoomDetails['RoomID']."\" /></td>\n");
          print("<td>".$RoomDetails['RoomName']."</td>\n");
          print("<td>".$RoomDetails['Descr']."</td>\n");
          print("<td>".$RoomDetails['ModDate']." ".$RoomDetails['ModTime']."</td>\n");
          print("<td>".$RoomDetails['ModByName']."</td>\n");
          print("</tr>\n");
        } // while
      }
      @mysql_free_result($res);
      //@mysql_close($lnk);
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
?>
</table>
<table width="100%">
<tr><td>&nbsp;</td></tr>
<tr><td><input name="SubmitAdd" type="submit" value="Добави" />
<input name="SubmitEdit" type="submit" value="Редактирай" />
<input name="SubmitDel" type="submit" value="Изтрий" />
</td></tr></table></form></td></tr></table>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center"><a href="http://validator.w3.org/check/referer">
<img border="0" src="valid-xhtml10.gif" alt="Valid XHTML 1.0!"
height="31" width="88" /></a>
<a class="right" href="http://jigsaw.w3.org/css-validator/">
<img alt="Valid CSS!" border="0" height="31" src="valid-css.png" width="88" />
</a></p>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center" class="copyright">Автор &copy; 2003
<a class="aCopyright" href="mailto: <?php echo CHAT_CONTACT ?>">
<?php echo CHAT_AUTHOR ?></a></p>
</body>

</html>

