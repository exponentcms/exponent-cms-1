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
{if $moduletitle != ""}<div class="moduletitle contact_moduletitle">{$moduletitle}</div>{/if}
{if $numContacts != 0}
<form method="post" action="">
<input type="hidden" name="module" value="contactmodule"/>
<input type="hidden" name="action" value="contact"/>
<input type="hidden" name="src" value="{$loc->src}" />
<input type="hidden" name="msg" value="_Default" />
<table cellpadding="2" cellspacing="0" border="0">
<tr>
	<td width="10" style="width: 10px" valign="top">Email:</td>
	<td>
		<input type="text" name="email" />
	</td>
</tr>
<tr>
	<td valign="top">Subject:</td>
	<td>
		<input type="text" name="subject" />
	</td>
</tr>
<tr>
	<td valign="top">Message:</td>
	<td>
		<textarea name="message"></textarea>
	</td>
</tr>
<tr>
	<td colspan="2">
		<input type="submit" value="Send" />
	</td>
</tr>
</table>
</form>
{else}
{if $smarty.const.PREVIEW_READONLY == 1 || $permissions.configure == 1}
No contacts have been defined.  You will have to manage contacts for this Contact Form.<br /><br />
{/if}
{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.configure == 1}
<a class="mngmntlink contact_mngmntlink" href="{link action=manage_contacts}">Manage Contacts</a>
{/if}
{/permissions}