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
 
 
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}

{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)} <div class="container_editheader" style="display:table; border:1px dotted #FF0000; width:100%">
  {/if}
{/permissions}


{permissions level=$smarty.const.UILEVEL_STRUCTURE}
{if $hasParent == 0 && ($permissions.edit_module == 1 || $permissions.administrate == 1)}{** top level container module **}
	<div style="background:#66FFFF; display:table; width:100%">
		<div style="float:right;">
			{if $permissions.administrate == 1}
				<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
				<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
			{/if}

			{if $permissions.edit_module == 1}
				<a href="{link action=edit id=$top->id}">
					<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configuremodule.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" />
				</a>
			{/if}
		</div>
		<div class="info">
			{$_TR.container_module} {$_TR.shown_in|sprintf:$__view}
		</div>
	</div>
{/if}
{if $permissions.add_module == 1 && $hidebox == 0}
	<a href="{link action=edit rerank=1 rank=0}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}add.png" title="{$_TR.add_new}" alt="{$_TR.add_new}"/></a>
{/if}

{/permissions}

{viewfile module=$singlemodule view=$singleview var=viewfile}
{foreach name=c from=$containers item=container}
	{assign var=i value=$smarty.foreach.c.iteration}
	{if $smarty.const.SELECTOR == 1}
		{include file=$viewfile}
	{else}
		{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1)}
			{permissions level=$smarty.const.UILEVEL_STRUCTURE}
						<div class="container_editheader" style="display:table; border:1px dotted #993399; width:100%">
						<div style=" background:#99FF00; ">
							<div style="float:right; display:table; clear:both; ">
								{if $container->is_private == 1 && $permissions.administrate == 1}
									<a href="{link action=userperms _common=1 int=$container->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_define_user}" alt="{$_TR.alt_define_user}" /></a>&nbsp;
									<a href="{link action=groupperms _common=1 int=$container->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_define_group}" alt="{$_TR.alt_define_group}" /></a>
								{/if}
								{if $smarty.foreach.c.first == false}
									{if $permissions.order_modules == 1}
									{math equation='x - 2' x=$smarty.foreach.c.iteration assign=a}
									{math equation='x - 1' x=$smarty.foreach.c.iteration assign=b}
									<a href="{link action=order a=$a b=$b}">
										<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.move_up|sprintf:$container->info.module}" alt="{$_TR.move_up|sprintf:$container->info.module}"/>
									</a>
									{/if}
								{/if}
								{if $smarty.foreach.c.last == false}
									{if $permissions.order_modules == 1}
									{math equation='x - 1' x=$smarty.foreach.c.iteration assign=a}
									<a href="{link action=order a=$a b=$smarty.foreach.c.iteration}">
										<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.move_down|sprintf:$container->info.module}" alt="{$_TR.move_down|sprintf:$container->info.module}" />
									</a>
									{/if}
								{/if}
								{if $permissions.edit_module == 1 || $container->permissions.administrate == 1}
									<a href="{link action=edit id=$container->id}">
										<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configuremodule.png" title="{$_TR.configure_module|sprintf:$container->info.module}" alt="{$_TR.configure_module|sprintf:$container->info.module}" />
									</a>
								{/if}
								{if $permissions.delete_module == 1 || $container->permissions.administrate == 1}
									<a href="{link action=delete rerank=1 id=$container->id}" onclick="return confirm('{$_TR.delete_confirm|sprintf:$container->info.module}');">
										<img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}deletemodule.png" title="{$_TR.delete|sprintf:$container->info.module}" alt="{$_TR.delete|sprintf:$container->info.module}" />
									</a>
								{/if}

							</div>
							<div class="info" style="display:table; height:20px;">
								{$container->info.module}
								{if $container->view != ""} {$_TR.shown_in|sprintf:$container->view}{/if}
								{if $container->info.workflowPolicy != ""}<br />{$_TR.workflow|sprintf:$container->info.workflowPolicy}{/if}
							</div>
						</div>

		{/permissions}
		
		{/if}
				{$container->output}
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}

{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)} </div>
  {/if}
{/permissions}
		{permissions level=$smarty.const.UILEVEL_STRUCTURE}
		{if $permissions.add_module == 1 && $hidebox == 0}
			<a href="{link action=edit rerank=1 rank=$smarty.foreach.c.iteration}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}add.png" title="{$_TR.add_new}" alt="{$_TR.add_new}" /></a>
		{/if}
		{/permissions}
	{/if}
{/foreach}
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}

{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)} </div>
  {/if}
{/permissions}
