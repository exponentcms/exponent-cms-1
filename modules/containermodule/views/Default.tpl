{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by Phillip Ball (this file anyways :)
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
	{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)} 
	
		<div class="containermodule">
	{/if}
{/permissions}


{permissions level=$smarty.const.UILEVEL_STRUCTURE}
{if $hasParent == 0 && ($permissions.edit_module == 1 || $permissions.administrate == 1)}{** top level container module **}
	<div class="container_editheader">
		<div id="perms-containermod-{$top->id}" class="yuimenu containermenu">
			<div class="bd trigger">
				<ul class="first-of-type">
					<li class="yuimenuitem">
						<a class="yuimenuitemlabel carrow" href="javascript:void(0)">&nbsp;</a>
					</li>
				</ul>
			</div>

		</div>
		<span class="modtype viewinfo" title="{$_TR.container_module} - {$_TR.shown_in|sprintf:$__view}">&nbsp;</span>
		
		{literal}
		<script type="text/javascript">
			YAHOO.util.Event.onContentReady("{/literal}perms-containermod-{$top->id}{literal}", function () {
				var oMenu = new YAHOO.widget.Menu("{/literal}perms-containermod-{$top->id}{literal}", { 
														position: "static", 
														hidedelay:	750,
														lazyload: true });

				var aSubmenuData = [
				
					{
						id:	 "{/literal}containerMenu-{$top->id}{literal}", 
						itemdata: [ 
						{ text: "{/literal}{$_TR.menu_userperm{literal}", classname: "userperms" , url: "{/literal}{link _common=1 action=userperms}{literal}" },
						{ text: "{/literal}{$_TR.menu_groupperm{literal}", classname: "groupperms" , url: "{/literal}{link _common=1 action=groupperms}{literal}" }
						]
				   
					}					 
				];

			   oMenu.subscribe("beforeRender", function () {
				  
					if (this.getRoot() == this) {

						this.getItem(0).cfg.setProperty("submenu", aSubmenuData[0]);
						//console.debug(this.getSubmenus());

						//this.getSubmenus()[0].setItemGroupTitle("Move Yourself", 0);

					}

				});
				oMenu.render();
			
			});

		</script>		{/literal}
		
		
		
	</div>
{/if}
{if $permissions.add_module == 1 && $hidebox == 0}

	<a id="addmod{$num}" class="addmodule" href="{link action=edit rerank=1 rank=0}"><span class="addtext">{$_TR.add_new}</span></a>

{/if}

{/permissions}

{viewfile module=$singlemodule view=$singleview var=viewfile}



{foreach key=key name=c from=$containers item=container}
	{assign var=i value=$smarty.foreach.c.iteration}
	{if $smarty.const.SELECTOR == 1}
		{include file=$viewfile}
	{else}
		<a name="mod_{$container->id}"></a> 
		{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1)}
			{permissions level=$smarty.const.UILEVEL_STRUCTURE}
					
					<div class="container_modulewrapper">
						<div class="container_moduleheader">
							<div id="perms-{$container->info.class}-{$container->id}" class="yuimenu containermenu">
								<div class="bd trigger">
									<ul class="first-of-type">
										<li class="yuimenuitem">
											<a class="yuimenuitemlabel carrow" href="javascript:void(0)">&nbsp;</a>
										</li>
									</ul>
								</div>

							</div>
							<span class="modtype viewinfo" title="{$container->info.module}-{$_TR.shown_in|sprintf:$container->view}">{*$container->info.module}-{$_TR.shown_in|sprintf:$container->view*}
							{if $container->info.workflowPolicy != ""}<br />{$_TR.workflow|sprintf:$container->info.workflowPolicy}{/if}</span>
						</div>
						{literal}
						<script type="text/javascript">
							YAHOO.util.Event.onContentReady("{/literal}perms-{$container->info.class}-{$container->id}{literal}", function () {
								var oMenu = new YAHOO.widget.Menu("{/literal}perms-{$container->info.class}-{$container->id}{literal}", { 
																		position: "static",
																		zIndex: 50, 
																		hidedelay:	750, 
																		lazyload: true });

								var aSubmenuData = [

									{
										id:	 "{/literal}menu-{$container->info.class}-{$container->id}{literal}", 
										itemdata: [ 
											{/literal}
											{if $smarty.foreach.c.first == false}
												{if $permissions.order_modules == 1}
												{math equation='x - 2' x=$smarty.foreach.c.iteration assign=a}
												{math equation='x - 1' x=$smarty.foreach.c.iteration assign=b}
											{literal}
											{ text: "{/literal}{$_TR.menu_moveup{literal}", classname: "rankup" , url: "{/literal}{link action=order a=$a b=$b}{literal}" },
											{/literal}
											
												{/if}
											{/if}
										
										
											{if $smarty.foreach.c.last == false}
												{if $permissions.order_modules == 1}
												{math equation='x - 1' x=$smarty.foreach.c.iteration assign=a}{literal}
											{ text: "{/literal}{$_TR.menu_movedown{literal}", classname: "rankdown" , url: "{/literal}{link action=order a=$a b=$smarty.foreach.c.iteration}{literal}" },
										
										
												{/literal}{/if}
											{/if}
										
											{if $container->permissions.administrate == 1}{literal}
											{ text: "{/literal}{$_TR.menu_userperm{literal}", classname: "userperms" , url: "{/literal}{link src=$container->info.source module=$container->info.class action=userperms _common=1}{literal}" },
											{ text: "{/literal}{$_TR.menu_groupperm{literal}", classname: "groupperms" , url: "{/literal}{link src=$container->info.source module=$container->info.class action=groupperms _common=1}{literal}" },
											{/literal}{/if}
										
											{if $permissions.edit_module == 1 || $container->permissions.administrate == 1}
											{literal}
											{ text: "{/literal}{$_TR.menu_confview{literal}", classname: "configview" , url: "{/literal}{link src=$container->info.source action=edit id=$container->id}{literal}" },
											{/literal}
											{/if}
										
											{if $container->permissions.configure == 1}
											{literal}
											{ text: "{/literal}{$_TR.menu_confsettings{literal}", classname: "configsettings" , url: "{/literal}{link module=$container->info.class src=$container->info.source action=configure _common=1}{literal}" },
											{/literal}
											{/if}
										
											{if $permissions.delete_module == 1 || $container->permissions.administrate == 1}{literal}
											{ text: "{/literal}{$_TR.menu_deletemod{literal}", classname: "deletemod" , url: "{/literal}{link action=delete rerank=1 id=$container->id}{literal}" }
										   
											{/literal}{/if}{literal}									   
											]

									}					 
								];

							   oMenu.subscribe("beforeRender", function () {

									if (this.getRoot() == this) {

										this.getItem(0).cfg.setProperty("submenu", aSubmenuData[0]);
										//console.debug(this.getSubmenus());

										//this.getSubmenus()[0].setItemGroupTitle("Move Yourself", 0);

									}

								});
								oMenu.render();

							});

						</script>		{/literal}
						
					
		{/permissions}
		
		{/if}
	{*<div id="{$container->info.class}-{$container->id}" class="{$container->info.class} {$container->info.class}-{$container->view|replace:' ':'-'|lower}">*}
	{$container->output}
	{*</div>*}

	{permissions level=$smarty.const.UILEVEL_STRUCTURE}
	{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)} 
	</div>
	{/if}
	{/permissions}


	{permissions level=$smarty.const.UILEVEL_STRUCTURE}
	{if $permissions.add_module == 1 && $hidebox == 0}
	<a id="addmod{$num}" class="addmodule" href="{link action=edit rerank=1 rank=$smarty.foreach.c.iteration}"><span class="addtext">{$_TR.add_new}</span></a>
	{/if}
	{/permissions}
	{/if}
{/foreach}


{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1 || $container->permissions.edit_module == 1 || $container->permissions.delete_module == 1)} 
<div style="clear:both"></div>
</div>
{/if}
{/permissions}
