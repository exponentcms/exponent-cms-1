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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_FULL}modules/cermi/css/picker.css" />
	<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_FULL}modules/cermi/css/treeview/assets/css/folders/tree.css">
	<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_FULL}external/yui/build/menu/assets/menu.css">
	<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_FULL}external/yui/build/container/assets/container.css">
	<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_FULL}external/yui/build/button/assets/skins/sam/button.css">
	<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_FULL}external/yui/build/container/assets/skins/sam/container.css">
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/yui/build/yahoo-dom-event/yahoo-dom-event.js" ></script> 
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/yui/build/animation/animation.js" ></script> 
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/yui/build/dragdrop/dragdrop-min.js" ></script> 
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/yui/build/container/container-min.js" ></script> 
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/yui/build/treeview/treeview-min.js" ></script> 
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/yui/build/menu/menu.js" ></script> 
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/yui/build/connection/connection-min.js" ></script> 
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/yui/build/element/element-beta-min.js" ></script> 
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/yui/build/button/button-min.js" ></script> 
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}modules/cermi/js/picker.js" /></script>
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}modules/cermi/js/funcs.js" /></script>
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}modules/cermi/js/cermi.js" /></script>
	<script type="text/javascript">
		{literal}var limit = {/literal}{$limit}{literal};{/literal}
		{literal}var item_type = {/literal}"{$item_type}"{literal};{/literal}
	</script>
</head>
<body>
	<div id="container" class="yui-skin-sam">
		<div id="left-col">
			<div id="tree-view">&nbsp;</div>
			{include file="_upload_form.tpl"}
		</div>
		<div id="main-pane">
			<div id="scroller">
				{include file="_files.tpl" files=$files}
				<div style="clear:both"></div>
			</div><!-- End Scroller -->
			<div id="file-cart"></div>
			<span id="selectbutton" class="yui-button yui-push-button">
			    	<span class="first-child">
					<input type="button" name="selectbtn" value="Select Files" />
				</span>
			</span>
		</div>
		<div style="clear:both"></div>
	</div><!--End bd -->
</body>
</html>
