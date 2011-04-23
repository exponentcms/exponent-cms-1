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
 
<div class="feedlistmodule default">
	<h2 class="moduletitle feedlist_moduletitle">
	{if $enable_rss == true}
		<a href="{rsslink}"><img class="mngmnt_icon" style="border: none;" src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_subrss}" alt="{$_TR.alt_subrss}" /></a>
	{/if}
	{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	<dl class="feedlist">
		{foreach item=feed from=$feeds }
			<dt>
				<a href="{$feed->link}" title="{$_TR.subscribeto} {$feed->title}">{$feed->title}</a>
			</dt>
			<dd>
				{$feed->description}
			</dd>
		{foreachelse}
			<dt>
				<i>$_TR.no_feeds</i>
			</dt>
		{/foreach}
	</dl>
</div>
