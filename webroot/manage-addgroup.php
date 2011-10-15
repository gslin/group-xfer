<?

include('need_authorized.inc.php');
require('functions.inc.php');

$newgroup = stripslashes($_POST['n']);

/* check */
do
{
  if (get_groupname_owner($newgroup) != "")
    break;

  if (!preg_match("/^\w{3,63}$/", $newgroup))
    break;

  create_groupname($newgroup, $email);
} while (0);

header('Location: manage.php');

