{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by James Hunt
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
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
	<td style="width: 125px;" valign="top"><label>{$_TR.name}:</label></td>
	<td>
		<input type="text" name="name" />
	</td>
</tr>
<tr>
	<td style="width: 125px;" valign="top"><label>{$_TR.email}:</label></td>
	<td>
		<input type="text" name="email" />
	</td>
</tr>
<tr>
	<td valign="top"><label>{$_TR.subject}:</label></td>
	<td>
		<input type="text" name="subject" />
	</td>
</tr>
<tr>
	<td valign="top"><label>{$_TR.message}:</label></td>
	<td>
		<textarea name="message" rows="8" cols="45" wrap=soft></textarea>
	</td>
</tr>
<tr>
	<td colspan="2">
		<input type="submit" value="{$_TR.send}" />
	</td>
</tr>
</table>
</form>
{else}
{if $smarty.const.PREVIEW_READONLY == 1 || $permissions.configure == 1}
{$_TR.no_contacts}<br /><br />
{/if}
{/if}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.configure == 1}
<a class="mngmntlink contact_mngmntlink" href="{link action=manage_contacts}">{$_TR.manage_contacts}</a>
{/if}
{/permissions}
