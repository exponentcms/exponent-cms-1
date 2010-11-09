<?php

#############################################################
# LINK MODULE
#############################################################
# Written by Eric Lestrade 
#
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

if (!defined('EXPONENT')) exit('');

global $db;

// Get the source link list module and actions to process
$linklistModule_id=$_POST['linklistModule'];
$export_links=isset($_POST['export_links']);
$export_permissions=isset($_POST['export_permissions']);
$linklistModule=$db->selectObject('container','id='.$linklistModule_id);
$nloc=unserialize($linklistModule->internal);

if (exponent_permissions_check('administrate',$nloc) && exponent_permissions_check('create',$nloc))
{
	if ($export_links)  
	{
		foreach($db->selectObjects('link','location_data=\''.serialize($loc).'\'') as $link)
		{
			$linklist_link=null;			
			$linklist_link->name=$link->name;
			$linklist_link->description=$link->description;
			$linklist_link->url=$link->url;
			$linklist_link->location_data=$linklistModule->internal;
			$linklist_link->rank = $db->max('linklist_link', 'rank', 'location_data', "location_data='".serialize($loc)."'");
			if ($linklist_link->rank == null) {
				$linklist_link->rank = 0;
			} else {
				$linklist_link->rank += 1;
			}
			$linklist_link->opennew=$link->opennew;
			$db->insertObject($linklist_link,'linklist_link');
		}
	}
	
	if($export_permissions)
	{
		$permission_list=linkmodule::permissions();
		$users_id=array();
		$groups_id=array();
		$groups=$db->selectObjects('group');

		// Read permissions
		foreach($permission_list as $permission=>$permission_title) {
			$users_id[$permission]=array();
			$groups_id[$permission]=array();
	
			// Handle individual permissions
			foreach(exponent_permissions_getUserIDsWithPermission($permission,$loc) as $user_id) {
				$user_one=$db->selectObject('user',"id=".$user_id);
				// Check if $user_one has direct permission (not through groups) to Link List Module.
				// non-direct permissions (through groups) are handled through group permission grants
				if(exponent_permissions_checkUser($user_one,$permission,$loc,true)) {
					$users_id[$permission][]=$user_id;	
				}
			}
			
			// Handle group permissions	
			foreach ($groups as $group) {
				if(exponent_permissions_checkGroup($group,$permission,$loc,true)) {
					$groups_id[$permission][]=$group->id;
				}
			}
		}
	
		// Write permissions	
		$users_new_id=array();
		$users_new_id['administrate']=$users_id['administrate'];
		$users_new_id['configure']=$users_id['configure'];
		$users_new_id['create']=$users_id['add'];
		$users_new_id['edit']=$users_id['edit'];
		$users_new_id['delete']=$users_id['edit'];
		foreach($users_new_id as $permission => $users_perm_id)
		{
			foreach($users_perm_id as $user_id)
			{
				$user_one=$db->selectObject('user',"id=".$user_id);
				exponent_permissions_grant($user_one,$permission,$nloc);
			}
		}
		$groups_new_id=array();
		$groups_new_id['administrate']=$groups_id['administrate'];
		$groups_new_id['configure']=$groups_id['configure'];
		$groups_new_id['create']=$groups_id['add'];
		$groups_new_id['edit']=$groups_id['edit'];
		$groups_new_id['delete']=$groups_id['edit'];
		foreach($groups_new_id as $permission => $groups_perm_id)
		{
			foreach($groups_perm_id as $group_id)
			{
				$group_one=$db->selectObject('group',"id=".$group_id);
				exponent_permissions_grantGroup($group_one,$permission,$nloc);
			}
		}
	}
}
exponent_permissions_triggerRefresh();			
exponent_sessions_clearAllUsersSessionCache('linklistodule');
exponent_flow_redirect();
?>