{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
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
<div class="form_title">Users Entered to Database</div>
<div class="form_header">
The following users were added to the database.  If the user info is highlighted green, then the user was addded to the database with changes to the username.  If the user
info is highlighted in red, that user record could not be added to the database due to errors.
</div>
<table cellspacing="0" cellpadding="2" border="0" width="100%">
	<tr>
		<td class="header importer_header">Status</td>
		<td class="header importer_header">User ID</td>
		<td class="header importer_header">User Name</td>
		<td class="header importer_header">Password</td>
		<td class="header importer_header">First Name</td>
		<td class="header importer_header">Last Name</td>
		<td class="header importer_header">Email Address</td>
	</tr>
{foreach from=$userarray item=user}
<tr class="row {cycle values=even_row,odd_row}">
	<td style="background-color:inherit;">
		{if $user->changed == 1}<span style="color:green;">Changed</span>
		{elseif $user->changed == "skipped"}<span style="color:red;">Ignored&nbsp;(Line&nbsp;{$user->linenum}&nbsp;)</span>
		{else}<span style="color:black;">Success</span>
		{/if}
	</td>
	<td>{$user->id}</td>
	<td>{$user->username}</td>
	<td>{$user->clearpassword}</td>
	<td>{$user->firstname}</td>
	<td>{$user->lastname}</td>
	<td>{$user->email}</td>
</tr>
{/foreach}
		 
</table>
