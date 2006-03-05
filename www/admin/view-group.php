<?

$groupname = stripslashes($_POST['g']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Group.NCTU.edu.tw</title>
	<link rel="stylesheet" type="text/css" href="/group.css" />
</head>

<body>

<? include('../template/toolbar.inc.php'); ?>

<h1>Manage group.*.<?= $groupname ?></h1>

</body>

</html>
