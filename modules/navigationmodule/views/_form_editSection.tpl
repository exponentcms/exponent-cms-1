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
<div class="form_title">{if $is_edit}Edit Existing Section{else}Create New Section{/if}</div>
<div class="form_header">
{if $is_edit}
Now, you can edit the properties of a section.
{else}
Use this form to add a new section to the site hierarchy.
{/if}
<br /><br />
Active sections show up in the Navigation Hierarchy as clickable links.  Inactive sections just show up as text.
<br />
Public sections are viewable by everybody.  If a section is not public, users must be assigned the 'View Section' permission in order to view that section.
<br />
</div>
{$form_html}