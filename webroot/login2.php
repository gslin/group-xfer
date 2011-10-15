<?

if (!isset($_POST['email']) || $_POST['email'] == '' ||
    !isset($_POST['password']) || $_POST['password'] == '')
{
  header('Location: /login.php');
  exit();
}

include('functions.inc.php');

session_start();
session_destroy();

$email = stripslashes($_POST['email']);
$password = stripslashes($_POST['password']);

$r = sql_connect();

$q = sprintf('SELECT * FROM user WHERE email = "%s" AND password = MD5("%s") AND disable = "N";', mysql_escape_string($email), mysql_escape_string($password));
$result = mysql_query($q, $r);
if ($result)
{
  $tmp = mysql_fetch_row($result);

  if ($tmp)
  {
    session_start();
    $_SESSION['email'] = $email;

    $ip = $_SERVER['REMOTE_ADDR'];
    $q = sprintf('UPDATE user SET lastlogin = NOW(), lastloginip = "%s" WHERE email = "%s";', mysql_escape_string($ip), mysql_escape_string($email));
    mysql_query($q, $r);

    if (isset($_POST['redirect']))
      header(sprintf('Location: %s', $_POST['redirect']));
    else
      header('Location: /manage.php');

    mysql_free_result($result);
    mysql_close($r);
    exit();
  }
  else
  {
    $msg = "No such user or password error";
  }

  mysql_free_result($result);
}

sql_disconnect($r);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Group.NCTU.edu.tw (Login)</title>
  <link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<div class="msg"><?= $msg ?>.</div>

</body>

</html>
