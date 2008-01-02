{*
 * Copyright (c) 2004-2007 OIC Group, Inc.
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

<h3>File Information</h3>
Name: {$file->name}<br />
Filename: {$file->filename}<br />
Filesize: {$file->filesize}<br />
Filetype: {$file->mimetype}<br />
{if $file->is_image == 1}
Image Width: {$file->image_width}<br />
Image Height: {$file->image_height}<br />
{/if}

