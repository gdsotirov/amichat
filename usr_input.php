<?php
  session_start();

  include("error.inc.php");

  if ( !isset($_SESSION['USR_ID']) ) {
    PrintError(103);
    exit;
  }

  include("common.inc.php");

  if ( isset($_POST['Submit']) ) {
    $Msg = $_POST['Message'];

    $USR_ID     = $_SESSION['USR_ID'];
    $USR_ROOMID = $_SESSION['USR_ROOMID'];

    include("passwd.inc.php");

    if ( $lnk = @mysqli_connect(DB_SERVER, DB_RW_USER, DB_RW_PWD) ) {
      if ( @mysqli_select_db(DB_NAME, $lnk) ) {
        $query  = "INSERT INTO messages";
        $query .= " (PostDate,PostTime,RoomID,AuthorID,RecipientID,Message)";
        $query .= " VALUES (CURDATE(), CURTIME(), $USR_ROOMID, $USR_ID, 0,";
        $query .= " '$Msg')";
        @mysqli_query($query, $lnk);
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

  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<title><?php echo CHAT_NAME ?> - Send message</title>
<meta http-equiv="Centent-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="chat.css" />
<script src="common.js" type="text/javascript"></script>
</head>

<body class="User" onload="javascript: focusFirst(); return true"
style="padding: 5px 0px 0px 5px;">
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="MsgPost">
<input type="text" name="Message" size="65" maxlength="255" />
<input type="submit" name="Submit" value="Изпрати" /></form>
</body>

</html>

