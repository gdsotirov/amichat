<?php
  session_start();

  include("error.inc.php");

  if ( !isset($_SESSION['USR_ID']) ) {
    PrintError(103);
    exit;
  }

  include("common.inc.php");
  include("color.inc.php");

  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Centent-type" content="text/html; charset=UTF-8" />
<title><?php echo CHAT_NAME ?> - Users online</title>
<meta http-equiv="Refresh" content="5; URL=<?php echo $_SERVER['PHP_SELF'] ?>" />
<link rel="stylesheet" type="text/css" href="chat.css" />
</head>

<body class="User" style="padding: 5px 5px 5px 5px;">
<table align="left" class="tbOnline" width="100%">
<?php
  include("passwd.inc.php");

  if ( $lnk = @mysqli_connect(DB_SERVER, DB_RO_USER, DB_RO_PWD) ) {
    if ( @mysqli_select_db(DB_NAME, $lnk) ) {
      $query  = "SELECT users.UserID,users.Nickname,colors.Red,colors.Green,";
      $query .= "colors.Blue";
      $query .= " FROM users,colors";
      $query .= " WHERE users.Active='1'";
      $query .= " AND";
      $query .= " users.ColorID=colors.ColorID";
      $query .= " ORDER BY Nickname";
      $res = @mysqli_query($query, $lnk);
      $Count = @mysqli_num_rows($res);
      print("<tr><th>Online: $Count</th></tr>");
      if ( $Count > 0 ) {        
        while ( $row = @mysqli_fetch_array($res, MYSQL_ASSOC) ) {
          print("<tr><td style=\"color: ");
          print(MakeTriplet($row['Red'], $row['Green'], $row['Blue']).";");
          print(" \">".$row['Nickname']."</td></tr>");
        }
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
?>
</table>
</body>

</html>

