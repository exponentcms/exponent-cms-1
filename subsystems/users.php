<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

/**
 * Users Subsystem
 *
 * Manages User Accounts.  As a subsystem, the default database
 * implementation can be replaced with an LDAP user manager,
 * SMB authentication, etc.
 *
 * @package		Subsystems
 * @subpackage	Users
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS flag
 *
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 */
define("SYS_USERS",1);

$SYS_USERS_CACHE = array();

function pathos_users_includeProfileExtensions() {
	if (!defined("SYS_USERS_EXT")) {
		define("SYS_USERS_EXT",1);
		// start the includes.
		$ext_dir = BASE."subsystems/users/profileextensions";
		foreach (pathos_users_listExtensions() as $file) {
			if (is_readable("$ext_dir/$file.php")) include_once("$ext_dir/$file.php");
		}
	}
}

function pathos_users_listExtensions() {
	$ext = array();
	$ext_dir = BASE."subsystems/users/profileextensions";
	if (is_readable($ext_dir)) {
		$dh = opendir($ext_dir);
		while (($file = readdir($dh)) !== false) {
			if (is_file("$ext_dir/$file") && is_readable("$ext_dir/$file") && substr($file,-13,13) == "extension.php") {
				$ext[substr($file,0,-4)] = substr($file,0,-4);
			}
		}
	}
	
	return $ext;
}

function pathos_users_listUnusedExtensions() {
	global $db;
	$exts = $db->selectObjects("profileextension");
	$used = array();
	foreach ($exts as $e) {
		$used[$e->extension] = $e->extension;
	}
	return array_diff_assoc(pathos_users_listExtensions(),$used);
}

function pathos_users_clearDeletedExtensions() {
	global $db;
	foreach ($db->selectObjects("profileextension") as $e) {
		if (!is_readable(BASE."subsystems/users/profileextensions/".$e->extension.".php")) {
			$db->delete("profileextension","id=".$e->id);
			$db->decrement("profileextension","rank",1,"rank >= ". $e->rank);
		}
	}
}

function pathos_users_getFullProfile($user) {
	// Profile extensions
	pathos_users_includeProfileExtensions();
	pathos_users_clearDeletedExtensions();
	global $db;
	$exts = $db->selectObjects("profileextension");
	if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
	usort($exts,"pathos_sorting_byRankAscending");
	foreach ($exts as $ext) {
		$user = call_user_func(array($ext->extension,"getProfile"),$user);
	}
	return $user;
}

function pathos_users_login($username, $password) {
	global $db;
	$user = $db->selectObject("user","username='" . $username . "'");
	if ($user && ($user->is_admin || !$user->is_locked) && $user->password == md5($password)) {
		$user = pathos_users_getFullProfile($user);
		pathos_sessions_login($user);
	}
}

function pathos_users_logout() {
	pathos_sessions_logout();
}

function pathos_users_form($user = null) {
	$form = new form();
	if (isset($user->id)) {
		$form->meta("id",$user->id);
	} else {
		$user->firstname = "";
		$user->lastname = "";
		$user->email = "";
		$user->recv_html = 1;
		$form->register("username","Desired Username",new textcontrol());
		$form->register("pass1","Password", new passwordcontrol());
		$form->register("pass2","Confirm",new passwordcontrol());
		$form->register(uniqid(""),"",new htmlcontrol("<br />"));
	}
	
	$form->register("firstname","First Name",new textcontrol($user->firstname));
	$form->register("lastname","Last Name",new textcontrol($user->lastname));
	$form->register(uniqid(""),"",new htmlcontrol("<br />"));
	$form->register("email","Email",new textcontrol($user->email));
	$form->register("recv_html","Receive HTML Email", new checkboxcontrol($user->recv_html,true));
	$form->register(uniqid(""),"",new htmlcontrol("<br />"));
	
	// Profile Integrations
	pathos_users_clearDeletedExtensions();
	pathos_users_includeProfileExtensions();
	$tmpu = pathos_users_getFullProfile($user);
	global $db;
	$exts = $db->selectObjects("profileextension");
	usort($exts,"pathos_sorting_byRankAscending");
	foreach ($exts as $ext) {
		$form = call_user_func(array($ext->extension,"modifyForm"),$form,$tmpu);
	}
	
	$form->register("submit","",new buttongroupcontrol("Save"));
	return $form;
}

function pathos_users_groupForm($group = null) {
	$form = new form();
	if ($group) {
		$form->meta("id",$group->id);
	} else {
		$group->name = "";
		$group->description = "";
		$group->inclusive = false;
	}
	$form->register("name","Name",new textcontrol($group->name));
	$form->register("description","Description",new texteditorcontrol($group->description));
	$form->register("inclusive","Default?",new checkboxcontrol($group->inclusive));
	$form->register("submit","",new buttongroupcontrol("Save"));
	
	return $form;
}

function pathos_users_update($formvalues, $user = null) {
	$user->firstname = $formvalues['firstname'];
	$user->lastname = $formvalues['lastname'];
	$user->email = $formvalues['email'];
	$user->recv_html = isset($formvalues['recv_html']);
	return $user;
}

function pathos_users_saveProfileExtensions($formvalues,$user,$is_new) {
	// Profile Integrations
	pathos_users_includeProfileExtensions();
	global $db;
	$exts = $db->selectObjects("profileextension");
	usort($exts,"pathos_sorting_byRankAscending");
	foreach ($exts as $ext) {
		$user = call_user_func(array($ext->extension,"saveProfile"),$formvalues,$user,$is_new);
	}
	return $user;
}

function pathos_users_groupUpdate($formvalues, $group = null) {
	$group->name = $formvalues['name'];
	$group->description = $formvalues['description'];
	$group->inclusive = isset($formvalues['inclusive']);
	return $group;
}

function pathos_users_create($formvalues) {
	$user = pathos_users_update($formvalues,null);
	$user->username = $formvalues['username'];
	$user->password = md5($formvalues['pass1']);
	global $db;
	$user->id = $db->insertObject($user,"user");
	
	// Group Memberships for Default Groups
	$memb = null;
	$memb->member_id = $user->id;
	foreach($db->selectObjects("group","inclusive='1'") as $g) {
		$memb->group_id = $g->id;
		$db->insertObject($memb,"groupmembership");
	}
	
	return $user;
}

function pathos_users_userManagerFormTemplate($template) {
	global $db;
	$users = $db->selectObjects("user");
	
	if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
	if (!function_exists("pathos_sorting_byLastFirstAscending")) {
		function pathos_sorting_byLastFirstAscending($a,$b) {
			return strnatcmp($a->lastname . ", ". $a->firstname,$b->lastname . ", ". $b->firstname);
		}
	}
	usort($users,"pathos_sorting_byLastFirstAscending");
	for ($i = 0; $i < count($users); $i++) {
		$users[$i] = pathos_users_getUserById($users[$i]->id);
	}
	
	$template->assign("users",$users);
	$template->assign("blankpass",md5(""));
	
	return $template;
}

function pathos_users_groupManagerFormTemplate($template) {
	global $db;
	$groups = $db->selectObjects("group");
	
	function sortTMPGroups($a,$b) {
		return strnatcmp($a->name,$b->name);
	}
	usort($groups,"sortTMPGroups");
	
	$template->assign("groups",$groups);
	
	return $template;
}

function pathos_users_clearPassword($uid) {
	global $db;
	$user = null;
	$user->password = md5("");
	$db->updateObject($user,"user","id=$uid AND is_admin='0'");
}

function pathos_users_delete($uid) {
	global $db;
	$u = $db->selectObject("user","id=$uid");
	if ($u && $u->is_admin == 0) {
		$db->delete("user","id=$uid AND is_admin='0'");
		$db->delete("groupmembership","member_id=".$uid);
		$db->delete("userpermission","uid=".$uid);
	}
}

function pathos_users_groupDelete($gid) {
	global $db;
	$db->delete("group","id=".$gid);
	$db->delete("groupmembership","group_id=".$gid);
	$db->delete("grouppermission","gid=".$gid);
}

function pathos_users_getUserById($uid) {
	global $SYS_USERS_CACHE;
	if (!isset($SYS_USERS_CACHE[$uid])) {
		global $db;
		$SYS_USERS_CACHE[$uid] = $db->selectObject("user","id=$uid");
	}
	return $SYS_USERS_CACHE[$uid];
}

/**
 * Return all Users in the System
 *
 * Gets a list of all user accounts in the system
 * @return associated array of userids to user objects.
 */
function pathos_users_getAllUsers($allow_admin=1,$allow_normal=1) {
	global $db;
	if ($allow_admin && $allow_normal) return $db->selectObjects("user");
	else if ($allow_admin) return $db->selectObjects("user","is_admin='1'");
	else if ($allow_normal) return $db->selectObjects("user","is_admin='0'");
	else return array();
}

function pathos_users_getGroupById($gid) {
	global $db;
	return $db->selectObject("group","id=$gid");
}

function pathos_users_getUserByName($name) {
	global $db;
	return $db->selectObject("user","username='$name'");
}

function pathos_users_getGroupByName($name) {
	global $db;
	return $db->selectObject("group","name='$name'");
}

function pathos_users_getAllGroups($allow_exclusive=1,$allow_inclusive=1) {
	global $db;
	if ($allow_exclusive && $allow_inclusive) return $db->selectObjects("group");
	else if ($allow_exclusive) return $db->selectObjects("group","inclusive = 0");
	else if ($allow_inclusive) return $db->selectObjects("group","inclusive = 1");
	else return array();
}

function pathos_users_getGroupsForUser($u, $allow_exclusive=1, $allow_inclusive=1) {
	global $db;
	$groups = array();
	if (!$u) return $groups;
	if ($u->is_admin) return pathos_users_getAllGroups($allow_exclusive,$allow_inclusive);
	foreach ($db->selectObjects("groupmembership","member_id=".$u->id) as $m) {
		$o = $db->selectObject("group","id=".$m->group_id);
		if ($o->inclusive == 1 && $allow_inclusive) $groups[] = $o;
		if ($o->inclusive == 0 && $allow_exclusive) $groups[] = $o;
	}
	return $groups;
}

function pathos_users_getUsersInGroup($g) {
	global $db;
	$users = array();
	foreach ($db->selectObjects("groupmembership","group_id=".$g->id) as $m) {
		$users[] = $db->selectObject("user","id=".$m->member_id);
	}
	return $users;
}

function pathos_users_saveUser($user) {
	if ($user == null) return;
	global $db;
	
	$tmp = null;
	$tmp->username = $user->username;
	$tmp->password = $user->password;
	$tmp->is_admin = $user->is_admin;
	$tmp->is_locked = $user->is_locked;
	$tmp->firstname = $user->firstname;
	$tmp->lastname = $user->lastname;
	$tmp->email = $user->email;
	
	if (isset($user->id)) {
		$tmp->id = $user->id;
		$db->updateObject($tmp,"user");
	}
	else $user->id = $db->insertObject($tmp,"user");
	return $user;
}

function pathos_users_saveGroup($group) {
	if ($group == null) return;
	global $db;
	if ($group->id) $db->updateObject($group,"group");
	else $db->insertObject($group,"group");
}

function pathos_users_changepass($pass, $user = null) {
	if (!$user) global $user;
	if (!$user) return;
	$u = null;
	$u->id = $user->id;
	$u->password = md5($pass);
	global $db;
	$db->updateObject($u,"user","id=".$user->id);
}

?>