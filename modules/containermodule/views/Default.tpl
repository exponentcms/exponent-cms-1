{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by Phillip Ball
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
		
		
		<div id="perms-containermod-{$top->id}" class="yuimenubar yuimenubarnav perms">
		<div class="bd">
			<ul class="first-of-type">
			<li class="yuimenubaritem first-of-type" >
				<a class="yuimenubaritemlabel dropmenu" href="javascript:void(0)">
			&nbsp;
				</a>
			</li>
			<ul>
		</div>
		</div>
					{literal}
					<script type="text/javascript">

					            // Initialize and render the menu bar when it is available in the DOM

					            YAHOO.util.Event.onContentReady("{/literal}perms-containermod-{$top->id}{literal}", function () {

					                function oncMenuBarBeforeRender(p_sType, p_sArgs) {
										
					                    var oSubmenuData = {

					                        "{/literal}containerMenu-{$top->id}{literal}": [ 
												
				                            { text: "User Permissions", classname: "userperms" , url: "{/literal}{link _common=1 action=userperms}{literal}" },
				                            { text: "Group Permissions", classname: "groupperms" , url: "{/literal}{link _common=1 action=groupperms}{literal}" },
				                            { text: "Configure View", classname: "configview" , url: "{/literal}{link action=edit id=$top->id}{literal}" },
				                       			
				 							]


					                    };
										
											
					                    
										
					                    this.getItem(0).cfg.setProperty("submenu", { id: "{/literal}containerMenu-{$top->id}{literal}", itemdata: oSubmenuData["{/literal}containerMenu-{$top->id}{literal}"] });
										//this.cfg.setProperty("zIndex",{/literal}{$zindex}{literal} );
										
					                }


				 					var containerDrop{/literal}{$top->id}{literal} = new YAHOO.widget.MenuBar("{/literal}perms-containermod-{$top->id}{literal}", { autosubmenudisplay: true, showdelay: 250, hidedelay:  750, lazyload: true, submenualignment: ["tl","br"] });


					                // Subscribe to the "beforerender" event

					                containerDrop{/literal}{$top->id}{literal}.beforeRenderEvent.subscribe(oncMenuBarBeforeRender);					
					                containerDrop{/literal}{$top->id}{literal}.render();            

					            });

					        </script>
					{/literal}		

				
		
		
		
		{$_TR.container_module} {$_TR.shown_in|sprintf:$__view}
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
		
		{if ($permissions.administrate == 1 || $permissions.edit_module == 1 || $permissions.delete_module == 1 || $permissions.add_module == 1 || $container->permissions.administrate == 1)}
			{permissions level=$smarty.const.UILEVEL_STRUCTURE}
					
					<div class="container_modulewrapper">
						<div class="container_moduleheader">
								
								
								{math equation="999 - z" z=$smarty.foreach.c.iteration assign=zindex}
								<div id="perms-{$container->info.class}-{$container->id}" style="position:relative; z-index:{$zindex}" class="yuimenubar yuimenubarnav perms">
								   <div class="bd">
		                                <ul class="first-of-type">

		                                    <li class="yuimenubaritem first-of-type"><a class="yuimenubaritemlabel dropmenu" href="javascript:void(0);">&nbsp;</a></li>
		                                </ul>
		                            </div>
								{literal}
								<script type="text/javascript">

								            // Initialize and render the menu bar when it is available in the DOM

								            YAHOO.util.Event.onContentReady("{/literal}perms-{$container->info.class}-{$container->id}{literal}", function () {

								                // "beforerender" event handler for the menu bar

								                function onMenuBarBeforeRender(p_sType, p_sArgs) {

								                    var oSubmenuData = {

								                        "{/literal}menu-{$container->info.class}-{$container->id}{literal}": [ 
								
															{/literal}
															{if $smarty.foreach.c.first == false}
																{if $permissions.order_modules == 1}
																{math equation='x - 2' x=$smarty.foreach.c.iteration assign=a}
																{math equation='x - 1' x=$smarty.foreach.c.iteration assign=b}
															{literal}
								                            { text: "Move Module Up", classname: "rankup" , url: "{/literal}{link action=order a=$a b=$b}{literal}" },
															{/literal}
																
																{/if}
															{/if}
															
															
															{if $smarty.foreach.c.last == false}
																{if $permissions.order_modules == 1}
																{math equation='x - 1' x=$smarty.foreach.c.iteration assign=a}{literal}
								                            { text: "Move Module Down", classname: "rankdown" , url: "{/literal}{link action=order a=$a b=$smarty.foreach.c.iteration}{literal}" },
															
															
																{/literal}{/if}
															{/if}
															
															{if $container->permissions.administrate == 1}{literal}
								                            { text: "User Permissions", classname: "userperms" , url: "{/literal}{link module=$container->info.class action=userperms _common=1 int=$container->id}{literal}" },
								                            { text: "Group Permissions", classname: "groupperms" , url: "{/literal}{link module=$container->info.class action=groupperms _common=1 int=$container->id}{literal}" },
							                       			{/literal}{/if}
															
															{if $permissions.edit_module == 1 || $container->permissions.administrate == 1}
															{literal}
								                            { text: "Configure View", classname: "configview" , url: "{/literal}{link action=edit id=$container->id}{literal}" },
															{/literal}
															{/if}
															
															{if $container->permissions.configure == 1}
															{literal}
								                            { text: "Configure Settings", classname: "configsettings" , url: "{/literal}{link module=$container->info.class source=$container->info.source action=configure _common=1}{literal}" },
															{/literal}
															{/if}
															
															{if $permissions.delete_module == 1 || $container->permissions.administrate == 1}{literal}
								                            { text: "Remove Module", classname: "deletemod" , url: "{/literal}{link action=delete rerank=1 id=$container->id}{literal}" },
							                       			{/literal}{/if}{literal}
							 							]


								                    };

								                    // Add a submenu to each of the menu items in the menu bar
														
								                    this.getItem(0).cfg.setProperty("submenu", { id: "{/literal}menu-{$container->info.class}-{$container->id}{literal}", itemdata: oSubmenuData["{/literal}menu-{$container->info.class}-{$container->id}{literal}"] });
								                    //this.cfg.setProperty("zIndex",{/literal}{$zindex}{literal} );

								                }


								                /*
								                     Instantiate the menubar.  The first argument passed to the 
								                     constructor is the id of the element in the DOM that 
								                     represents the menubar; the second is an object literal 
								                     representing a set of configuration properties for 
								                     the menubar.
								                */

								                var {/literal}menubar{$container->info.class}{$container->id}{literal} = new YAHOO.widget.MenuBar("{/literal}perms-{$container->info.class}-{$container->id}{literal}", { autosubmenudisplay: true, showdelay: 250, hidedelay:  750, lazyload: true });


								                // Subscribe to the "beforerender" event

								                {/literal}menubar{$container->info.class}{$container->id}{literal}.beforeRenderEvent.subscribe(onMenuBarBeforeRender);


								                /*
								                     Call the "render" method with no arguments since the markup for 
								                     this menu already exists in the DOM.
								                */

								                {/literal}menubar{$container->info.class}{$container->id}{literal}.render();            

								            });

								        </script>
								{/literal}
								
								</div>
								
								
								{*}<div class="bd">
									<ul class="first-of-type" style="height:1px">
									
								
									<li class="yuimenubaritem first-of-type" >
										<a class="yuimenubaritemlabel dropmenu" href="javascript:void(0)">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										</a>
										<div id="user-group-perms{$container->id}" class="yuimenu">
											<div class="bd">
												<ul class="sub">
												{if $smarty.foreach.c.first == false}
													{if $permissions.order_modules == 1}
													{math equation='x - 2' x=$smarty.foreach.c.iteration assign=a}
													{math equation='x - 1' x=$smarty.foreach.c.iteration assign=b}
												<li class="yuimenuitem">	
														<a class="yuimenuitemlabel rankup" href="{link action=order a=$a b=$b}">
															Move module up
														</a>
												</li>
													{/if}
												{/if}
												{if $smarty.foreach.c.last == false}
													{if $permissions.order_modules == 1}
													{math equation='x - 1' x=$smarty.foreach.c.iteration assign=a}
													<li class="yuimenuitem">	
														<a class="yuimenuitemlabel rankdown" href="{link action=order a=$a b=$smarty.foreach.c.iteration}">
															Move module down
														</a>
													</li>
													{/if}
												{/if}

												{if $container->permissions.administrate == 1}
													<li class="yuimenuitem"><a class="yuimenuitemlabel userperms" href="{link module=$container->info.class action=userperms _common=1}">{$_TR.perm_user}</a></li>
													<li class="yuimenuitem"><a class="yuimenuitemlabel groupperms" href="{link module=$container->info.class action=groupperms _common=1}">{$_TR.perm_group}</a></li>
												{/if}
												{if $container->is_private == 1 && $permissions.administrate == 1}
													<li class="yuimenuitem"><a class="yuimenuitemlabel" href="{link action=userperms _common=1 int=$container->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_define_user}" alt="{$_TR.alt_define_user}" /></a></li>
													<li class="yuimenuitem"><a class="yuimenuitemlabel" href="{link action=groupperms _common=1 int=$container->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_define_group}" alt="{$_TR.alt_define_group}" /></a></li>
												{/if}
								
								
								
												{if $permissions.edit_module == 1 || $container->permissions.administrate == 1}
												<li class="yuimenuitem"><a class="yuimenuitemlabel configview" href="{link action=edit id=$container->id}">
														{$_TR.config_view}
													</a></li>
												{/if}
												{if $permissions.delete_module == 1 || $container->permissions.administrate == 1}
												<li class="yuimenuitem"><a class="yuimenuitemlabel deletemod" href="{link action=delete rerank=1 id=$container->id}" onclick="return confirm('{$_TR.delete_confirm|sprintf:$container->info.module}');">
													{$_TR.delete_mod}
													</a></li>
												{/if}
												</ul>
											</div>
										</div>
										
									</li>
									<ul>
								</div>*}
							
							
			{*literal}
			<script type="text/javascript">

			            // Initialize and render the menu bar when it is available in the DOM

			            YAHOO.util.Event.onContentReady("{/literal}perms-{$container->info.class}-{$container->id}{literal}", function () {

			                // Instantiate and render the menu bar

			                var containerDrop{/literal}{$container->id}{literal} = new YAHOO.widget.MenuBar("{/literal}perms-{$container->info.class}-{$container->id}{literal}", { autosubmenudisplay: true, hidedelay: 750, lazyload: true });

			                /*
			                     Call the "render" method with no arguments since the markup for 
			                     this menu already exists in the DOM.
			                */

			                containerDrop{/literal}{$container->id}{literal}.render();

			            });

			        </script>
				{/literal*}		
				
							{$container->info.module} {$_TR.shown_in|sprintf:$container->view}

							
								{if $container->info.workflowPolicy != ""}<br />{$_TR.workflow|sprintf:$container->info.workflowPolicy}{/if}
						
						</div>
					
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
