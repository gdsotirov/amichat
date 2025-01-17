<?php
  session_start();

  include("error.inc.php");

  if ( !isset($_SESSION['USR_ID']) ) {
    PrintError(103);
    exit;
  }

  function PrintOK($msg) {
    print("<form action=\"usrpage.php\" method=\"post\">\n");
    print("<table align=\"center\">\n");
    print("<tr><td>$msg</td></tr>\n");
    print("<tr><td>&nbsp;</td></tr>\n");
    print("<tr><td align=\"center\">\n");
    print("<input type=\"submit\" name=\"Submit\" value=\"Добре\" />\n");
    print("</td></tr>\n");
    print("</table></form>");
  }

  include("common.inc.php");

  if ( isset($_POST['CancelEdit']) ) {
    Redirect("usrpage.php");
  }

  include("color.inc.php");

  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title><?php echo CHAT_NAME ?> - Change user details</title>
<link rel="stylesheet" type="text/css" href="chat.css" />
<script defer="defer" src="common.js" type="text/javascript"></script>
<script type="text/javascript"><!--
    function previewColor() {
        var Nickname    = document.getElementById("Nickname");
        var ColorPicker = document.getElementById("ColorPicker");

        if ( ColorPicker ) {
          var index = ColorPicker.selectedIndex;
          return changeColor(Nickname, ColorPicker[index].title);
        }

        return 0;
    }
//-->
</script>
</head>

<body class="User" onload="javascript: previewColor();">
<p align="center" class="smlhdr">Редактиране на потребителски настройки</p>
<?php
  $Error         = FALSE;
  $Pass_Err  = "";
  $Pass2_Err = "";
  $Nick_Err      = "";
  $Name_Err      = "";
  $Email_Err     = "";

  if ( isset($_POST['SubmitEdit']) ) { /* Process Edit request */
    if ( isset($_POST['CheckForm']) ) {
      if ( !empty($_POST['Password']) || !empty($_POST['Password2']) ) {
        $Pass_Err  = CheckStringField($Error, $_POST['Password'], 6, 32, true);
        $Pass2_Err = CheckStringField($Error, $_POST['Password2'], 6, 32, true);
        if ( strcmp($_POST['Password'],$_POST['Password2']) != 0 ) {
          $Pass2_Err = "<span class=\"error\">Паролите не съвпадат!</span>";
          $Error = TRUE;
        }
      }
      $Nick_Err  = CheckStringField($Error, $_POST['Nick'], 1, 32);
      $Name_Err  = CheckStringField($Error, $_POST['Name'], 1, 96);
      $Email_Err = CheckEmailField($Error, $_POST['Email'], 2, 255, true);
      if ( empty($_POST['Color']) ) {
        $_POST['Color'] = DEF_COLORID;
      }

      if ( !$Error ) {
        include("passwd.inc.php");
        if ( $lnk = @mysqli_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
          if ( @mysqli_select_db($lnk, DB_NAME) ) {
            // check if nickname exists
            if ( strcmp($_POST['Nick'], $_POST['OrigNick']) != 0 ) {
              $query  = "SELECT UserID FROM users";
              $query .= " WHERE Nickname='".$_POST['Nick']."'";
              $res = @mysqli_query($lnk, $query);
              if ( @mysqli_num_rows($res) > 0 ) {
                PrintError(108); // nickname exists
                exit;
              }
              @mysqli_free_result($res);
            }
            // add the user
            $query = "UPDATE users SET ";
            if ( !empty($_POST['Password']) ) {
              $query .= "Password=SHA1('".$_POST['Password']."'),";
            }
            $query .= "Nickname='".$_POST['Nick']."',";
            $query .= "UsrName='".$_POST['Name']."',";
            $query .= "Email='".$_POST['Email']."',";
            $query .= "ColorID=".$_POST['Color'].",";
            $query .= "ModDate=CURDATE(),ModTime=CURTIME(),";
            $query .= "AdminID=".SUPERUSER_ID;
            $query .= " WHERE UserID=".$_SESSION['USR_ID'];
            @mysqli_query($lnk, $query);
            if ( @mysqli_affected_rows($lnk) == 1 ) {
              $_SESSION['USR_NICK'] = $_POST['Nick'];
              PrintOK("Вашите данни са променени успешно.");
            }
            else {
              PrintOK("Вашите данни НЕ са променени!");
            }
            @mysqli_close($lnk);
            if ( isset($_POST['Refresh']) ) {
              $_SESSION['USR_REFRESH'] = $_POST['Refresh'];
            }
            if ( isset($_POST['MsgCount']) ) {
              $_SESSION['USR_MSGCNT'] = $_POST['MsgCount'];
            }
          }
        }
      }
    }
  }

  if ( !isset($_POST['CheckForm']) || $Error ) {
    include("passwd.inc.php");
    if ( $lnk = @mysqli_connect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
      if ( @mysqli_select_db($lnk, DB_NAME) ) {
        $query  = "SELECT UserID,Username,Password,Nickname,UsrName,Email,";
        $query .= "ColorID";
        $query .= " FROM users";
        $query .= " WHERE UserID=".$_SESSION['USR_ID'];
        $UsrRes = @mysqli_query($lnk, $query);
        $query = "SELECT ColorID,ClrName,Red,Green,Blue FROM colors";
        $ClrRes = @mysqli_query($lnk, $query);

        $UsrDetails = @mysqli_fetch_array($UsrRes, MYSQL_ASSOC);
?>
<p align="center"><span class="required">*</span> - задължително поле<br />
<span class="required">**</span> - задължително само ако другото поле за
парола е попълнено</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table align="center">
<tr><th align="left" colspan="2">Системна информация</th></tr>
<tr><td align="right">Потребителско име</td>
<td><b><?php echo $UsrDetails['Username'] ?></b></td>
</tr>
<tr valign="top"><td align="right">Парола <span class="required">**</span></td>
<td><input type="password" name="Password" maxlength="32" size="32" />
<?php
  if ( $Error ) {
    print("<br />");
    print($Pass_Err);
  }
?></td></tr>
<tr valign="top">
<td align="right">Парола (повторете) <span class="required">**</span></td>
<td><input type="password" name="Password2" maxlength="32" size="32" />
<?php
  if ( $Error ) {
    print(" /><br />");
    print($Pass2_Err);
  }
?></td></tr>
<tr><th align="left" colspan="2">Лична информация</th></tr>
<tr valign="top"><td align="right">Псевдоним <span class="required">*</span></td>
<td><input id="Nickname" type="text" name="Nick" maxlength="96" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$_POST['Nick']."\"");
  }
  else {
    print(" value=\"".$UsrDetails['Nickname']."\"");
  } ?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Nick_Err);
  }
  else {
    print("<input type=\"hidden\" name=\"OrigNick\" value=\"");
    print($UsrDetails['Nickname']."\" />\n");
  }
?>
</td></tr>
<tr valign="top"><td align="right">Име <span class="required">*</span></td>
<td><input type="text" name="Name" maxlength="96" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$_POST['Name']."\"");
  }
  else {
    print(" value=\"".$UsrDetails['UsrName']."\"");
  } ?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Name_Err);
  }
  else {
    print("<input type=\"hidden\" name=\"OrigName\" value=\"");
    print($UsrDetails['UsrName']."\" />\n");
  }
?>
</td></tr>
<tr valign="top"><td align="right">Е-поща</td>
<td><input type="text" name="Email" maxlength="255" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$_POST['Email']."\"");
  }
  else {
    print(" value=\"".$UsrDetails['Email']."\"");
  } ?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Email_Err);
  }
  else {
    print("<input type=\"hidden\" name=\"OrigEmail\" value=\"");
    print($UsrDetails['Email']."\" />\n");
  }
?>
</td></tr>
<tr valign="top"><td align="right">Цвят</td>
<td>
<select id="ColorPicker" name="Color" onchange="javascript: previewColor();">
<option value="">-- Моля, изберете цвят --</option>
<?php
        if ( @mysqli_num_rows($ClrRes) > 0 ) {
          while ( $Clr = @mysqli_fetch_array($ClrRes, MYSQL_ASSOC) ) {
            print("<option");
            if ( $Error ) {
              $CompareID = $_POST['Color'];
            }
            else {
              $CompareID = $UsrDetails['ColorID'];
            }
            if ( $CompareID == $Clr['ColorID'] ) {
              print(" selected=\"selected\"");
            }
            print(" value=\"".$Clr['ColorID']."\"");
            print(" title=\"".MakeTriplet($Clr['Red'], $Clr['Green'], $Clr['Blue'])."\">");
            print($Clr['ClrName']."</option>\n");
          } // while
        }
?>
</select>
<?php
  print("<input type=\"hidden\" name=\"OrigColor\" value=\"");
  print($UsrDetails['ColorID']."\" />\n");
?>
</td></tr>
<tr><th align="left" colspan="2">Настройки за текущата сесия</th></tr>
<tr><td align="right">Опресняване на всеки</td>
<td><select name="Refresh">
<?php
  $RefreshArr = array(5 => "5", 10 => "10", 15 => "15", 20 => "20");
  reset($RefreshArr);
  while ( list($key, $val) = each($RefreshArr) ) {
    print("<option value=\"$key\"");
    if ( isset($_SESSION['USR_REFRESH']) && $val == $_SESSION['USR_REFRESH'] ) {
      print(" selected=\"selected\"");
    }
    print(">$val</option>\n");
  }
?>
</select> секунди </td></tr>
<tr><td align="right">Показвай</td>
<td><select name="MsgCount">
<?php
  $MsgCntArr = array(10 => "10", 15 => "15", 20 => "20", 25 => "25",
                     30 => "30", 35 => "35", 40 => "40", 45 => "45",
                     50 => "50");
  reset($MsgCntArr);
  while ( list($key, $val) = each($MsgCntArr) ) {
    print("<option value=\"$key\"");
    if ( isset($_SESSION['USR_MSGCNT']) && $val == $_SESSION['USR_MSGCNT'] ) {
      print(" selected=\"selected\"");
    }
    print(">$val</option>\n");
  }
?>
</select> съобщения</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td align="center" colspan="2">
<input type="hidden" name="CheckForm" value="1" />
<input type="submit" name="SubmitEdit" value="Редактирай" />
<input type="submit" name="CancelEdit" value="Откажи" />
</td></tr>
</table>
</form>
<?php
        @mysqli_free_result($ClrRes);
        @mysqli_free_result($UsrRes);
        @mysqli_close($lnk);
      }
      else {
        PrintError(202); // can't use database
        exit;
      }
    }
    else {
      PrintError(201); // can't connect to server
      exit;
    }
  }
?>
</body>

</html>

