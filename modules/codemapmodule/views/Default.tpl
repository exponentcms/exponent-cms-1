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
 * $Id: Default.tpl,v 1.4 2005/02/23 23:51:34 filetreefrog Exp $
 *}
{permissions level=$smarty.const.UILEVEL_PERMISSION}
{if $permissions.administrate == 1}
	<a href="{$linkbase}userperms&_common=1" title="Assign permissions on this Software Roadmap"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{$linkbase}groupperms&_common=1" title="Assign group permissions on this Software Roadmap"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{$linkbase}configure&_common=1" title="Configure this Software Roadmap"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
<table cellspacing="0" cellpadding="2" border="0" style="border: 1px solid black" width="100%" rules="all">
	<tr>
		<td style="background-color: black; color: white; font-weight: bold">{$moduletitle}</td>
		{foreach from=$milestones item=m}
		<td style="background-color: black"></td>
		{/foreach}
		<td align="center" style="background-color: black; color: white">Developer</td>
	</tr>
	<tr>
		<td></td>
		{foreach name=ms from=$milestones item=m}
		<td align="center">
			{permissions level=$smarty.const.UILEVEL_NORMAL}{if $permissions.manage_miles == 1}
			{assign var=nexti value=$smarty.foreach.ms.iteration}
			{math equation="x-1" assign="i" x=$nexti}
			{math equation="x-1" assign="previ" x=$i}
			{if $smarty.foreach.ms.first== false}
				<a href="{link action=order_milestones a=$previ b=$i}">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}left.png" border="0" />
				</a>
			{/if}
			{/if}{/permissions}
				<b><a href="{link action=milestone_view id=$m->id}" class="mngmntlink codemap_mngmntlink">
					{$m->name}
				</a></b>
			{permissions level=$smarty.const.UILEVEL_NORMAL}{if $permissions.manage_miles == 1}
			{if $smarty.foreach.ms.last == false}
				<a href="{link action=order_milestones a=$nexti b=$i}">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}right.png" border="0" />
				</a>
			{/if}
			{/if}{/permissions}
		</td>
		{/foreach}
		<td></td>
	</tr>
	{foreach from=$stepstones item=s}
	<tr class="row {cycle values='even_row,odd_row'}">
		<td><a href="{link action=stepstone_view id=$s->id}" class="mngmntlink codemap_mngmntlink">{$s->name}</a></td>
		{assign var=before value=1}
		{foreach from=$milestones item=m}
		{if $m->id == $s->milestone_id}{assign var=before value=0}{/if}
		{if $before == 1}
			<td style="background-color: #999" align="center">-</td>
		{else}
			<td style="background-color: {if $s->status == 2}green{elseif $s->status == 0}red{else}blue{/if}" align="center">x</td>
		{/if}
		{/foreach}
		<td align="center">
		{if $s->developer == ""}
			{if $s->contact != ""}( <a href="{link action=contact id=$s->id}" class="mngmntlink codemap_mngmntlink">Volunteer</a> ){/if}
		{else}
			{if $s->contact != ""}<a href="{link action=contact id=$s->id}" class="mngmntlink codemap_mngmntlink">{$s->developer}</a>
			{else}{$s->developer}
			{/if}	
		{/if}
		</td>
	</tr>
	{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.manage_miles == 1}
<a href="{link action=milestone_edit}">New Milestone</a><br />
{/if}
{if $permissions.manage_steps == 1}
<a href="{link action=stepstone_edit}">New Stepstone</a><br />
{/if}
{/permissions}