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

<script type="text/javascript">
<!--
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-26333392-1']);
_gaq.push(['_trackPageview']);
(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
//-->
</script>

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
