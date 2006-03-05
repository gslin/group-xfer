<?

include('need_authorized.inc.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Group.NCTU.edu.tw (Change Password)</title>
  <link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<h1>Change Password (變更密碼)</h1>

<form action="changepassword2.php" method="post">

<table border="0">

<tr>
	<td align="right">Old Password:</td>
	<td><input type="password" name="oldpassword" /></td>
</tr>
<tr>
	<td align="right">New Password:</td>
	<td><input type="password" name="newpassword" /></td>
</tr>
<tr>
	<td align="right">New Password again:</td>
	<td><input type="password" name="newpassword2" /></td>
</tr>

<tr>
	<td></td>
	<td><input type="submit" value="Change" /></td>
</tr>

</table>

</form>

</body>

</html>
