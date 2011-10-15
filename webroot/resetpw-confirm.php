<?

session_start();

if (!isset($_GET['e']) || $_GET['e'] == "" ||
    !isset($_GET['p']) || $_GET['p'] == "")
{
  header('Location: /');
  exit();
}

require_once('functions.inc.php');

do
{
  $email = stripslashes($_GET['e']);
  $pwdmd5 = stripslashes($_GET['p']);

  $r = sql_connect();

  $q = sprintf('SELECT * FROM user WHERE email = "%s" AND password = "%s";', mysql_escape_string($email), mysql_escape_string($pwdmd5));
  $result = mysql_query($q, $r);
  if ($result)
  {
    $tmp = mysql_fetch_row($result);
    if ($tmp)
    {
      /* Generate new password */
      $newpassword = sprintf("%x%x%x%x", rand(0, 2147483647), rand(0, 2147483647), rand(0, 2147483647), rand(0, 2147483647));
      $q = sprintf('UPDATE user SET password = MD5("%s") WHERE email = "%s";', $newpassword, $email);
      mysql_query($q, $r);

      /* mail */
      $body = "Your password has changed to:\n\n";
      $body .= sprintf('%s', $newpassword);
      $body .= "\n\n--\nGroup.NCTU.edu.tw\n";

      mail($email, 'New Password in Group.NCTU.edu.tw', $body);

      $msg = "New password has mailed to your mailbox";
    }
    else
    {
      $msg = "No such record";
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
  <title>Group.NCTU.edu.tw</title>
  <link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<div class="msg"><?= $msg ?>.</div>

</body>

</html>
