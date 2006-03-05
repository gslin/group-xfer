<?

if (!isset($_POST['groupname']) || $_POST['groupname'] == '' ||
    !isset($_POST['password']) || $_POST['password'] == '')
{
  header('Location: /login.php');
  exit();
}

include('functions.inc.php');

session_start();
session_destroy();

$groupname = stripslashes($_POST['groupname']);
$password = stripslashes($_POST['password']);

$r = sql_connect();

$q = sprintf('SELECT email FROM groups WHERE groupname = "%s";', mysql_escape_string($groupname));
$result = mysql_query($q, $r);
$email = '';
if ($result) 
{
  $tmp = mysql_fetch_row($result);
  $email = $tmp[0];
}

$q = sprintf('SELECT * FROM user WHERE email = "%s" AND password = MD5("%s");', mysql_escape_string($email), mysql_escape_string($password));
$result = mysql_query($q, $r);
if ($result)
{
  $tmp = mysql_fetch_row($result);

  if ($tmp)
  {
    $msg = sprintf('The email registed in %s is %s', $groupname, $email);
  }
  else
  {
    $msg = 'The information is not stored in our database';
  }

  mysql_free_result($result);
}

sql_disconnect($r);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Group.NCTU.edu.tw (Login-by-group)</title>
  <link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<div class="msg"><?= $msg ?>.</div>

</body>

</html>
