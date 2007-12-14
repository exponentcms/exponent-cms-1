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

<div class="navigationmodule manager">
	<div id="nav-tabs" class="yui-navset">
	    <ul class="yui-nav">
        	<li class="selected"><a href="#tab1"><em>Hierarchy</em></a></li>
	        <li><a href="#tab2"><em>Standalone</em></a></li>
        	<li><a href="#tab3"><em>Page Sets</em></a></li>
	    </ul>            
	    <div class="yui-content">
        	<div id="tab1">{include file="`$smarty.const.BASE`modules/navigationmodule/views/_manager_hierarchy.tpl"}</div>
	        <div id="tab2">{chain module=navigationmodule action=manage_standalone}</div>
        	<div id="tab3">{chain module=navigationmodule action=manage_pagesets}</div>
	    </div>
	</div>
</div>
<script type="text/javascript">
    {literal}var tabView = new YAHOO.widget.TabView('nav-tabs');{/literal}
</script>

