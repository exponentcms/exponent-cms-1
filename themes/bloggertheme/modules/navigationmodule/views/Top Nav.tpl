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
 * $Id: Top\040Nav.tpl,v 1.4.2.2 2005/04/20 16:51:55 filetreefrog Exp $
 *}
 {assign var=bar value=0}
		&nbsp;&nbsp;|&nbsp;
		{foreach from=$sections item=section}
		{if $section->parent == 0}
		{if $section->name == "Break" && $section->active == 0}
		&nbsp;&nbsp;|&nbsp;
		{else}		
			{if $section->active == 1}
				<a class="navlink" href="{$section->link}"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>
			{else}
				<span class="navlink">{$section->name}</span>
			{/if}
			&nbsp;|&nbsp;
			{/if}
		{/if}
		{/foreach}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $canManage == 1}
			&nbsp;[&nbsp;<a class="navlink" href="{link action=manage}">manage</a>&nbsp;]&nbsp;
		{/if}
		{/permissions}