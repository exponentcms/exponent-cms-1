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
<div class="form_title">{if $is_edit == 1}Edit Image Information{else}Upload Image to Manager{/if}</div>
<div class="form_header">
{if $is_edit == 1}
You can only change the information about this uploaded image.  If you want to change the actual image, you will need to delete this image, and then upload a new one.
{else}
To upload an image to the manager, click the browse button below, search your hard drive for the image file, and click OK.
{/if}
<br /><br />
The "Scale %" field specifies a previewing aspect ratio.  This is only used when looking at the image manager - it does not resize the umage being upload to a given size.
</div>
{if $dir_not_writable == 1}<br /><i>Uploading images to this Image Manager is disabled, because of bad permissions.</i><br /><br />{/if}
{$form_html}