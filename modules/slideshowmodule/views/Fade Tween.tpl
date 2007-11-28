{*
 *
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
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id: Default.tpl,v 1.4 2005/02/23 23:30:27 filetreefrog Exp $
 *}

<script type="text/javascript">
	fade_images = new Array({foreach name=s from=$slides item=slide}'{$slide->file->directory}/{$slide->file->filename}'{if $smarty.foreach.s.last != 1},{/if}{/foreach});
	delay = {$config->delay};
	random = {$config->random};
</script>

<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}modules/slideshowmodule/fade.js"></script>

<div class="slideshowmodule fade">
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
        	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}

{if $moduletitle != ''}<h1>{$moduletitle}</h1>{/if}

<div id="fade-div" style="background-image: url({$slides[1]->file->directory}/{$slides[1]->file->filename});">
<!--div id="fade-div"-->
	{if $number > 0}
		<!--img src="{$slides[0]->file->directory}/{$slides[0]->file->filename}" style="border: 0 none; filter: alpha(opacity=0); -moz-opacity: 0; opacity: 0;" id="blendimage" alt="" /-->	
		<div id="fade-div-2" style="background-image: url({$slides[0]->file->directory}/{$slides[0]->file->filename})" /></div>	
		<!--div id="fade-div-2"></div-->	
		<script type="text/javascript">fadeImages(0, delay)</script>
	{else}
		<h3>There are no slides in the slideshow.</h3>
	{/if}
</div>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create_slide == 1 || $permissions.edit_slide == 1 || $permissions.delete_slide == 1}
<a class="mngmntlink slideshow_mngmntlink" href="{link action=manage_slides}">Manage Slides</a>
{/if}
{/permissions}

</div>
