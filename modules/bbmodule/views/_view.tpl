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
<div style="padding:15px;">
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}

{if $permissions.administrate == 1}
	{capture assign=int}b{$board->id}{/capture}
	<a href="{link action=userperms _common=1 int=$int}" title="Assign permissions on this Forum"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1 int=$int}" title="Assign group permissions on this Forum"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{/permissions}

<div class="moduletitle bb_moduletitle">{$board->name}
<div class="bb_boarddesc">{$board->description}</div></div>

<table cellpadding="0" cellspacing="1" border="0" width="100%">
<tr class="bb_boardlist_header">
	<td class="">TOPIC</td>
	<td class="">REPLIES</td>
	<td class="">AUTHOR</td>
	<td class="">VIEWS</td>
	<!--td class="" width="150px">POSTED</td-->
	<td class="" width="150px">LAST UPDATED</td>
</tr>
{foreach from=$announcements item=announcement}
<!--tr class="row {cycle values=odd_,even_}row"-->
<tr class="bb_row">
	<!-- td class="bb_threadlist"><img style="border:none;" src="{$smarty.const.ICON_RELATIVE}thread_icon.gif" title="{$_TR.alt_thread_icon}" alt="{$_TR.alt_thread_icon}" /></td -->
	<td class="bb_threadlist" valign="middle">
		<div style=" float:left; width:25px; height:20px; ">
		<img style="border:none; text-align:left;" src="{$smarty.const.ICON_RELATIVE}sticky16x16.gif" title="{$_TR.alt_sticky}" alt="{$_TR.alt_sticky}" />
		</div>
		<a href="{link action=view_thread id=$announcement->id}" class="mngmntlink bb_mngmntlink">{$announcement->subject}</a>
	</td>
	<td class="center">{$announcement->num_replies}</td>
	<td class="center">{attribution user=$announcement->user}<br /><span class="bb_date">{$announcement->posted|format_date:"%D %T"}</span></td>
	<td class="center">{$announcement->num_views}</td>
	<!--td class="center"><span class="bb_date">{$announcement->posted|format_date:"%D %T"}</span></td-->
	<td class="center"><span class="bb_date">{$announcement->updated|format_date:"%D %T"}<br />{$announcement->last_poster->username}</span></td>
</tr>
{/foreach}
{foreach from=$stickys item=sticky}
<!--tr class="row {cycle values=odd_,even_}row"-->
<tr class="bb_row">
	<!-- td class="bb_threadlist"><img style="border:none;" src="{$smarty.const.ICON_RELATIVE}thread_icon.gif" title="{$_TR.alt_thread_icon}" alt="{$_TR.alt_thread_icon}" /></td -->
	<td class="bb_threadlist" valign="middle">
		<div style=" float:left; width:25px; height:20px; ">
	<img style="border:none; text-align:left;" src="{$smarty.const.ICON_RELATIVE}sticky16x16.gif" title="{$_TR.alt_sticky}" alt="{$_TR.alt_sticky}" />	
		</div>
		<a href="{link action=view_thread id=$sticky->id}" class="mngmntlink bb_mngmntlink">{$sticky->subject}</a>
	</td>
	<td class="center">{$sticky->num_replies}</td>
	<td class="center">{attribution user=$sticky->user}<br /><span class="bb_date">{$sticky->posted|format_date:"%D %T"}</span></td>
	<td class="center">{$sticky->num_views}</td>
	<!--td class="center"><span class="bb_date">{$sticky->posted|format_date:"%D %T"}</span></td-->
	<td class="center"><span class="bb_date">{$sticky->updated|format_date:"%D %T"}<br />{$sticky->last_poster->username}</span></td>
</tr>
{/foreach}
{foreach from=$threads item=thread}
<!--tr class="row {cycle values=odd_,even_}row"-->
<tr class="bb_row">
	<!-- td class="bb_threadlist"><img style="border:none;" src="{$smarty.const.ICON_RELATIVE}thread_icon.gif" title="{$_TR.alt_thread_icon}" alt="{$_TR.alt_thread_icon}" /></td -->
	<td class="bb_threadlist" valign="middle">
		<div style=" float:left; width:0px; height:20px; ">

		</div>
		<a href="{link action=view_thread id=$thread->id}" class="mngmntlink bb_mngmntlink">{$thread->subject}</a>
	</td>
	<td class="center">{$thread->num_replies}</td>
	<td class="center"><a href="{link action=showuserprofile module=loginmodule id=$thread->user->id}" class="mngmntlink bb_mngmntlink">{attribution user=$thread->user}</a><br /><span class="bb_date">{$thread->posted|format_date:"%D %T"}</span></td>
	<td class="center">{$thread->num_views}</td>
	<!--td class="center"><span class="bb_date">{$thread->posted|format_date:"%D %T"}</span></td-->
	<td class="center"><span class="bb_date">{$thread->updated|format_date:"%D %T"}<br /><a href="{link action=showuserprofile module=loginmodule id=$thread->last_poster->id}" class="mngmntlink bb_mngmntlink">{$thread->last_poster->username}</a></span></td>
</tr>
{foreachelse}
<tr>
	<td colspan="2"><i>No posts found on this board</i></td>
{/foreach}

</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.create_thread == 1}
<div align="right">
<a class="bb_icon_link" href="{link module="bbmodule" action="edit_post" bb=$board->id}"><img style="border:none;" src="{$smarty.const.THEME_RELATIVE}images/bb_newtopic.gif" title="{$_TR.alt_bb_newtopic}" alt="{$_TR.alt_bb_newtopic}" /></a>
</div>
{/if}
{/permissions}

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
<br /><br />
{if $loggedin == 1}
{if $monitoring == 1}
<i>
You are monitoring this board for new threads.</i>
<br /><i><a class="mngmntlink bb_mngmntlink" href="{link action=monitor_board id=$board->id monitor=0}">Click here</a> to stop monitoring it.
{else}
You are not monitoring this board.
<br /><a class="mngmntlink bb_mngmntlink" href="{link action=monitor_board id=$board->id monitor=1}">Click here</a> to start monitoring it for new threads.</i>
{/if}
{/if}
</div>
