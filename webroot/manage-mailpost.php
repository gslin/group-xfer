<?

include('need_authorized.inc.php');
require('functions.inc.php');

$groupname = stripslashes($_POST['g']);

/* check */
do
{
  if (get_groupname_owner($groupname) != $email)
    break;

  $mailpost = stripslashes($_POST['m']);
  set_mailpost($groupname, $mailpost == 'Turn On' ? 'Y' : 'N');
} while (0);

header('Location: manage-group.php?g=' . urlencode($groupname));

