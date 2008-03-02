{*
 * Copyright (c) 2004-2005 OIC Group, Inc.
 * Written and Designed by Phillip Ball and  Adam Kessler
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
 *{math equation="x*20" x=$section->depth}
 *}
<div class="navigationmodule navigationmodule-yui-top-nav">
	<div id="flyoutmenujs" class="yuimenubar yuimenubarnav">
		<div class="bd">
			<ul class="first-of-type">
{foreach name="children" key=key from=$sections item=section}{if $section->depth==0}
	{if $section->name!="Home"}
	{if $section->active == 1}
		<li class="yuimenubaritem first-of-type">
			<a class="yuimenubaritemlabel" href="{link section=$section->id}">{$section->name|replace:"&":"&amp;"}</a>
		</li>
	{else }
		<li class="yuimenuitem">
			<span class="yuimenulabel">{$section->name}</span>
		</li>
		{/if}
		{/if}
{/if}{/foreach}
			</ul>
		</div>
	</div>
{yuimenubar buildon="flyoutmenujs"}
</div>
