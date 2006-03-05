<?

require('functions.inc.php');
require_once('Net/NNTP/Client.php');

if (!isset($_GET['g'])) {
    header('Location: /');
    exit();
}

$group = stripslashes($_GET['g']);
$groupname = sprintf('group.rss.%s', $group);

# trying to crack ?
if (!preg_match('/^\w+$/', $group) || !get_groupname_owner($group)) {
    header('Location: /');
    exit();
}

# not exist
$nntp = new Net_NNTP_Client();
$ret = $nntp->connect('localhost');

if (!$ret || !$nntp->selectGroup($groupname)) {
    header('Location: /');
    exit();
}

$rssnum = 20;
if (isset($_GET['n'])) {
    $rssnum = $_GET['n'];
    if ($rssnum > 50)
	$rssnum = 50;
}

$lastnum = $nntp->last();
$firstnum = $lastnum - $rssnum;
if ($firstnum < 1)
    $firstnum = 1;

$description = get_description($group);
$title = $description;
$grouprss10 = sprintf('http://group.nctu.edu.tw/rss10/%s', $group);

header('Content-type: text/xml; charset="UTF-8"');

print('<?xml version="1.0" encoding="utf-8"?>');

?>

<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/">

<channel rdf:about="<?= $grouprss10 ?>">
	<title><?= $title ?></title>
	<link><?= $grouprss10 ?></link>
	<description><?= $description ?></description>

	<items>
		<rdf:Seq>
<?
for ($i = $firstnum; $i <= $lastnum; $i++)
{
	$link = sprintf('http://group.nctu.edu.tw/article/%s/%d', $group, $i);
?>
			<rdf:li resource="<?= $link ?>" />
<?
}
?>
		</rdf:Seq>
	</items>
</channel>

<?

for ($i = $lastnum; $i > $firstnum; $i--) {
    $link = sprintf('http://group.nctu.edu.tw/article/%s/%d', $group, $i);

    /* XXX: This only supported in Net::NNTP::Client 1.2.x */
    /* $head = $nntp->getHeader($i)->{'fields'}; */
    $title = '';
    $headers = $nntp->getHeaderRaw($i);
    foreach ($headers as $head) {
	if (substr($head, 0, 9) == 'Subject: ')
	    $title = substr($head, 9);
    }

    if (preg_match('/=\?.+\?=$/', $title))
	$title = iconv_mime_decode($head['subject'], ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8');
    else
	$title = big52utf8($title);

    $description = big52utf8(join("\n", $nntp->getBodyRaw($i)));
?>
<item rdf:about="<?= $link ?>">
	<title><![CDATA[<?= html_normalize($title) ?>]]></title>
	<link><?= $link ?></link>
	<description><![CDATA[<?= html_normalize($description) ?>]]></description>
</item>
<?
}
?>

</rdf:RDF>
