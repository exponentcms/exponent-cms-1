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
<div class="form_title">Manage User Sessions</div>
<div class="form_header">This page shows all of the active sessions, along with session information like login time, browser signature, etc.  You can forcibly end either a specific session or all sessions for a user account.  Ending a session will cause that user to be logged out of the site, and any content they were editting will be lost.
<br /><br />
Administrator sessions cannot be forcibly ended.</div>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
	{foreach from=$sessions item=session}
	<tr>
		<td style="background-color: lightgrey">{$session->user->username}</td>
		<td style="background-color: lightgrey">IP: {$session->ip_address}</td>
		<td style="background-color: lightgrey">Duration: {foreach name=d from=$session->duration key=tag item=number}{$number}{if $smarty.foreach.d.last == false}:{/if}{/foreach}</td>
	</tr>
	<tr>
		<td colspan="3" style="padding-left: 10px; border: 1px solid lightgrey;">
			{if $session->user->is_admin == 0}
				<a class="mngmntlink administration_mngmntlink" href="{link action=session_kick ticket=$session->ticket}">End This Session</a><br />
				<a class="mngmntlink administration_mngmntlink" href="{link action=session_kickuser id=$session->user->id}">End All Sessions for '{$session->user->username}'</a>
			{else}
				<!--i>Administrators cannot be forcibly logged out.</i-->
			{/if}
			<table>
				<tr>
					<td></td>
					<td>Logged In: </td>
					<td>{$session->start_time|date_format:$smarty.const.DISPLAY_DATE_FORMAT}</td>
				</tr>
				<tr>
					<td></td>
					<td>Last Active: </td>
					<td>{$session->last_active|date_format:$smarty.const.DISPLAY_DATE_FORMAT}</td>
				<tr>
					<td></td>
					<td>Browser: </td>
					<td>{$session->browser}</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr></tr>
	{/foreach}
</table>