<?php
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="bg" lang="bg">

<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title>AMI Chat Административни страници: Потребители</title>
<link href="chat.css" rel="stylesheet" type="text/css" />
</head>

<body class="Admin">
<p align="center" class="midhdr">Административни страници: Изход</p>
<table align="center" class="tbThinBorder" cellspacing="0" width="100%">
<tr>
<td height="20px" width="20%" class="tdTabInactive">

<a class="aTabInactive" href="adm_adms.php">Администратори</a></td>
<td height="20px" width="20%" class="tdTabInactive">
<a class="aTabInactive" href="adm_usrs.php">Потребители</a></td>
<td height="20px" width="20%" class="tdTabInactive">
<a class="aTabInactive" href="adm_clrs.php">Цветове</a></td>
<td height="20px" width="20%" class="tdTabInactive">
<a class="aTabInactive" href="adm_rooms.php">Стаи</a></td>
<td height="20px" width="20%" class="tdTabActive">Изход</td>
</tr><tr><td colspan="6" class="tdData">
<p align="center" class="smlhdr">
Наистина ли желате да излезете от административните страници?</p>
<table align="center">
<tr><td>
<form action="logout.php" method="post">
<input type="submit" name="Yes" value="Да" />
</form>
</td>
<td>
<form action="admpage.php" method="post">
<input type="submit" name="No" value="Не" />
</form>
</td></tr></table>
</td></tr></table>
</body>

</html>
