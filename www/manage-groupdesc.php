<?

include('need_authorized.inc.php');
require('functions.inc.php');

$description = stripslashes($_POST['d']);
$groupname = stripslashes($_POST['g']);

/* check */
do
{
  if (get_groupname_owner($groupname) != $email)
    break;

  change_groupdesc($groupname, $description);
} while (0);

header('Location: manage-group.php?g=' . urlencode($groupname));

?>
