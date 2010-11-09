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
$import_links=isset($_POST['import_links']);
$import_permissions=isset($_POST['import_permissions']);
$cat=$_POST['categories'];
$linklistModule=$db->selectObject('container','id='.$linklistModule_id);
$oloc=unserialize($linklistModule->internal);
 
if (exponent_permissions_check('import',$loc))
{
	if ($import_links)  
	{			
		foreach($db->selectObjects('linklist_link','location_data=\''.$linklistModule->internal.'\'') as $linklist_link)
		{
			$link=null;
			$link->name=$linklist_link->name;
			$link->description=$linklist_link->description;
			$link->url=$linklist_link->url;
			$link->location_data=serialize($loc);
			$link->category_id=$cat;
			$link->rank = $db->max('link', 'rank', 'location_data', "location_data='".serialize($loc)."'");
			if ($link->rank == null) {
				$link->rank = 0;
			} else {
				$link->rank += 1;
			}			
			$link->opennew=$linklist_link->opennew;
			$db->insertObject($link,"link");
		}		
	}
	
	if($import_permissions)
	{
		$permission_list=linklistmodule::permissions();
		$users_id=array();
		$groups_id=array();
		$groups=$db->selectObjects('group');

		// Read permissions
		foreach($permission_list as $permission=>$permission_title) {
			$users_id[$permission]=array();
			$groups_id[$permission]=array();
	
			// Handle individual permissions
			foreach(exponent_permissions_getUserIDsWithPermission($permission,$oloc) as $user_id) {
				$user_one=$db->selectObject('user',"id=".$user_id);
				// Check if $user_one has direct permission (not through groups) to Link List Module.
				// non-direct permissions (through groups) are handled through group permission grants
				if(exponent_permissions_checkUser($user_one,$permission,$oloc,true)) {
					$users_id[$permission][]=$user_id;	
				}
			}
			
			// Handle group permissions	
			foreach ($groups as $group) {
				if(exponent_permissions_checkGroup($group,$permission,$oloc,true)) {
					$groups_id[$permission][]=$group->id;
				}
			}
		}
	
		// Write permissions	
		$users_new_id=array();
		$users_new_id['add']=$users_id['create'];
		$users_new_id['administrate']=$users_id['administrate'];
		$users_new_id['configure']=$users_id['configure'];
		$users_new_id['edit']=array_intersect($users_id['edit'],$users_id['delete']);
		foreach($users_new_id as $permission => $users_perm_id)
		{
			foreach($users_perm_id as $user_id)
			{
				$user_one=$db->selectObject('user',"id=".$user_id);
				exponent_permissions_grant($user_one,$permission,$loc);
			}
		}
		$groups_new_id=array();
		$groups_new_id['add']=$groups_id['create'];
		$groups_new_id['administrate']=$groups_id['administrate'];
		$groups_new_id['configure']=$groups_id['configure'];
		$groups_new_id['edit']=array_intersect($groups_id['edit'],$groups_id['delete']);
		foreach($groups_new_id as $permission => $groups_perm_id)
		{
			foreach($groups_perm_id as $group_id)
			{
				$group_one=$db->selectObject('group',"id=".$group_id);
				exponent_permissions_grantGroup($group_one,$permission,$loc);
			}
		}
	}
}
exponent_permissions_triggerRefresh();			
exponent_sessions_clearAllUsersSessionCache('linkmodule');
exponent_flow_redirect();
?>
