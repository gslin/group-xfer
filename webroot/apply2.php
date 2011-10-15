<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Group.NCTU.edu.tw</title>
  <link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<h1>Apply Account (申請帳號)</h1>

<form action="https://group.nctu.edu.tw/apply3.php" method="post">
<table border="0">

<tr>
	<td align="right">E-mail:</td>
	<td><input type="text" name="email" /></td>
	<td></td>
</tr>
<tr>
	<td align="right">Password:</td>
	<td><input type="password" name="password" /></td>
	<td></td>
</tr>
<tr>
	<td align="right">Password again:</td>
	<td><input type="password" name="password2" /></td>
	<td></td>
</tr>
<tr>
	<td align="right">Secure Code:</td>
	<td><input type="text" name="securecode" /></td>
	<td><img alt="Secure Code" src="securecode.php" /></td>
</tr>

<tr>
	<td></td>
	<td><input type="submit" value="Send" /></td>
</tr>

</table>
</form>

</body>

</html>
