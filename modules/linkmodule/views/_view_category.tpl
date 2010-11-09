{*
 * Copyright (c) 2006 Eric Lestrade 
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

<div class="linklistmodule default">
{if $moduletitle != ""}
	<h2>{$moduletitle}</h2>
{/if}
{if $enable_rss == true}
	<a href="{rsslink}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rss}" /></a>
{/if}
{if $category->id!= 0}
	<div class="itemtitle">
		 <h2>{$category->name}
			 {if $enable_rss_categories == true}
				<a href="{rsslink}&category={$category->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rss_category}" /></a>
			 {/if}
		 </h2>
	</div>
{/if}
<ul>
    {foreach name=links from=$links item=link}
		{math equation="x-1" x=$link->rank assign=prev}
		{math equation="x+1" x=$link->rank assign=next}
		<li>
		{if $category->id !=0}
			<div style="padding-left:1em;">
		{else}
			<div>
		{/if}
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.edit == 1 || $permissions.delete == 1}
				<div class="itemactions">							
					{if $permissions.edit == 1}
						<a href="{link action=edit_link id=$link->id}" title="{$_TR.alt_edit}">
						<img border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" />
						</a>
					{/if}
					{if $permissions.delete == 1}
						<a href="{link action=delete_link id=$link->id}" title="{$_TR.alt_delete}" onClick="return confirm('{$_TR.delete_confirm}');">
						<img border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" />
						</a>	
					{/if}
					{if $orderhow == 2}
						{if $smarty.foreach.links.first == 0}
							<a href="{link action=rank_switch a=$link->rank b=$prev id=$link->id}">			
							<img src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_previous}" alt="{$_TR.alt_previous}" />
							</a>
						{/if}
						{if $smarty.foreach.links.last == 0}
							<a href="{link action=rank_switch a=$next b=$link->rank id=$link->id}">
							<img src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_next}" alt="{$_TR.alt_next}" />
							</a>
						{/if}
					{/if}
				</div>
			{/if}
		{/permissions}
        <strong><a href="{$link->url}" title="{$link->url}" {if $link->opennew == 1} target="_blank"{/if}>{$link->name}</a></strong>
	    {if $link->description!=''}- {$link->description}
	    {/if}
		</div>
 		</li>
	{foreachelse}
		{if ($category->id != 0) }
			<div style="padding-left:1em"><i>{$_TR.no_link}</i></div>
		{/if}
    {/foreach}
</ul>
{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.add == 1}
		<a class="mngmntlink additem" href="{link action=edit_link}">{$_TR.new_link}</a>
	{/if}
	{if $permissions.import == 1}
		{br}<a class="mngmntlink" href="{link action=export_import}">{$_TR.export_import}</a>
	{/if}
	{if $permissions.manage_categories == 1 && $enable_categories == 1}
		{br}<a class="mngmntlink cats" href="{link module=categories action=manage orig_module=linkmodule}">{$_TR.manage_categories}</a>
	{/if}
{/permissions}

{br}
<a href="{link action=return}">{$_TR.return}</a>
</div>
