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
<div class="navigationmodule yui-side-nav">
<div id="flyoutmenu" class="yuimenu">
	<div class="bd">
			<ul class="first-of-type">
			{assign var=startdepth value=0}
			{foreach name="children" key=key from=$sections item=section}
			{assign var=nextkey value=`$key+1`}
			{assign var=previouskey value=`$key-1`}
	
			{if $sections[$previouskey]->depth < $section->depth && $smarty.foreach.children.first!=true}

			<div id="flyout`$section`" class="yuimenu">
				<div class="bd">
					<ul class="first-of-type">

		{/if}

		{if $section->active == 1}
			<li class="yuimenuitem">
			<a class="yuimenuitemlabel" href="{$section->link}">{$section->name}</a>
			{if $sections[$nextkey]->depth == $section->depth}</li>{/if}
		{/if}

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


{literal}
<script type="text/javascript">
/*
     Initialize and render the Menu when its elements are ready 
     to be scripted.
*/

YAHOO.util.Event.onContentReady("flyoutmenu", function () {

    /*
         Instantiate a Menu:  The first argument passed to the 
         constructor is the id of the element in the page 
         representing the Menu; the second is an object literal 
         of configuration properties.
    */

    var sidemenu = new YAHOO.widget.Menu("flyoutmenu", { 
                                            position: "static", 
                                            hidedelay:  100, 
                                            lazyload: true });

    /*
         Call the "render" method with no arguments since the 
         markup for this Menu instance is already exists in the page.
    */

    sidemenu.render();            

});

</script>
{/literal}



</div>

