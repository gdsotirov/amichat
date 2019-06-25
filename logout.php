<?php
  include("common.inc.php");
  include("error.inc.php");

  session_start();

  // Process administrator logout
  if ( isset($_SESSION['ADM_ID']) ) {
    // close @mysql persistent link
    if ( $lnk = @mysql_pconnect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
      @mysql_close($lnk);
    }
    /* *** finalize administrative session *** */
    session_unset();
    session_destroy();

    Redirect("index.php?admin=1");
  }
  else { // Process user logout
    include("passwd.inc.php");
    if ( $lnk = @mysql_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
      if ( @mysql_select_db(DB_NAME) ) {
        $query  = "UPDATE users SET Active='0'";
        $query .= " WHERE UserID=".$_SESSION['USR_ID'];
        @mysql_query($query, $lnk);
        @mysql_close($lnk);
      }
    }
    /* *** finalize user session *** */
    session_unset();
    session_destroy();

    Redirect("index.php");
  }
?>

