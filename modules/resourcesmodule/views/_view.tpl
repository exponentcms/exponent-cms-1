{*
 * Copyright (c) 2004-2011 OIC Group, Inc.
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
 
<div class="resourcemodule view">
	{if $resource->fileexists}
	<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/audio-player/audio-player.js"></script> 
	<script type="text/javascript">  
		AudioPlayer.setup("{$smarty.const.URL_FULL}external/audio-player/player.swf",{literal} {   
			width: 290,  
			transparentpagebg: "yes"   
		});  {/literal} 
	</script> 
	{/if}
	{printer_friendly_link class="printer-friendly-link" text=$_TR.printer_friendly}
	{br}
	 <div>
		<h2>
		{if ($resource->mimetype->icon != "")}
			<img src="{$smarty.const.MIMEICON_RELATIVE}{$resource->mimetype->icon}" alt="" />
		{/if}
		{$resource->name}
		<div class="itemactions">
		{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
			{if $permissions.administrate == 1 || $resource->permissions.administrate == 1}
				<a class="mngmntlink resources_mngmntlink" href="{link action=userperms int=$resource->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
				<a class="mngmntlink resources_mngmntlink" href="{link action=groupperms int=$resource->id _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
			{/if}
		{/permissions}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.edit == 1 || $resource->permissions.edit == 1}
				<a class="mngmntlink resources_mngmntlink" href="{link action=edit id=$resource->id}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
			{/if}
			{if $permissions.delete == 1 || $resource->permissions.delete == 1}
				<a class="mngmntlink resources_mngmntlink" href="{link action=delete id=$resource->id}" onclick="return confirm('{$_TR.delete_confirm}');">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
				</a>
			{/if}
			{if $permissions.manage_approval == 1 || $resource->permissions.manage_approval == 1}
				<a class="mngmntlink news_mngmntlink" href="{link module=workflow datatype=resourceitem m=resourcesmodule s=$__loc->src action=revisions_view id=$resource->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
			{/if}
		{/permissions}	
		</div>
		</h2>
		{if $resource->category_id != 0 && $hasCategories}
			{assign var=cat1id value=$resource->category_id}
			<p><b>from &quot;{$categories[$cat1id]->name}&quot;</b></p>
		{/if}
		<div class="itemactions">
		{if $resource->locked != 0 && $resource->flock_owner != $user->id && ($permissions.edit == 1 || $resource->permissions.edit == 1)}
			<i>
			{capture assign=name}{$resource->lock_owner->firstname} {$resource->lock_owner->lastname}{/capture}
			{$_TR.locked_by|sprintf:$name}
			{if $user->is_acting_admin != 1}
				{$_TR.no_change}
			{/if}
			</i>
			{br}
		{elseif $resource->locked != 0 && $resource->flock_owner == $user->id}
			<i>{$_TR.you_locked}</i>
			{br}
		{/if}
		<div class="attribution">		
			{if $resource->edited != 0 && $config->sortfield == "edited"}
				{assign var='sortdate' value=$resource->edited}
				{assign var='whopost'  value=$resource->editor}
			{else}
				{assign var='sortdate' value=$resource->posted}
				{assign var='whopost'  value=$resource->poster}
			{/if}
			Size: {$resource->filesize} - {$_TR.uploaded} on {$sortdate|format_date:$smarty.const.DISPLAY_DATE_FORMAT} - <em>({$_TR.downloaded} {$resource->num_downloads} {$_TR.times})</em>
		</div>	
		<p>
		{if !$resource->fileexists}
			<b><i>File is Missing!</i></b>
		{else}
			<a class="mngmntlink resources_mngmntlink" href="{link action=download_resource id=$resource->id}">{$_TR.download}</a>
		{/if}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.edit == 1 || $resource->permissions.edit == 1}
				{if $resource->locked == 0}
					&nbsp;|&nbsp;
					<a class="mngmntlink resources_mngmntlink" href="{link action=updatefile id=$resource->id}">{$_TR.update}</a>
					&nbsp;|&nbsp;			
					<a class="mngmntlink resources_mngmntlink" href="{link action=changelock id=$resource->id}">{$_TR.lock}</a>
				{elseif $resource->flock_owner == $user->id || $user->is_acting_admin == 1}
					&nbsp;|&nbsp;
					<a class="mngmntlink resources_mngmntlink" href="{link action=updatefile id=$resource->id}">{$_TR.update}</a>
					&nbsp;|&nbsp;			
					<a class="mngmntlink resources_mngmntlink" href="{link action=changelock id=$resource->id}">{$_TR.unlock}</a>
				{/if}
			{/if}
		{/permissions}
		</p>
		{if $resource->filename != "" && $resource->fileexists && $resource->mimetype->mimetype == "audio/mpeg"}
			<div class="itemactions">
			<p id="snd{$resource->id}">mp3 file<p>
			{literal}
				<script type="text/javascript"> 
					AudioPlayer.embed("snd{/literal}{$resource->id}{literal}", {soundFile: "{/literal}{$resource->filename}{literal}"});   
				</script> 
			{/literal}
			</div>
			<i>Length (mm:ss) - {$resource->duration}</i>
		{/if}
		</div>
	</div>
	<div style="padding-left: 20px;">
		{$resource->description}
	</div>
</div>
