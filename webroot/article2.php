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

$xmldata = sprintf("<article><title>%s</title><author>%s</author><description>%s</description></article>", xml_normalize($title), xml_normalize($from), xml_normalize($body));

$arguments = array('/_xml' => $xmldata);
$xsltproc = xslt_create();
xslt_set_encoding($xsltproc, 'UTF-8');
print(xslt_process($xsltproc, 'arg:/_xml', "article.xsl", NULL, $arguments));

?>
