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

<div class="linklistmodule quick-links">

{if $moduletitle != ""}
	<h2>{$moduletitle}</h2>
{/if}
{if $enable_rss == true}
	<a href="{rsslink}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_rss}" /></a>
{/if}

<ul>
{foreach name=links from=$data[0] item=link}
	{math equation="x-1" x=$link->rank assign=prev}
	{math equation="x+1" x=$link->rank assign=next}
	<li>
		{permissions level=$smarty.const.UILEVEL_NORMAL}
			{if $permissions.edit == 1 || $permissions.delete == 1}
				<div class="itemactions">							
					{if $permissions.edit == 1}
						<a href="{link action=edit_link id=$link->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>
					{/if}
					{if $permissions.delete == 1}
						<a href="{link action=delete_link id=$link->id}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" onclick="return confirm('{$_TR.delete_link_confirm}');" /></a>
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
		<a title="{$link->description}" href="{$link->url}"{if $link->opennew == 1} target="_blank"{/if} >{$link->name}</a>
	</li>
{/foreach}
</ul>

<ul>
{foreach from=$data key=catid item=links}
      {if $catid != 0}
		<li><h3>
            <a href="{link action=view_category id=$catid}">
               {$categories[$catid]->name}
            </a>
            ({$cat_count[$catid]})
         </h3></li>
      {/if}
{/foreach}
</ul>

{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.edit == 1}
    <a class="mngmntlink additem" href="{link action=edit_link}">{$_TR.new_link}</a>
{/if}
{if $permissions.import == 1}
    {br}<a class="mngmntlink" href="{link action=export_import}">{$_TR.export_import}</a>
{/if}
{if ($permissions.manage_categories == 1 && $enable_categories == 1)}
	{br}<a class="mngmntlink cats" href="{link module=categories action=manage orig_module=linkmodule}">{$_TR.manage_categories}</a>
{/if}
{/permissions}
</div>
