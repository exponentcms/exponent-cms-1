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
 
	{assign var=col1width value="320px"}
	{assign var=col2width value="215px"}
 
 
 
 
{permissions level=$smarty.const.UILEVEL_STRUCTURE}
{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)}
{/if}

{** top level container module **}
{if $hasParent == 0 &&  $permissions.edit_module == 1}
<div class="container_editheader" style="display:table; border:1px dotted #FF0000; width:100%">
		<div>
			{$_TR.container_module} {$_TR.shown_in|sprintf:$__view}
		<div class="permissions" style="float:right">
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
	</div>
{/if}


{/permissions}
{viewfile module=$singlemodule view=$singleview var=viewfile}

<div style="width:{$col2width}; float:right;">
	{assign var=container value=$containers.1}
	{assign var=rank value=1}
	{include file=$viewfile}
</div>

<div style="width:{$col1width}; float:left;">
	{assign var=container value=$containers.0}
	{assign var=rank value=0}
	{include file=$viewfile}
</div>

{if $hasParent == 0 &&  $permissions.edit_module == 1}
</div>
{/if}