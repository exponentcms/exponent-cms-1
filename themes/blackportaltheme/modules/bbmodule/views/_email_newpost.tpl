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
 * $Id: _email_newpost.tpl,v 1.3 2005/02/19 16:42:19 filetreefrog Exp $
 *}
Hello,

You are receiving this email because you are monitoring the board "{$board->name}" at "{$smarty.const.SITE_TITLE}".  Someone has posted a new thread.

{if $showpost == 1}
------------------------------
{$post->subject}

{$poster->firstname} {$poster->lastname} ({$poster->username})

{$post->body}
------------------------------

{/if}
To view the full thread, visit the following URL:
{$viewlink}

{$signature}