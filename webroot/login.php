<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Group.NCTU.edu.tw (Login)</title>
  <link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<h1>Login (登入系統)</h1>

<form action="https://group.nctu.edu.tw/login2.php" method="post">
<table border="0">

<tr>
	<td align="right">E-mail:</td>
	<td><input type="text" name="email" /> <small>(ex: foo@spam.edu.tw)</small></td>
</tr>
<tr>
	<td align="right">Password:</td>
	<td><input type="password" name="password" /></td>
</tr>

<tr>
	<td></td>
	<td><input type="submit" value="Login" /> <input type="reset" value="Reset" /></td>
</tr>
<tr>
	<td></td>
	<td>If you forget your password, you may <a href="resetpw.php">reset my password</a>.</td>
</tr>

</table>
<?
if (isset($_GET['redirect']))
{
?>
<input type="hidden" name="redirect" value="<?= urlencode($_GET['redirect']); ?>" />
<?
}
?>
</form>

<h1>Login By Groupname (忘記 e-mail 的人查詢用)</h1>

<form action="https://group.nctu.edu.tw/login-by-group.php" method="post">
<table border="0">

<tr>
	<td align="right">Group Name:</td>
	<td><input type="text" name="groupname" /> <small>(ex: test)</small></td>
</tr>
<tr>
	<td align="right">Password:</td>
	<td><input type="password" name="password" /></td>
</tr>

<tr>
	<td></td>
	<td><input type="submit" value="Login" /> <input type="reset" value="Reset" /></td>
</tr>
<tr>
	<td></td>
	<td>If you forget which e-mail you register by, you may use this function to check.</td>
</tr>

</table>
</form>

</body>

</html>
