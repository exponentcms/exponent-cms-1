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

<table cellpadding="0" cellspacing="0">
<tr><td class="tab_btn">
<a href="{link action=manage}">Hierarchy</a>
</td><td class="tab_btn tab_btn">
<a href="{link action=manage_standalone}">Standalone&nbsp;Pages</a>
</td><td class="tab_btn tab_btn_current">
<a href="{link action=manage_pagesets}">Pagesets</a>
</td><td class="tab_spacer" width="50%">
&nbsp;
</td></tr>
<tr><td colspan="4" class="tab_main">
 
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

</td></tr>
</table>