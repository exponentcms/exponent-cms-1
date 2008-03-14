<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
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

/* exdoc
 * The definition of this constant lets other parts of the system know
 * that the subsystem has been included for use.
 * @node Subsystems:Permissions
 */
define('SYS_PERMISSIONS',1);

/* exdoc
 * UI Level of Preview - No management links of any kind should be shown.
 * @node Subsystems:Permissions
 */
define('UILEVEL_PREVIEW',0);
/* exdoc
* UI Level of Norma - Only normal management links (edit, delete, etc.) should be shown.
* @node Subsystems:Permissions
*/
define('UILEVEL_NORMAL',1);
/* exdoc
* UI Level of Permissions - Permission Management links (user and group perms) should be shown.
* @node Subsystems:Permissions
*/
define('UILEVEL_PERMISSIONS',2);
/* exdoc
* UI Level of Structure - All management links are shown.
* @node Subsystems:Permissions
*/
define('UILEVEL_STRUCTURE',3);

// Stores the permission data for the current user.  This should not be modified
// by anything outside of the permissions subsystem.
$exponent_permissions_r = array();

/* exdoc
 * Loads permission data from the database for the specified user.
 *
 * @param User $user THe user to load permissions for.
 * @node Subsystems:Permissions
 */
function exponent_permissions_load($user) {
	global $db, $exponent_permissions_r;
	// The $has_admin boolean will be flipped to true if the user has any administrate permission anywhere.
	// It will be used for figuring out the allowable UI levels.
	$has_admin = 0;
	// Clear the global permissions array;
	$exponent_permissions_r = array();

	if ($user == null) {
		// If the user is not logged in, they have no permissions.
		return;
	}
	if ($user->is_acting_admin == 0) {
		// Retrieve all of the explicit user permissions, by user id
		foreach ($db->selectObjects('userpermission','uid=' . $user->id) as $obj) {
			if ($obj->permission == 'administrate') $has_admin = 1;
			$exponent_permissions_r[$obj->module][$obj->source][$obj->internal][$obj->permission] = 1;
		}
		// Retrieve all of the implicit user permissions (by virtue of group membership).
		foreach ($db->selectObjects('groupmembership','member_id='.$user->id) as $memb) {
			foreach ($db->selectObjects('grouppermission','gid=' . $memb->group_id) as $obj) {
				if ($obj->permission == 'administrate') $has_admin = 1;
				$exponent_permissions_r[$obj->module][$obj->source][$obj->internal][$obj->permission] = 1;
			}
		}
		// Retrieve all of the implicit user permissions (by virtue of subscriptions).
		foreach ($db->selectObjects('subscriptions_users','user_id='.$user->id) as $memb) {
			foreach ($db->selectObjects('subscriptionpermission','subscription_id=' . $memb->subscription_id) as $obj) {
				if ($obj->permission == 'administrate') $has_admin = 1;
				$exponent_permissions_r[$obj->module][$obj->source][$obj->internal][$obj->permission] = 1;
			}
		}
		// Retrieve sectional admin status.
		// First, figure out what sections the user has permission to manage, through the navigationmodule permissions
		if (isset($exponent_permissions_r['navigationmodule']['']) && is_array($exponent_permissions_r['navigationmodule'][''])) {
			foreach ($exponent_permissions_r['navigationmodule'][''] as $id=>$perm_data) {
				if ($perm_data['manage'] == 1) {
					// The user is allowed to manage sections.
					// Pull in all stuff for the section, using section ref.
					//$sectionrefs = $db->selectObjects('sectionref','is_original=1 AND section='.$id);
					$sectionrefs = $db->selectObjects('sectionref','section='.$id);
					foreach ($sectionrefs as $sref) {
						$sloc = exponent_core_makeLocation($sref->module,$sref->source);
						if (class_exists($sref->module)) { // In business, the module exists
							$perms = call_user_func(array($sref->module,'permissions'));
							if ($perms == null) $perms = array(); // For good measure, since some mods return no perms.
							foreach ($perms as $perm=>$name) {
								$exponent_permissions_r[$sloc->mod][$sloc->src][''][$perm] = 1;
							}
						}
					}
				}
			}
		}
	}

	exponent_sessions_set('permissions',$exponent_permissions_r);

	// Check perm stats for UI levels
	$ui_levels = array();
	$i18n = exponent_lang_loadFile('subsystems/permissions.php');

	if ($user->is_acting_admin == 1) {
		$ui_levels = array(
			$i18n['preview'],
			$i18n['normal'],
			$i18n['perms'],
			$i18n['structure']
		);
	} else {
		if (count($exponent_permissions_r)) {
			$ui_levels = array(
				$i18n['preview'],
				$i18n['normal']
			);
		}
		if ($has_admin) {
			$ui_levels[] = $i18n['perms'];
		}
		if (isset($exponent_permissions_r['containermodule']) && count($exponent_permissions_r['containermodule'])) {
			$ui_levels[] = $i18n['structure'];
		}
	}
	exponent_sessions_set('uilevels',$ui_levels);
}

/* exdoc
 * This clears the cached permission data.  It does NOT
 * delete that data out of the database.
 * @node Subsystems:Permissions
 */
function exponent_permissions_clear() {
	exponent_sessions_unset("permissions");
}

/* exdoc
 * Initialize Permissions Subsystems
 * Pulls in the permission data from the session, for faster
 * access later.
 * @node Subsystems:Permissions
 */
function exponent_permissions_initialize() {
	global $exponent_permissions_r;
	$exponent_permissions_r = exponent_sessions_get("permissions");
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function exponent_permissions_getSourceUID($src) {
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
function exponent_permissions_check($permission,$location) {
	global $exponent_permissions_r, $user;
	if (isset($user) && $user != null) {		
		if (isset($user->is_acting_admin) && $user->is_acting_admin == 1) return true;
		//FJD - don't need this anymore
		//if (exponent_permissions_getSourceUID($location->src) == $user->id) return true;
	} else {
		return false;
	}

	if (!is_array($permission)) {
		$permission = array($permission);
	}

	$has_perm = false;

	if (is_callable(array($location->mod,"getLocationHierarchy"))) {
		foreach (call_user_func(array($location->mod,"getLocationHierarchy"),$location) as $loc) {
			foreach ($permission as $perm) {
				if (isset($exponent_permissions_r[$loc->mod][$loc->src][$loc->int][$perm])) {
					$has_perm = true;
					break;
				}
			}
		}
	} else {
		foreach ($permission as $perm) {
			if (isset($exponent_permissions_r[$location->mod][$location->src][$location->int][$perm])) {
				$has_perm = true;
				break;
			}
		}
	}
	if (!$has_perm && $location->mod != 'navigationmodule') {
		global $db;
		foreach ($db->selectObjects('sectionref',"is_original=1 AND module='".$location->mod."' AND source='".$location->src."'") as $secref) {
			if (exponent_permissions_check('manage',exponent_core_makeLocation('navigationmodule','',$secref->section))) {
				$has_perm = true;
				break;
			}
		}
	}

	return $has_perm;
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
function exponent_permissions_checkOnModule($permission,$module) {
	global $exponent_permissions_r, $user;
	if ($user && $user->is_acting_admin == 1) return true;
	return (isset($exponent_permissions_r[$module]) && (count($exponent_permissions_r[$module]) > 0));
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
function exponent_permissions_checkUser($user,$permission,$location,$explicitOnly = false) {
	global $db;
	if ($user == null) return false;
	if ($user->is_acting_admin == 1) return true;
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
	if (!$implicit && $location->mod != 'navigationmodule') {
		foreach ($db->selectObjects('sectionref',"is_original=1 AND module='".$location->mod."' AND source='".$location->src."'") as $secref) {
			if (exponent_permissions_checkUser($user,'manage',exponent_core_makeLocation('navigationmodule','',$secref->section))) {
				$implicit = true;
				break;
			}
		}

		// Now check the section management
		/*
		$section_perms = $db->selectObjects('userpermission','uid='.$user->id." AND module='navigationmodule' AND permission='manage'");
		foreach ($section_perms as $perm) {
			if ($db->countObjects('sectionref','is_original=1 AND section='.$perm->internal." AND module='".$location->mod."' AND source='".$location->src."'")) {
				$implicit = true;
				break;
			}
		}*/
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
function exponent_permissions_checkGroup($group,$permission,$location,$explicitOnly = false) {
	global $db;
	if ($group == null) return false;
	$explicit = $db->selectObject("grouppermission","gid=" . $group->id . " AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "' AND permission='$permission'");
	if ($explicitOnly == true) return $explicit;

	if (!$explicit){
		// Calculate implicit permissions if we dont' already have explicit perms
		$implicit = false;
		foreach ($db->selectObjects('grouppermission','gid='.$group->id." AND module='navigationmodule' AND permission='manage'") as $perm) {
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
function exponent_permissions_checkSubscription($subscription,$permission,$location,$explicitOnly = false) {
        global $db;
        if ($subscription == null) return false;
        $explicit = $db->selectObject("subscriptionpermission","subscription_id=" . $subscription->id . " AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "' AND permission='$permission'");
        if ($explicitOnly == true) return $explicit;

        if (!$explicit){
                // Calculate implicit permissions if we dont' already have explicit perms
                $implicit = false;
                foreach ($db->selectObjects('subscriptionpermission','subscription_id='.$subscription->id." AND module='navigationmodule' AND permission='manage'") as $perm) {
                        if ($db->countObjects('sectionref','is_original=1 AND section='.$perm->internal." AND module='".$location->mod."' AND source='".$location->src."'")) {
                                $implicit = true;
                                break;
                        }
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
function exponent_permissions_grant($user,$permission,$location) {
	if ($user !== null) {
		if (!exponent_permissions_checkUser($user,$permission,$location)) {
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
function exponent_permissions_grantGroup($group,$permission,$location) {
	if ($group !== null) {
		if (!exponent_permissions_checkGroup($group,$permission,$location)) {
			$obj = null;
			$obj->gid = $group->id;
			$obj->module = $location->mod;
			$obj->source = $location->src;
			$obj->internal = $location->int;
			$obj->permission = $permission;

			global $db;

      $db->delete("grouppermission", " gid='" . $obj->gid . "' module = '" . $obj->module . "' AND source='" . $obj->source . "' AND internal='" . $obj->internal . "'");
			$db->insertObject($obj,"grouppermission");
			echo "In groupGrant</br>";
		}
	}
}

/* exdoc
 * Grants the specified permission to the specified subscription, on the given location
 *
 * @param Object $sub The subscription to grant the permission to
 * @param string $permission The name of the permission to grant
 * @param Object $location The location to grant the permission on
 * @node Subsystems:Permissions
 */
function exponent_permissions_grantSubscription($sub,$permission,$location) {
        if ($sub !== null) {
                if (!exponent_permissions_checkGroup($group,$permission,$location)) {
                        $obj = null;
                        $obj->subscription_id = $sub->id;
                        $obj->module = $location->mod;
                        $obj->source = $location->src;
                        $obj->internal = $location->int;
                        $obj->permission = $permission;

                        global $db;

      			$db->delete("subscriptionpermission", " subscription_id='" . $obj->subscription_id . "' module = '" . $obj->module . "' AND source='" . $obj->source . "' AND internal='" . $obj->internal . "'");
                        $db->insertObject($obj,"subscriptionpermission");
                        //echo "In groupGrant</br>";
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
function exponent_permissions_revokeGroup($group,$permission,$location) {
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
function exponent_permissions_revoke($user,$permission,$location) {
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
function exponent_permissions_revokeAll($user,$location) {
	global $db;
	return $db->delete("userpermission","uid=" . $user->id . " AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "'");
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function exponent_permissions_revokeComplete($location) {
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
function exponent_permissions_revokeAllGroup($group,$location) {
	global $db;
	return $db->delete('grouppermission','gid=' . $group->id . " AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "'");
}

/* exdoc
 * Removes all permissions from a subscription, on a specific location.
 *
 * @param Object $subscription The subscription to remove all permissions from
 * @param Object $location The location to remove all permission on
 * @node Subsystems:Permissions
 */
function exponent_permissions_revokeAllSubscription($sub,$location) {
        global $db;
        return $db->delete('subscriptionpermissions','subscription_id=' . $sub->id . " AND module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "'");
}

/* exdoc
 * This call will force all active session to reload their
 * permission data.  This is useful if permissions are assigned
 * or revoked, and is required to see these changes.
 * @node Subsystems:Permissions
 */
function exponent_permissions_triggerRefresh() {
	global $db;
	$obj = null;
	$obj->refresh = 1;
	$db->updateObject($obj,'sessionticket','true'); // force a global refresh
}

/* exdoc
 * This call will force all active sessions for the given user to
 * reload their permission data.  This is useful if permissions
 * are assigned or revoked, and is required to see these changes.
 * @node Subsystems:Permissions
 */
function exponent_permissions_triggerSingleRefresh($user) {
	global $db;
	$obj = null;
	$obj->refresh = 1;
	$db->updateObject($obj,'sessionticket','uid='.$user->id); // force a global refresh
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
function exponent_permissions_getUserIDsWithPermission($permission,$location) {
	global $db;
	$perms = $db->selectObjects("userpermission","module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "' AND permission='$permission'");
	$users = array();
	foreach ($perms as $perm) {
		$users[] = $perm->uid;
	}

	$groupperms = $db->selectObjects("grouppermission","module='" . $location->mod . "' AND source='" . $location->src . "' AND internal='" . $location->int . "' AND permission='$permission'");
	foreach ($groupperms as $gperm) {
		foreach ($db->selectObjects("groupmembership","group_id=".$gperm->gid) as $member) {
			if (!in_array($member->member_id, $users)) $users[] = $member->member_id;
		}

	}

	return $users;
}

?>
