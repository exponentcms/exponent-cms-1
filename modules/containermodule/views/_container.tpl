{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
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
							{if $container->view != ""}<br />{$_TR.shown_in|sprintf:$container->view}{/if}
							{if $container->info.workflowPolicy != ""}<br />{$_TR.workflow|sprintf:$container->info.workflowPolicy}{/if}
						</td>
						<td align="right" valign="top">
							{if $container->is_private == 1 && $permissions.administrate == 1}
									<a href="{link action=userperms _common=1 int=$container->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="Define which user(s) can see this module" alt="Define which user(s) can see this module" /></a>&nbsp;
									<a href="{link action=groupperms _common=1 int=$container->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="Define which group(s) can see this module" alt="Define which group(s) can see this module" /></a>
							{/if}
							{if $permissions.edit_module == 1 || $container->permissions.administrate == 1}
								<a href="{link action=edit id=$container->id}">
									<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configuremodule.png" title="{$_TR.configure_module|sprintf:$container->info.module}" alt="{$_TR.configure_module|sprintf:$container->info.module}" />
								</a>
							{/if}
							{if $permissions.delete_module == 1 || $container->permissions.administrate == 1}
								<a href="{link action=delete id=$container->id}" onClick="return confirm('{$_TR.delete_confirm|sprintf:$container->info.module}');">
									<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}deletemodule.png" title="{$_TR.delete|sprintf:$container->info.module}" alt="{$_TR.delete|sprintf:$container->info.module}" />
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
		<a href="{link action=edit rank=$rank}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}add.png" title="{$_TR.add_new}" alt="{$_TR.add_new}" /></a>
	{/if}
	{/permissions}
{/if}