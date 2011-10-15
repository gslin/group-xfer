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

<h1>Reset Password (密碼重設服務)</h1>

<form action="resetpw2.php" method="post">
<table border="0">

<tr>
	<td align="right">E-mail:</td><td>
	<input type="text" name="email" /></td>
	<td></td>
</tr>
<tr>
	<td align="right">Secure Code:</td>
	<td><input type="text" name="securecode" /></td>
	<td><img alt="Secure Code" src="securecode.php" /></td>
</tr>

<tr>
	<td></td>
	<td colspan="2"><input type="submit" value="Send" /> <input type="reset" value="Reset" /></td>
</tr>

</table>
</form>

</body>

</html>
