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
 * $Id$
 *}
{if $container != null}
	<a name="mod_{$container->id}"></a>
	{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1)}
		{permissions level=$smarty.const.UILEVEL_STRUCTURE}
		<div class="container_editbox">
		 
			<div class="container_editheader">
				{* I.E. requires a 'dummy' div inside of the above div, so that it
				   doesn't just 'lose' the margins and padding. jh 8/23/04 *}
				<div width="100%" style="width: 100%">
				<table width="100%" cellpadding="0" cellspacing="3" border="0" class="container_editheader">
					<tr>
						<td valign="top" class="info">
							{$container->info.module}
							{if $container->view != ""}<br />Shown in {$container->view} view{/if}
							{if $container->info.workflowPolicy != ""}<br />Uses '{$container->info.workflowPolicy}' Workflow Policy{/if}
						</td>
						<td align="right" valign="top">
							{if $permissions.edit_module == 1 || $container->permissions.administrate == 1}
								<a href="{link action=edit id=$container->id}">
									<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configuremodule.png" title="Change the layout of this {$container->info.module}" alt="Change the layout of this {$container->info.module}" />
								</a>
							{/if}
							{if $permissions.delete_module == 1 || $container->permissions.administrate == 1}
								<a href="{link action=delete id=$container->id}" onClick="return confirm('Are you sure you want to delete this {$container->info.module}?');">
									<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}deletemodule.png" title="Delete this {$container->info.module}" alt="Delete this {$container->info.module}" />
								</a>
							{/if}
							
						</td>
					</tr>
				</table>
				</div>
			</div>
		{/permissions}
			<div class="container_box">
				<div width="100%" style="width: 100%">
				{$container->output}
				</div>
			</div>
		{permissions level=$smarty.const.UILEVEL_STRUCTURE}
		</div>
		{/permissions}
	{else}
		<div class="container_box">
			<div width="100%" style="width: 100%">
			{$container->output}
			</div>
		</div>
	{/if}
{else}
	{permissions level=$smarty.const.UILEVEL_STRUCTURE}
	{if $permissions.add_module == 1 && $hidebox == 0}
		<a href="{link action=edit rank=$rank}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}add.png" title="Add a new module here" alt="Add a new module here" /></a>
	{/if}
	{/permissions}
{/if}