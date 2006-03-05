<?

require('functions.inc.php');

if (!isset($_GET['g']) || !isset($_GET['n']))
{
	# !@#$%^ -_-
	header('Location: /');
	exit();
}

$group = stripslashes($_GET['g']);
$num = stripslashes($_GET['n']);

if (!preg_match('/^\w+$/', $group) || !preg_match('/^\d+$/', $num) || !get_groupname_owner($group))
{
	# trying crack ?
	header('Location: /');
	exit();
}

$groupname = sprintf('group.rss.%s', $group);

# open it
$imap = @imap_open(sprintf('{localhost:119/nntp}%s', $groupname), '', '');

# no such group
if (!$imap)
{
	header('Location: /');
	exit();
}

$header = @imap_headerinfo($imap, $num);
$body = @imap_body($imap, $num);

# no such article
if (!$header)
{
	header('Location: /');
	exit();
}

$from = big52utf8($header->fromaddress);
$body = big52utf8($body);

$title = $header->subject;
if (preg_match('/=\?.+\?=$/', $title))
	$title = iconv_mime_decode($title, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8');
else
	$title = big52utf8($title);

header('Content-type: text/xml');

print('<?xml version="1.0" encoding="UTF-8"?>');
print('<?xml-stylesheet type="text/xsl" href="/article.xsl"?>');

?>


<article>
	<title><?= xml_normalize($title); ?></title>
	<author><?= xml_normalize($from); ?></author>
	<description><?= xml_normalize($body); ?></description>
</article>

