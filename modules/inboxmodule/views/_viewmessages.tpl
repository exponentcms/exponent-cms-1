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
<b>Private Messages for {$user->firstname} {$user->lastname}</b>
<br />
{$totalMessages} messages, {$unreadMessages} unread.
<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header inbox_header">Subject</td>
		<td class="header inbox_header">Sender</td>
		<td class="header inbox_header">Date Sent</td>
		<td class="header inbox_header">&nbsp;</td>
	</tr>
{foreach from=$messages item=message}
	<tr>
		<td>
			{if $message->unread == 1}*{/if}
			<a class="mngmntlink inbox_mngmntlink" href="{link action=message id=$message->id}">
				{$message->subject}
			</a>
		</td>
		<td>{$message->from_name}</td>
		<td>{$message->date_sent|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</td>
		<td>
			<a class="mngmntlink inbox_mngmntlink" href="{link action=delete id=$message->id}" onClick="return confirm('Are you sure you want to delete this Private Message?');">
				<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" title="Delete this Private Message" alt="Delete this Private Message" />
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="4" align="center"><i>No messages in inbox</i></td>
	</tr>
{/foreach}
</table>
<hr size="1" />
<a class="mngmntlink inbox_mngmntlink" href="{link action=compose}">Compose Message</a>
<br />
<a class="mngmntlink inbox_mngmntlink" href="{link action=view_contacts}">Personal Contacts</a>