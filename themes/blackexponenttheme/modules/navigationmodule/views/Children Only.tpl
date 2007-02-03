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
 * $Id: Children\040Only.tpl,v 1.4.2.2 2005/04/20 16:51:55 filetreefrog Exp $
 *}
<table class="childrenonly" cellpadding="0" cellspacing="0" border="0">
{foreach from=$sections item=section}
{if $section->numParents != 0 && ($section->parents[0] == $current->parents[0] || $section->parents[0] == $current->id)}
<tr><td class="childrenonly" style="padding-left: {math equation="x*20-20" x=$section->depth}px">
{if $section->active == 1}
<img src="{$smarty.const.THEME_RELATIVE}images/arrowsub2.gif" alt="arrow" border="0">
<span class="childrenonly"><a href="{$section->link}" class="navlink"{if $section->new_window} target="_blank"{/if}>&nbsp;{$section->name}</a>&nbsp;</span>
{else}
<span class="navlink">&nbsp;{$section->name}</span>&nbsp;
{/if}
</td></tr>
{/if}
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $canManage == 1}
<img style="border:none;" src="{$smarty.const.ICON_RELATIVE}sitetree.png" align="absmiddle" alt="Sitetree" />&nbsp;<a class="mngmntlink preview_mngmntlink" href="{link action=manage}"><strong>Manage Site</strong></a>&nbsp;
{/if}
{/permissions}