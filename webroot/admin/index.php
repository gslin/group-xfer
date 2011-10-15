<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Group.NCTU.edu.tw</title>
	<link rel="stylesheet" type="text/css" href="/group.css" />
</head>

<body>

<? include('../template/toolbar.inc.php'); ?>

<h1>Administration Interface</h1>

<form action="view-group.php" method="post">
<table border="0">
<tr>
	<td align="right">E-mail:</td>
	<td><input accesskey="1" name="m" type="text" /></td>
</tr>
<tr>
	<td align="right">Group Name:</td>
	<td><input accesskey="2" name="g" type="text" /></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" value="Check" /></td>
</tr>
</table>
</form>

</body>

</html>
