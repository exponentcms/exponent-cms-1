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

<div class="newsmodule featured-news-with-picker">
        {include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}

        {literal}
        <script type="text/javascript">
                function setPicture(img) {
                        imgDiv = document.getElementById('featured-news-img');
                        imgDiv.innerHTML = '<img src="{/literal}{$smarty.const.PATH_RELATIVE}{literal}thumb.php?file=' + img + '&constraint=1&width=342&height=230" />';
                }

                function setTitle(title) {
                        titleDiv = document.getElementById('featured-news-title');
                        titleDiv.innerHTML = title;
                }
        
                function setSummary(summary) {
                        sumDiv = document.getElementById('featured-news-summary');
                        sumDiv.innerHTML = summary;
                }
        </script>
        {/literal}

        {if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}

	<div class="topbg bgcolor1">
		<div id="featured-news-img">
		        <a class="mngmntlink calendar_mngmntlink" href="{link action=view id=$item->id date_id=$item->eventdate->id}">
		                <img src="{$smarty.const.PATH_RELATIVE}thumb.php?file={$news[0]->image_path}&constraint=1&width=342&height=230" />
		        </a>
		</div>
		<div id="featured-news-picker">
		        {foreach from=$news item=item name="news"}
		                <div class="featured-news-picker-img" onclick="setPicture('{$item->image_path}'); setTitle('{$item->title}'); setSummary('{$item->body|summarize:html:para}');">
		                        <img src="{$smarty.const.PATH_RELATIVE}thumb.php?file={$item->image_path}&width=96&height=43" />
		                </div>
		        {/foreach}
		</div>
		{clear}
	</div>
	<div class="lowerbg bgcolor2 textcolor1">
		<div id="featured-news-body">
		        <h4 id="featured-news-title">{$news[0]->title}</h4>
		        <p id="featured-news-summary">{$news[0]->body|summarize:html:para}</p>
		</div>
	</div>
	{if $morenews == 1}
	<a href="{link action=view_all_news}">{$_TR.view_all}</a>
	{/if}
	{if $permissions.add_item == true}
		<br /><a class="mngmntlink news_mngmntlink" href="{link action=edit}">{$_TR.create_news}</a>
	{/if}
</div>
