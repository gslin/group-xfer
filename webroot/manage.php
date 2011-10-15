<?

include('need_authorized.inc.php');
require('functions.inc.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Group.NCTU.edu.tw (Manage)</title>
	<link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<form action="manage-addgroup.php" method="post">
<div class="left">
	<ul>
<?
foreach (get_groupname_list($email) as $groupname)
{
	printf("\t\t<li><a href=\"manage-group.php?g=%s\">%s</a></li>\n", urlencode($groupname), htmlentities($groupname));
}
?>
	</ul>
	<table border="0">
	<tr>
		<td>Create Group:</td>
		<td><input type="text" name="n" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="Create" /></td>
	</tr>
	</table>
</div>

</form>

<div class="right">
	<ul style="list-style: none;">
		<li><a href="changepassword.php">Change password</a> (變更密碼)</li>
	</ul>
</div>

</body>

</html>
