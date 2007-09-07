<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright 2006 Maxim Mueller
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
 * @node Subsystems:Users
 */
define('SYS_USERS',1);

// This global array belongs exclusively to the Users subsystem, and is used to cache
// users as they are retrieved, to help out with performance when doing a lot of
// work with user accounts and profile information.
$SYS_USERS_CACHE = array();

/* exdoc
 * This function includes the class files for user profile extensions.  Profile extensions
 * are stored in the subsystems/users/profileextensions/ directory.  The class name is
 * the name of the file, minus the '.php' extension.
 *
 * This function can be called more than once, without performing any needless
 * processing.  It will only include the profile extensions once per script request.
 * @node Subsystems:Users
 */
function exponent_users_includeProfileExtensions() {
	// Check for an explicit constant definition, to keep from re-initializing stuff.
	if (!defined('SYS_USERS_EXT')) {
		// Define SYS_USERS_EXT (doesn't matter to what) so that this part of the
		// function will never execute again.
		define('SYS_USERS_EXT',1);
		// Store the base directory, for readability later on.
		$ext_dir = BASE.'subsystems/users/profileextensions';
		foreach (exponent_users_listExtensions() as $file) {
			// Include each User Extension Profile class file, if it is readable by the web server.
			if (is_readable("$ext_dir/$file.php")) {
				include_once("$ext_dir/$file.php");
			}
		}
	}
}

/* exdoc
 * This method queries the subsystems/users/profileextensions/ directory for
 * a list of installed User Profile Extension class files.  It returns the class
 * names, in an array. This function does not take into account whether or not
 * the administrator has activated or deactivated certain profiles.
 *
 * In order to be picked up by this function (and therefore the rest of the Users
 * subsystem) a profile extension filename MUST end in 'extension.php', and
 * therefore the classname MUST end in 'extension'.
 *
 * Returns an array of installed (but not necessarily activated) user profile
 *    extenions.  If this array is empty, either Exponent encountered a problem
 *    reading the Profile Extensions directory, or there were simply no extensions
 *    to list.
 * @node Subsystems:Users
 */
function exponent_users_listExtensions() {
	// A holding array to keep the extension class names we find.  This will be returned
	// to the caller when we are completely done.
	$ext = array();
	// Store the base directory in a variable, for readability later on.
	$ext_dir = BASE.'subsystems/users/profileextensions';
	// Profiles directory has to be readable by the web server.
	if (is_readable($ext_dir)) {
		$dh = opendir($ext_dir);
		// For each directory entry we find.
		while (($file = readdir($dh)) !== false) {
			if (is_file("$ext_dir/$file") && is_readable("$ext_dir/$file") && substr($file,-13,13) == 'extension.php') {
				// Only include readable, regular files that end in 'extension.php'

				// Store the same data in the key and the value of the array.  This is safe
				// since we are getting file names from a single directory.  The .php
				// suffix needs to be stripped out.
				$ext[substr($file,0,-4)] = substr($file,0,-4);
			}
		}
	}
	// Return the list to the calling scope.  If something went wrong, an empty
	// array will be returned.
	return $ext;
}

/* exdoc
 * Looks through the database and pulls in a list of all User Profile
 * Extensions that have not been explicitly activated by the administrator.
 *
 * Returns an array of unused extensions, identical in structure /
 *    format to the return value of exponent_users_listExtensions().
 * @node Subsystems:Users
 */
function exponent_users_listUnusedExtensions() {
	// Pull database object in from global scope.
	global $db;
	// Retrieve Profile Extension Activation states from database.  If an extension
	// is activated, there will be a record for it in the profileextension table.  Inactive
	// Profile Extensions have no records in this table.
	$exts = $db->selectObjects('profileextension');
	$used = array();
	foreach ($exts as $e) {
		// Populate an array with the activated extension names.
		$used[$e->extension] = $e->extension;
	}
	// Return the difference between the full list and the activated list, and we should
	// get the inactive extensions only.
	return array_diff_assoc(exponent_users_listExtensions(),$used);
}

/* exdoc
 * This function removes records from the profileextension table for
 * User Profile Extensions that are no longer found in the system (i.e.
 * have been deleted from subsystems/users/profileextensions).  This
 * safety check allows other profile extensions code to blindly trust the
 * contents of the profileextension table.
 * @node Subsystems:Users
 */
function exponent_users_clearDeletedExtensions() {
	// Pull in database object from the global scope.
	global $db;
	foreach ($db->selectObjects('profileextension') as $e) {
		// For every activated profile extension, check to see if its class file is still readable / exists.
		if (!is_readable(BASE.'subsystems/users/profileextensions/'.$e->extension.'.php')) {
			// An active profile extension was manually uninstalled from the system.  Remove the non-existent
			// profile extension from the database.
			$db->delete('profileextension','id='.$e->id);
			// Because profile extensions are kept in a specific order, and we need to keep that
			// order intact, a delete requires us to re-rank all profile extension records found AFTER
			// the newly deleted record.
			$db->decrement('profileextension','rank',1,'rank >= '. $e->rank);
		}
	}
}

/* exdoc
 * This function looks at the configuration of User Profile Extensions
 * (which ones are active, and which ones aren't) and then assembles
 * a full profile object for the passed user object.
 *
 * Returns the initial user object, with extra information
 *    added to it for any available and active Profile Extensions.
 *
 * @param Object $user The user object to get a full profile for.
 * @node Subsystems:Users
 */
function exponent_users_getFullProfile($user) {
	// Sanity Checks.  First we need to ensure that the profile extension classes
	// have been included and are ready for use.  Then we delete all uninstalled
	// profile extensions from the active profileextensions table.
	exponent_users_includeProfileExtensions();
	exponent_users_clearDeletedExtensions();
	// Pull the database object in from the global scope.
	global $db;
	// Get a list of all active Profile Extenions.
	$exts = $db->selectObjects('profileextension');
	// Initialize the Sorting Subsystem, if this hasn't previously been done.
	if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
	// Sort the active extension objects by their rank,
	usort($exts,'exponent_sorting_byRankAscending');
	foreach ($exts as $ext) {
		// Update this part of the User's profile.
		$user = call_user_func(array($ext->extension,'getProfile'),$user);
	}
	// let's also return what groups they are in as well so we don't have to hunt for them later <- Phillip Ball : sept 3 2007
	$user->groups = exponent_users_getGroupsForUser($user);
	
	// At this point, the $user object has been augmented with new attributes defined
	// by whatever Profile Extensions have been enabled.
	return $user;
}

/* exdoc
 * This function is in place as a login hook, so that future (more advanced and
 * 'unconventional') implementations of the Users Subsystem can run some
 * custom code to handle a login.
 *
 * This function is expected to generate the appropriate user object, run authentication
 * checks, and then call exponent_sessions_login($user) to initialize login session data.
 *
 * @param string $username The username that the visitor is logging in with.
 * @param string $password The password that the visitor has supplied as credentials
 * @node Subsystems:Users
 */
function exponent_users_login($username, $password) {
	// This specific implementation of the Users Subsystem stores user information in the
	// same database as all other website content.  Therefore, we must pull in the database
	// object from the global scope.
	global $db;
	
	// Retrieve the user object from the database.  Note that this may be null, if the username is
	// non-existent.
	$user = $db->selectObject('user', "username='" . $username . "'");
	//eDebug(debug_backtrace());
	// Check to make sure that the username exists ($user is not null)
	//echo "Here:";
	//eDebug($user);
	if(isset($user) && $user != null) {
		//DEPRECATED: scheduled for removal
		if ($user->is_admin == 1 || $user->is_acting_admin == 1) {
			// User is an admin.  Update is_acting_admin, just in case.
			// This can be removed as soon as 0.95 is deprecated.
			$user->is_acting_admin = 1;
		} else $user->is_acting_admin = 0;
		
		// Check MAINTENANCE_MODE, and only allow admins or acting admins in.
		if (!MAINTENANCE_MODE || (MAINTENANCE_MODE && $user->is_acting_admin != 0 )){
			// check whether the password is correct,
			// and that the account is either not locked, or an admin account (account locking doesn't
			// apply to administrators.
			if (($user->is_admin == 1 || $user->is_locked == 0) && $user->password == md5($password)) {
				//Update the last login timestamp for this user.
                $user->last_login = time();
                $db->updateObject($user, 'user');

				// Retrieve the full profile, complete with all Extension data.
				$user = exponent_users_getFullProfile($user);
				
				// Call on the Sessions subsystem to log the user into the site.
				exponent_sessions_login($user);
			}
		}	
	}
}

/* exdoc
 * This function is in place as a logout hook, so that future (more advanced and
 * 'unconventional') implementaitons of the Users Subsystem can run some
 * custom code to handle a login.
 *
 * This function is expected to call exponent_sessions_logout(), so that the session
 * can be cleaned up for the next user.
 * @node Subsystems:Users
 */
function exponent_users_logout() {
	exponent_sessions_logout();
}

/* exdoc
 * This function returns a form for creating a new user or editing
 * an existing user.  It belongs in the users subsystem so that different
 * implementations of the users subsystem can define its own form structure.
 *
 * @param Object $user The user object if the form is for editing a user, and nul
 *    for a new user form.
 * @node Subsystems:Users
 */
function exponent_users_form($user = null) {
	$form = user::form($user);

	$form->unregister('submit');

	// Pull in form data for all active profile extensions.
	// First, we have to clear delete extensions, so that we don't try to include
	// or use previously active extensions that have been disabled.
	exponent_users_clearDeletedExtensions();
	// Include all class files for active profile extensions.
	exponent_users_includeProfileExtensions();
	// Store the users full profile in a temporary object.
	$tmpu = exponent_users_getFullProfile($user);
	// Pull the database object in from the global scope.
	global $db;
	// Retrieve a list of the active profile extensions, and sort them by rank so that
	// the form controls get added to the form in order.
	$exts = $db->selectObjects('profileextension');
	if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
	usort($exts,'exponent_sorting_byRankAscending');
	foreach ($exts as $ext) {
		// Modify the form object by passing it through each profile extension,
		// each of which may or may not register new controls.
		$form = call_user_func(array($ext->extension,'modifyForm'),$form,$tmpu);
	}

	// Add the submit button and return the complete form object to the caller.
	$i18n = exponent_lang_loadFile('subsystems/users.php');
	$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
	return $form;
}

/* exdoc
 * This function returns a form for creating a new group or
 * editing an existing group.  It belongs in the users subsystem so that
 * different implementations can define their own form structure.
 *
 * @param Object $group The group object if the form is for editing a group, and
 *    null for a new group form.
 * @node Subsystems:Users
 */
function exponent_users_groupForm($group = null) {
	// DEPRECATE
	return group::form($group);
}

/* exdoc
 * This method returns an updated user object, using the form
 * data from exponent_users_userForm(). Returns the updated user object.
 *
 * @param Array $formvalues The POSTed data, for pulling new values from.
 * @param Object $user The user object to update.  This can be null.
 * @node Subsystems:Users
 */
function exponent_users_update($formvalues, $u = null) {
	$u = user::update($formvalues,$u);
	global $user;
	// Set the is_acting_admin flag.  There a re a few ways that this should be done.
	// If the admin is editing or creating a user, solely check if isset()
	//If the user is editing themselves, don't set it.
	// If the user is signing up, set it to 0.
	if ($user->is_admin == 1) {
		$u->is_acting_admin = (isset($formvalues['is_acting_admin']) ? 1 : 0);
	} else if (!isset($u->id)) {
		$u->is_acting_admin = 0;
	}
	return $u;
}

/* exdoc
 * This function runs through the list of active profile extensions
 * and calls the saveProfile method of each, to save data that the
 * user entered in 'extra' fields.
 *
 * @param Array $formvalues The POSTed data, for pulling the values from.
 * @param Object $user The user that this data is attached to.  The $user object will
 *    be used by each profile extension for associating data.
 * @param bool $is_new A flag indicating if the $user object has been saved to the database
 *    previously or not.  True if the user object has yet to be saved (user just created account)
 *    and false if the account already existed prior to the edit.
 * @node Subsystems:Users
 */
function exponent_users_saveProfileExtensions($formvalues,$user,$is_new) {
	// Pull in form data for all active profile extensions.
	// First, we have to clear delete extensions, so that we don't try to include
	// or use previously active extensions that have been disabled.
	exponent_users_clearDeletedExtensions();
	// Include all class files for active profile extensions.
	exponent_users_includeProfileExtensions();
	// Pull in the database object from the global scope.
	global $db;
	// Read in all of the active profile extensions and sort them (which may actually be
	// unnecessary.)
	$exts = $db->selectObjects('profileextension');
	usort($exts,'exponent_sorting_byRankAscending');
	foreach ($exts as $ext) {
		// Call the saveProfile method of each profile extension class, which will return the modified user object.
		$user = call_user_func(array($ext->extension,'saveProfile'),$formvalues,$user,$is_new);
	}
	// Return the full user object to the caller.
	return $user;
}

/* exdoc
 * This method returns an updated group object, using the form
 * data from exponent_users_groupForm().  Returns the updated group object.
 *
 * @param Array $formvalues The POSTed data, for pulling new values from.
 * @param Object $group The group object to update.  This can be null.
 * @node Subsystems:Users
 */
function exponent_users_groupUpdate($formvalues, $group = null) {
	// DEPRECATE
	return group::update($formvalues,$group);
}

/* exdoc
 * This function saves a user to whatever storage medium the subsystem uses.
 * For the default implemenetation, this is the database, but the method is defined
 * to give future implementations the option to use something else (like an LDAP
 * directory, or a KDC).  Returns the created user object, complete with id.
 *
 * @param Array $formvalues The POSTed data received from the New User form.
 * @node Subsystems:Users
 */
function exponent_users_create($formvalues) {
	// Update the user object (at this point we are not dealing with profile
	// extensions, just the basic object).
	$u = exponent_users_update($formvalues,null);

	// The username is not included in the update method, so we define it here.
	$u->username = $formvalues['username'];

	// Make an md5 checksum hash of the password for storage.  That way no
	// one can know a password without being told.
	$u->password = md5($formvalues['pass1']);

	// Set the acting admin flag if we need to.
	global $user;
	$u->is_acting_admin = ((isset($formvalues['is_acting_admin']) && $user->is_admin == 1) ? 1 : 0);

	// Insert the user object into the database, and save the ID.
	global $db;
	$u->created_on = time();
	$u->id = $db->insertObject($u,'user');

	// Calculate Group Memeberships for newly created users.  Any groups that
	// are marked as 'inclusive' automatically pick up new users.  This is the part
	// of the code that goes out, finds those groups, and makes the new user a member
	// of them.
	$memb = null;
	$memb->member_id = $u->id;
	// Also need to process the groupcodes, for promotional signup
	$code_where = '';
	if (isset($formvalues['groupcode']) && $formvalues['groupcode'] != '') {
		$code_where = " OR code='".$formvalues['groupcode']."'";
	}
	foreach($db->selectObjects('group','inclusive=1'.$code_where) as $g) {
		$memb->group_id = $g->id;
		$db->insertObject($memb,'groupmembership');
	}

	//signup email stuff
  	if (USER_REGISTRATION_SEND_WELCOME){
    		//email user
    		//their username is: $formvalues['username'];
    		//their password is: $formvalues['pass1'];
    		//their email is: $formvalues['email'] or $u->email;
    		$headers = ''; //define email specific headers here if you'd like
    		$from = SMTP_FROMADDRESS;
    		$to = $u->email;
    		$subject = USER_REGISTRATION_WELCOME_SUBJECT;
    		$msg = $u->firstname . ", \n\n";
		$msg .= USER_REGISTRATION_WELCOME_MSG;

    		if (!defined("SYS_SMTP")) include_once(BASE."subsystems/smtp.php");
    		if (!exponent_smtp_mail($to, $from ,$subject,$msg,$headers));
	}

	if (USER_REGISTRATION_SEND_NOTIF){
    		//email admin
    		$headers = ''; //define email specific headers here if you'd like
    		$from = SMTP_FROMADDRESS;
    		$to = USER_REGISTRATION_ADMIN_EMAIL; //put admin email here.
    		$subject = USER_REGISTRATION_NOTIF_SUBJECT;
    		$msg = "When: " . date("F j, Y, g:i a") ."\n\n";
    		$msg .= "Their name is: " . $u->firstname . " " . $u->lastname . "\n\n";

    		if (!defined("SYS_SMTP")) include_once(BASE."subsystems/smtp.php");
    		if (exponent_smtp_mail($to, $from ,$subject,$msg,$headers));
  	}
	// Return the newly created user object (complete with ID) to the caller.
	return $u;
}

 // FIXME: Does exponent_users_userManagerFormTemplate still need to exist?
 // FIXME:
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function exponent_users_userManagerFormTemplate($template) {
	global $db;
	global $user;
	$users = $db->selectObjects('user');

	if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
	if (!function_exists('exponent_sorting_byLastFirstAscending')) {
		function exponent_sorting_byLastFirstAscending($a,$b) {
			return strnatcmp($a->lastname . ', '. $a->firstname,$b->lastname . ', '. $b->firstname);
		}
	}
	usort($users,'exponent_sorting_byLastFirstAscending');
	for ($i = 0; $i < count($users); $i++) {
		$users[$i] = exponent_users_getUserById($users[$i]->id);
		if ($users[$i]->is_acting_admin && $user->is_admin == 0) {
			// Dealing with an acting admin, and the current user is not a super user
			// Fake the is_admin parameter to disable editting.
			$users[$i]->is_admin = 1;
		}

	}

	$template->assign('users',$users);
	$template->assign('blankpass',md5(''));

	return $template;
}

 // FIXME: Does exponent_users_groupManagerFormTemplate still need to exist?
 // FIXME:
/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function exponent_users_groupManagerFormTemplate($template) {
	global $db;
	$groups = $db->selectObjects('group');

	if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
	usort($groups,'exponent_sorting_byNameAscending');

	$template->assign('groups',$groups);

	return $template;
}

/* exdoc
 * This function will clear a user's password, but only if they are
 * not an administrator.
 *
 * @param integer $uid The ID of the user to clear the password for.
 * @node Subsystems:Users
 */
function exponent_users_clearPassword($uid) {
	global $db;
	$user = null;
	// Calculate the md5 of a blank
	$user->password = md5('');
	$db->updateObject($user,'user','id='.$uid.' AND is_admin=0');
}

/* exdoc
 * This function removes the user object, the profile extension data and all of its group
 * memberships and permissions.
 *
 * @param integer $uid The id of the account to delete.
 * @node Subsystems:Users
 */
function exponent_users_delete($uid) {
	global $db;
	global $user;
	$u = $db->selectObject('user','id='.$uid);
	if ($u && $u->is_admin == 0 && ($u->is_acting_admin == 0 || $user->is_admin == 1)) {
		$db->delete('user','id='.$uid);
		$db->delete('groupmembership','member_id='.$uid);
		$db->delete('userpermission','uid='.$uid);

		// clean up profile extensions
		exponent_users_includeProfileExtensions();
		$extensions = $db->selectObjects('profileextension');
		foreach ($extensions as $ext) {
			call_user_func(array($ext->extension,'cleanup'),$u);
		}
	}
}

/* exdoc
 * This function removes the group object, and all group memberships
 * and permissions associated with it.
 *
 * @param integer $gid The id of the group account to delete.
 * @node Subsystems:Users
 */
function exponent_users_groupDelete($gid) {
	global $db;
	$db->delete('group','id='.$gid);
	$db->delete('groupmembership','group_id='.$gid);
	$db->delete('grouppermission','gid='.$gid);
}

/* exdoc
 * This function pulls a user object from the subsystem's storage mechanism
 * according to its ID.  For the default implementation, this is equivalent to a
 * $db->selectObject() call, but it may not be the same for other implementations.
 * Returns a basic user object, and null if no user was found.
 *
 * This function uses the exclusive global variable $SYS_USERS_CACHE to cache
 * previously retrieved user accounts, so that subsequent requests for the same user
 * object do not result in another trip to the database engine.
 *
 * @param integer $uid The id of the user account to retrieve.
 * @node Subsystems:Users
 */
function exponent_users_getUserById($uid) {
	// Pull in the exclusive global variable $SYS_USERS_CACHE
	global $SYS_USERS_CACHE;
	if (!isset($SYS_USERS_CACHE[$uid])) {
		// If we haven't previously retrieved an object for this ID, pull it out from
		// the database and stick it in the cache array, for future calls.
		global $db;
		$tmpu = $db->selectObject('user','id='.$uid);
		if ($tmpu && $tmpu->is_admin == 1) {
			// User is an admin.  Update is_acting_admin, just in case.
			// This can be removed as soon as 0.95 is deprecated.
			$tmpu->is_acting_admin = 1;
		}
		$SYS_USERS_CACHE[$uid] = $tmpu;
	}
	// Regardless of whether or not the user had been retrieved prior to the calling of
	// this function, it is now in the cache array.
	return $SYS_USERS_CACHE[$uid];
}

function exponent_users_getUsersByEmail($email) {
    global $db;
    $tmpus = $db->selectObjects('user',"email='$email'");
    foreach ($tmpus as $key=>$tmpu) {
	    if ($tmpu && $tmpu->is_admin == 1) {
           	// User is an admin.  Update is_acting_admin, just in case.
                // This can be removed as soon as 0.95 is deprecated.
                $tmpus[$key]->is_acting_admin = 1;
            }
    }
    return $tmpus;
}

/* exdoc
 * Gets a list of all user accounts in the system.  By giving different
 * combinations of the two boolean arguments. threee different lists
 * of users can be returned.  Returns alist of users, according to the two parameters passed in.
 *
 * @param bool $allow_admin Whether or not to include admin accounts in the returned list.
 * @param bool $allow_normal Whether or not to include normal accounts in the returned list.
 * @node Subsystems:Users
 */
function exponent_users_getAllUsers($allow_admin=1,$allow_normal=1) {
	global $db;
	if ($allow_admin && $allow_normal) return $db->selectObjects('user');
	else if ($allow_admin) return $db->selectObjects('user','is_admin=1 OR is_acting_admin = 1');
	else if ($allow_normal) return $db->selectObjects('user','is_admin=0 AND is_acting_admin = 0');
	else return array();
}

/* exdoc
 * This function pulls a group object form the subsystem's storage mechanism,
 * according to its ID.  For the default implementation, this is equivalent to a
 * $db->selectObject() call, but it may not be the same for other implementations.
 * Returns a group object, and null if no group was found.
 *
 * This function does NOT perform group caching like the exponent_users_getUserById
 * function does.  Multiple calls to retrieve the same group result in multiple calls
 * to the database.
 *
 * @param integer $gid The id of the group account to retrieve.
 * @node Subsystems:Users
 */
function exponent_users_getGroupById($gid) {
//anonymous group -- NOT YET IMPLEMENTED
/*    global $db;
    $i18n = exponent_lang_loadFile('subsystems/users.php');
    if ($gid == 0){
       //anonymous group
       $g->id = 0;
       $g->name = $i18n['anonymous_group_name'];
       $g->description = $i18n['anonymous_group_description'];
       $g->inclusive = 1;
       return $g;
    } else {
       return $db->selectObject('group','id='.$gid);
    }
*/
   global $db;
   return $db->selectObject('group','id='.$gid);
}

/* exdoc
 * This function pulls a user object from the subsystem's storage mechanism,
 * according to the username.  For the default implementation, this is equivalent
 * to a $db->selectObject() call, but it may not be the same for other implementations.
 * Returns a basic user object, and null if no user was found.
 *
 * This function does NOT perform user caching like the exponent_users_getUserById
 * function does.  Multiple calls to retrieve the same user result in multiple calls
 * to the database.
 *
 * @param string $name The username of the user account to retrieve.
 * @node Subsystems:Users
 */
function exponent_users_getUserByName($name) {
	global $db;
	$tmpu = $db->selectObject('user',"username='$name'");
	if ($tmpu && $tmpu->is_admin == 1) {
		// User is an admin.  Update is_acting_admin, just in case.
		// This can be removed as soon as 0.95 is deprecated.
		$tmpu->is_acting_admin = 1;
	}
	return $tmpu;
}

/* exdoc
 * This funciton pulls a group object from the subsystem's storage mechanism,
 * according to the group name.  For the default implementation, this is equivalent
 * to a $db->selectObject() call, but it may not be the same for other implementations.
 * Returns a group object, and null if no group was found.
 *
 * This function does NOT perform group caching like the exponent_users_getUserById
 * function does.  Multiple calls to retrieve the same group result in multiple calls
 * to the database.
 *
 * @param integer $name The name of the group account to retrieve.
 * @node Subsystems:Users
 */
function exponent_users_getGroupByName($name) {
	global $db;
	return $db->selectObject('group',"name='$name'");
}

/* exdoc
 * Gets a list of all group in the system.  By giving different
 * combinations of the two boolean arguments. threee different lists
 * of groups can be returned.  Returns a list of groups, according to
 *  the two parameters passed in.
 *
 * @param bool $allow_exclusive Whether or not to include exclusive groups in the returned list.
 * @param bool $allow_inclusive Whether or not to include inclusive groups in the returned list.
 * @node Subsystems:Users
 */
function exponent_users_getAllGroups($allow_exclusive=1,$allow_inclusive=1) {
	global $db;
	if ($allow_exclusive && $allow_inclusive) {
		// For both, just do a straight selectObjects call, with no WHERE criteria.
		return $db->selectObjects('group');
	} else if ($allow_exclusive) {
		// At this point, we know that $allow_inclusive was passed as false
		// So, we need to retrieve groups that are not inclusive.
		return $db->selectObjects('group','inclusive = 0');
	} else if ($allow_inclusive) {
		// At this point, we know that $allow_exclusive was passed as false
		// So, we need to retrieve groups that are inclusive.
		return $db->selectObjects('group','inclusive = 1');
	} else {
		// Both arguments were passed as false.  This is nonsensical, but why not
		// let the programmer shoot themselves in the foot.  Return an empty array.
		return array();
	}
}

/* exdoc
 * This function consults the group membership data and returns a
 * list of all groups (according to the filtration criteria in arguments 2
 * and 3) that the user belongs to. Returns an array of all group objects
 * that the specified user is a member of (according to the filtration criteria in arguments 2 and 3).
 *
 * @param Object $u The user to retrieve group memberships for.
 * @param bool $allow_exclusive Whether or not to include exclusive groups in the returned list.
 * @param bool $allow_inclusive Whether or not to include inclusive groups in the returned list.
 * @node Subsystems:Users
 */
function exponent_users_getGroupsForUser($u, $allow_exclusive=1, $allow_inclusive=1) {
	global $db;
	if ($u == null || !isset($u->id)) {
		// Don't have enough information to consult the membership tables.
		// Return an empty array.
		return array();
	}
	// Holding array for the groups.
	$groups = array();
	if ($u->is_admin == 1) {
		// For administrators, we synthesize group memberships - they effectively
		// belong to all groups.  So, we call exponent_users_getAllGroups, and pass the
		// filtration criteria arguments (2 and 3) to it.
		return exponent_users_getAllGroups($allow_exclusive,$allow_inclusive);
	}
	foreach ($db->selectObjects('groupmembership','member_id='.$u->id) as $m) {
		// Loop over the membership records for this user, and select the full
		// group object for each group.
		$o = $db->selectObject('group','id='.$m->group_id);
		if ($o->inclusive == 1 && $allow_inclusive) {
			// The group is inclusive and the caller has asked us to allow inclusive groups.
			// Append the group object to the end of the holding array.
			$groups[] = $o;
		}
		if ($o->inclusive == 0 && $allow_exclusive) {
			// The group is exclusive and the caller has asked us to allow exclusive groups.
			// Append the group object to the end of the holding array.
			$groups[] = $o;
		}
	}
	// Return the list of group objects to the caller.
	return $groups;
}

function exponent_user_getGroupById($id=null) {
	global $db;
	if ($id == null || !isset($id)) {
		return array();
	}
	
	$group = null;
	$group = $db->selectObject("group", "id=".$id);
	return $group;
}

/* exdoc
 * This function consults the group membership data and returns a
 * list of all users that belong to the specified group.  Returns
 * an array of all user objects that belong to the specified group.
 *
 * @param Object $g The group object to obtain a member list for.
 * @node Subsystems:Users
 */
function exponent_users_getUsersInGroup($g) {
	global $db;
	if ($g == null || !isset($g->id)) {
		// Don't have enough information to consult the membership tables.
		// Return an empty array.
		return array();
	}
	// Holding array for the member users.
	$users = array();
	foreach ($db->selectObjects('groupmembership','group_id='.$g->id) as $m) {
		// Loop over the membership records for this group, and append a basic user object to the holding array.
		$users[] = $db->selectObject('user','id='.$m->member_id);
	}
	// Return the list of user objects to the caller.
	return $users;
}

/* exdoc
 * Saves a user account to the subsystem's storage mechanism.  This function
 * will actually ignore any extraneous attributes of the passed object, and only
 * deal with the standard set of user account attributes.  This means that a full
 * user object can be passed in with no repercussions.  Returns the user object,
 * complete with an ID (a new ID if the user is inserted as a new user).
 *
 * @param Object $user The User object to save.  If the id attribute of this object
 *    is set, an update is performed.  Otherwise, a new record is created.
 * @node Subsystems:Users
 */
function exponent_users_saveUser($u) {
	if ($u == null) {
		// If the passed user is null, then we need to bail.
		return null;
	}
	// Pull the database object in from the global scope.
	global $db;

	// Reset the is_acting_admin flag, because that's what we really check.
	if ($u->is_admin == 1) {
		$u->is_acting_admin = 1;
	}

	// Create a temporary object to house the standard
	// member attributes stored in the passed object.  This block
	// of code allows us to pass in user objects with 'extra' attributes
	// without fearing breakage.
	$tmp = null;
	$tmp->username = $u->username;
	$tmp->password = $u->password;
	$tmp->is_admin = $u->is_admin;
	$tmp->is_acting_admin = $u->is_acting_admin;
	$tmp->is_locked = $u->is_locked;
	$tmp->firstname = $u->firstname;
	$tmp->lastname = $u->lastname;
	$tmp->email = $u->email;
###	$tmp->home_section = $u->home_section;

	if (isset($u->id)) {
		// If the user already has an ID, an update should be performed.  For that,
		// we need the original ID.
		$tmp->id = $u->id;
		$db->updateObject($tmp,'user');
	} else {
		// Since no ID was set, we need to create a new record in the user table,
		// and store the ID in the user object so that it can be returned.
		$u->id = $db->insertObject($tmp,'user');
	}
	return $u;
}

/* exdoc
 * Saves a group account to the subsystem's storage mechanism.   Returns
 * the full group object, complete with an ID (a new ID if the group is
 *    inserted as a new group).
 *
 * @param Object $group The group account to update / create.
 * @node Subsystems:Users
 */
function exponent_users_saveGroup($group) {
	if ($group == null) {
		// No group to save.  Need to bail.
		return null;
	}
	// Pull the database object in from the global scope.
	global $db;
	if (isset($group->id)) {
		// If the group already has an ID, an update should be performed.
		$db->updateObject($group,'group');
	} else {
		// Since no ID was set, we need to create a new record in the group table,
		// and store the ID in the group object so that it can be returned.
		$group->id = $db->insertObject($group,'group');
	}
	return $group;
}

/* exdoc
 * This function changes a user's password to an arbitrary value.
 *
 * @param string $pass The new password.
 * @param Object $user The user object to change the password for.  If this argument
 *     is not passed, or is null, the function will try to use the current user.  If no one is
 *     logged in, then it exits immediately.
 * @node Subsystems:Users
 */
function exponent_users_changepass($pass, $user = null) {
	if ($user == null) {
		// If a valid user object was not passed, try to fall back to the current user.
		global $user;
	}
	if ($user == null || !isset($user->id)) {
		// This will only be reached if:
		//	A) The second argument was null
		//	B) The current visitor is not logged in.
		// We need to bail, because there isn't enough information to change the password.
		return;
	}
	// Set up a partial object for doing the update (since we don't want to change other
	// attributes that may be set for the user.)
	$u = null;
	$u->id = $user->id;
	$u->password = md5($pass);
	// Pull the database object in from the global scope.
	global $db;
	// Update the user object with the new password.
	$db->updateObject($u,'user');
}

?>
