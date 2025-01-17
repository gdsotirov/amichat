<?php
  session_start();

  include("common.inc.php");
  include("error.inc.php");

  if ( !isset($_SESSION['ADM_ID']) ) {
    Redirect("index.php?admin=1");
  }

  /* if user cancels the request -> redirect */
  if ( isset($_POST['CancelAdd']) || isset($_POST['CancelEdit']) || isset($_POST['CancelDelete']) ) {
    Redirect("adm_clrs.php");
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
    print("<form action=\"adm_clrs.php\" method=\"post\">\n");
    print("<table align=\"center\">\n");
    print("<tr><td>$msg</td></tr>\n");
    print("<tr><td>&nbsp;</td></tr>\n");
    print("<tr><td align=\"center\">\n");
    print("<input type=\"submit\" name=\"Submit\" value=\"Добре\" />");
    print("</td></tr>\n");
    print("</table></form>\n");
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
<title><?php echo CHAT_NAME ?> Административни страници: Цветове -> <?php PrintAction() ?></title>
<link href="chat.css" rel="stylesheet" type="text/css" />
<script defer="defer" src="common.js" type="text/javascript"></script>
<script type="text/javascript"><!--
    function previewColor() {
        var Red          = document.getElementsByName("Red[]");
        var Green        = document.getElementsByName("Green[]");
        var Blue         = document.getElementsByName("Blue[]");

        var i = 0;
        var ColorPrevElem;
        while ( (ColorPrevElem = document.getElementById("ColorPreview" + i)) ) {
            changeBgColor(ColorPrevElem, Red[i].value, Green[i].value, Blue[i].value);
            i++;
        }

        return 0;
    }
//-->
</script>
</head>

<body class="Admin" onload="javascript: focusFirst(); previewColor(); return true">
<p align="center" class="midhdr">Административни страници:
<a href="adm_clrs.php" class="aMidHdr">Цветове</a> -&gt; <?php PrintAction() ?></p>
<table align="center" class="tbThinBorder" cellspacing="0" width="100%">
<?php PrintTabs('colors') ?><tr><td colspan="6" class="tdData">
<p align="center" class="smlhdr"><?php PrintAction() ?> на цветове</p>
<?php
  if ( isset($_POST['SubmitAdd']) ) { /* Process add request */
    $Error     = FALSE;
    $Name_Err  = "";
    $Red_Err   = "";
    $Green_Err = "";
    $Blue_Err  = "";
    if ( isset($_POST['CheckForm']) ) {
      $Name  = $_POST['Name'][0];
      $Red   = $_POST['Red'][0];
      $Green = $_POST['Green'][0];
      $Blue  = $_POST['Blue'][0];
      $Name_Err  = CheckStringField($Error, $Name, 1, 16);
      $Red_Err   = CheckNumField($Error, $Red, 0, 255, "единици");
      $Green_Err = CheckNumField($Error, $Green, 0, 255, "единици");
      $Blue_Err  = CheckNumField($Error, $Blue, 0, 255, "единици");
      if ( !$Error ) {
        include("passwd.inc.php");
        if ( $lnk = @mysqli_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
          if ( @mysqli_select_db($lnk, DB_NAME) ) {
            // check if color exists
            $query  = "SELECT ColorID FROM colors";
            $query .= " WHERE ClrName='".$Name."'";
            $res = @mysqli_query($lnk, $query);
            if ( @mysqli_num_rows($res) > 0 ) {
              PrintError(109); // name exists
              exit;
            }
            @mysqli_free_result($res);
            // add the color
            $query  = "INSERT INTO colors";
            $query .= " (ClrName,Red,Green,Blue,ModDate,ModTime,AdminID)";
            $query .= " VALUES ('".$Name."',";
            $query .= "'".$Red."',";
            $query .= "'".$Green."',";
            $query .= "'".$Blue."',";
            $query .= "CURDATE(), CURTIME(), ".$_SESSION['ADM_ID'].")";
            @mysqli_query($lnk, $query);
            if ( @mysqli_affected_rows($lnk) == 1 ) {
              PrintOK("Цветът ".$Name." е добавен успешно.");
            }
            else {
              PrintOK("Цветът НЕ е добавен!");
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
      }
    }
    if ( !isset($_POST['CheckForm']) || $Error ) {
      // Print form with or without errors
?>
<p align="center"><span class="required">*</span> - задължително поле</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table align="center">
<tr valign="top">
<td align="right">Име <span class="required">*</span></td>
<td><input type="text" name="Name[]" maxlength="16" size="16"<?php
  if ( $Error ) {
    print(" value=\"".$Name."\"");
  }
?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Name_Err);
  }
?>
</td>
<td class="ColorPreviewCell" id="ColorPreview0" rowspan="4">
Представяне на цвета</td></tr>
<tr valign="top"><td align="right">Червено <span class="required">*</span></td>
<td><input onkeypress="javascript: previewColor();" type="text" name="Red[]"
 maxlength="3" size="3"<?php
  if ( $Error ) {
    print(" value=\"".$Red."\"");
  }
?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Red_Err);
  }
?>
</td></tr>
<tr valign="top"><td align="right">Зелено <span class="required">*</span></td>
<td><input onchange="javascript: previewColor();" type="text" name="Green[]"
 maxlength="3" size="3"<?php
  if ( $Error ) {
    print(" value=\"".$Green."\"");
  }
?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Green_Err);
  }
?>
</td></tr>
<tr valign="top"><td align="right">Синьо <span class="required">*</span></td>
<td><input onchange="javascript: previewColor();" type="text" name="Blue[]"
 maxlength="3" size="3"<?php
  if ( $Error ) {
    print(" value=\"".$Blue."\"");
  }
?> />
<?php
  if ( $Error ) {
    print("<br />\n");
    print($Blue_Err);
  }
?>
</td></tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td align="center" colspan="3">
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
    if ( isset($_POST['ColorIds']) ) {
      if ( count($_POST['ColorIds']) == 0 ) {
        Redirect("adm_clrs.php");
      }
      $ColorIds  = $_POST['ColorIds'];
      $ClrCount  = count($ColorIds);
      $Error     = array_fill(0, $ClrCount, 0);
      $Name_Err  = array_fill(0, $ClrCount, "");
      $Red_Err   = array_fill(0, $ClrCount, "");
      $Green_Err = array_fill(0, $ClrCount, "");
      $Blue_Err  = array_fill(0, $ClrCount, "");
      /* check forms ? */
      if ( isset($_POST['CheckForms']) ) {
        $Name  = $_POST['Name'];
        $Red   = $_POST['Red'];
        $Green = $_POST['Green'];
        $Blue  = $_POST['Blue'];
        reset($ColorIds);
        /* check values in arrays */
        while ( list($ClrKey) = each($ColorIds) ) {
          $Name_Err[$ClrKey]  = CheckStringField($Error[$ClrKey], $Name[$ClrKey], 1, 16);
          $Red_Err[$ClrKey]   = CheckNumField($Error[$ClrKey], $Red[$ClrKey], 0, 255, "единици");
          $Green_Err[$ClrKey] = CheckNumField($Error[$ClrKey], $Green[$ClrKey], 0, 255, "единици");
          $Blue_Err[$ClrKey]  = CheckNumField($Error[$ClrKey], $Blue[$ClrKey], 0, 255, "единици");
        } // while
        reset($Error);
        if ( !in_array(TRUE, $Error) ) { // if there are no errors
          // update color
          include("passwd.inc.php");
          if ( $lnk = @mysqli_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
            $EditCount = 0;
            if ( @mysqli_select_db($lnk, DB_NAME) ) {
              while ( list($ErrKey) = each($Error) ) {
                $query = "UPDATE colors SET ";
                $query .= "ClrName='".$Name[$ErrKey]."',";
                $query .= "Red=".$Red[$ErrKey].",";
                $query .= "Green=".$Green[$ErrKey].",";
                $query .= "Blue=".$Blue[$ErrKey].",";
                $query .= "ModDate=CURDATE(),ModTime=CURTIME(),";
                $query .= "AdminID=".$_SESSION['ADM_ID'];
                $query .= " WHERE ColorID=".$ColorIds[$ErrKey];
                @mysqli_query($lnk, $query);
                if ( @mysqli_affected_rows($lnk) == 1 ) {
                  $EditCount++;
                }
              } // while
              @mysqli_close($lnk);
              if ( $EditCount == $ClrCount ) {
                PrintOK("Цветовете са редактирани успешно.");
              }
              elseif ( $EditCount > 0 && $EditCount < $ClrCount ) {
                PrintOK("Някои от цветовете са редактирани успешно!");
              }
              elseif ( $EditCount == 0 ) {
                PrintOK("Цветовете НЕ са редактирани успешно!");
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
            $query  = "SELECT ColorID,ClrName,Red,Green,Blue";
            $query .= " FROM colors";
            $query .= " WHERE ColorID";
            // TODO: Print error message if this function returns false
            MakeQueryList($ColorIds, $query);
            $res = @mysqli_query($lnk, $query);

            if ( @mysqli_num_rows($res) > 0 ) {
?>
<p align="center"><span class="required">*</span> - задължително поле</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table align="center"><?php
              $Index = 0;
              while ( $ClrDetails = @mysqli_fetch_array($res, MYSQL_ASSOC) ) { ?>
<tr valign="top"><td align="right">
<input type="hidden" name="ColorIds[]" value="<?php echo $ClrDetails['ColorID'] ?>" />
<b>Име</b><span class="required">*</span></td>
<td><input type="text" name="Name[]" maxlength="16" size="16"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Name[$Index]."\"");
  }
  else {
    print(" value=\"".$ClrDetails['ClrName']."\"");
  }
?> />
<?php
  if ( $Error[$Index] ) {
    print("<br />\n");
    print($Name_Err[$Index]);
  }
?>
</td>
<td class="ColorPreviewCell" id="ColorPreview<?php print($Index) ?>" rowspan="4">
Представяне на цвета</td></tr>
<tr valign="top">
<td align="right">Червено <span class="required">*</span></td>
<td><input onchange="javascript: previewColor();" type="text" name="Red[]"
 maxlength="3" size="3"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Red[$Index]."\"");
  }
  else {
    print(" value=\"".$ClrDetails['Red']."\"");
  }
?> />
<?php
  if ( $Error[$Index] ) {
    print("<br />\n");
    print($Red_Err[$Index]);
  }
?>
</td></tr>
<tr valign="top">
<td align="right">Зелено <span class="required">*</span></td>
<td><input onchange="javascript: previewColor();" type="text" name="Green[]"
 maxlength="3" size="3"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Green[$Index]."\"");
  }
  else {
    print(" value=\"".$ClrDetails['Green']."\"");
  }
?> />
<?php
  if ( $Error[$Index] ) {
    print("<br />\n");
    print($Green_Err[$Index]);
  }
?>
</td></tr>
<tr valign="top">
<td align="right">Синьо <span class="required">*</span></td>
<td><input onchange="javascript: previewColor();" type="text" name="Blue[]"
 maxlength="3" size="3"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Blue[$Index]."\"");
  }
  else {
    print(" value=\"".$ClrDetails['Blue']."\"");
  }
?> />
<?php
  if ( $Error[$Index] ) {
    print("<br />\n");
    print($Blue_Err[$Index]);
  }
?>
</td></tr>
<tr><td>&nbsp;</td></tr><?php         
                $Index++;
              } // while ?>
<tr><td align="center" colspan="3">
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
      Redirect("adm_clrs.php");
    }
  }
  elseif( isset($_POST['SubmitDel']) ) { /* Process delete request */
    if ( isset($_POST['ColorIds']) ) {
      if ( count($_POST['ColorIds']) == 0 ) {
        Redirect("adm_clrs.php");
      }
      $ColorIds = $_POST['ColorIds'];
      $ClrCount = count($ColorIds);
      include("passwd.inc.php");
      if ( $lnk = @mysqli_connect("p:" . DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
        if ( @mysqli_select_db($lnk, DB_NAME) ) {
          $query = "SELECT ColorID,ClrName FROM colors WHERE ColorID";
          MakeQueryList($ColorIds, $query);
          $res = @mysqli_query($lnk, $query); ?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table align="center">
<tr><td>Желаете ли да изтриете тези цветове?</td></tr>
<tr><td><ul>
<?php
          while ( $ClrDetails = @mysqli_fetch_array($res, MYSQL_ASSOC) ) {
            print("<li>".$ClrDetails['ClrName']);
            print(" <input type=\"hidden\" name=\"ColorIds[]\"");
            print(" value=\"".$ClrDetails['ColorID']."\" /></li>\n");
          }
          @mysqli_free_result($res);
?>
</ul></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="center">
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
      Redirect("adm_clrs.php");
    }
  }
  elseif( isset($_POST['Delete']) ) { /* Delete */
    if ( isset($_POST['ColorIds']) ) {
      if ( count($_POST['ColorIds']) == 0 ) {
        Redirect("adm_clrs.php");
      }
      $ColorIds = $_POST['ColorIds'];
      include("passwd.inc.php");
      if ( $lnk = @mysqli_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
        if ( @mysqli_select_db($lnk, DB_NAME) ) {
          $query = "DELETE FROM colors WHERE ColorID";
          $ClrCount = count($ColorIds);
          MakeQueryList($ColorIds, $query);
          @mysqli_query($lnk, $query);
          $DelCount = @mysqli_affected_rows($lnk);
          @mysqli_close($lnk);
          if ( $DelCount == $ClrCount ) {
            PrintOK("Цветовете са изтрити успешно.");
          }
          elseif( $DelCount > 0 && $DelCount < $ClrCount ) {
            PrintOK("Някои от цветовете са изтрити успешно!");
          }
          elseif( $DelCount == 0 ) {
            PrintOK("Цветовете НЕ са изтрити успешно!");
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
      Redirect("adm_clrs.php");
    }
  } // elseif
?>
</td></tr></table>
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

