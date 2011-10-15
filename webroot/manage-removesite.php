<?

include('need_authorized.inc.php');
require('functions.inc.php');

$groupname = stripslashes($_POST['g']);

/* check */
do
{
  if (get_groupname_owner($groupname) != $email)
    break;

  $host = stripslashes($_POST['r']);
  if (!check_groupname_host($groupname, $host))
    break;

  delete_groupname_host($groupname, $host);
} while (0);

header('Location: manage-group.php?g=' . urlencode($groupname));

