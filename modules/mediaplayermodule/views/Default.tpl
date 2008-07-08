{*
 * Copyright (c) 2004-2008 OIC Group, Inc.
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
<div class="mediaplayermodule default">
{if $moduletitle != ""}{$moduletitle}{/if}
 {permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=edit}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}

{if $noupload == 1}
<div class="error">
{$_TR.uploads_disabled}<br />
{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}{$_TR.err_foundfile}
{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}{$_TR.err_cantmkdir}
{else}{$_TR.err_unknown}
{/if}
</div>
{/if}
<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/swfobject/swfobject.js"></script>
<div id="{$loc->src}" class="mediaplayer" align="{$data->_align}">
        This text is replaced by FlowPlayer.
</div>
{literal}
<script type="text/javascript">
		var fp = new SWFObject("{/literal}{$smarty.const.URL_FULL}external/flowplayer/FlowPlayerDark.swf{literal}","FlowPlayer", "{/literal}{$data->width}{literal}", "{/literal}{$data->height}{literal}", 9, "{/literal}{$data->bgcolor}{literal}");
		fp.useExpressInstall('{/literal}{$smarty.const.URL_FULL}external/swfobject/expressinstall.swf{literal}');
		fp.addVariable("config", "{videoFile: '{/literal}{$smarty.const.URL_FULL}{$data->_flashurl}{literal}', initialScale: 'fit', showMenu: false, showFullScreenButton: false, {/literal}{if $data->autoplay == 0}{literal}autoPlay: false, {/literal}{/if}{if $data->loop_media == 0}{literal}loop: false, {/literal}{/if}{if $data->auto_rewind == 1}{literal}autoRewind: true, {/literal}{/if}{if $data->hide_controls == 1}{literal}hideControls: true, {/literal}{/if}{literal}protected: true}");
        fp.write("{/literal}{$loc->src}{literal}");
</script>
{/literal}
</div>