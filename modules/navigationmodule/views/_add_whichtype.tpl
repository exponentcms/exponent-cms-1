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
<div class="moduletitle navigation_modultitle">Add New Page to Site Navigation</div>
<div class="form_header">
{if $parent->id == 0}
You are adding a new top-level page.
{else}
You are adding a new sub page to "{$parent->name}".
{/if}
Please select the type of page you would like to add.
</div>

<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_contentpage parent=$parent->id}">
Content Page
</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">
Content Pages are regular pages on the site that allow you to add modules to them.  With content pages, you are able to override the global Site Title, Site Description and Site Keywords settings.
</div>

<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_externalalias parent=$parent->id}">
External Website Link
</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">
If you need or want a link in your site hiearchy to link to some off-site webpage, create an External Link.
</div>

<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_internalalias parent=$parent->id}">
Internal Page Alias
</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">
If you need or want a link to another page in your site hierarchy, use an internal page alias.
</div>

{if $havePagesets != 0}
<div style="background-color: #CCC; padding: 5px;"><a class="mngmntlink navigation_mngmntlink" href="{link action=add_pagesetpage parent=$parent->id}">
Pageset
</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">
Pagesets are powerful tools that allow you to create sections with default content and subsections by adding a single pageset.
</div>
{/if}

{if $haveStandalone != 0 && $isAdministrator == 1}
<div style="background-color: #CCC; padding: 5px;"><a class="mngmntlink navigation_mngmntlink" href="{link action=move_standalone parent=$parent->id}">
Move Standalone Page
</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">
Use this if you want to move a standalone page into the navigation hierarchy.
</div>
{/if}
