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
<div class="moduletitle navigation_moduletitle">Manage Site Navigation</div>
<div class="form_header">
Manage the pages and site structure here.
<br />
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit parent=0}">New Top Level Section</a>
</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header navigation_header">Section Name</td>
	<td class="header navigation_header"></td>
</tr>
{foreach from=$sections item=section}
{math equation="x+1" x=$section->rank assign=nextrank}
{math equation="x-1" x=$section->rank assign=prevrank}
<tr class="row {cycle values="odd,even"}_row"><td style="padding-left: {math equation="x*20" x=$section->depth}px">
{if $section->active}
<a href="{link section=$section->id}" class="navlink">{$section->name}</a>&nbsp;
{else}
{$section->name}&nbsp;
{/if}
</td><td>

[ <a class="mngmntlink navigation_mngmntlink" href="{link action=edit parent=$section->id}">Add Subsection</a> ]
[ <a class="mngmntlink navigation_mngmntlink" href="{link action=edit id=$section->id}">Properties</a> ]
[ <a class="mngmntlink navigation_mngmntlink" href="{link action=delete id=$section->id}" onClick="return confirm('Are you sure you want to delete this section?');">Delete</a> ]
{if $section->public == 0}
	<a href="{link int=$section->id action=userperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" title="Assign user permissions for viewing this section" alt="Assign user permissions for viewing this section" /></a>
	<a href="{link int=$section->id action=groupperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" title="Assign group permissions for viewing this section" alt="Assign group permissions for viewing this section" /></a>
{/if}
{if $section->last == 0}
	<a href="{link action=order parent=$section->parent a=$section->rank b=$nextrank}"><img src="{$smarty.const.ICON_RELATIVE}down.gif" border="0" /></a>
{else}
	<img src="{$smarty.const.ICON_RELATIVE}down.disabled.gif" border="0" />
{/if}
{if $section->first == 0}
	<a href="{link action=order parent=$section->parent a=$section->rank b=$prevrank}"><img src="{$smarty.const.ICON_RELATIVE}up.gif" border="0" /></a>
{else}
	<img src="{$smarty.const.ICON_RELATIVE}up.disabled.gif" border="0" />
{/if}
</td></tr>
{/foreach}
</table>
<hr size="1" />
<br /><br />
<div class="moduletitle navigation_moduletitle">Manage Pagesets</div>
<div class="form_header">
Pagesets are powerful tools to help you manage your site hierarchy.  A pageset is sort of like a sectional template layout - it allows you to define a commonly repeated structure as a miniature navigation hierarchy.  When you add a new section, you can set the page type to one of your Pagesets, and the sectional structure will be created for you, automatically.<br /><br />
Another benefit of pagesets is default page content.  Any page in the page set can have modules on it, and the content of those modules is then copied to the newly created sections.
<br />
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_template}">New Pageset</a>
</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header navigation_header">Pageset Name</td>
	<td class="header navigation_header"></td>
</tr>
{foreach from=$templates item=t}
<tr class="row {cycle values='odd,even'}_row">
<td style="padding-left: 10px">
<b>{$t->name}</b>
</td><td>

[ <a class="mngmntlink navigation_mngmntlink" href="{link action=view_template id=$t->id}">View</a> ]
[ <a class="mngmntlink navigation_mngmntlink" href="{link action=edit_template id=$t->id}">Properties</a> ]
[ <a class="mngmntlink navigation_mngmntlink" href="{link action=delete_template id=$t->id}" onClick="return confirm('Are you sure you want to delete this template?');">Delete</a> ]
</td></tr>
{foreachelse}
<tr><td><i>No pagesets found</i></td></tr>
{/foreach}
</table>