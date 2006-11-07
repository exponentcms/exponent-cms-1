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
 * $Id: _view_milestone.tpl,v 1.4 2005/02/23 23:51:34 filetreefrog Exp $
 *}
<b>Milestone: {$milestone->name}</b>

{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.manage_miles == 1}
<br />
<a href="{link action=milestone_edit id=$milestone->id}" class="mngmntlink codemap_mngmntlink">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0"/>
</a>
<a href="{link action=milestone_delete id=$milestone->id}" class="mngmntlink codemap_mngmntlink">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"/>
</a>
{/if}
{/permissions}
<hr size='1' />
{$milestone->description|nl2br}
<hr size='1' />
<b>Tasks for this milestone</b>
<table cellpadding="0" cellspacing="2" border="0">
{foreach from=$stepstones item=s}
<tr><td><a class="mngmntlink codemap_mngmntlink" href="{link action=stepstone_view id=$s->id}">{$s->name}</a></td>
{if $s->status == 2}<td align="right" style="color: green"><b>Completed</b></td>
{elseif $s->status == 1}<td align="right" style="color: blue"><b>In Progress</b></td>
{else}<td align="right" style="color: red"><b>Not Completed</b></td>
{/if}
</tr>
<tr><td colspan="2">
	<div style="padding-left: 3em;">{$s->description|nl2br}</div>
</td></tr>
{/foreach}
</table>