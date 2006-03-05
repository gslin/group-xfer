<?

session_start();
if (!isset($_SESSION['email']))
{
  header(sprintf('Location: /login.php?redirect=%s', urlencode($_SERVER['QUERY_STRING'])));
  exit();
}

$email = $_SESSION['email'];

?>
