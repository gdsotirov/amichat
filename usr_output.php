<?php
  session_start();

  include("error.inc.php");

  if ( !isset($_SESSION['USR_ID']) ) {
    PrintError(103);
    exit;
  }

  include("common.inc.php");
  include("color.inc.php");

  if ( isset($_SESSION['USR_REFRESH']) ) {
    $REFRESH = $_SESSION['USR_REFRESH'];
  }
  else $REFRESH = DEF_REFRESH;

  echo "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=windows-1251" />
<title><?php echo CHAT_NAME ?> - Messages window</title>
<meta http-equiv="Refresh" content="<?php echo $REFRESH ?>; URL=<?php echo $HTTP_SERVER_VARS['PHP_SELF'] ?>" />
<link rel="stylesheet" type="text/css" href="chat.css" />
</head>

<body class="Output" onload="javascript: window.scroll(0, 1000)"
style="padding: 5px 5px 5px 5px;">
<?php
  include("passwd.inc.php");

  $USR_ROOMID = $_SESSION['USR_ROOMID'];

  if ( $lnk = @mysql_connect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
    if ( @mysql_select_db(DB_NAME, $lnk) ) {
      $query = "SELECT COUNT(*) AS RowCount FROM messages";
      $res = @mysql_query($query, $lnk);
      $row = @mysql_fetch_array($res, MYSQL_ASSOC);

      $RowCount = $row['RowCount'];
      if ( isset($_SESSION['USR_MSGCNT']) )
        $MSGCOUNT = $_SESSION['USR_MSGCNT'];
      else $MSGCOUNT = DEF_MSGCOUNT;
      if ( $RowCount <= $MSGCOUNT )
        $Offset = 0;
      else $Offset = $RowCount - $MSGCOUNT;

      $query  = "SELECT messages.PostDate,messages.PostTime,messages.Message,";
      $query .= "users.Nickname,users.Teacher,";
      $query .= "colors.Red,colors.Green,colors.Blue";
      $query .= " FROM messages,users,colors";
      $query .= " WHERE messages.RoomID=$USR_ROOMID";
      $query .= " AND";
      $query .= " messages.AuthorID=users.UserID";
      $query .= " AND";
      $query .= " users.ColorID=colors.ColorID";
      $query .= " ORDER BY messages.PostDate,messages.PostTime";
      $query .= " LIMIT $Offset,$MSGCOUNT";
      $res = @mysql_query($query, $lnk);
      while ( $row = @mysql_fetch_array($res, MYSQL_ASSOC) ) {
        $USR_COLOR = MakeTriplet($row['Red'],$row['Green'],$row['Blue']);
        print("[".$row['PostDate']." ".$row['PostTime']."] ");
        print("<span style=\"color: $USR_COLOR;");
        if ( $row['Teacher'] == '1' )
          print(" font-weight: bold;\">");
        else print("\">");
        print($row['Nickname']."</span> -> ");
        if ( $row['Teacher'] == '1' )
          print("<b>".$row['Message']."</b><br />\n");
        else print($row['Message']."<br />\n");
      } // while
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
?>
</body>

</html>

