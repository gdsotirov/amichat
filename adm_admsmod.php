<?php
  session_start();

  include("common.inc.php");
  include("error.inc.php");

  if ( !isset($_SESSION['ADM_ID']) ) {
    Redirect("index.php?admin=1");
  }

  /* if user cancels the request -> redirect */
  if ( isset($_POST['CancelAdd']) || isset($_POST['CancelEdit']) || isset($_POST['CancelDelete']) ) {
    Redirect("adm_adms.php");
  }

  function PrintAction() {
    if ( isset($_POST['SubmitAdd']) ) {
      echo "Добавяне";
    }
    elseif ( isset($_POST['SubmitEdit']) ) {
      echo "Редакция";
    }
    elseif ( isset($_POST['SubmitDel']) || isset($_POST['Delete']) || isset($_POST['CancelDelete']) ) {
      echo "Изтриване";
    }
  }

  function PrintOK($msg) {
    print("<form action=\"adm_adms.php\" method=\"post\">\n");
    print("<table align=\"center\">\n");
    print("<tr><td>$msg</td></tr>\n");
    print("<tr><td>&nbsp;</td></tr>\n");
    print("<tr><td align=\"center\">\n");
    print("<input type=\"submit\" name=\"Submit\" value=\"Добре\" />");
    print("</td></tr>\n");
    print("</table></form>");
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
<title><?php echo CHAT_NAME ?> Административни страници: Администратори -> <?php PrintAction() ?></title>
<link href="chat.css" rel="stylesheet" type="text/css" />
<script defer="defer" src="common.js" type="text/javascript"></script>
</head>

<body class="Admin" onload="javascript: focusFirst(); return true">
<p class="midhdr">Административни страници:
<a href="adm_adms.php" class="aMidHdr">Администратори</a> -&gt; <?php PrintAction() ?></p>
<table class="tbThinBorder" cellspacing="0">
<?php PrintTabs('admins') ?><tr><td colspan="6" class="tdData">
<p class="smlhdr"><?php PrintAction() ?> на администратори</p>
<?php
  if ( isset($_POST['SubmitAdd']) ) { /* Process add request */
    $Error         = FALSE;
    $Username_Err  = "";
    $Pass_Err  = "";
    $Pass2_Err = "";
    $Name_Err      = "";
    $Email_Err     = "";
    if ( isset($_POST['CheckForm']) ) {
      $Username_Err  = CheckStringField($Error, $_POST['Username'], 6, 32, true);
      $Pass_Err  = CheckStringField($Error, $_POST['Password'], 6, 32, true);
      $Pass2_Err = CheckStringField($Error, $_POST['Password2'], 6, 32, true);
      if (strcmp($_POST['Password'],$_POST['Password2']) != 0) {
        $Pass2_Err = "<span class=\"error\">Паролите не съвпадат!</span>";
        $Error = TRUE;
      }
      $Name_Err  = CheckStringField($Error, $_POST['Name'], 1, 96);
      $Email_Err = CheckEmailField($Error, $_POST['Email'], 2, 255, true);
      if ( !$Error ) {
        include("passwd.inc.php");
        if ( $lnk = @mysqli_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
          if ( @mysqli_select_db($lnk, DB_NAME) ) {
            // check if user exist
            $query  = "SELECT AdminID FROM administrators";
            $query .= " WHERE Username='".$_POST['Username']."'";
            $res = @mysqli_query($lnk, $query);
            if ( @mysqli_num_rows($res) > 0 ) {
              PrintError(107);
              exit;
            }
            @mysqli_free_result($res);
            // add the user
            $query  = "INSERT INTO administrators (Username,Password,AdmName,";
            $query .= "Email,Phone,ModDate,ModTime,ModByID)";
            $query .= " VALUES ('".$_POST['Username']."',";
            $query .= " SHA1('".$_POST['Password']."'),";
            $query .= "'".$_POST['Name']."',";
            $query .= "'".$_POST['Email']."',";
            $query .= "'".$_POST['Phone']."',";
            $query .= " CURDATE(), CURTIME(), ".$_SESSION['ADM_ID'].")";
            @mysqli_query($lnk, $query);
            if ( @mysqli_affected_rows($lnk) == 1 ) {
              PrintOK("Администратора ".$_POST['Username']." (".$_POST['Name'].") е добавен успешно.");
            }
            else {
              PrintOK("Администратора НЕ е добавен!");
            }
            @mysqli_close($lnk);
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
?><p class="center"><span class="required">*</span> - задължително поле</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table class="tbCenter">
<tr><th colspan="2">Системна информация</th></tr>
<tr valign="top">
<td class="right" width="50%">Потребителско име
<span class="required">*</span></td>
<td><input type="text" name="Username" maxlength="32" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$_POST['Username']."\"");
  }
?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Username_Err);
  }
?>
</td></tr>
<tr valign="top"><td class="right">Парола <span class="required">*</span></td>
<td><input type="password" name="Password" maxlength="32" size="32" />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Pass_Err);
  }
?></td></tr>
<tr valign="top">
<td class="right">Парола (повторете) <span class="required">*</span></td>
<td><input type="password" name="Password2" maxlength="32" size="32" />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Pass2_Err);
  }
?></td></tr>
<tr><th colspan="2">Лична информация</th></tr>
<tr valign="top"><td class="right">Име <span class="required">*</span></td>
<td><input type="text" name="Name" maxlength="96" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$_POST['Name']."\"");
  }
?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Name_Err);
  }
?>
</td></tr>
<tr valign="top"><td class="right">Е-поща <span class="required">*</span></td>
<td><input type="text" name="Email" maxlength="255" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$_POST['Email']."\"");
  }
?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Email_Err);
  }
?>
</td></tr>
<tr valign="top"><td class="right">Телефон</td>
<td><input type="text" name="Phone" maxlength="255" size="32"<?php
  if ( $Error ) {
    print(" value=\"".$_POST['Phone']."\"");
  }
?> /></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td class="center" colspan="2">
<input type="hidden" name="CheckForm" value="1" />
<input type="submit" name="SubmitAdd" value="Добави" />
<input type="reset" name="Reset" value="Изчисти" />
<input type="submit" name="CancelAdd" value="Откажи" />
</td></tr>
</table>
</form>
<?php
    }
  }
  elseif ( isset($_POST['SubmitEdit']) ) { /* Process EDIT request */
    if ( isset($_POST['AdminIds']) ) {
      if ( count($_POST['AdminIds']) == 0 ) {
        Redirect("adm_adms.php");
      }
      if ( in_array(SUPERUSER_ID, $_POST['AdminIds'], TRUE) ) {
        PrintError(104);
        exit;
      }
      $AdminIds  = $_POST['AdminIds'];  /* Admin id's array */
      $AdmCount  = count($AdminIds);
      $Error     = array_fill(0, $AdmCount, 0);
      $Pass_Err  = array_fill(0, $AdmCount, "");
      $Pass2_Err = array_fill(0, $AdmCount, "");
      $Name_Err  = array_fill(0, $AdmCount, "");
      $Email_Err = array_fill(0, $AdmCount, "");
      /* check forms ? */
      if ( isset($_POST['CheckForms']) ) {
        $Password  = $_POST['Password'];  /* passwords array */
        $Password2 = $_POST['Password2']; /* passwords confirm array */
        $Name      = $_POST['Name'];      /* names array */
        $Email     = $_POST['Email'];     /* email array */
        $Phone     = $_POST['Phone'];     /* phones array */
        reset($AdminIds);
        /* check values in arrays */
        foreach ( $AdminIds as $AdmKey ) {
          if ( !empty($Password[$AdmKey]) || !empty($Password2[$AdmKey]) ) {
            $Pass_Err[$AdmKey]  =
              CheckStringField($Error[$AdmKey], $Password[$AdmKey], 6, 32, true);
            $Pass2_Err[$AdmKey] =
              CheckStringField($Error[$AdmKey], $Password2[$AdmKey], 6, 32, true);
            if ( strcmp($Password[$AdmKey], $Password2[$AdmKey]) != 0 ) {
              $Pass2_Err[$AdmKey] = "<span class=\"error\">Паролите не съвпадат!</span>";
              $Error[$AdmKey] = TRUE;
            }
          }
          $Name_Err[$AdmKey]  =
            CheckStringField($Error[$AdmKey], $Name[$AdmKey], 1, 96, true);
          $Email_Err[$AdmKey] =
            CheckEmailField($Error[$AdmKey], $Email[$AdmKey], 2, 255, true);
        }
        reset($Error);
        if ( !in_array(TRUE, $Error) ) { // if there are no errors
          // update administrator
          include("passwd.inc.php");
          if ( $lnk = @mysqli_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
            $EditCount = 0;
            if ( @mysqli_select_db($lnk, DB_NAME) ) {
              foreach ( $Error as $ErrKey ) {
                $query = "UPDATE administrators SET ";
                if ( !empty($Password[$ErrKey]) ) {
                  $query .= "Password=SHA1('".$Password[$ErrKey]."'),";
                }
                $query .= "AdmName='".$Name[$ErrKey]."',";
                $query .= "Email='".$Email[$ErrKey]."',";
                $query .= "Phone='".$Phone[$ErrKey]."',";
                $query .= "ModDate=CURDATE(),ModTime=CURTIME(),";
                $query .= "ModByID=".$_SESSION['ADM_ID'];
                $query .= " WHERE AdminID=".$AdminIds[$ErrKey];

                @mysqli_query($lnk, $query);
                if ( @mysqli_affected_rows($lnk) == 1 ) {
                  $EditCount++;
                }
              }
              @mysqli_close($lnk);
              if ( $EditCount == $AdmCount ) {
                PrintOK("Администраторите са редактирани успешно.");
              }
              elseif ( $EditCount > 0 && $EditCount < $AdmCount ) {
                PrintOK("Някои от администраторите са редактирани успешно!");
              }
              elseif ( $EditCount == 0 ) {
                PrintOK("Администраторите НЕ са редактирани успешно!");
              }
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
        if ( $lnk = @mysqli_connect("p:" . DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
          if ( @mysqli_select_db($lnk, DB_NAME) ) {
            $query  = "SELECT AdminID,Username,Password,AdmName,Email,Phone";
            $query .= " FROM administrators WHERE AdminID";
            MakeQueryList($AdminIds, $query);
            $res = @mysqli_query($lnk, $query);
            if ( @mysqli_num_rows($res) > 0 ) { ?>
<p class="center"><span class="required">*</span> - задължително поле<br />
<span class="required">**</span> - задължително само ако другото поле за
парола е попълнено</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table class="tbCenter"><?php
              $Index = 0;
              while ( $AdmDetails = @mysqli_fetch_array($res, MYSQLI_ASSOC) ) { ?>
<tr><th class="center" colspan="2">Администратор: <?php echo $AdmDetails['Username']?>
<input type="hidden" name="AdminIds[]" value="<?php echo $AdmDetails['AdminID'] ?>" /></th></tr>
<tr><td class="right">Парола <span class="required">**</span></td>
<td><input type="password" name="Password[]" maxlength="32" size="32" /><?php
  if ( $Error[$Index] ) {
    print("<br />\n");
    print($Pass_Err[$Index]);
  } ?>
</td></tr>
<tr valign="top">
<td class="right">Парола (повторете) <span class="required">**</span></td>
<td><input type="password" name="Password2[]" maxlength="32" size="32" /><?php
  if ( $Error[$Index] ) {
    print("<br />\n");
    print($Pass2_Err[$Index]);
  } ?></td></tr>
<tr valign="top">
<td class="right">Име <span class="required">*</span></td>
<td><input type="text" name="Name[]" maxlength="96" size="32"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Name[$Index]."\"");
  }
  else {
    print(" value=\"".$AdmDetails['AdmName']."\"");
  }
?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Name_Err[$Index]);
  }
?>
</td></tr>
<tr valign="top">
<td class="right">Е-поща <span class="required">*</span></td>
<td><input type="text" name="Email[]" maxlength="255" size="32"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Email[$Index]."\"");
    
  }
  else {
    print(" value=\"".$AdmDetails['Email']."\"");
  }
?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Email_Err[$Index]);
  }
?></td></tr>
<tr valign="top">
<td class="right">Телефон</td>
<td><input type="text" name="Phone[]" maxlength="255" size="32"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Phone[$Index]."\"");
  }
  else {
    print(" value=\"".$AdmDetails['Phone']."\"");
  } ?> /></td></tr>
<tr><td>&nbsp;</td></tr><?php
                $Index++;
              } // while ?>
<tr><td class="center" colspan="2">
<input type="hidden" name="CheckForms" value="1" />
<input type="submit" name="SubmitEdit" value="Редактирай" />
<input type="submit" name="CancelEdit" value="Откажи" /></td></tr>
</table>
</form>
<?php
            } // if ( num_rows > 0...
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
      }
    } // if ( isset(...
    else {
      Redirect("amd_adms.php");
    }
  }
  elseif ( isset($_POST['SubmitDel']) ) { /* Process delete request */
    if ( isset($_POST['AdminIds']) ) {
      if ( count($_POST['AdminIds']) == 0 ) {
        Redirect("adm_adms.php");
      }
      $AdminIds = $_POST['AdminIds'];
      if ( in_array(SUPERUSER_ID, $AdminIds, TRUE) ) {
        PrintError(105);
        exit;
      }
      if ( in_array($_SESSION['ADM_ID'], $AdminIds, TRUE) ) {
        PrintError(106);
        exit;
      }
      $AdmCount = count($AdminIds);
      include("passwd.inc.php");
      if ( $lnk = @mysqli_connect("p:" . DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
        if ( @mysqli_select_db($lnk, DB_NAME) ) {
          $query = "SELECT AdminID,Username FROM administrators WHERE AdminID";
          MakeQueryList($AdminIds, $query);
          $res = @mysqli_query($lnk, $query); ?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table class="tbCenter">
<tr><td>Желаете ли да изтриете тези администратори?</td></tr>
<tr><td><ul><?php
          while ( $AdminDetails = @mysqli_fetch_array($res, MYSQLI_ASSOC) ) {
            print("<li>".$AdminDetails['Username']);
            print(" <input type=\"hidden\" name=\"AdminIds[]\"");
            print(" value=\"".$AdminDetails['AdminID']."\" /></li>\n");
          }

          @mysqli_free_result($res); ?>
</ul></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td class="center">
<input type="submit" name="Delete" value="Да" />
<input type="submit" name="CancelDelete" value="Не" />
</td></tr>
</table>
</form>
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
    else {
      Redirect("adm_adms.php");
    }
  }
  elseif ( isset($_POST['Delete']) ) { /* Delete */
    if ( isset($_POST['AdminIds']) ) {
      if ( count($_POST['AdminIds']) == 0 ) {
        Redirect("adm_adms.php");
      }
      if ( in_array(SUPERUSER_ID, $_POST['AdminIds'], TRUE) ) {
        PrintError(105);
        exit;
      }
      if ( in_array($_SESSION['ADM_ID'], $_POST['AdminIds'], TRUE) ) {
        PrintError(106);
        exit;
      }
      $AdminIds = $_POST['AdminIds'];
      include("passwd.inc.php");
      if ( $lnk = @mysqli_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
        if ( @mysqli_select_db($lnk, DB_NAME) ) {
          $query = "DELETE FROM administrators WHERE AdminID";
          $AdmCount = count($AdminIds);
          MakeQueryList($AdminIds, $query);
          @mysqli_query($lnk, $query);
          $DelCount = @mysqli_affected_rows($lnk);
          @mysqli_close($lnk);
          if ( $DelCount == $AdmCount ) {
            PrintOK("Администраторите са изтрити успешно.");
          }
          elseif ( $DelCount > 0 && $DelCount < $AdmCount ) {
            PrintOK("Някои от администраторите са изтрити успешно!");
          }
          elseif ( $DelCount == 0 ) {
            PrintOK("Администраторите НЕ са изтрити успешно!");
          }
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
    else {
      Redirect("adm_adms.php");
    }
  } // elseif ?>
</td></tr></table>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p class="center"><a href="https://validator.w3.org/check/referer">
<img border="0" src="valid-xhtml.png" alt="Valid XHTML 1.0!"
height="31" width="88" /></a>
<a class="right" href="https://jigsaw.w3.org/css-validator/check/referer">
<img alt="Valid CSS!" border="0" height="31" src="valid-css.png" width="88" />
</a></p>
<!-- Valid XHTML 1.0 Transitional, Valid CSS //-->
<p class="copyright">Автор &copy; 2003-2005
<a href="mailto: <?php echo CHAT_CONTACT ?>" class="aCopyright">
<?php echo CHAT_AUTHOR ?></a></p>
</body>

</html>

