<?

require_once('/home/wwwadm/data/functions.inc.php');

$r = sql_connect();

$q = "SELECT COUNT(*) FROM groups;";
$result = mysql_query($q);

$num = -1;

if ($result)
{
  $tmp = mysql_fetch_row($result);
  $num = $tmp[0];
  mysql_free_result($result);
}

sql_disconnect($r);

?>
<table border="0" id="navbar" width="100%">
<tr>
	<td id="navbar_left"><a href="/">Group.NCTU.edu.tw</a> (目前共有 <?= $num ?> 個群組)</td>
	<td align="right" id="navbar_right">
<? if (isset($_SESSION['email'])) { ?>
		<a href="/manage.php">manage</a> | <a href="/logout.php">logout</a>
<? } else { ?>
		<a href="https://group.nctu.edu.tw/login.php">login</a> | <a href="/apply.php">apply</a>
<? } ?>
	</td>
</tr>
</table>
