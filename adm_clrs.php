<?php
  session_start();

  include("common.inc.php");
  include("error.inc.php");

  if ( !isset($_SESSION['ADM_ID'] ) ) {
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
<title><?php echo CHAT_NAME ?> Административни страници: Цветове</title>
<link href="chat.css" rel="stylesheet" type="text/css" />
<script defer="defer" src="common.js" type="text/javascript"></script>
</head>

<body class="Admin" onload="javascript: focusFirst(); return true">
<p align="center" class="midhdr">Административни страници: Цветове</p>
<table align="center" class="tbThinBorder" cellspacing="0" width="100%">
<?php PrintTabs('colors'); ?>
<tr><td colspan="6" class="tdData">
<p align="center" class="smlhdr">Записани цветове</p>
<form action="adm_clrsmod.php" method="post">
<table align="center" cellspacing="0" class="tbInfo" width="100%">
<tr>
<th>Избор</th>
<th>Име</th>
<th>Червен</th>
<th>Зелен</th>
<th>Син</th>
<th>Представяне</th>
<th>Записан</th>
<th>Записан от</th>
</tr>
<?php
  include("passwd.inc.php");
  if ( $lnk = @mysqli_connect("p:" . DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
    if ( @mysqli_select_db($lnk, DB_NAME) ) {
      $query  = "SELECT colors.ColorID,colors.ClrName,colors.Red,";
      $query .= "colors.Green,colors.Blue,colors.ModDate,colors.ModTime,";
      $query .= "administrators.Username AS ModByName";
      $query .= " FROM colors,administrators";
      $query .= " WHERE colors.AdminID=administrators.AdminID";
      $res = @mysqli_query($lnk, $query);
      if ( @mysqli_num_rows($res) > 0 ) {
        $RowNum = 1;
        while ( $ColorDetails = @mysqli_fetch_array($res, MYSQLI_ASSOC) ) {
          if ( $RowNum++ % 2 ) {
            print("<tr class=\"trOdd\">\n");
          }
          else {
            print("<tr>\n");
          }
          print("<td><input name=\"ColorIds[]\" type=\"checkbox\" value=\"");
          print($ColorDetails['ColorID']."\" /></td>\n");
          print("<td>".$ColorDetails['ClrName']."</td>\n");
          print("<td>".$ColorDetails['Red']."</td>\n");
          print("<td>".$ColorDetails['Green']."</td>\n");
          print("<td>".$ColorDetails['Blue']."</td>\n");
          print("<td style=\"background-color: ".MakeTriplet($ColorDetails['Red'],$ColorDetails['Green'],$ColorDetails['Blue']).";\">&nbsp;</td>\n");
          print("<td>".$ColorDetails['ModDate']." ".$ColorDetails['ModTime']."</td>\n");
          print("<td>".$ColorDetails['ModByName']."</td>\n");
          print("</tr>\n");
        } // while
      }
      @mysqli_free_result($res);
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

