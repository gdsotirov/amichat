<?php
  session_start();

  include("common.inc.php");
  include("error.inc.php");

  if ( !isset($_SESSION['ADM_ID']) ) {
    Redirect("index.php?admin=1");
  }

  include("admin.inc.php");
  include("color.inc.php");

  Headers();

  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title><?php echo CHAT_NAME ?> Административни страници: Потребители</title>
<link href="chat.css" rel="stylesheet" type="text/css" />
<script defer="defer" src="common.js" type="text/javascript"></script>
</head>

<body class="Admin" onload="javascript: focusFirst(); return true">
<p align="center" class="midhdr">Административни страници: Потребители</p>
<table align="center" class="tbThinBorder" cellspacing="0" width="100%">
<?php PrintTabs('users'); ?>
<tr>
<td colspan="6" class="tdData">
<p align="center" class="smlhdr">Записани потребители</p>
<form action="adm_usrsmod.php" method="post">
<table align="center" cellspacing="0" class="tbInfo" width="100%">
<tr>
<th>Избор</th>
<th>Потр. име</th>
<th>Псевдоним</th>
<th>Име</th>
<th>Е-поща</th>
<th>Учител</th>
<th>Цвят</th>
<th>Записан</th>
<th>Записан от</th>
</tr>
<?php
  include("passwd.inc.php");
  if ( $lnk = @mysql_pconnect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
    if ( @mysql_select_db(DB_NAME, $lnk) ) {
      $query  = "SELECT users.UserID,users.Username,users.Nickname,";
      $query .= "users.UsrName,users.Email,users.Teacher,colors.Red,";
      $query .= "colors.Green,colors.Blue,users.ModDate,users.ModTime,";
      $query .= "administrators.Username AS ModByName";
      $query .= " FROM users,colors,administrators";
      $query .= " WHERE users.ColorID=colors.ColorID";
      $query .= " AND";
      $query .= " users.AdminID=administrators.AdminID";
      $query .= " ORDER BY users.UserID";
      $res = @mysql_query($query, $lnk);
      if ( @mysql_num_rows($res) > 0 ) {
        $RowNum = 1;
        while ( $UserDetails = @mysql_fetch_array($res, MYSQL_ASSOC) ) {
          if ( $RowNum++ % 2 ) {
            print("<tr class=\"trOdd\">\n");
          }
          else {
            print("<tr>\n");
          }
          print("<td><input name=\"UserIds[]\" type=\"checkbox\" value=\"");
          print($UserDetails['UserID']."\" />\n</td>\n");
          print("<td>".$UserDetails['Username']."</td>\n");
          print("<td>".$UserDetails['Nickname']."</td>\n");
          print("<td>".$UserDetails['UsrName']."</td>\n");
          if ( $UserDetails['Email'] != '' ) {
            print("<td><a href=\"mailto:".$UserDetails['Email']."\">");
            print($UserDetails['Email']."</a></td>\n");
          }
          else {
            print("<td>&nbsp;</td>\n");
          }
          print("<td>".PrintBool($UserDetails['Teacher'])."</td>\n");
          print("<td style=\"background-color: ");
          print(MakeTriplet($UserDetails['Red'], $UserDetails['Green'], $UserDetails['Blue']));
          print("\" title=\"RGB (".$UserDetails['Red'].", ");
          print($UserDetails['Green'].", ".$UserDetails['Blue']);
          print(")\">&nbsp;</td>\n");
          print("<td>".$UserDetails['ModDate']." ".$UserDetails['ModTime']."</td>\n");
          print("<td>".$UserDetails['ModByName']."</td>\n");
          print("</tr>\n");
        } // while
      }
      @mysql_free_result($res);
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

