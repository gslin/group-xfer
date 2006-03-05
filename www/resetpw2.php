<?

session_start();

require('functions.inc.php');

$msg = "";

if (!isset($_POST['email']) || !isset($_POST['securecode']))
{
  header('Location: resetpw.php');
  exit();
}

do
{
  if (!isset($_SESSION['securecode']) ||
      stripslashes($_POST['securecode']) != $_SESSION['securecode'])
  {
    $msg = "Secure code error";
    break;
  }

  $email = stripslashes($_POST['email']);

  /* find groupname & e-mail */
  $r = sql_connect();

  $q = sprintf('SELECT password FROM user WHERE email = "%s";', mysql_escape_string($email));
  $result = mysql_query($q, $r);

  $ok = 0;
  $pwdmd5 = "";

  if ($result)
  {
    $ok = 1;

    $tmp = mysql_fetch_row($result);
    $pwdmd5 = $tmp[0];

    mysql_free_result($result);
  }

  sql_disconnect($r);

  if ($pwdmd5 == "")
  {
    $msg = "Unknown record (newsgroup or email not found)";
    break;
  }

  /* mail */
  $body = "You may click the following link to reset your password:\n\n";
  $body .= sprintf('https://group.nctu.edu.tw/resetpw-confirm.php?e=%s&p=%s', urlencode($email), urlencode($pwdmd5));
  $body .= "\n\n--\nGroup.NCTU.edu.tw\n";

  mail($email, 'Reset Password Service in Group.NCTU.edu.tw', $body);
  /* mail('gslin@gslin.org', 'Reset Password Service in Group.NCTU.edu.tw', $body); */

  $msg = "Password sent, you might go to your mail box to click the link";
} while (0);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Group.NCTU.edu.tw (Reset Password)</title>
  <link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<div class="msg"><?= $msg ?>.</div>

</body>

</html>
