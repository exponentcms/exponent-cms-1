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
<div class="form_title">{if $is_edit == 1}Edit News Item{else}Post a new News Item{/if}</div>
<div class="form_header">
The Publish and Unpublish dates are optional, and are not dependent on one another.  You can, for instance, specify a publish date without an Unpublish date.<br />
<br />
If you specify a Publish Date, the News Item will not appear on the site until that date and time.
<br />
If you specify an Unpublish Date, the News Item will disappear after that date and time.
</div>
{$form_html}