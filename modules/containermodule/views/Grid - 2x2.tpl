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
 {permissions level=$smarty.const.UILEVEL_STRUCTURE}
{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)}
<b>Container Module</b>
<br />
{/if}
{if $hasParent == 0 &&  $permissions.edit_module == 1}{** top level container module **}
<div class="container_editheader">
	{* I.E. requires a 'dummy' div inside of the above div, so that it
	   doesn't just 'lose' the margins and padding. jh 8/23/04 *}
	<div width="100%" style="width: 100%">
	<table width="100%" cellpadding="0" cellspacing="3" border="0" class="container_editheader">
		<tr>
			<td valign="top" class="info">
				Container Module
				<br />Shown in {$__view} view
			</td>
			<td align="right" valign="top">
				{if $permissions.administrate == 1}
					<a href="{link action=userperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" title="Assign user permissions on this Container Module" alt="Assign user permissions on this Container Module" /></a>&nbsp;
					<a href="{link action=groupperms _common=1}"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" title="Assign group permissions on this Container Module" alt="Assign group permissions on this Container Module" /></a>
				{/if}
			
				{if $permissions.edit_module == 1}
					<a href="{link action=edit id=$top->id}">
						<img border="0" src="{$smarty.const.ICON_RELATIVE}configuremodule.gif" title="Change the layout of this Container Module" alt="Change the layout of this Container Module" />
					</a>
				{/if}
			</td>
		</tr>
	</table>
	</div>
</div>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{viewfile module=$singlemodule view=$singleview var=viewfile}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td valign="top" style="border-right: 2px dotted lightgrey; border-bottom: 2px dotted lightgrey">
	{assign var="rank" value="0"}
	{assign var=container value=$containers.0}
	{assign var=rank value=0}
	{include file=$viewfile}
</td>
<td valign="top" style="border-bottom: 2px dotted lightgrey">
	{assign var="rank" value="1"}
	{assign var=container value=$containers.1}
	{assign var=rank value=1}
	{include file=$viewfile}
</td>
</tr>
<tr>
<td valign="top" style="border-right: 2px dotted lightgrey;">
	{assign var="rank" value="2"}
	{assign var=container value=$containers.2}
	{assign var=rank value=2}
	{include file=$viewfile}
</td>
<td valign="top">
	{assign var="rank" value="3"}
	{assign var=container value=$containers.3}
	{assign var=rank value=3}
	{include file=$viewfile}
</td>
</tr>
</table>
