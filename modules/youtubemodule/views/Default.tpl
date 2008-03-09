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

<div class="youtubemodule default">
	{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
	{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}

	<script language="javascript">
        {literal}
                function FlashInstalled()
                {
                        result = false;

                        if (navigator.mimeTypes && navigator.mimeTypes["application/x-shockwave-flash"]) {
                                result = navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin;
                        } else if (document.all && (navigator.appVersion.indexOf("Mac")==-1)) {
                                // IE Windows only -- check for ActiveX control, have to hide code in eval from Netscape (doesn't like try)
                                eval ('try {var xObj = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");if (xObj) result = true; xObj = null; } catch (e) {}');
                        }
                        //alert(result);
                        return result;
                }
        {/literal}
        </script>
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/swfobject/swfobject.js"></script>
	{foreach from=$videos item=youtube}
		<div class="item">
			<div class="bodycopy">
				{if $youtube->id != 0}
					{if $youtube->name != ''}<h2>{$youtube->name}</h2>{/if}
					<div id="flashcontent{$youtube->id}">
                        			No YouTube Video found.
                			</div>
                			{literal}
			                <script type="text/javascript">
                        			var so = new SWFObject("{/literal}{$youtube->url}{literal}","swf{/literal}{$youtube->id}{literal}","{/literal}{$youtube->width}{literal}","{/literal}{$youtube->height}{literal}", "6", "#ffffff");
			                        so.addParam("wmode", "opaque");
                        			so.write("flashcontent{/literal}{$youtube->id}{literal}");
                			</script>
                			{/literal}
					{if $youtube->description != ''}<p>{$youtube->description}</a>{/if}
	       			{else}
       					<p>No video found.</p>
	       			{/if}
			</div>
			<div class="itemactions">
				{permissions level=$smarty.const.UILEVEL_NORMAL}
					{if $permissions.edit == 1}
						<a href="{link action=edit id=$youtube->id}"><img src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
					{/if}
				{/permissions}
			</div>
		</div>
	{/foreach}
	<div style="clear:both"></div>
	<a href="{link action=edit}">Add another video</a>
</div>
