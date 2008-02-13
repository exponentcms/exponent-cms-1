{*
 *
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 * $Id: Default.tpl,v 1.4 2005/03/13 18:57:28 filetreefrog Exp $
 *}

<div class="articlemodule default">
{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}
<h1>{$moduletitle}</h1>
<table cellspacing="0" cellpadding="0" style="border:none;" width="100%">
{foreach name=c from=$data key=catid item=articles}

{if $hasCategories != 0}
<tr><td><hr size="1"></td></tr>
<tr>
	{if $catid != 0}
	<td colspan="2" class="category_title">{$categories[$catid]->name}</td>
	{else}
	<td colspan="2" class="category_title">{$_TR.no_category}</td>
	{/if}
</tr>
<tr><td>&nbsp;</td></tr>
{/if}
{foreach name=a from=$articles item=article}
{assign var=article_found value=0}
	{math equation="x-1" x=$article->rank assign=prev}
	{math equation="x+1" x=$article->rank assign=next}

<!--tr>
	<td colspan="2"><hr size="1"></td></tr-->
<tr>
	<td style="padding-left:1.5em" class="question">
		<a href="{link action=view_article id=$article->id}" class="article_title_link">{$article->title}<br />
	</td>
	{if $permissions.manage == 1}
	<td align="right">
		<a href="{link action=edit_article id=$article->id}" title="{$_TR.alt_edit}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
		</a>
		<a href="{link action=delete_article id=$article->id}" title="{$_TR.alt_delete}" onclick="return confirm('{$_TR.delete_confirm}');">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
		</a>	
		{if $smarty.foreach.a.first == 0}
		<a href="{link action=rank_switch a=$article->rank b=$prev id=$article->id category_id=$catid}">			
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_up}" alt="{$_TR.alt_up}" />
		</a>
		{else}
		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.disabled.png" title="{$_TR.alt_up_disabled}" alt="{$_TR.alt_up_disabled}" />
		{/if}
		
		{if $smarty.foreach.a.last == 0}
		<a href="{link action=rank_switch a=$next b=$article->rank id=$article->id category_id=$catid}">
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_down}" alt="{$_TR.alt_down}" />
		</a>
		{else}
		<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.disabled.png" title="{$_TR.alt_down_disabled}" alt="{$_TR.alt_down_disabled}" />
		{/if}
		
	</td>
	{/if}
<tr>
<tr>
	<td style="padding-left:1.5em" class="article_summary" colspan="2">
		{$article->body|summarize:'html':'para'}
	</td>
</tr>
<tr><td>&nbsp;</td></tr>
{assign var=article_found value=1}

{foreachelse}
{ if ($config->enable_categories == 1 && $catid != 0) || ($config->enable_categories==0)}
<tr>
	<td align="center"><i>{$_TR.no_article}</i></td>
</tr>
{/if}
{/foreach}
{foreachelse}
{/foreach}
</table>

{if $permissions.manage == 1}
	{if $submissions > 0}<a href="{link action=view_submissions}">You have {$submissions} submitted article{if $submissions > 1}s{/if}</a>{/if}
	<br /><a href="{link action=edit_article}">{$_TR.new_entry}</a><br />
	{if $config->enable_categories == 1}
		<a href="{link module=categories action=manage orig_module=articlemodule}">{$_TR.manage_categories}</a>
	{/if}
{/if}

{if $config->allow_submissions == 1}
	{get_user assign=user}
	{if $config->require_login == 0 || $user->id > 0}
		<a href="{link action=submit_article}">Submit an Article</a>
	{/if}
{/if}
</div>
