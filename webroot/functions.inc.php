<?

require_once('share.inc.php');

function add_groupname_host($groupname, $host)
{
	$r = sql_connect();

	$groupname = strtolower($groupname);
	$host = strtolower($host);

	$q = sprintf('INSERT bbs (groupname,host) VALUES("%s","%s");', mysql_escape_string($groupname), mysql_escape_string($host));
	mysql_query($q, $r);

	sql_disconnect($r);
}

function big52utf8($str)
{
	return iconv('BIG5', 'UTF-8', $str);
}

function change_groupdesc($groupname, $description)
{
	$r = sql_connect();

	$q = sprintf('UPDATE groups SET description = "%s" WHERE groupname = "%s";', mysql_escape_string($description), mysql_escape_string($groupname));
	mysql_query($q, $r);

	sql_disconnect($r);
}

function check_groupname_host($groupname, $host)
{
	$r = sql_connect();

	$ok = 0;
	$q = sprintf('SELECT * FROM bbs WHERE groupname = "%s" AND host = "%s";', mysql_escape_string($groupname), mysql_escape_string($host));
	$result = mysql_query($q, $r);
	if ($result)
	{
		$tmp = mysql_fetch_row($result);
		if ($tmp)
			$ok = 1;

		mysql_free_result($result);
	}

	sql_disconnect($r);

	return $ok;
}

function create_groupname($groupname, $email)
{
	$r = sql_connect();

	$groupname = strtolower($groupname);

	$q = sprintf('INSERT groups (groupname,email,applydate) VALUES("%s","%s",NOW());', mysql_escape_string($groupname), mysql_escape_string($email));
	mysql_query($q);

	sql_disconnect($r);
}

function delete_groupname_host($groupname, $host)
{
	$r = sql_connect();

	$q = sprintf('DELETE FROM bbs WHERE groupname = "%s" AND host = "%s";', mysql_escape_string($groupname), mysql_escape_string($host));
	mysql_query($q, $r);

	sql_disconnect($r);
}

function get_description($groupname)
{
	$r = sql_connect();

	$description = '';

	$q = sprintf('SELECT description FROM groups WHERE groupname = "%s";', mysql_escape_string($groupname));
	$result = mysql_query($q, $r);
	if ($result)
	{
		$tmp = mysql_fetch_row($result);
		if ($tmp)
			$description = $tmp[0];

		mysql_free_result($result);
	}

	sql_disconnect($r);

	return $description;
}

function get_mailpost($groupname)
{
	$r = sql_connect();

	$mailpost = 'Unknown';

	$q = sprintf('SELECT mailpost FROM groups WHERE groupname = "%s";', mysql_escape_string($groupname));
	$result = mysql_query($q, $r);
	if ($result)
	{
		$tmp = mysql_fetch_row($result);
		if ($tmp)
			$mailpost = $tmp[0];

		mysql_free_result($result);
	}

	sql_disconnect($r);

	return $mailpost;
}

function get_groupname_list($email)
{
	$a[] = "";
	array_pop($a);

	$r = sql_connect();

	$q = sprintf('SELECT DISTINCT groupname FROM groups WHERE email = "%s" AND disable = "N" ORDER BY groupname;', mysql_escape_string($email));
	$result = mysql_query($q, $r);
	if ($result)
	{
		while ($tmp = mysql_fetch_row($result))
		{
			array_push($a, $tmp[0]);
		}

		mysql_free_result($result);
	}

	sql_disconnect($r);

	return $a;
}

function get_groupname_owner($groupname)
{
	$r = sql_connect();

	$email = "";

	$q = sprintf('SELECT email FROM groups WHERE groupname = "%s" AND disable = "N";', mysql_escape_string($groupname));
	$result = mysql_query($q, $r);
	if ($result)
	{
		$tmp = mysql_fetch_row($result);
		$email = $tmp[0];

		mysql_free_result($result);
	}

	sql_disconnect($r);

	return $email;
}

function get_host_list($groupname)
{
	$a[] = "";
	array_pop($a);

	$r = sql_connect();

	$q = sprintf('SELECT DISTINCT host FROM bbs WHERE groupname = "%s" ORDER BY host;', mysql_escape_string($groupname));
	$result = mysql_query($q, $r);
	if ($result)
	{
		while ($tmp = mysql_fetch_row($result))
		{
			array_push($a, $tmp[0]);
		}

		mysql_free_result($result);
	}

	sql_disconnect($r);

	return $a;
}

function html_normalize($str)
{
	# strip ANSI code and convert \n to <br />
	$str = preg_replace('/\e\[[\d;]*m/', '', $str);

	return nl2br(htmlentities($str, ENT_QUOTES, 'UTF-8'));
}

function set_mailpost($groupname, $m)
{
	$r = sql_connect();

	$q = sprintf('UPDATE groups SET mailpost = "%s" WHERE groupname = "%s";', mysql_escape_string($m), mysql_escape_string($groupname));
	mysql_query($q, $r);

	sql_disconnect($r);
}

function sql_connect()
{
	$r = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
	mysql_select_db(DB_DATABASE, $r);

	return $r;
}

function sql_disconnect($r)
{
	mysql_close($r);
}

function xml_normalize($str)
{
	# strip ANSI code and convert \n to <br />
	$str = preg_replace('/\e\[[\d;]*m/', '', $str);

	# just need to convert '<', '&', '>'.
	$str = preg_replace('/\</', '&lt;', $str);
	$str = preg_replace('/&/', '&amp;', $str);

	return $str;
}

?>
