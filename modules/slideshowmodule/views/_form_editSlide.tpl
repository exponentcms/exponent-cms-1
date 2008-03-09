{*
 *
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 * $Id: _form_editSlide.tpl,v 1.3 2005/02/19 16:53:36 filetreefrog Exp $
 *}
<h1>{if $is_edit == 1}Edit Slide Data{else}Upload new Slide{/if}</h1>
<div class="bodycopy">	
	{if $is_edit == 1}
	You can change only the name, description and scaling percentage of this slide.
	{else}
	Upload a new slide.
	{/if}
</div>
{$form_html}