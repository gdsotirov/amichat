<?php
  session_start();

  include("common.inc.php");
  include("error.inc.php");

  if ( !isset($_SESSION['ADM_ID']) )
    Redirect("index.php?admin=1");

  /* if user cancels the request -> redirect */
  if ( isset($_POST['CancelAdd']) || isset($_POST['CancelEdit']) || isset($_POST['CancelDelete']) )
    Redirect("adm_usrs.php");

  function PrintAction() {
    if ( isset($_POST['SubmitAdd']) )
      echo "Добавяне";
    elseif( isset($_POST['SubmitEdit']) )
      echo "Редакция";
    elseif( isset($_POST['SubmitDel']) || isset($_POST['Delete']) || isset($_POST['CancelDelete']) )
      echo "Изтриване";
  }

  function PrintOK($msg) {
    print("<form action=\"adm_usrs.php\" method=\"post\">\n");
    print("<table align=\"center\">\n");
    print("<tr><td>$msg</td></tr>\n");
    print("<tr><td>&nbsp;</td></tr>\n");
    print("<tr><td align=\"center\">\n");
    print("<input type=\"submit\" name=\"Submit\" value=\"Добре\" />\n");
    print("</td></tr>\n");
  }

  include("admin.inc.php");
  include("color.inc.php");

  Headers();

  echo "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=windows-1251" />
<title><?php echo CHAT_NAME ?> Административни страници: Потребители -> <?php PrintAction() ?></title>
<link href="chat.css" rel="stylesheet" type="text/css" />
<script defer="defer" src="common.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
    function previewColor() {
        var Nickname    = document.getElementsByName("Nick[]");
        var ColorPicker = document.getElementsByName("Color[]");

        for ( var i = 0; i < ColorPicker.length; ++i )
            if ( ColorPicker[i] ) {
              var CP    = ColorPicker[i];
              var index = CP.selectedIndex;

              changeColor(Nickname[i], CP[index].title);
            }

        return 0;
    }
//-->
</script>
</head>

<body class="Admin" onload="javascript: focusFirst(); previewColor(); return true">
<p align="center" class="midhdr">Административни страници:
<a href="adm_usrs.php" class="aMidHdr">Потребители</a> -&gt; <?php PrintAction() ?></p>
<table align="center" class="tbThinBorder" cellspacing="0" width="100%">
<?php PrintTabs('users') ?>
<tr><td colspan="6" class="tdData">
<p align="center" class="smlhdr"><?php PrintAction() ?> на потребители</p>
<?php
  if ( isset($_POST['SubmitAdd']) ) { /* Process add request */
    $Error         = FALSE;
    $Username_Err  = "";
    $Password_Err  = "";
    $Password2_Err = "";
    $Nick_Err      = "";
    $Name_Err      = "";
    //$Email_Err     = "";
    if ( isset($_POST['CheckForm']) ) {
      $Username  = $_POST['Username'][0];
      $Password  = $_POST['Password'][0];
      $Password2 = $_POST['Password2'][0];
      $Nick      = $_POST['Nick'][0];
      $Name      = $_POST['Name'][0];
      $Email     = $_POST['Email'][0];
      if ( isset($_POST['Teacher']) )
        $Teacher = $_POST['Teacher'];
      $Color     = $_POST['Color'][0];
      $Username_Err  = CheckStringField($Error, $Username, 6, 32, true);
      $Password_Err  = CheckStringField($Error, $Password, 6, 32, true);
      $Password2_Err = CheckStringField($Error, $Password2, 6, 32, true);
      if (strcmp($Password, $Password2) != 0) {
        $Password2_Err = "<span class=\"error\">Паролите не съвпадат!</span>";
        $Error = TRUE;
      }
      $Nick_Err  = CheckStringField($Error, $Nick, 1, 32);
      $Name_Err  = CheckStringField($Error, $Name, 1, 96);
      //$Email_Err = CheckStringField($Error, $_POST['Email'], 2, 255);
      if ( empty($Color) )
        $Color = DEF_COLORID;
      if ( !$Error ) {
        include("passwd.inc.php");
        if ( $lnk = @mysql_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
          if ( @mysql_select_db(DB_NAME, $lnk) ) {
            // check if user exists
            $query  = "SELECT UserID FROM users";
            $query .= " WHERE Username='".$Username."'";
            $res = @mysql_query($query, $lnk);
            if ( @mysql_num_rows($res) > 0 ) {
              PrintError(107); // username exists
              exit;
            }
            // check if nickname exists
            $query  = "SELECT UserID FROM users";
            $query .= " WHERE Nickname='".$Nick."'";
            $res = @mysql_query($query, $lnk);
            if ( @mysql_num_rows($res) > 0 ) {
              PrintError(108); // nickname exists
              exit;
            }
            @mysql_free_result($res);
            // add the user
            $query  = "INSERT INTO users";
            $query .= "(Username,Password,Nickname,UsrName,Email,Teacher,";
            $query .= "ColorID,ModDate,ModTime,AdminID)";
            $query .= " VALUES ('".$Username."',";
            $query .= " password('".$Password."'),";
            $query .= "'".$Nick."',";
            $query .= "'".$Name."',";
            $query .= "'".$Email."',";
            $query .= "'".(isset($Teacher)?1:0)."',";
            $query .= "".$Color.",";
            $query .= "CURDATE(), CURTIME(), ".$_SESSION['ADM_ID'].")";
            @mysql_query($query, $lnk);
            if ( @mysql_affected_rows($lnk) == 1 )
              PrintOK("Потребителя ".$Username." (".$Name.") е добавен успешно.");
            else PrintOK("Потребителя НЕ е добавен!");
            @mysql_close($lnk);
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
      } // if ( !Error )
    }
    if ( !isset($_POST['CheckForm']) || $Error ) {
      // Print form with or without errors
?>
<p align="center"><span class="required">*</span> - задължително поле</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table align="center">
<tr><th colspan="2">Системна информация</th></tr>
<tr valign="top">
<td align="right" width="50%">Потребителско име
<span class="required">*</span></td>
<td><input type="text" name="Username[]" maxlength="32" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$Username."\" /><br />");
    print($Username_Err);
  }
  else print(" />\n"); ?></td></tr>
<tr valign="top"><td align="right">Парола <span class="required">*</span></td>
<td><input type="password" name="Password[]" maxlength="32" size="32"<?php
  if ( $Error ) {
    print(" /><br />");
    print($Password_Err);
  }
  else print(" />\n"); ?></td></tr>
<tr valign="top">
<td align="right">Парола (повторете) <span class="required">*</span></td>
<td><input type="password" name="Password2[]" maxlength="32" size="32"<?php
  if ( $Error ) {
    print(" /><br />");
    print($Password2_Err);
  }
  else print(" />\n"); ?></td></tr>
<tr><th colspan="2">Лична информация</th></tr>
<tr valign="top"><td align="right">Псевдоним <span class="required">*</span></td>
<td><input type="text" name="Nick[]" maxlength="96" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$Nick."\" /><br />");
    print($Nick_Err);
  }
  else print(" />\n"); ?></td></tr>
<tr valign="top"><td align="right">Име <span class="required">*</span></td>
<td><input type="text" name="Name[]" maxlength="96" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$Name."\" /><br />");
    print($Name_Err);
  }
  else print(" />\n"); ?></td></tr>
<tr valign="top"><td align="right">Е-поща <!-- <span class="required">*</span> --></td>
<td><input type="text" name="Email[]" maxlength="255" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$Email."\" /><br />");
    //print($Email_Err);
  }
  else print(" />\n"); ?></td></tr>
<tr valign="top"><td align="right">Учител</td>
<td><input type="checkbox" name="Teacher[]"<?php
  if ( $Error && isset($Teacher) )
    print(" checked=\"checked\" />\n");
  else print(" />\n"); ?></td></tr>
<tr valign="top"><td align="right">Цвят</td>
<td>
<select name="Color[]" onchange="javascript: previewColor();">
<option value="">-- Моля, изберете цвят --</option>
<?php
    include("passwd.inc.php");
    if ( $lnk = @mysql_pconnect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
      if ( @mysql_select_db(DB_NAME, $lnk) ) {
        $query = "SELECT ColorID,ClrName,Red,Green,Blue from colors";

        $res = @mysql_query($query, $lnk);

        if ( @mysql_num_rows($res) > 0 )
          while ( $Clr = @mysql_fetch_array($res, MYSQL_ASSOC) ) {
            print("<option");
            if ( isset($Color) )
              if ( $Color == $Clr['ColorID'] )
                print(" selected=\"selected\"");
            print(" value=\"".$Clr['ColorID']."\"");
            print(" title=\"".MakeTriplet($Clr['Red'], $Clr['Green'], $Clr['Blue'])."\">");
            print($Clr['ClrName']."</option>\n");
          }

        @mysql_free_result($res);
        //@mysql_close($lnk);
      }
    }
?></select></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td align="center" colspan="2">
<input type="hidden" name="CheckForm" value="1" />
<input type="submit" name="SubmitAdd" value="Добави" />
<input type="reset" name="Reset" value="Изчисти" />
<input type="submit" name="CancelAdd" value="Откажи" />
</td></tr>
<?php
    }
  }
  elseif( isset($_POST['SubmitEdit']) ) { /* Process EDIT request */
    if ( isset($_POST['UserIds']) ) {
      if ( count($_POST['UserIds']) == 0 )
        Redirect("adm_usrs.php");
      $UserIds  = $_POST['UserIds'];  /* Admin id's array */
      $UsrCount  = count($UserIds);
      $Error     = array_fill(0, $UsrCount, 0);
      $Pass_Err  = array_fill(0, $UsrCount, "");
      $Pass2_Err = array_fill(0, $UsrCount, "");
      $Nick_Err  = array_fill(0, $UsrCount, "");
      $Name_Err  = array_fill(0, $UsrCount, "");
      //$Email_Err = array_fill(0, $UsrCount, "");
      /* check forms ? */
      if ( isset($_POST['CheckForms']) ) {
        $Password  = $_POST['Password'];
        $Password2 = $_POST['Password2'];
        $Nick      = $_POST['Nick'];
        $Name      = $_POST['Name'];
        $Email     = $_POST['Email'];
        //$Color     = $_POST['Color'];
        if ( isset($_POST['Teacher']) )
            $Teacher = $_POST['Teacher'];
        $Color     = $_POST['Color'];
        reset($UserIds);
        /* check values in arrays */
        while ( list($UsrKey, $UsrID) = each($UserIds) ) {
          if ( !empty($Password[$UsrKey]) || !empty($Password2[$UsrKey]) ) {
            $Pass_Err[$UsrKey]  =
              CheckStringField($Error[$UsrKey], $Password[$UsrKey], 6, 32, true);
            $Pass2_Err[$UsrKey] =
              CheckStringField($Error[$UsrKey], $Password2[$UsrKey], 6, 32, true);
            if ( strcmp($Password[$UsrKey], $Password2[$UsrKey]) != 0 ) {
              $Pass2_Err[$UsrKey] = "<span class=\"error\">Паролите не съвпадат!</span>";
              $Error[$UsrKey] = TRUE;
            }
          }
          $Nick_Err[$UsrKey] =
            CheckStringField($Error[$UsrKey], $Nick[$UsrKey], 1, 32);
          $Name_Err[$UsrKey]  =
            CheckStringField($Error[$UsrKey], $Name[$UsrKey], 1, 96);
          /*$Email_Err[$UsrKey] =
            CheckStringField($Error[$UsrKey], $Email[$UsrKey], 2, 255);*/
        } // while
        reset($Error);
        if ( !in_array(TRUE, $Error) ) { // if there are no errors
          // update user
          include("passwd.inc.php");
          if ( $lnk = @mysql_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
            $EditCount = 0;
            if ( @mysql_select_db(DB_NAME, $lnk) ) {
              while ( list($ErrKey, $ErrVal) = each($Error) ) {
                $query = "UPDATE users SET ";
                if ( !empty($Password[$ErrKey]) ) {
                  $query .= "Password=password('".$Password[$ErrKey]."'),";
                }
                $query .= "Nickname='".$Nick[$ErrKey]."',";
                $query .= "UsrName='".$Name[$ErrKey]."',";
                $query .= "Email='".$Email[$ErrKey]."',";
                $query .= "Teacher='".(isset($Teacher[$ErrKey])?1:0)."',";
                $query .= "ColorID=".$Color[$ErrKey].",";
                $query .= "ModDate=CURDATE(),ModTime=CURTIME(),";
                $query .= "AdminID=".$_SESSION['ADM_ID'];
                $query .= " WHERE UserID=".$UserIds[$ErrKey];
                @mysql_query($query, $lnk);
                if ( @mysql_affected_rows($lnk) == 1 )
                  $EditCount++;
              } // while
              @mysql_close($lnk);
              if ( $EditCount == $UsrCount )
                PrintOK("Потребителите са редактирани успешно.");
              elseif( $EditCount > 0 && $EditCount < $UsrCount )
                PrintOK("Някои от потребителите са редактирани успешно!");
              elseif( $EditCount == 0 )
                PrintOK("Потребителите НЕ са редактирани успешно!");
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
        } // if there are no errors
      } // if ( isset(CheckForm...
      if ( !isset($_POST['CheckForms']) || in_array(TRUE, $Error) ) {
        include("passwd.inc.php");
        if ( $lnk = @mysql_pconnect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
          if ( @mysql_select_db(DB_NAME, $lnk) ) {
            $query  = "SELECT UserID,Username,Password,Nickname,UsrName,";
            $query .= "Email,Teacher,ColorID";
            $query .= " FROM users WHERE UserID";
            // TODO: Print error message if this function returns false
            MakeQueryList($UserIds, $query);
            $UsrRes = @mysql_query($query, $lnk);

            $query = "SELECT ColorID,ClrName,Red,Green,Blue from colors";
            $ClrRes = @mysql_query($query, $lnk);

            if ( @mysql_num_rows($UsrRes) > 0 ) {
?>
<p align="center"><span class="required">*</span> - задължително поле<br />
<span class="required">**</span> - задължително само ако другото поле за
парола е попълнено</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table align="center">
<?php
              $Index = 0;
              while ( $UsrDetails = @mysql_fetch_array($UsrRes, MYSQL_ASSOC) ) {
?>
<tr><th align="center" colspan="2">Потребител: <?php echo $UsrDetails['Username']?>
<input type="hidden" name="UserIds[]" value="<?php echo $UsrDetails['UserID'] ?>" /></th></tr>
<tr><td align="right">Парола <span class="required">**</span></td>
<td><input type="password" name="Password[]" maxlength="32" size="32" />
<?php
  if ( $Error[$Index] ) {
    print("<br />");
    print($Pass_Err[$Index]);
  }
?>
</td></tr>
<tr valign="top">
<td align="right">Парола (повторете) <span class="required">**</span></td>
<td><input type="password" name="Password2[]" maxlength="32" size="32" />
<?php
  if ( $Error[$Index] ) {
    print("<br />");
    print($Pass2_Err[$Index]);
  } 
?>
</td></tr>
<tr valign="top">
<td align="right">Псевдоним <span class="required">*</span></td>
<td><input type="text" name="Nick[]" maxlength="32" size="32"
<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Nick[$Index]."\" /><br />");
    print($Nick_Err[$Index]);
  }
  else print(" value=\"".$UsrDetails['Nickname']."\" />\n"); ?></td></tr>
<tr valign="top">
<td align="right">Име <span class="required">*</span></td>
<td><input type="text" name="Name[]" maxlength="96" size="32"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Name[$Index]."\" /><br />");
    print($Name_Err[$Index]);
  }
  else print(" value=\"".$UsrDetails['UsrName']."\" />\n"); ?></td></tr>
<tr valign="top">
<td align="right">Е-поща <!-- <span class="required">*</span> --></td>
<td><input type="text" name="Email[]" maxlength="255" size="32"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Email[$Index]."\" /><br />");
    //print($Email_Err[$Index]);
  }
  else print(" value=\"".$UsrDetails['Email']."\" />\n"); ?></td></tr>
<tr valign="top">
<td align="right">Учител</td>
<td><input type="checkbox" name="Teacher[]"<?php
  if ( $Error[$Index] && isset($Teacher[$Index]) )
    print(" checked=\"checked\" />\n");
  else print(" />\n"); ?></td></tr>
<tr valign="top"><td align="right">Цвят</td>
<td>
<select name="Color[]" onchange="javascript: previewColor();">
<option value="">-- Моля, изберете цвят --</option>
<?php
                if ( @mysql_num_rows($ClrRes) > 0 ) {
                  @mysql_data_seek($ClrRes, 0);
                  while ( $Clr = @mysql_fetch_array($ClrRes, MYSQL_ASSOC) ) {
                    print("<option");
                    if ( $Error[$Index] )
                      $CompareID = $Color[$Index];
                    else $CompareID = $UsrDetails['ColorID'];
                    if ( $CompareID == $Clr['ColorID'] )
                        print(" selected=\"selected\"");
                    print(" value=\"".$Clr['ColorID']."\"");
                    print(" title=\"".MakeTriplet($Clr['Red'], $Clr['Green'], $Clr['Blue'])."\">");
                    print($Clr['ClrName']."</option>\n");
                  } // while
                }
?>
</select></td></tr>
<tr><td>&nbsp;</td></tr>
<?php
                $Index++;
              } // while
?>
<tr><td align="center" colspan="2">
<input type="hidden" name="CheckForms" value="1" />
<input type="submit" name="SubmitEdit" value="Редактирай" />
<input type="submit" name="CancelEdit" value="Откажи" /></td></tr>
<?php
            } // if ( num_rows > 0...
            @mysql_free_result($UsrRes);
            @mysql_free_result($ClrRes);
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
      }
    } // if ( isset(...
    else Redirect("adm_usrs.php");
  }
  elseif( isset($_POST['SubmitDel']) ) { /* Process delete request */
    if ( isset($_POST['UserIds']) ) {
      if ( count($_POST['UserIds']) == 0 )
        Redirect("adm_usrs.php");
      $UserIds = $_POST['UserIds'];
      $UserCount = count($UserIds);
      include("passwd.inc.php");
      if ( $lnk = @mysql_pconnect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
        if ( @mysql_select_db(DB_NAME, $lnk) ) {
          $query = "SELECT UserID,Username FROM users WHERE UserID";
          MakeQueryList($UserIds, $query);
          $res = @mysql_query($query, $lnk);
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table align="center">
<tr><td>Желаете ли да изтриете тези потребители?</td></tr>
<tr><td><ul>
<?php
          while ( $UsrDetails = @mysql_fetch_array($res, MYSQL_ASSOC) ) {
            print("<li>".$UsrDetails['Username']);
            print(" <input type=\"hidden\" name=\"UserIds[]\"");
            print(" value=\"".$UsrDetails['UserID']."\" /></li>\n");
          }

          @mysql_free_result($res);
          //@mysql_close($lnk); ?>
</ul></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="center">
<input type="submit" name="Delete" value="Да" />
<input type="submit" name="CancelDelete" value="Не" />
</td></tr>
<?php
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
    } // if ( isset
    else Redirect("adm_usrs.php");
  }
  elseif( isset($_POST['Delete']) ) { /* Delete */
    if ( isset($_POST['UserIds']) ) {
      if ( count($_POST['UserIds']) == 0 )
        Redirect();
      $UserIds = $_POST['UserIds'];
      include("passwd.inc.php");
      if ( $lnk = @mysql_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
        if ( @mysql_select_db(DB_NAME, $lnk) ) {
          $query = "DELETE FROM users WHERE UserID";
          $UserCount = count($UserIds);
          MakeQueryList($UserIds, $query);
          @mysql_query($query, $lnk);
          $DelCount = @mysql_affected_rows($lnk);
          @mysql_close($lnk);
          if ( $DelCount == $UserCount )
            PrintOK("Потребителите са изтрити успешно.");
          elseif( $DelCount > 0 && $DelCount < $UserCount )
            PrintOK("Някои от потребителите са изтрити успешно!");
          elseif( $DelCount == 0 )
            PrintOK("Потребителите НЕ са изтрити успешно!");
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
    }
    else Redirect("adm_usrs.php");
  } // elseif
?>
</table></form>
</td></tr></table>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center"><a href="http://validator.w3.org/check/referer">
<img border="0" src="valid-xhtml10.gif" alt="Valid XHTML 1.0!"
height="31" width="88" /></a>
<a class="right" href="http://jigsaw.w3.org/css-validator/">
<img alt="Valid CSS!" border="0" height="31" src="valid-css.png" width="88" />
</a></p>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p align="center" class="copyright">Автор &copy; 2003
<a href="mailto: <?php echo CHAT_CONTACT ?>" class="aCopyright">
<?php echo CHAT_AUTHOR ?></a></p>
</body>

</html>

