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
 * $Id: _view.tpl,v 1.7 2005/04/08 15:45:49 filetreefrog Exp $
 *}
<div class="bbmodule view">
	{capture assign=int}b{$board->id}{/capture}
	{if $permissions.configure == 1 || $permissions.administrate == 1}
		<div class="modulepermissions">
			{if $permissions.administrate == 1}
			        <a href="{link action=userperms _common=1 int=$int}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
			        <a href="{link action=groupperms _common=1 int=$int}"><img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
			{/if}
		</div>
	{/if}
	{* If you want to link your different forum back to the main forum page *}
	{* Find the section number for your forum page and put it in the section number below *}
	{* and uncomment the next line *}
	{*<a class="backtoboard" href="{link module=navigationmodule section=11}">Back to Discussion Boards</a>*}
	<h1>{$board->name}</h1>
	<div class="bodycopy">
			{$board->description}
	</div>
	<table>
	<tr class="bb_boardlist_header">
		<th>Topic</th>
		<th>Replies</th>
		<th>Author</th>
		<th>Views</th>
		<th>Last Updated</th>
	</tr>
	{foreach from=$announcements item=announcement}
	<tr class="bbrow {cycle values='odd,even'} announcement">
		<td>
			<a class="announcement" href="{link action=view_thread id=$announcement->id}">{$announcement->subject}</a>
		</td>
		<td>{$announcement->num_replies}</td>
		<td>{attribution user=$announcement->user}<br /><span class="date">{$announcement->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</span></td>
		<td class="number">{$announcement->num_views}</td>
		<td><span class="date">{$announcement->updated|format_date:$smarty.const.DISPLAY_DATE_FORMAT}<br />{$announcement->last_poster->username}</span></td>
	</tr>
	{/foreach}
	
	{foreach from=$stickys item=sticky}
	<tr class="bbrow {cycle values='odd,even'} sticky">
		<td>
			<a class="sticky" href="{link action=view_thread id=$sticky->id}">{$sticky->subject}</a>
		</td>
		<td class="number">{$sticky->num_replies}</td>
		<td>{attribution user=$sticky->user}<br /><span class="date">{$sticky->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</span></td>
		<td class="number">{$sticky->num_views}</td>
		<td><span class="date">{$sticky->updated|format_date:$smarty.const.DISPLAY_DATE_FORMAT}<br />{$sticky->last_poster->username}</span></td>
	</tr>
	{/foreach}
	
	{foreach from=$threads item=thread}
	<tr class="bb_row">
		<td class="bb_threadlist" valign="middle">
			<a href="{link action=view_thread id=$thread->id}">{$thread->subject}</a>
		</td>
		<td class="number">{$thread->num_replies}</td>
		<td><a href="{link action=showuserprofile module=loginmodule id=$thread->user->id}">{attribution user=$thread->user}</a><br /><span class="date">{$thread->posted|format_date:$smarty.const.DISPLAY_DATE_FORMAT}</span></td>
		<td class="number">{$thread->num_views}</td>
		<td><span class="date">{$thread->updated|format_date:$smarty.const.DISPLAY_DATE_FORMAT}<br /><a href="{link action=showuserprofile module=loginmodule id=$thread->last_poster->id}" class="mngmntlink bb_mngmntlink">{$thread->last_poster->username}</a></span></td>
	</tr>
	{foreachelse}
	<tr>
		<td colspan="5"><em>{$_TR.no_posts}</em></td>
	{/foreach}
	</table>
	
	
	
	<div class="moduleactions">
		{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.create_thread == 1}
		<div>
		<a class="bb_icon_link" href="{link module="bbmodule" action="edit_post" bb=$board->id}"><img class="button" src="{$smarty.const.ICON_RELATIVE}bb_newtopic.gif" title="{$_TR.alt_bb_newtopic}" alt="{$_TR.alt_bb_newtopic}" /></a>
		</div>
		{/if}
		{/permissions}
	</div>
	
	{if $pagecount>0}
	<div class="pagination">
		Page({$curpage} of {$pagecount})
		{if $curpage != 1}
			<a class="bb_page_link" href="{link module="bbmodule" action="view_board" id=$board->id page=1}"><<</a>&nbsp;
		  	<a class="bb_page_link" href="{link module="bbmodule" action="view_board" id=$board->id page=$curpage-1}"><</a>
		{/if}
		{if $downlimit>1 }...{/if}
		{section name=pages start=$downlimit loop=$pagecount+1 max=$uplimit} 
		  <a class="bb_page_link" href="{link module="bbmodule" action="view_board" id=$board->id page=$smarty.section.pages.index}">
		    {if $curpage == $smarty.section.pages.index}
		    [{$smarty.section.pages.index}]
		    {else}
		    {$smarty.section.pages.index}
		    {/if}
		  </a>  
		{/section}
		{if $uplimit<$pagecount }...{/if}
		{if $curpage != $pagecount}
			<a class="bb_page_link" href="{link module="bbmodule" action="view_board" id=$board->id page=$curpage+1}">></a>&nbsp;
			<a class="bb_page_link" href="{link module="bbmodule" action="view_board" id=$board->id page=$pagecount}">>></a>
		{/if}
	</div>
	{/if}
	<div class="monitoring">
		{if $loggedin == 1}
			{if $monitoring == 1}
				<em>{$_TR.board_monitor}</em>
				<br /><em><a class="mngmntlink bb_mngmntlink" href="{link action=monitor_board id=$board->id monitor=0}">{$_TR.stop_monitoring}</a></em>
		{else}
		{$_TR.not_monitoring}
		<br /><a class="mngmntlink bb_mngmntlink" href="{link action=monitor_board id=$board->id monitor=1}">{$_TR.start_monitoring}</a>
		{/if}
		{else}
			{$_TR.not_logged}
			<a href="{$smarty.const.URL_FULL}login.php">{$_TR.login}</a>
		{/if}	
	</div>
</div>
