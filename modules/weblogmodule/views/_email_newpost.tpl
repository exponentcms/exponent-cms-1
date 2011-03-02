{*
 *
 * Copyright (c) 2004-2011 OIC Group, Inc.
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
 *}
Hello,

You are receiving this email because you are monitoring the weblog "{$blogname}" at "{$smarty.const.SITE_TITLE}".  Someone has posted a new entry titled "{$post->title}".

{if $showpost == 1}
------------------------------
{$poster->firstname} {$poster->lastname} ({$poster->username})

{$post->body}
------------------------------

{/if}
To view the full post, visit the following URL:
{$viewlink}

{$signature}