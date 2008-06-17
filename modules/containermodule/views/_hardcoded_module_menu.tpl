{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by Adam Kessler
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
<div class="hardcoded-menu">
<a id="{$container->info.class}{$container->randomizer}" class="modulemenutrigger hardcoded" href="#" rel="{$container->info.module}">&nbsp;</a>
<span class="modtype viewinfo" title="{$container->info.module}-{$_TR.shown_in|sprintf:$container->view}">&nbsp;</span>
<script type="text/javascript" charset="utf-8">YAHOO.expadminmenus["{$container->info.class}{$container->randomizer}"] =  {getchromemenu module=$container}</script>
</div>

{script yuimodules='"container","menu"' unique="tooltipAndMenu"}
{literal}

var contextElements = YAHOO.util.Dom.getElementsByClassName("viewinfo");

var ttA = new YAHOO.widget.Tooltip("ttA", { 
			context:contextElements,
			zIndex:500
});

YAHOO.util.Event.onDOMReady(function(){

			containerModuleMenus = function () {
				var E =YAHOO.util.Event,
					D =YAHOO.util.Dom;

				var triggers = YAHOO.util.Dom.getElementsByClassName("modulemenutrigger");

				var containermenu = new YAHOO.widget.Menu("containermodulemenu", { 
					position: "dynamic",
					clicktohide: true,
					zIndex:500,
					classname: "containermenu",
					hidedelay: 0,
					fixedcenter: false
					});

				containermenu.render(document.body);

				YAHOO.util.Event.addListener(triggers, "mouseover", function(e){
					containermenu.hide();
					var el = E.getTarget(e);
					var items = YAHOO.expadminmenus[el.id];
					containermenu.cfg.setProperty("context",[el,"tl","tr"]);
					containermenu.clearContent();
					containermenu.addItems(items);
					containermenu.setItemGroupTitle("For this "+el.getAttribute("rel"), 0);
					containermenu.render();
					containermenu.show();
				});

			}();

});	

{/literal}

{/script}




{/permissions}
