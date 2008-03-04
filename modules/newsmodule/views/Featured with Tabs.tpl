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

		{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}

		{literal}
		<script type="text/javascript">
				function setPicture(img,link) {
						imgDiv = document.getElementById('featured-news-img-a');
						//imgDiv.innerHTML = '<a href="'+link+'"><img src="{/literal}{$smarty.const.PATH_RELATIVE}{literal}thumb.php?file=' + img + '&constraint=1&width=380&height=310" /></a>';
						imgDiv.href = link;
						//YAHOO.util.Dom.setStyle(imgDiv,"background-image",)
						imgDiv.style.backgroundImage = 'url({/literal}{$smarty.const.URL_FULL}{literal}thumb.php?file=' + img + '&constraint=1&width=380&height=310)';
						
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


<div class="newsmodule featured-tab-view">
		{if $moduletitle != ""}<h1>{$moduletitle}</h1>{/if}
	<div class="images">
		<div class="zoom" id="featured-news-img">
			<a id="featured-news-img-a" href="{link action=view id=$news[0]->id }" style="background-image:url({$smarty.const.URL_FULL}thumb.php?file={$news[0]->image_path}&constraint=1&height=310&width=380)">
				&nbsp;
			</a>
		</div>
		<div class="thumbnails">
				{foreach from=$news item=item key=key name="news"}
				{if key < 4}
					{if $smarty.foreach.news.iteration <= $config->item_limit}
						{if $item->image_path!=""}	  
						<div class="thumbnail" style="background:url({$smarty.const.PATH_RELATIVE}thumb.php?file={$item->image_path}&constraint=1&height=9999&width=90)" class="featured-news-picker-img" onclick="setPicture('{$item->image_path}','{link action=view id=$item->id}'); setTitle('{$item->title}'); setSummary('{$item->body|summarize:html:para}');">
							&nbsp;
						</div>
						{/if}
					{/if}
				{/if}
				{/foreach}
		</div>
		<div class="links">
			{if $news|@count >= 4}
				<a href="{link action=view_all_news view=FeaturedTabViewAll}">View All News</a>
			{/if}
			{if $permissions.add_item == true}
				<br /><a class="mngmntlink news_mngmntlink" href="{link action=edit}">Create News</a>
			{/if}
		</div>
	</div>
	<div class="body">
		<div class="featured-news-body">
				{if $news[0]->title !="" }<h4 id="featured-news-title" class="featured-news-title">{$news[0]->title}</h4>{/if}
				<p id="featured-news-summary" class="featured-news-summary">{$news[0]->body|summarize:html:para}</p>
		</div>
	</div>
</div>
