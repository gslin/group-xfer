<?

include('need_authorized.inc.php');

require_once('functions.inc.php');

if (!isset($_POST['oldpassword']) || $_POST['oldpassword'] == "" ||
    !isset($_POST['newpassword']) || $_POST['newpassword'] == "" ||
    !isset($_POST['newpassword2']) || $_POST['newpassword2'] == "")
{
  header('Location: changepassword.php');
  exit();
}

do
{
  $oldpassword = stripslashes($_POST['oldpassword']);
  $newpassword = stripslashes($_POST['newpassword']);
  $newpassword2 = stripslashes($_POST['newpassword2']);

  if ($newpassword != $newpassword2)
  {
    $msg = "New passwords don't match";
    break;
  }

  $r = sql_connect();

  $q = sprintf('SELECT * FROM user WHERE email = "%s" AND password = MD5("%s");', mysql_escape_string($email), mysql_escape_string($oldpassword));
  $result = mysql_query($q, $r);
  if ($result)
  {
    $tmp = mysql_fetch_row($result);
    if (!$tmp)
    {
      $msg = "Old password not match.";
    }
    else
    {
      $q = sprintf('UPDATE user SET password = MD5("%s") WHERE email = "%s";', mysql_escape_string($newpassword), mysql_escape_string($email));
      mysql_query($q, $r);

      $msg = "Password changed";
    }

    mysql_free_result($result);
  }

  sql_disconnect($r);
} while (0);

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

<div class="msg"><?= $msg ?>.</div>

</body>

</html>
