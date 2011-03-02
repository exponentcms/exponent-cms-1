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
 
<div class="resourcemodule default">
<script type="text/javascript" src="{$smarty.const.PATH_RELATIVE}external/audio-player/audio-player.js"></script> 
<script type="text/javascript">  
    AudioPlayer.setup("{$smarty.const.URL_FULL}external/audio-player/player.swf",{literal} {   
        width: 290,  
        transparentpagebg: "yes"   
    });  {/literal} 
</script>
<h2>
{if $config->enable_rss == 1}
	<a href="{rsslink}"><img src="{$smarty.const.ICON_RELATIVE}podcast_small.gif" height="20" width="20" class="itemactions" title="{$_TR.alt_rssfeed}" alt="{$_TR.alt_rssfeed}" /></a>
{/if}
{if $moduletitle != ""}Recent {$moduletitle}{/if}
</h2>
{permissions level=$smarty.const.UILEVEL_NORMAL}
	<div class="moduleactions">
		{if $permissions.post == 1 && $noupload != 1}
			{br}<a class="mngmntlink additem" href="{link action=edit}">{$_TR.upload}</a>
			{*{br}<a class="mngmntlink resources_mngmntlink" href="{link action=upload_zip}">{$_TR.zipfile}</a>*}
		{/if}
		{if $permissions.manage_approval == 1}
			{br}<a class="mngmntlink resources_mngmntlink" href="{link module=filemanager action=admin_mimetypes}">{$_TR.manage_filetypes}</a>
		{/if}
		{if $permissions.administrate == 1}
			{if $config->enable_categories == 1}
				{br}<a class="mngmntlink cats" href="{link module=categories action=manage orig_module=resourcesmodule}">{$_TR.manage_categories}</a>
			{/if}			
		{/if}
	</div>
{/permissions}
{if $config->description}
	{$config->description}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		<div class="moduleactions">
			{if $permissions.edit == 1}
				<a href="{link action=edit_desc id=$config->id}"><img src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit_desc}" alt="{$_TR.alt_edit_desc}" {$smarty.const.XHTML_CLOSING}></a>
			{/if}
		</div>
	{/permissions}
{/if}

<ul>
{assign var=listing_number value=0}
{foreach name=c from=$data key=catid item=resources}
	{assign var=category_printed value=0}
	{assign var=listing_found value=0}
	{if $hasCategories != 0}
		{assign var=category_printed value=1}
	{/if}		
	{foreach name=a from=$resources item=resource}
{if $listing_number < $__viewconfig.num_posts}	
		{assign var=id value=$resource->id}
		{assign var=fid value=$resource->file_id}
		{math equation="x-1" x=$resource->rank assign=prev}
		{math equation="x+1" x=$resource->rank assign=next}
		{if $resource->edited != 0 && $config->sortfield == "edited"}
			{assign var='sortdate' value=$resource->edited}
			{assign var='whopost'  value=$resource->editor}
		{else}
			{assign var='sortdate' value=$resource->posted}
			{assign var='whopost'  value=$resource->poster}
		{/if}
		<li>
		<h3>
		{if $__viewconfig.show_icons && ($resource->mimetype->icon != "")}
			<img src="{$smarty.const.MIMEICON_RELATIVE}{$resource->mimetype->icon}" title="{$resource->mimetype->name}" alt="{$resource->mimetype->name}" />
		{/if}
		{if $__viewconfig.direct_download && $resource->fileexists}
			<a class="itemtitle resources_mngmntlink" href="{link action=download_resource id=$resource->id}" title="Download {$resource->name}{if !$__viewconfig.show_descriptions} - {$resource->description|summarize:"html":"para"}{/if}">{$resource->name}</a>
		{else}
			<a class="itemtitle resources_mngmntlink" href="{link action=view id=$resource->id}"{if !$__viewconfig.show_descriptions} title="{$resource->description|summarize:"html":"para"}"{/if}>{$resource->name}</a>
		{/if}
		{if !$resource->fileexists}
			<p><b><i>(File is Missing)</i></b></p>
		{/if}		
		</h3>
		<div class="itemactions">
		{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
			{if $permissions.administrate == 1 || $resource->permissions.administrate == 1}
				<a class="mngmntlink resources_mngmntlink" href="{link action=userperms int=$resource->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm_one}" alt="{$_TR.alt_userperm_one}" /></a>
				<a class="mngmntlink resources_mngmntlink" href="{link action=groupperms int=$resource->id _common=1}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm_one}" alt="{$_TR.alt_groupperm_one}" /></a>
			{/if}
		{/permissions}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.edit == 1 || $resource->permissions.edit == 1}
				<a class="mngmntlink resources_mngmntlink" href="{link action=edit id=$resource->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
			{/if}
			{if $permissions.delete == 1 || $resource->permissions.delete == 1}
				<a class="mngmntlink resources_mngmntlink" href="{link action=delete id=$resource->id}" onclick="return confirm('{$_TR.delete_confirm}');">
					<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
				</a>
			{/if}
			{if $permissions.manage_approval == 1 || $resource->permissions.manage_approval == 1}
				<a class="mngmntlink resources_mngmntlink" href="{link module=workflow datatype=resourceitem m=resourcesmodule s=$__loc->src action=revisions_view id=$resource->id}" title="{$_TR.alt_revisions}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}revisions.png" title="{$_TR.alt_revisions}" alt="{$_TR.alt_revisions}"/></a>
			{/if}
		{/permissions}
		{if !$hasCategories && $config->orderhow == 2}
			{if $smarty.foreach.a.first == 0}
			<a href="{link action=order a=$resource->rank b=$prev id=$resource->id}">			
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_up}" alt="{$_TR.alt_up}" />
			</a>
			{else}
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.disabled.png" title="{$_TR.alt_up_disabled}" alt="{$_TR.alt_up_disabled}" />
			{/if}
			
			{if $smarty.foreach.a.last == 0}
			<a href="{link action=order a=$next b=$resource->rank id=$resource->id}">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_down}" alt="{$_TR.alt_down}" />
			</a>
			{else}
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.disabled.png" title="{$_TR.alt_down_disabled}" alt="{$_TR.alt_down_disabled}" />
			{/if}	
		{/if}
		</div>
		<div>
		{if $resource->category_id != 0 && $__viewconfig.show_category}
			{assign var=cat1id value=$resource->category_id}
			from &quot;{$categories[$cat1id]->name}&quot; 
		{/if}
		{$_TR.posted} {$sortdate|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</div>
		<div class="itemactions">
		{if $__viewconfig.show_player && $resource->filename != "" && $resource->fileexists && $resource->mimetype->mimetype == "audio/mpeg"}
			<p id="snd{$resource->id}">mp3 file<p>
			{literal}
				<script type="text/javascript"> 
					AudioPlayer.embed("snd{/literal}{$resource->id}{literal}", {soundFile: "{/literal}{$resource->filename}{literal}"});   
				</script> 
			{/literal}
			<i>Length (mm:ss) - {$resource->duration}</i>
		{/if}
		{if $__viewconfig.direct_download}	
			<div class="attribution">		
				Size: {$resource->filesize} - {$_TR.uploaded} on {$sortdate|format_date:$smarty.const.DISPLAY_DATE_FORMAT} - <em>({$_TR.downloaded} {$resource->num_downloads} {$_TR.times})</em>
				{if !$resource->fileexists}
					<p><b><i>File is Missing</i></b></p>
				{/if}
			</div>		
			{permissions level=$smarty.const.UILEVEL_NORMAL}
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
				{if $permissions.edit == 1 || $resource->permissions.edit == 1}
					{if $resource->locked == 0}
						<a class="mngmntlink resources_mngmntlink" href="{link action=updatefile id=$resource->id}">{$_TR.update}</a>
						&nbsp;|&nbsp;			
						<a class="mngmntlink resources_mngmntlink" href="{link action=changelock id=$resource->id}">{$_TR.lock}</a>
					{elseif $resource->flock_owner == $user->id || $user->is_acting_admin == 1}
						<a class="mngmntlink resources_mngmntlink" href="{link action=updatefile id=$resource->id}">{$_TR.update}</a>
						&nbsp;|&nbsp;			
						<a class="mngmntlink resources_mngmntlink" href="{link action=changelock id=$resource->id}">{$_TR.unlock}</a>
					{/if}
				{/if}
			{/permissions}	
		</div>
		{/if}
		{if $__viewconfig.show_descriptions}
			<div class="bodycopy" style="padding-left: 20px;">
				{$resource->description}
			</div>
		{/if}
		</li>
		
{/if}		
		
		{assign var=listing_found value=1}
		{assign var=listing_number value=$listing_number+1}	
	{foreachelse}
		{ if (($config->enable_categories == 1 && $catid != 0) || ($config->enable_categories==0)) && (($listing_number >= $first) && ($listing_number <= $last)) && !$listing_found}	
			{if ($hasCategories != 0) && !$category_printed}
				<hr size="1">
				<li>
				{if $catid != 0}
					<h3>{$categories[$catid]->name}</h3>
				{else}
					<h3>{$_TR.no_category}</h3>
				{/if}	
				</li>
				{assign var=category_printed value=1}
			{/if}			
			<li><em>{$_TR.no_resources}</em></li>
		{/if}	
	{/foreach}
{foreachelse}
{/foreach}
</ul>
<div class="moduleactions">
	<p><a class="moreposts" href="{link _common=1 view='Default' action='show_view'}">{$_TR.more_resources} in &quot;{$moduletitle}&quot;</a></p>
</div>
{if $noupload == 1}
	<div class="error">
		{$_TR.no_upload}{br}
		{if $uploadError == $smarty.const.SYS_FILES_FOUNDFILE}{$_TR.file_in_path}
		{elseif $uploadError == $smarty.const.SYS_FILES_NOTWRITABLE}{$_TR.file_cant_mkdir}
		{else}{$_TR.file_unknown}
		{/if}
	</div>
{/if}
<hr>
</div>