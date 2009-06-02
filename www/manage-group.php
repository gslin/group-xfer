<?

include('need_authorized.inc.php');
require('functions.inc.php');

$groupname = stripslashes($_GET['g']);

/* check */
if (get_groupname_owner($groupname) != $email)
{
	header('Location: manage.php');
	exit();
}

$mailpost = get_mailpost($groupname);
$description = get_description($groupname);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Group.NCTU.edu.tw (Manage Group)</title>
	<link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<h1>Manage Group (管理 group.*.<?= $groupname ?>)</h1>

<form action="manage-groupdesc.php" method="post">
<input name="g" type="hidden" value="<?= $groupname ?>" />
<table border="0">
<tr>
	<td>Description:</td>
	<td><input name="d" type="text" value="<?= htmlentities($description, ENT_QUOTES, 'UTF-8') ?>" /></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" value="Change" /></td>
</tr>
</form>

<br />

<form action="manage-addhost.php" method="post">
<input name="g" type="hidden" value="<?= $groupname ?>" />
<table border="0">
<tr>
	<td>Add host:</td>
	<td><input name="h" type="text" />.twbbs.org</td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" value="Add" /></td>
</tr>
</table>
</form>

<br />

<form action="manage-removesite.php" method="post">
<table border="0">
<tr>
	<input name="g" type="hidden" value="<?= $groupname ?>" />
	<td>
	Remove host:
	</td>
	<td>
<?
foreach (get_host_list($groupname) as $bbs)
{
?>
		<input name="r" type="submit" value="<?= $bbs ?>" />
<?
}
?>
	</td>
</tr>
</table>
</form>

<br />

<form action="manage-mailpost.php" method="post">
<input name="g" type="hidden" value="<?= $groupname ?>" />
<table border="0">
<tr>
	<td>Mail Post:</td>
	<td><?= $mailpost ?> (You can mail something to <a href="mailto:group.<?= $groupname ?>@group.nctu.edu.tw">group.<?= $groupname ?>@group.nctu.edu.tw</a>, then it will appear in this group)</td>
</tr>
<tr>
	<td></td>
	<td><input name="m" type="submit" value="Turn <?= $mailpost == 'Y' ? "Off" : "On" ?>" /></td>
</tr>
</table>
</form>

</body>

</html>
