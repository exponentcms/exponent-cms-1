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
 * $Id: _view_stepstone.tpl,v 1.4 2005/02/23 23:51:34 filetreefrog Exp $
 *}
<b>{$stepstone->name}</b>

{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.manage_steps == 1}
<br />
<a href="{link action=stepstone_edit id=$stepstone->id}" class="mngmntlink codemap_mngmntlink">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" border="0"/>
</a>
<a href="{link action=stepstone_delete id=$stepstone->id}" class="mngmntlink codemap_mngmntlink">
	<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" border="0"/>
</a>
{/if}
{/permissions}

<table cellpadding="2" cellspacing="0" border="1" rules="all">
<tr>
	<td>Developer</td>
	<td>
		{if $stepstone->developer == ""}
			This task has no developer.
			{if $stepstone->contact != ""}<a href="{link action=contact id=$stepstone->id}">Volunteer</a>{/if}
		{else}
			{if $stepstone->contact != ""}<a href="{link action=contact id=$stepstone->id}">{$stepstone->developer}</a>
			{else}{$stepstone->developer}
			{/if}	
		{/if}
	</td>
</tr>
<tr>
	<td>Target Milestone</td>
	<td><a class="mngmntlink codemap_mngmntlink" href="{link action=milestone_view id=$stepstone->milestone_id}">
	{$stepstone->milestone->name}
	</a></td>
</tr>
<tr>
	<td>Status</td>
	<td>{if $stepstone->status == 0}Not Yet Started{elseif $stepstone->status == 1}In Progress{else}Completed{/if}</td>
</tr>
</table>

<hr size='1' />
{$stepstone->description|nl2br}
<hr size='1' />