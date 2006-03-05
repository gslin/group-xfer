<?

session_start();

if (!isset($_GET['e']) || $_GET['e'] == "" ||
    !isset($_GET['r']) || $_GET['r'] == "")
{
  header('Location: /');
  exit();
}

require_once('functions.inc.php');

$msg = "";

do
{
  $email = stripslashes($_GET['e']);
  $randomkey = stripslashes($_GET['r']);

  $r = sql_connect();

  $q = sprintf('SELECT password, applydate FROM apply WHERE email = "%s" AND randomkey = "%s";', mysql_escape_string($email), mysql_escape_string($randomkey));
  $result = mysql_query($q, $r);
  if ($result)
  {
    $tmp = mysql_fetch_row($result);
    if ($tmp)
    {
      $password = $tmp[0];
      $applydate = $tmp[1];

      /* Active account */
      $q = sprintf('INSERT user (email,password,authdate) VALUES ("%s","%s",NOW())', mysql_escape_string($email), mysql_escape_string($password));
      mysql_query($q, $r);

      $msg = "Account actived";
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
  <title>Group.NCTU.edu.tw (Active account)</title>
  <link rel="stylesheet" type="text/css" href="group.css" />
</head>

<body>

<? include('template/toolbar.inc.php'); ?>

<div class="msg"><?= $msg ?>.</div>

</body>

</html>
