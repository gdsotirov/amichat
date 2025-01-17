<?php
  include("common.inc.php");
  include("error.inc.php");

  if ( !isset($_POST['Submit']) || !isset($_POST['Username']) || !isset($_POST['Password']) ) {
    Redirect("index.php");
  }

  $Username = $_POST['Username'];
  $Password = $_POST['Password'];

  if ( isset($_POST['Admin']) ) {
    $ADMIN = $_POST['Admin'];
  }

  include("passwd.inc.php");

  if ( $lnk = @mysqli_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
    if ( @mysqli_select_db($lnk, DB_NAME) ) {
      if ( isset($ADMIN) ) { // Process administrator login
        $query  = "SELECT AdminID,AdmName,Password,LLDate,LLTime,LLHost,";
        $query .= "SHA1('$Password') AS SuplPassword";
        $query .= " FROM administrators";
        $query .= " WHERE Username='$Username'";
        $res = @mysqli_query($lnk, $query);
        if ( @mysqli_num_rows($res) > 0 ) {
          $AdmDetails = @mysqli_fetch_array($res, MYSQL_ASSOC);
          @mysqli_free_result($res);
          if ( $AdmDetails['SuplPassword'] == $AdmDetails['Password'] ) {
            session_start(); // *** Start administrative session ***
            // set session variables
            $_SESSION['ADM_ID']     = $AdmDetails['AdminID'];
            $_SESSION['ADM_NAME']   = $AdmDetails['AdmName'];
            $_SESSION['ADM_LLDATE'] = $AdmDetails['LLDate'];
            $_SESSION['ADM_LLTIME'] = $AdmDetails['LLTime'];
            $_SESSION['ADM_LLHOST'] = $AdmDetails['LLHost'];
            $query  = "UPDATE administrators SET";
            $query .= " LLDate='".date("Y-m-d")."',";
            $query .= "LLTime='".date("H:i:s")."',";
            $query .= "LLHost='".$_SERVER['REMOTE_ADDR']."'";
            $query .= " WHERE AdminID=".$AdmDetails['AdminID'];
            @mysqli_query($lnk, $query);
            @mysqli_close($lnk);
            Redirect("admpage.php");
            exit;
          }
          else {
            PrintError(102); // invalid password
            exit;
          }
        }
        else {
          PrintError(101); // invalid username
          exit;
        }
      }
      else { // Process user login
        $query = "SELECT UserID,Password,Nickname,SHA1('$Password') AS SuplPassword FROM users WHERE Username='$Username'";
        $res = @mysqli_query($lnk, $query);
        if ( @mysqli_num_rows($res) > 0 ) {
          $UsrDetails = @mysqli_fetch_array($res, MYSQL_ASSOC);
          @mysqli_free_result($res);
          if ( $UsrDetails['SuplPassword'] == $UsrDetails['Password'] ) {
            session_start(); // *** Start user chat session ***
            $query = "UPDATE users SET LLDate='".date("Y-m-d")."',LLTime='".date("H:i:s")."',LLHost='".$_SERVER['REMOTE_ADDR']."',Active='1' WHERE UserID=".$UsrDetails['UserID'];
            /* NOTE: Did I need error reporting here? */
            @mysqli_query($lnk, $query);
            @mysqli_close($lnk);
            // set session variables
            $_SESSION['USR_ID']     = $UsrDetails['UserID'];
            $_SESSION['USR_NICK']   = $UsrDetails['Nickname'];
            $_SESSION['USR_ROOMID'] = 1; // public
            $_SESSION['USR_REFRESH'] = DEF_REFRESH;
            Redirect("usrpage.php");
            exit;
          }
          else {
            PrintError(102); // invalid password
            exit;
          }
        }
        else {
          PrintError(101); // invalid username
          exit;
        }
      } // else - process user login
    }
    else {
      PrintError(202); // can't use database
    }
  }
  else {
    PrintError(201); // can't connect to server
  }
?>

