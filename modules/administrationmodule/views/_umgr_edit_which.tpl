{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 *}
<div class="form_title">Edit User "{$user->firstname} {$user->lastname} ({$user->username})"</div>
<div class="form_caption">What would you like to do?</div>
{* Lock / Unlock Account *}
{if $user->is_locked}
<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink administration_mngmntlink" href="{link action=umgr_lockuser id=$user->id value=0}">Unlock Account</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">
This account is locked.  The user will not be able to log in until you unlock it.
</div>
{else}
<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink administration_mngmntlink" href="{link action=umgr_lockuser id=$user->id value=1}">Lock Account</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">
To prevent this user from logging in, you can lock the account.
</div>
{/if}

<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink administration_mngmntlink" href="{link action=umgr_editprofile id=$user->id}">Edit Profile Information</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">
To change this user's real name, and other information stored in their profile, click the above link.
</div>

<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink administration_mngmntlink" href="{link action=umgr_clearpass id=$user->id}">Clear Password</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">
If this user is unable to remember their password, and cannot use the password retrieval system, you can clear the account password here.  (This will reset it to nothing)
</div>

<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink administration_mngmntlink" href="{link action=umgr_membership id=$user->id}">Manage Groups</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">
Assign this user to one or more (or zero) user groups, to ease permission management.
</div>

