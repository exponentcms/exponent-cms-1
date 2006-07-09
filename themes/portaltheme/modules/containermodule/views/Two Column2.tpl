{*
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
{permissions level=$smarty.const.UILEVEL_STRUCTURE}
{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)}
<b>{$_TR.container_module}</b>
<br />
{/if}
{if $hasParent == 0 &&  $permissions.edit_module == 1}{** top level container module **}
<div class="container_editheader">
	{* I.E. requires a 'dummy' div inside of the above div, so that it
	   doesn't just 'lose' the margins and padding. jh 8/23/04 *}
	<div style="width: 100%">
	<table width="100%" cellpadding="0" cellspacing="3" border="0" class="container_editheader">
		<tr>
			<td valign="top" class="info">
				{$_TR.container_module}
				<br />{$_TR.shown_in|sprintf:$__view}
			</td>
			<td align="right" valign="top">
				{if $permissions.administrate == 1}
					<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
					<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
				{/if}
			
				{if $permissions.edit_module == 1}
					<a href="{link action=edit id=$top->id}">
						<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configuremodule.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" />
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
<td valign="top" style="padding: 15px 0 15px 15px">
	{assign var=container value=$containers.0}
	{assign var=rank value=0}
	{include file=$viewfile}
</td>
<td width="180px" valign="top" style="padding: 15px;">
	{assign var=container value=$containers.1}
	{assign var=rank value=1}
	{include file=$viewfile}
</td>
</tr>
</table>
