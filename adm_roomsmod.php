<?php
  session_start();

  include("common.inc.php");
  include("error.inc.php");

  if ( !isset($_SESSION['ADM_ID']) )
    Redirect("index.php?admin=1");

  /* if user cancels the request -> redirect */
  if ( isset($_POST['CancelAdd']) || isset($_POST['CancelEdit']) || isset($_POST['CancelDelete']) )
    Redirect("adm_rooms.php");

  function PrintAction() {
    if ( isset($_POST['SubmitAdd']) )
        echo "Добавяне";
    elseif( isset($_POST['SubmitEdit']) )
        echo "Редакция";
    elseif( isset($_POST['SubmitDel']) || isset($_POST['Delete']) || isset($_POST['CancelDelete']) )
        echo "Изтриване";
  }

  function PrintOK($msg) {
    print("<form action=\"adm_rooms.php\" method=\"post\">\n");
    print("<table align=\"center\">\n");
    print("<tr><td>$msg</td></tr>\n");
    print("<tr><td>&nbsp;</td></tr>\n");
    print("<tr><td align=\"center\">\n");
    print("<input type=\"submit\" name=\"Submit\" value=\"Добре\" />");
    print("</td></tr>\n");
  }

  include("admin.inc.php");

  Headers();

  echo "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=windows-1251" />
<title><?php echo CHAT_NAME ?> Административни страници: Стаи -> <?php PrintAction() ?></title>
<link href="chat.css" rel="stylesheet" type="text/css" />
<script defer="defer" src="common.js" type="text/javascript"></script>
</head>

<body class="Admin" onload="javascript: focusFirst(); return true">
<p align="center" class="midhdr">Административни страници:
<a href="adm_rooms.php" class="aMidHdr">Стаи</a> -&gt; <?php PrintAction() ?></p>
<table align="center" class="tbThinBorder" cellspacing="0" width="100%">
<?php PrintTabs('rooms') ?><tr><td colspan="6" class="tdData">
<p align="center" class="smlhdr"><?php PrintAction() ?> на стаи</p>
<?php
  if ( isset($_POST['SubmitAdd']) ) { /* Process add request */
    $Error     = FALSE;
    $Name_Err  = "";
    if ( isset($_POST['CheckForm']) ) {
      $Name_Err =
        CheckStringField($Error, $_POST['Name'], 1, 16);
      if ( !$Error ) {
        include("passwd.inc.php");
        if ( $lnk = @mysql_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
          if ( @mysql_select_db(DB_NAME, $lnk) ) {
            // check if room exists
            $query  = "SELECT RoomID FROM rooms";
            $query .= " WHERE RoomName='".$_POST['Name']."'";
            $res = @mysql_query($query, $lnk);
            if ( @mysql_num_rows($res) > 0 ) {
              PrintError(110); // room exists
              exit;
            }
            @mysql_free_result($res);
            // add the user
            $query  = "INSERT INTO rooms";
            $query .= " (RoomName,Descr,ModDate,ModTime,AdminID)";
            $query .= " VALUES ('".$_POST['Name']."',";
            $query .= "'".$_POST['Descr']."',";
            $query .= " CURDATE(), CURTIME(), ".$_SESSION['ADM_ID'].")";
            @mysql_query($query, $lnk);
            if ( @mysql_affected_rows($lnk) == 1 )
              PrintOK("Стаята ".$_POST['Name']." е добавена успешно.");
            else PrintOK("Стаята НЕ е добавенa!");
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
?><p align="center"><span class="required">*</span> - задължително поле</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table align="center">
<tr valign="top">
<td align="right" width="50%">Име <span class="required">*</span></td>
<td><input type="text" name="Name" maxlength="16" size="16"<?php
  if ( $Error ) {
    print(" value=\"".$_POST['Name']."\" /><br />\n");
    print($Name_Err);
  }
  else print(" />"); ?></td></tr>
<tr valign="top"><td align="right">Описание</td>
<td><input type="text" name="Descr" maxlength="255" size="32"<?php
  if ( $Error )
    print(" value=\"".$_POST['Descr']."\" /><br />\n");
  else print(" />"); ?></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td align="center" colspan="2">
<input type="hidden" name="CheckForm" value="1" />
<input type="submit" name="SubmitAdd" value="Добави" />
<input type="reset" name="Reset" value="Изчисти" />
<input type="submit" name="CancelAdd" value="Откажи" />
</td></tr><?php
    }
  }
  elseif( isset($_POST['SubmitEdit']) ) { /* Process EDIT request */
    if ( isset($_POST['RoomIds']) ) {
      if ( count($_POST['RoomIds']) == 0 )
        Redirect("adm_rooms.php");
      $RoomIds   = $_POST['RoomIds'];
      $RoomCount = count($RoomIds);
      $Error     = array_fill(0, $RoomCount, 0);
      $Name_Err  = array_fill(0, $RoomCount, "");
      /* check forms ? */
      if ( isset($_POST['CheckForms']) ) {
        $Name  = $_POST['Name'];
        $Descr = $_POST['Descr'];
        reset($RoomIds);
        /* check values in arrays */
        while ( list($RoomKey, $RoomID) = each($RoomIds) ) {
          $Name_Err[$RoomKey] =
            CheckStringField($Error[$RoomKey], $Name[$RoomKey], 1, 16);
        } // while
        reset($Error);
        if ( !in_array(TRUE, $Error) ) { // if there are no errors
          // update color
          include("passwd.inc.php");
          if ( $lnk = @mysql_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
            $EditCount = 0;
            if ( @mysql_select_db(DB_NAME, $lnk) ) {
              while ( list($ErrKey, $ErrVal) = each($Error) ) {
                $query = "UPDATE rooms SET ";
                $query .= "RoomName='".$Name[$ErrKey]."',";
                $query .= "Descr='".$Descr[$ErrKey]."',";
                $query .= "ModDate=CURDATE(),ModTime=CURTIME(),";
                $query .= "AdminID=".$_SESSION['ADM_ID'];
                $query .= " WHERE RoomID=".$RoomIds[$ErrKey];
                @mysql_query($query, $lnk);
                if ( @mysql_affected_rows($lnk) == 1 )
                  $EditCount++;
              } // while
              @mysql_close($lnk);
              if ( $EditCount == $RoomCount )
                PrintOK("Стаите са редактирани успешно.");
              elseif( $EditCount > 0 && $EditCount < $RoomCount )
                PrintOK("Някои от стаите са редактирани успешно!");
              elseif( $EditCount == 0 )
                PrintOK("Стаите НЕ са редактирани успешно!");
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
            $query = "SELECT RoomID,RoomName,Descr FROM rooms WHERE RoomID";
            // TODO: Print error message if this function returns false
            MakeQueryList($RoomIds, $query);
            $res = @mysql_query($query, $lnk);
            if ( @mysql_num_rows($res) > 0 ) { ?>
<p align="center"><span class="required">*</span> - задължително поле</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table align="center"><?php
              $Index = 0;
              while ( $RoomDetails = @mysql_fetch_array($res, MYSQL_ASSOC) ) { ?>
<tr valign="top"><td align="right">
<input type="hidden" name="RoomIds[]" value="<?php echo $RoomDetails['RoomID'] ?>" />
<b>Име</b> <span class="required">*</span></td>
<td><input type="text" name="Name[]" maxlength="16" size="16"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Name[$Index]."\" /><br />\n");
    print($Name_Err[$Index]);
  }
  else print(" value=\"".$RoomDetails['RoomName']."\" />"); ?></td></tr>
<tr valign="top">
<td align="right">Описание</td>
<td><input type="text" name="Descr[]" maxlength="255" size="32"<?php
  if ( $Error[$Index] ) {
    print(" value=\"".$Descr[$Index]."\" /><br />\n");
    print($Descr_Err[$Index]);
  }
  else print(" value=\"".$RoomDetails['Descr']."\" />"); ?></td></tr>
<tr><td>&nbsp;</td></tr>
<?php         } // while ?>
<tr><td align="center" colspan="2">
<input type="hidden" name="CheckForms" value="1" />
<input type="submit" name="SubmitEdit" value="Редактирай" />
<input type="submit" name="CancelEdit" value="Откажи" /></td></tr><?php
            } // if ( num_rows > 0...
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
      }
    } // if ( isset(...
    else Redirect("adm_rooms.php");
  }
  elseif( isset($_POST['SubmitDel']) ) { /* Process delete request */
    if ( isset($_POST['RoomIds']) ) {
      if ( count($_POST['RoomIds']) == 0 )
        Redirect("adm_rooms.php");
      $RoomIds   = $_POST['RoomIds'];
      $RoomCount = count($RoomIds);
      include("passwd.inc.php");
      if ( $lnk = @mysql_pconnect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
        if ( @mysql_select_db(DB_NAME, $lnk) ) {
          $query = "SELECT RoomID,RoomName FROM rooms WHERE RoomID";
          MakeQueryList($RoomIds, $query);
          $res = @mysql_query($query, $lnk); ?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<table align="center">
<tr><td>Желаете ли да изтриете тези стаи?</td></tr>
<tr><td><ul><?php
          while ( $RoomDetails = @mysql_fetch_array($res, MYSQL_ASSOC) ) {
            print("<li>".$RoomDetails['RoomName']);
            print(" <input type=\"hidden\" name=\"RoomIds[]\"");
            print(" value=\"".$RoomDetails['RoomID']."\" /></li>\n");
          }
          @mysql_free_result($res);
          //@mysql_close($lnk); ?>
</ul></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="center">
<input type="submit" name="Delete" value="Да" />
<input type="submit" name="CancelDelete" value="Не" />
</td></tr><?php
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
    else Redirect("adm_rooms.php");
  }
  elseif( isset($_POST['Delete']) ) { /* Delete */
    if ( isset($_POST['RoomIds']) ) {
      if ( count($_POST['RoomIds']) == 0 )
        Redirect("adm_rooms.php");
      $RoomIds = $_POST['RoomIds'];
      include("passwd.inc.php");
      if ( $lnk = @mysql_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
        if ( @mysql_select_db(DB_NAME, $lnk) ) {
          $query = "DELETE FROM rooms WHERE RoomID";
          $RoomCount = count($RoomIds);
          MakeQueryList($RoomIds, $query);
          @mysql_query($query, $lnk);
          $DelCount = @mysql_affected_rows($lnk);
          @mysql_close($lnk);
          if ( $DelCount == $RoomCount )
            PrintOK("Стаите са изтрити успешно.");
          elseif( $DelCount > 0 && $DelCount < $RoomCount )
            PrintOK("Някои от стаите са изтрити успешно!");
          elseif( $DelCount == 0 )
            PrintOK("Стаите НЕ са изтрити успешно!");
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
    else Redirect("adm_rooms.php");
  } // elseif ?>
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
<a class="aCopyright" href="mailto: <?php echo CHAT_CONTACT ?>">
<?php echo CHAT_AUTHOR ?></a></p>
</body>

</html>

