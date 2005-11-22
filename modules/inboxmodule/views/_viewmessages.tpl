{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
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
{$_TR.messages|sprintf:$totalMessages:$unreadMessages}
<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header inbox_header">{$_TR.subject}</td>
		<td class="header inbox_header">{$_TR.sender}</td>
		<td class="header inbox_header">{$_TR.date_sent}</td>
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
			<a class="mngmntlink inbox_mngmntlink" href="{link action=delete id=$message->id}" onClick="return confirm('{$_TR.delete_confirm}');">
				<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.delete}" alt="{$_TR.delete}" />
			</a>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="4" align="center"><i>{$_TR.no_messages}</i></td>
	</tr>
{/foreach}
</table>
<hr size="1" />
<a class="mngmntlink inbox_mngmntlink" href="{link action=compose}">{$_TR.compose}</a>
<br />
<a class="mngmntlink inbox_mngmntlink" href="{link action=view_contacts}">{$_TR.address_book}</a>