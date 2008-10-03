{*
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
 * GPL: http://www.gnu.org/licenses/gpl.txt
*}
<div class="navigationmodule yui-side-nav exp-yui-nav">
	<div id="sidenav" class="yuimenu">
		<div class="bd">
				<ul class="first-of-type">
				{assign var=startdepth value=0}
				{foreach name="children" key=key from=$sections item=section}
				{assign var=nextkey value=`$key+1`}
				{assign var=previouskey value=`$key-1`}
	
				{if $sections[$previouskey]->depth < $section->depth && $smarty.foreach.children.first!=true}

				<div id="flyout{$section->id}" class="yuimenu">
					<div class="bd">
						<ul class="first-of-type">
				{/if}

				<li class="yuimenuitem{if $section->id == $current->id} current{/if}">
				<a class="yuimenuitemlabel" href="{$section->link}" {if $section->new_window} target="_blank"{/if}>{$section->name}</a>
				{if $sections[$nextkey]->depth == $section->depth}</li>{/if}

				{if $sections[$nextkey]->depth < $section->depth}
				{if $smarty.foreach.children.last==true}
					{assign var=nextdepth value=$startdepth}
				{else}
					{assign var=nextdepth value=$sections[$nextkey]->depth}
				{/if}
				{math equation="x-y" x=$section->depth y=$nextdepth assign=looper}
				{section name="close" loop=$looper}
							</li>
						</ul>
					</div>	
				</div>	

				{/section}
					</li>
			{/if}

			{/foreach}
		</ul>
	</div>
</div>


{script unique="sidemenu" yuimodules="menu"}
{literal}

YAHOO.util.Event.onDOMReady(function(){
	var sidemenu = new YAHOO.widget.Menu("sidenav", { 
											position: "static", 
											hidedelay:	100, 
											lazyload: true });

	sidemenu.render();			  
});



{/literal}
{/script}



</div>

