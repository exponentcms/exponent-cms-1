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
//GREP:HARDCODEDTEXT

/* exdoc
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 * @node Subsystems:Permissions
 */
define("SYS_PERMISSIONS",1);

/* exdoc
 * UI Level of Preview - No management links of any kind should be shown.
 * @node Subsystems:Permissions
 */
define("UILEVEL_PREVIEW",0);
/* exdoc
* UI Level of Norma - Only normal management links (edit, delete, etc.) should be shown.
* @node Subsystems:Permissions
*/
define("UILEVEL_NORMAL",1);
/* exdoc
* UI Level of Permissions - Permission Management links (user and group perms) should be shown.
* @node Subsystems:Permissions
*/
define("UILEVEL_PERMISSIONS",2);
/* exdoc
* UI Level of Structure - All management links are shown.
* @node Subsystems:Permissions
*/
define("UILEVEL_STRUCTURE",3);

// Stores the permission data for the current user.  This should not be modified
// by anything outside of the permissions subsystem.
$pathos_permissions_r = array();

/* exdoc
 * Loads permission data from the database for the specified user.
 *
 * @param User $user THe user to load permissions for.
 * @node Subsystems:Permissions
 */
function pathos_permissions_load($user) {
	global $db, $pathos_permissions_r;
	// The $has_admin boolean will be flipped to true if the user has any administrate permission anywhere.
	// It will be used for figuring out the allowable UI levels.
	$has_admin = 0;
	// Clear the global permissions array;
	$pathos_permissions_r = array();
	
	if ($user == null) {
		// If the user is not logged in, they have no permissions.
		return;
	}
	if (!$user->is_acting_admin) {
		// Retrieve all of the explicit user permissions, by user id
		foreach ($db->selectObjects('userpermission','uid=' . $user->id) as $obj) {
			if ($obj->permission == 'administrate') $has_admin = 1;
			$pathos_permissions_r[$obj->module][$obj->source][$obj->internal][$obj->permission] = 1;
		}
		// Retrieve all of the implicit user permissions (by virtue of group membership).
		foreach ($db->selectObjects('groupmembership','member_id='.$user->id) as $memb) {
			foreach ($db->selectObjects('grouppermission','gid=' . $memb->group_id) as $obj) {
				if ($obj->permission == 'administrate') $has_admin = 1;
				$pathos_permissions_r[$obj->module][$obj->source][$obj->internal][$obj->permission] = 1;
			}
		}
		// Retrieve sectional admin status.
		// First, figure out what sections the user has permission to manage, through the navigationmodule permissions
		if (isset($pathos_permissions_r['navigationmodule']['']) && is_array($pathos_permissions_r['navigationmodule'][''])) {
			foreach ($pathos_permissions_r['navigationmodule'][''] as $id=>$perm_data) {
				if ($perm_data['manage'] == 1) {
					// The user is allowed to manage sections.
					// Pull in all stuff for the section, using section ref.
					$sectionrefs = $db->selectObjects('sectionref','is_original=1 AND section='.$id);
					foreach ($sectionrefs as $sref) {
						$sloc = pathos_core_makeLocation($sref->module,$sref->source);
						if (class_exists($sref->module)) { // In business, the module exists
							$perms = call_user_func(array($sref->module,'permissions'));
							if ($perms == null) $perms = array(); // For good measure, since some mods return no perms.
							foreach ($perms as $perm=>$name) {
								$pathos_permissions_r[$sloc->mod][$sloc->src][''][$perm] = 1;
							}
						}
					}
				}
			}
		}
	}
	pathos_sessions_set('permissions',$pathos_permissions_r);
	
	// Check perm stats for UI levels
	$ui_levels = array();
	if ($user->is_acting_admin) {
		$ui_levels = array('Preview','Normal','Permission Management','Structure Management');
	} else {
		if (count($pathos_permissions_r)) $ui_levels = array('Preview','Normal');
		if ($has_admin) $ui_levels[] = 'Permission Management';
		if (isset($pathos_permissions_r['containermodule']) && count($pathos_permissions_r['containermodule'])) $ui_levels[] = 'Structure Management';
	}
	pathos_sessions_set('uilevels',$ui_levels);
}

/* exdoc
 * This clears the cached permission data.  It does NOT
 * delete that data out of the database.
 * @node Subsystems:Permissions
 */
function pathos_permissions_clear() {
	pathos_sessions_unset("permissions");
}

/* exdoc
 * Initialize Permissions Subsystems
 * Pulls in the permission data from the session, for faster
 * access later.
 * @node Subsystems:Permissions
 */
function pathos_permissions_initialize() {
	global $pathos_permissions_r;
	$pathos_permissions_r = pathos_sessions_get("permissions");
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_permissions_getSourceUID($src) {
	if (substr($src,0,5) == "@uid_") {
		$t = split("_",$src);
		return $t[count($t)-1]+0;
	} else return 0;
}

/* exdoc
 * Looks to the permission data and checks to see
 * if the current user has been granted the given permission
 * on the granted the given location.  Recursive checking is
 * implemented through the modules getLocationHierarchy call,
 * for stupider permission checks (user permission assignment form)
 * Returns true if the permission is granted, false if it is not.
 *
 * @param string $permission The name of the permission to check
 * @param Object $location The location to check on.  This will be passed
 *	to getLocationHierarchy (defined by the module) for a full hierarchy
 *	of permissions.
 * @node Subsystems:Permissions
 */
function pathos_permissions_check($permission,$location) {
	global $pathos_permissions_r, $user;
	if ($user) {
		if ($user->is_acting_admin) return true;
		if (pathos_permissions_getSourceUID($location->src) == $user->id) return true;
	}
	if (is_callable(array($location->mod,"getLocationHierarchy"))) {
		foreach (call_user_func(array($location->mod,"getLocationHierarchy"),$location) as $loc) {
			if (isset($pathos_permissions_r[$loc->mod][$loc->src][$loc->int][$permission])) {
				return true;
			}
		}
		return false;
	} else {
		return (isset($pathos_permissions_r[$location->mod][$location->src][$location->int][$permission]));
	}
}

/* exdoc
 * Looks to the permission data and check to see
 * if the current user has been granted the given permission
 * on any instance of the module type. Returns true if the permission is granted, false if it is not.
 *
 * @param string $permission The name of the permission to check
 * @param string $module The classname of the module to check.
 * @node Subsystems:Permissions
 * @state BUGGY
 */
function pathos_permissions_checkOnModule($permission,$module) {
	global $pathos_permissions_r, $user;
	if ($user && $user->is_acting_admin) return true;
	return (isset($pathos_permissions_r[$module]) && (count($pathos_permissions_r[$module]) > 0));
}

/* exdoc
 * <i>Recursive checking took care of this.</i>
 * @state Deprecated
 */
function pathos_permissions_checkOnSource($module,$source) {
	global $pathos_permissions_r, $user;
	if ($user && $user->is_acting_admin) return true;
	return (isset($pathos_permissions_r[$module]) && isset($pathos_permissions_r[$module][$source]) && (count($pathos_permissions_r[$module][$source]) > 0));
}

/* exdoc
 * Checks to see if the given user has been given permission.  Handles
 * explicit checks (actually assigned to the user) or implicit checks
 * (assigned to a group the user belongs to).  Returns true if the permission is granted, false if it is not.
 *
 * @param User $user The user to check permission on
 * @param string $permission The name of the permission to check
 * @param Object $location The location to check on.
 * @param boolean $explicitOnly Whether to check for explit assignment or implicit.
 *
 * @node Subsystems:Permissions
 */
function pathos_permissions_checkUser($user,$permission,$location,$explicitOnly = false) {
	global $db;
	if ($user == null) return false;
	if ($user->is_acting_admin) return true;
	$explicit = $db->selectObject("userpermission","uid=" . $user->id . " AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "' AND permission='$permission'");
	if ($explicitOnly == true) return $explicit;
	
	$implicit = false;
	// Check locationHierarchy
	if (is_callable(array($location->mod,"getLocationHierarchy"))) {
		foreach (call_user_func(array($location->mod,"getLocationHierarchy"),$location) as $loc) {
			if ($db->selectObject("userpermission","uid=" . $user->id . " AND module='" . $loc->mod . "' AND source='" . $loc->src . "' AND internal='" . $loc->int . "' AND permission='$permission'")) {
				$implicit = true;
				break;
			}
		}
	}
	if (!$implicit) {
		$memberships = $db->selectObjects("groupmembership","member_id=".$user->id);
		foreach ($memberships as $memb) {
			if ($db->selectObject("grouppermission","gid=" . $memb->group_id . " AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "' AND permission='$permission'")) {
				$implicit = true;
				break;
			}
			$section_perms = $db->selectObjects('grouppermission','gid='.$memb->group_id." AND module='navigationmodule' AND permission='manage'");
			foreach ($section_perms as $perm) {
				if ($db->countObjects('sectionref','is_original=1 AND section='.$perm->internal." AND module='".$location->mod."' AND source='".$location->src."'")) {
					$implicit = true;
					break;
				}
			}
		}
	}
	if (!$implicit) {
		// Now check the section management
		$section_perms = $db->selectObjects('userpermission','uid='.$user->id." AND module='navigationmodule' AND permission='manage'");
		foreach ($section_perms as $perm) {
			if ($db->countObjects('sectionref','is_original=1 AND section='.$perm->internal." AND module='".$location->mod."' AND source='".$location->src."'")) {
				$implicit = true;
				break;
			}
		}
	}
	return ($implicit || $explicit);
}

/* exdoc
 * Checks to see if the given group has been given permission on a location.
 * Returns true if the permission is granted, false if it is not.
 *
 * @param Object $group The group to check
 * @param string $permission The name of the permission to check
 * @param Object $location The location to check on.
 *
 * @node Subsystems:Permissions
 */
function pathos_permissions_checkGroup($group,$permission,$location,$explicitOnly = false) {
	global $db;
	if ($group == null) return false;
	$explicit = $db->selectObject("grouppermission","gid=" . $group->id . " AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "' AND permission='$permission'");
	if ($explicitOnly == true) return $explicit;
	// Calculate implicit permissions
	$implicit = false;
	foreach ($db->selectObjects('grouppermission','gid='.$group->id." AND module='navigationmodule' AND permission='manage'") as $perm) {
		if ($db->countObjects('sectionref','is_original=1 AND section='.$perm->internal." AND module='".$location->mod."' AND source='".$location->src."'")) {
			$implicit = true;
			break;
		}
	}
	return ($implicit || $explicit);
}

/* exdoc
 * Grants the specified permission to the specified user, on the given location
 *
 * @param User $user The user to grant the permission to
 * @param string $permission The name of the permission to grant
 * @param Object $location The location to grant the permission on
 * @node Subsystems:Permissions
 */
function pathos_permissions_grant($user,$permission,$location) {
	if ($user !== null) {
		if (!pathos_permissions_checkUser($user,$permission,$location)) {
			$obj = null;
			$obj->uid = $user->id;
			$obj->module = $location->mod;
			$obj->source = $location->src;
			$obj->internal = $location->int;
			$obj->permission = $permission;
			
			global $db;
			$db->insertObject($obj,"userpermission");
		}
	}
}

/* exdoc
 * Grants the specified permission to the specified user group, on the given location
 *
 * @param Object $group The group to grant the permission to
 * @param string $permission The name of the permission to grant
 * @param Object $location The location to grant the permission on
 * @node Subsystems:Permissions
 */
function pathos_permissions_grantGroup($group,$permission,$location) {
	if ($group !== null) {
		if (!pathos_permissions_checkGroup($group,$permission,$location)) {
			$obj = null;
			$obj->gid = $group->id;
			$obj->module = $location->mod;
			$obj->source = $location->src;
			$obj->internal = $location->int;
			$obj->permission = $permission;
			
			global $db;
			$db->insertObject($obj,"grouppermission");
		}
	}
}

/* exdoc
 * Takes a permission away from a group, on a specific location.
 * This actually modifies the database.
 *
 * @param Object $group The group to remove the permission from
 * @param string $permission The name of the permission to revoke
 * @param Object $location The location to revoke the permission on
 * @node Subsystems:Permissions
 */
function pathos_permissions_revokeGroup($group,$permission,$location) {
	global $db;
	return $db->delete("grouppermission","gid=" . $group->id . " AND permission='$permission' AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "'");
}

/* exdoc
 * Takes a permission away from a user, on a specific location.
 * This actually modifies the database.
 *
 * @param User $user The user to remove the permission from
 * @param string $permission The name of the permission to revoke
 * @param Object $location The location to revoke the permission on
 * @node Subsystems:Permissions
 */
function pathos_permissions_revoke($user,$permission,$location) {
	global $db;
	return $db->delete("userpermission","uid=" . $user->id . " AND permission='$permission' AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "'");
}

/* exdoc
 * Removes all permissions from a user, on a specific location.
 *
 * @param User $user The user to remove all permissions from
 * @param Object $location The location to remove all permission on
 * @node Subsystems:Permissions
 */
function pathos_permissions_revokeAll($user,$location) {
	global $db;
	return $db->delete("userpermission","uid=" . $user->id . " AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "'");
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_permissions_revokeComplete($location) {
	global $db;
	$db->delete("userpermission","module='".$location->mod."' AND source='".$location->src."'");
	$db->delete("grouppermission","module='".$location->mod."' AND source='".$location->src."'");
	return true;
}

/* exdoc
 * Removes all permissions from a group, on a specific location.
 *
 * @param Object $group The group to remove all permissions from
 * @param Object $location The location to remove all permission on
 * @node Subsystems:Permissions
 */
function pathos_permissions_revokeAllGroup($group,$location) {
	global $db;
	return $db->delete("grouppermission","gid=" . $group->id . " AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "'");
}

/* exdoc
 * This call will force all active session to reload their
 * permission data.  This is useful if permissions are assigned
 * or revoked, and is required to see these changes.
 * @node Subsystems:Permissions
 */
function pathos_permissions_triggerRefresh() {
	global $db;
	$obj = null;
	$obj->refresh = 1;
	$db->updateObject($obj,"sessionticket","1"); // force a global refresh
	pathos_template_clear(SYS_TEMPLATE_CLEAR_USERS);
}

/* exdoc
 * This call will force all active sessions for the given user to
 * reload their permission data.  This is useful if permissions
 * are assigned or revoked, and is required to see these changes.
 * @node Subsystems:Permissions
 */
function pathos_permissions_triggerSingleRefresh($user) {
	global $db;
	$obj = null;
	$obj->refresh = 1;
	$db->updateObject($obj,"sessionticket","uid=".$user->id); // force a global refresh
	pathos_template_clear(SYS_TEMPLATE_CLEAR_USERS);
}

/* exdoc
 * Looks through the entire permissions database and finds all users who have been
 * assigned the specified permission on the specified location.  Returns an array
 * of user ids for users that matched criteria.
 *
 * @param string $permission The name of the permission to search by
 * @param Object $location The location to check on
 * @node Subsystems:Permissions
 */
function pathos_permissions_getUserIDsWithPermission($permission,$location) {
	global $db;
	$perms = $db->selectObjects("userpermission","module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "' AND permission='$permission'");
	$users = array();
	foreach ($perms as $perm) {
		$users[] = $perm->uid;
	}
	
	$groupperms = $db->selectObjects("grouppermission","module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "' AND permission='$permission'");
	foreach ($groupperms as $gperm) {
		foreach ($db->selectObjects("groupmember","group_id=".$gperm->gid) as $member) {
			if (!in_array($users,$member->member_id)) $users[] = $member->member_id;
		}
		
	}
	
	return $users;
}

?>