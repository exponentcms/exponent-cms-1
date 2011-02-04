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
 * $Id: Default.tpl,v 1.6.2.1 2005/04/20 16:19:47 filetreefrog Exp $
 *}

<div class="feedlistmodule">
	<h2 class="moduletitle feedlist_moduletitle">
		{if $enable_rss == true}
			<a href="{rsslink}"><img class="mngmnt_icon" style="border: none;" src="{$smarty.const.ICON_RELATIVE}rss-feed.gif" title="{$_TR.alt_subrss}" alt="{$_TR.alt_subrss}" /></a>
		{/if}
		{if $moduletitle != ""}{$moduletitle}{/if}
	</h2>
	<table class="feedlist">
		{foreach item=feed from=$feeds }
			<tr>
				<td>
					<a href="{$feed->link}" title="{$_TR.subscribeto} {$feed->title}">{$feed->title}</a>
				</td>
				<td>
					<a href="http://fusion.google.com/add?feedurl={$feed->link|regex_replace:"/&amp;/":"&"|urlencode}" title="{"Google"|string_format:$_TR.addfeedto}">
					   <img src="{$smarty.const.ICON_RELATIVE}addtogoogle.gif"
					   width="104" height="17" style="border: none;"
					   alt="{"Google"|string_format:$_TR.addfeedto}" />
					</a>    
				</td>
				<td>
					<a href="http://add.my.yahoo.com/rss?url={$feed->link|regex_replace:"/&amp;/":"&"|urlencode}" title="{"My Yahoo!"|string_format:$_TR.addfeedto}" >
					  <img src="{$smarty.const.ICON_RELATIVE}addtomyyahoo.gif"
					   width="91" height="17"
					   style="border: none;" alt="{"My Yahoo!"|string_format:$_TR.addfeedto}" />
					</a>    
				</td>
				<td>
					<a href="http://www.newsgator.com/ngs/subscriber/subext.aspx?url={$feed->link|regex_replace:"/&amp;/":"&"|urlencode}" title="{"Newsgator Online"|string_format:$_TR.addfeedto}">
					   <img src="{$smarty.const.ICON_RELATIVE}addtonewsgator.gif"
						 width="91" height="17"
						 alt="{"Newsgator Online"|string_format:$_TR.addfeedto}" style="border: none;" />
					</a>    
				</td>
				<td>
					 {* bloglines seems to have issues with URL encoding *}
					<a href="http://www.bloglines.com/sub/{$feed->link}" title="{"Bloglines"|string_format:$_TR.addfeedto}"> 
					   <img src="{$smarty.const.ICON_RELATIVE}addtobloglines.gif"
						width="76" height="17" 
						alt="{"Bloglines"|string_format:$_TR.addfeedto}" style="border: none;" />
					</a>
				</td>
			</tr>
		{foreachelse}    
			<tr>
				<td>
					<i>$_TR.no_feeds</i>
				</td>
			</tr>
		{/foreach}
	</table>
</div>
