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
<div class="form_title">Active Extensions</div>
<div class="form_header">Active Profile Extensions let users store more information in their profile.  Users will only be able to manage information governed by the active extensions listed below.</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header administration_header">Extension Name</td>
	<td class="header administration_header">&nbsp;</td>
</tr>
{foreach name=e from=$extensions item=extension}
{math equation="x+1" x=$extension->rank assign=nextrank}
{math equation="x-1" x=$extension->rank assign=prevrank}
	<tr class="row {cycle values='odd,even'}_row">
		<td>{$extension->name}</td>
		<td>
			<a class="mngmntlink administration_mngmntlink" href="{link action=profileext_delete id=$extension->id}" onClick="return confirm('Are you sure you want to deactivate the profile extension \'{$extension->name}\'?');">
				<img src="{$smarty.const.ICON_RELATIVE}delete.gif" border="0" />
			</a>
			{if $smarty.foreach.e.first != 1}
			<a class="mngmntlink administration_mngmntlink" href="{link action=profileext_order a=$extension->rank b=$prevrank}">
				<img src="{$smarty.const.ICON_RELATIVE}up.gif" border="0" />
			</a>
			{else}
			<img src="{$smarty.const.ICON_RELATIVE}up.disabled.gif" border="0" />
			{/if}
			{if $smarty.foreach.e.last != 1}
			<a class="mngmntlink administration_mngmntlink" href="{link action=profileext_order a=$extension->rank b=$nextrank}">
				<img src="{$smarty.const.ICON_RELATIVE}down.gif" border="0" />
			</a>
			{else}
			<img src="{$smarty.const.ICON_RELATIVE}down.disabled.gif" border="0" />
			{/if}
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="2" align="center">
			<i>No Active Extensions</i>
		</td>
	</tr>
{/foreach}
</table>
<br /><br />
<hr size="1" />
<div class="form_title">Inactive Extensions</div>
<div class="form_header">The extensions listed below have been deactivated.  They will not show up as part of user profiles until they are activated.</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header administration_header">Extension Name</td>
	<td class="header administration_header">&nbsp;</td>
</tr>
{foreach from=$unused key=extclass item=extension}
<tr class="row {cycle values='odd,even'}_row">
	<td>{$extension->name}</td>
	<td>
		<a class="mngmntlink administration_mngmntlink" href="{link action=profileext_save ext=$extclass}">Activate</a>
		{if $extension->hasData == 1}
		<a class="mngmntlink administration_mngmntlink" href="{link action=profileext_clear ext=$extclass}">Clear Data</a>
		{else}
		{/if}
	</td>
</tr>
{foreachelse}
	<tr>
		<td colspan="2" align="center">
			<i>All Inactive Extensions.</i>
		</td>
	</tr>
{/foreach}
</table>