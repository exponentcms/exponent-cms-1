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
<div class="moduletitle navigation_modultitle">{$_TR.form_title}</div>
<div class="form_header">
{if $parent->id == 0}{$_TR.new_top_level}{else}
{$_TR.new_sub_level|sprintf:$parent->name}{/if}
{$_TR.form_header}
</div>

<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_contentpage parent=$parent->id}">{$_TR.content_page}</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">{$_TR.content_page_desc}</div>

<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_externalalias parent=$parent->id}">{$_TR.ext_link}</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">{$_TR.ext_link_desc}</div>

<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_internalalias parent=$parent->id}">{$_TR.int_link}</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">{$_TR.int_link_desc}
</div>

{if $havePagesets != 0}
<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink navigation_mngmntlink" href="{link action=add_pagesetpage parent=$parent->id}">{$_TR.pageset}</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">{$_TR.pageset_desc}</div>
{/if}

{if $haveStandalone != 0}
<div style="background-color: #CCC; padding: 5px;">
<a class="mngmntlink navigation_mngmntlink" href="{link action=move_standalone parent=$parent->id}">{$_TR.standalone}</a>
</div>
<div style="padding: .5em; padding-bottom: 1.5em;">{$_TR.standalone_desc}</div>
{/if}
