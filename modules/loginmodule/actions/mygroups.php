<?php

if (!defined('PATHOS')) exit('');

pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

$template = new Template('administrationmodule','_groupmanager',$loc);
if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
$groups = array();
foreach ($db->selectObjects('groupmembership','member_id='.$user->id.' AND is_admin=1') as $memb) {
	$groups[] = $db->selectObject('group','id='.$memb->group_id);
}
$template->assign('groups',$groups);
$template->assign('perm_level',1); // So we don't get the edit/delete links.
$template->output();

?>