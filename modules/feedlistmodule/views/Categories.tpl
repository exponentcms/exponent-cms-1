{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
 *}

<div class="feedlistmodule categories">
	<h2 class="moduletitle feedlist_moduletitle">
	{if $enable_rss == true}
		<a href="{rsslink}"><img class="mngmnt_icon" style="border: none;" src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_subrss}" alt="{$_TR.alt_subrss}" /></a>
	{/if}
	{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	{foreach item=feedlist from=$categories key=category}
		<h3>{$category}</h3>
		<dl class="feedlist">
			{foreach item=feed from=$feedlist }
				<dt>
					<a href="{$feeds.$feed->link}" title="{$_TR.subscribeto} {$feed->title}">{$feeds.$feed->title}</a>
				</dt>
				<dd>
					{$feeds.$feed->description}
				</dd>
			{foreachelse}
				<dt>
					<i>$_TR.no_feeds</i>
				</dt>
			{/foreach}
		</dl>
	{foreachelse}
		<i>$_TR.no_categories</i>
	{/foreach}
</div>
