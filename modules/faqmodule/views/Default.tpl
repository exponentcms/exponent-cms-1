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
 * $Id: Default.tpl,v 1.4 2005/03/13 18:57:53 filetreefrog Exp $
 *}
<div class="faqmodule default">
	<div class="permissions">
		{include file="`$smarty.const.BASE`modules/common/views/_permission_icons.tpl"}	
	</div>	
	{if $moduletitle!=""}<h1>{$moduletitle}</h1>{/if}
	{if $permissions.administrate == 1}
		<br /><a class="addfaq" href="{link action=edit_faq}">New FAQ Entry</a><br />
	{/if}
	<a name="top"></a>
	<ul>
		{foreach from=$qnalist item=qna}
		  <li><a href="#{$qna->rank}">{$qna->question}</a></li>
		{/foreach}
	</ul>
	{foreach name=c from=$data key=catid item=qnas}

	{if $hasCategories != 0 && $catid != 0}

		{if $categories[$catid]->name == ""}
		<h2>Not Categorized</h2>
		{else}
		<h2>{$categories[$catid]->name}</h2>
		{/if}

	{/if}

{foreach name=a from=$qnas item=qna}
{assign var=qna_found value=0}
	{math equation="x-1" x=$qna->rank assign=prev}
	{math equation="x+1" x=$qna->rank assign=next}
	<div class="item">
		<div class="itemactions">
		{if $permissions.configure == 1 or $permissions.administrate == 1}
			<a href="{link action=edit_faq id=$qna->id}" title="Edit this entry">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
			</a>
			<a href="{link action=delete_faq id=$qna->id}" title="Delete this entry">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
			</a>	
			{if $smarty.foreach.a.first == 0}
			<a href="{link action=rank_switch a=$qna->rank b=$prev id=$qna->id category_id=$catid}">			
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_up}" alt="{$_TR.alt_up}" />
			</a>
			{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}up.disabled.png" title="{$_TR.alt_up_disabled}" alt="{$_TR.alt_up_disabled}" />
			{/if}
		
			{if $smarty.foreach.a.last == 0}
			<a href="{link action=rank_switch a=$next b=$qna->rank id=$qna->id category_id=$catid}">
				<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_down}" alt="{$_TR.alt_down}" />
			</a>
			{else}
			<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}down.disabled.png" title="{$_TR.alt_down_disabled}" alt="{$_TR.alt_down_disabled}" />
			{/if}
		{/if}
		</div>
		<div class="text">
			<a name="{$qna->rank}"></a>
			<h2>{$qna->question}</h2>
			<div class="answer">
				{$qna->answer}
			</div>
		</div>
</div>

{assign var=qna_found value=1}

{foreachelse}
{ if ($config->enable_categories == 1 && $catid != 0) || ($config->enable_categories==0)}
<i>No Questions or Answers were found for this FAQ category.</i>
{/if}
{/foreach}
{foreachelse}
BLAH
{/foreach}


{if $permissions.administrate == 1}
<br />
<a class="addfaq" href="{link action=edit_faq}">New FAQ Entry</a>
<br />
{if $config->enable_categories == 1}
<a href="{link module=categories action=manage orig_module=faqmodule}">Manage Categories</a>
{/if}
{/if}
</div>


