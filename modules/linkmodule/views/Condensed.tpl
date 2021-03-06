{*
 * Copyright (c) 2011 Eric Lestrade 
 *
 * This file is part of Exponent Linkmodule
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *}

<div class="linklistmodule condensed">
	<h2>
		{if $enable_rss == true}
			<a href="{rsslink}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rss}" /></a>
		{/if}
		{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		<div class="moduleactions">							
			{if $permissions.edit == 1}
				{br}<a class="mngmntlink additem" href="{link action=edit_link}">{$_TR.new_link}</a>
			{/if}
			{if $permissions.import == 1}
				{br}<a class="mngmntlink" href="{link action=export_import}">{$_TR.export_import}</a>
			{/if}
			{if $permissions.manage_categories == 1}
				{br}<a class="mngmntlink cats" href="{link module=categories action=manage orig_module=linkmodule}">{$_TR.manage_categories}</a>
			{/if}
		</div>
	{/permissions}
	{foreach from=$data key=catid item=articles}
	   {if $catid != 0}
		   <div class="itemtitle"><h3>{$categories[$catid]->name}
			 {if $enable_rss_categories == true}
				   <a href="{rsslink}&category={$catid}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rss_category}" /></a>
			 {/if}
			 </h3>
		   </div>
	   {/if}
	   {foreach from=$articles item=article}
		  {if $catid !=0}
			 <div style="padding-left:1em;padding-bottom:0.5em;">
		  {else}
			 <div style="padding-bottom:0.5em;">
		  {/if}
			 <a href="{$article->url}" target="{$target}" >{$article->name}</a>
			 <span class="search_result_item_link">- {$article->url}</span>
			 {if $article->description!=''}{br}{$article->description}{/if}
			 {permissions level=$smarty.const.UILEVEL_NORMAL}
			 {if $permissions.edit == 1}
				<a href="{link action=edit_link id=$article->id}" title="{$_TR.alt_edit}">
				   <img border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" />
				</a>
				<a href="{link action=delete_link id=$article->id}" title="{$_TR.alt_delete}" onClick="return confirm('{$_TR.delete_confirm}');">
				   <img border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" />
				</a>	
			 {/if}
			 {/permissions}
		  </div>
		{foreachelse}
		  {if ( $catid != 0)}
			  <div style="padding-left:1em;"><i>{$_TR.no_link}</i></div>
		  {/if}
		{/foreach}
		{br}
	{/foreach}
</div>