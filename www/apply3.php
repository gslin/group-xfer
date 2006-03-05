<?

session_start();

require_once('functions.inc.php');

if (!isset($_POST['email']) || $_POST['email'] == "" ||
    !isset($_POST['password']) || $_POST['password'] == "" ||
    !isset($_POST['password2']) || $_POST['password2'] == "" ||
    !isset($_POST['securecode']) || $_POST['securecode'] == "")
{
  header('Location: apply2.php');
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

  $password = stripslashes($_POST['password']);
  $password2 = stripslashes($_POST['password2']);

  if ($password != $password2)
  {
    $msg = "Password not match";
    break;
  }

  $email = stripslashes($_POST['email']);

  $r = sql_connect();

  $q = sprintf('SELECT * FROM user WHERE email = "%s";', mysql_escape_string($email));
  $result = mysql_query($q, $r);
  if ($result)
  {
    $tmp = mysql_fetch_row($result);
    if ($tmp)
    {
      $msg = "This e-mail had a account already";
    }
    else
    {
      $randomkey = sprintf("%x%x%x%x", rand(0, 2147483647), rand(0, 2147483647), rand(0, 2147483647), rand(0, 2147483647));
      $q = sprintf('INSERT apply (email,password,randomkey,applydate) VALUES ("%s",MD5("%s"),"%s",NOW())', mysql_escape_string($email), mysql_escape_string($password), $randomkey);
      mysql_query($q, $r);

      /* mail */
      $body = "You may click the following link to active your account:\n\n";
      $body .= sprintf('https://group.nctu.edu.tw/apply-confirm.php?e=%s&r=%s', urlencode($email), $randomkey);
      $body .= "\n\n--\nGroup.NCTU.edu.tw\n";

      mail($email, 'Active account in Group.NCTU.edu.tw', $body);

      $msg = "Please receive in your mail box and click url";
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
  <title>Group.NCTU.edu.tw (Apply Account)</title>
  <link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<div class="msg"><?= $msg ?>.</div>

</body>

</html>
