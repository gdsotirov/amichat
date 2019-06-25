<?php
  session_start();

  include("common.inc.php");
  include("error.inc.php");

  if ( !isset($_SESSION['ADM_ID']) ) {
    Redirect("index.php?admin=1");
  }

  include("admin.inc.php");

  Headers();

  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title><?php echo CHAT_NAME ?> Административни страници: Администратори</title>
<link href="chat.css" rel="stylesheet" type="text/css" />
<script defer="defer" src="common.js" type="text/javascript"></script>
</head>

<body class="Admin" onload="javascript: focusFirst(); return true">
<p align="center" class="midhdr">Административни страници: Администратори</p>
<table align="center" class="tbThinBorder" cellspacing="0" width="100%">
<?php PrintTabs('admins'); ?>
<tr><td colspan="6" class="tdData">
<p align="center" class="smlhdr">Записани администратори</p>
<form action="adm_admsmod.php" method="post" name="AdminsForm">
<table align="center" cellspacing="0" class="tbInfo" width="100%">
<tr>
<th>Избор</th>
<th>Потр. име</th>
<th>Име</th>
<th>Е-поща</th>
<th>Телефон</th>
<th>Записан</th>
<th>Записан от</th>
</tr>
<?php
  include("passwd.inc.php");
  if ( $lnk = @mysql_pconnect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
    if ( @mysql_select_db(DB_NAME, $lnk) ) {
      $query  = "SELECT AdminID,Username,AdmName,Email,Phone,ModDate,ModTime,";
      $query .= "ModByID";
      $query .= " FROM administrators ORDER BY AdminID";
      $res = @mysql_query($query, $lnk);
      if ( @mysql_num_rows($res) > 0 ) {
        $RowNum = 1;
        while ( $AdmDetails = @mysql_fetch_array($res, MYSQL_ASSOC) ) {
          if ( $RowNum++ % 2 ) {
            print("<tr class=\"trOdd\">");
          }
          else {
            print("<tr>\n");
          }
          $AdminID = $AdmDetails['AdminID'];
          if ( $AdminID != SUPERUSER_ID || ($AdminID == SUPERUSER_ID && $AdminID == $_SESSION['ADM_ID']) ) {
            print("<td><input name=\"AdminIds[]\" type=\"checkbox\" value=\"");
            print($AdminID."\" />\n</td>\n");
          }
          else {
            print("<td>&nbsp;</td>\n");
          }
          print("<td>".$AdmDetails['Username']."</td>\n");
          print("<td>".$AdmDetails['AdmName']."</td>\n");
          if ( $AdmDetails['Email'] != '' ) {
            print("<td><a href=\"mailto:".$AdmDetails['Email']."\">");
            print($AdmDetails['Email']."</a></td>\n");
          }
          else {
            print("<td>&nbsp;</td>\n");
          }
          print("<td>".$AdmDetails['Phone']."</td>\n");
          print("<td>".$AdmDetails['ModDate']." ".$AdmDetails['ModTime']."</td>\n");
          if ( $AdmDetails['AdminID'] == SUPERUSER_ID ) {
            print("<td>автоматично</td>\n");
          }
          else {
            $query  = "SELECT Username FROM administrators";
            $query .= " WHERE AdminID='".$AdmDetails['ModByID']."'";
            $tmpres = @mysql_query($query, $lnk);
            if ( @mysql_num_rows($tmpres) > 0 ) {
              $row = @mysql_fetch_array($tmpres, MYSQL_ASSOC);
              print("<td>".$row['Username']."</td>\n");
            }
            else {
              print("<td>n/a</td>\n");
            }
          }
          print("</tr>\n");
        } // while
        @mysql_free_result($res);
      }
      else {
        print("<tr>\n");
        print("<td>Празно.</td>\n");
        print("</tr>\n");
      }
    }
    else {
      PrintEror(202); // can't use database
      exit;
    }
  }
  else {
    PrintError(201); // can't connect
    exit;
  }
?>
</table>
<table width="100%">
<tr><td>&nbsp;</td></tr>
<tr><td><input type="submit" name="SubmitAdd" value="Добави" />
<input type="submit" name="SubmitEdit" value="Редактирай" />
<input type="submit" name="SubmitDel" value="Изтрий" />
</td></tr></table></form></td></tr></table>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center"><a href="https://validator.w3.org/check/referer">
<img border="0" src="valid-xhtml.png" alt="Valid XHTML 1.0!"
height="31" width="88" /></a>
<a class="right" href="https://jigsaw.w3.org/css-validator/check/referer">
<img alt="Valid CSS!" border="0" height="31" src="valid-css.png" width="88" />
</a></p>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center" class="copyright">Автор &copy; 2003-2005
<a class="aCopyright" href="mailto: <?php echo CHAT_CONTACT ?>">
<?php echo CHAT_AUTHOR ?></a></p>
</body>

</html>

