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
<div class="moduletitle">{$template->name}</div>
<hr size="1" />
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td><b>&lt;Name of Section&gt;</b></td>
<td>
[ <a class="mngmntlink sitetemplate_mngmntlink" href="{link action=edit_template parent=$template->id}">Add Subpage</a> ]
[ <a class="mngmntlink sitetemplate_mngmntlink" href="{link action=edit_template id=$template->id}">Properties</a> ]
[ <a class="mngmntlink sitetemplate_mngmntlink" href="#" onClick="window.open('{$smarty.const.PATH_RELATIVE}modules/navigationmodule/actions/edit_page.php?sitetemplate_id={$template->id}'); return false">Page Content</a> ]
</td>
{foreach from=$subs item=sub}
{math equation="x+1" x=$sub->rank assign=nextrank}
{math equation="x-1" x=$sub->rank assign=prevrank}
<tr>
<td style="padding-left: {math equation="x*20" x=$sub->depth}">
<b>{$sub->name}</b>
</td>
<td>
[ <a class="mngmntlink sitetemplate_mngmntlink" href="{link action=edit_template parent=$sub->id}">Add Subpage</a> ]
[ <a class="mngmntlink sitetemplate_mngmntlink" href="{link action=edit_template id=$sub->id}">Properties</a> ]
[ <a class="mngmntlink sitetemplate_mngmntlink" href="#" onClick="window.open('{$smarty.const.PATH_RELATIVE}modules/navigationmodule/actions/edit_page.php?sitetemplate_id={$sub->id}'); return false">Page Content</a> ]
[ <a class="mngmntlink sitetemplate_mngmntlink" href="{link action=delete_template id=$sub->id}">Delete</a> ]
{if $sub->last == 0}
	<a href="{link action=order_templates parent=$sub->parent a=$sub->rank b=$nextrank}"><img src="{$smarty.const.ICON_RELATIVE}down.gif" border="0" /></a>
{else}
	<img src="{$smarty.const.ICON_RELATIVE}down.disabled.gif" border="0" />
{/if}
{if $sub->first == 0}
	<a href="{link action=order_templates parent=$sub->parent a=$sub->rank b=$prevrank}"><img src="{$smarty.const.ICON_RELATIVE}up.gif" border="0" /></a>
{else}
	<img src="{$smarty.const.ICON_RELATIVE}up.disabled.gif" border="0" />
{/if}
</td>
</tr>
{/foreach}
</table>
<br />
<br />
<a class="mngmntlink navigation_mngmntlink" href="{link action=manage}">Back to Manager</a>