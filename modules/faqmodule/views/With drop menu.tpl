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
 
{literal}

<script type="text/javascript" charset="utf-8">
function go()
{
box = document.faqdrop.quicklinks;
destination = box.options[box.selectedIndex].value;
if (destination) location.href = destination;
}
</script>

{/literal}
<div class="faqmodule default-with-drop-menu">
	{if $moduletitle!=""}<h2>{$moduletitle}</h2>{/if}
	
	<a name="top"></a>
	<form name="faqdrop">
	<select id="quicklinks" name="quicklinks" onchange="go()">
		<option selected>{$_TR.plesase_select}</option>
		{foreach from=$qnalist item=qna}
		  <option value="#{$qna->rank}">{$qna->question}</option>
		{/foreach}
	</select>
	</form>
	<hr/>
	{foreach name=c from=$data key=catid item=qnas}

		{if $hasCategories != 0}
			<h3>{$categories[$catid]->name}</h3>
		{/if}

		{foreach name=a from=$qnas item=qna}
			{assign var=qna_found value=0}
			{math equation="x-1" x=$qna->rank assign=prev}
			{math equation="x+1" x=$qna->rank assign=next}
			<div class="item">
				<div class="itemactions">
					{permissions level=$smarty.const.UILEVEL_NORMAL}
						{if $permissions.configure == 1 or $permissions.administrate == 1}
							<a href="{link action=edit_faq id=$qna->id}" title="{$_TR.alt_edit}">
								<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
							</a>
							<a href="{link action=delete_faq id=$qna->id}" onclick="return confirm('{$_TR.delete_confirm}');" title="{$_TR.alt_delete}">
								<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
							</a>	
							{if $smarty.foreach.a.first == 0}
								<a href="{link action=rank_switch a=$qna->rank b=$prev id=$qna->id category_id=$catid}">			
									<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}up.png" title="{$_TR.alt_up}" alt="{$_TR.alt_up}" />
								</a>
							{else}
								<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}up.disabled.png" title="{$_TR.alt_up_disabled}" alt="{$_TR.alt_up_disabled}" />
							{/if}
						
							{if $smarty.foreach.a.last == 0}
								<a href="{link action=rank_switch a=$next b=$qna->rank id=$qna->id category_id=$catid}">
									<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}down.png" title="{$_TR.alt_down}" alt="{$_TR.alt_down}" />
								</a>
							{else}
								<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}down.disabled.png" title="{$_TR.alt_down_disabled}" alt="{$_TR.alt_down_disabled}" />
							{/if}
						{/if}
					{/permissions}
				</div>
				<div class="bodycopy">
					<a name="{$qna->rank}"></a>
					<h4>{$qna->question}</h4>
					<div class="answer">
						{$qna->answer}
					</div>
					<div class="back-to-top"><a href="#top" title="{$_TR.alt_to_the_top}">{$_TR.to_the_top}</a></div>
				</div>
			</div>

			{assign var=qna_found value=1}

		{foreachelse}
			{ if ($config->enable_categories == 1 && $catid != 0) || ($config->enable_categories==0)}
				<i>&nbsp;&nbsp;{$_TR.no_entry}</i>
			{/if}
		{/foreach}
	{foreachelse}
		BLAH
	{/foreach}

	{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
		{if $permissions.administrate == 1}
			<div class="itemactions">
				{br}
				<a class="addfaq additem mngmntlink" href="{link action=edit_faq}">{$_TR.new_entry}</a>
				{if $config->enable_categories == 1}
					{br}
					<a class="mngmntlink" href="{link module=categories action=manage orig_module=faqmodule}">{$_TR.manage_categories}</a>
				{/if}
			</div>
		{/if}
	{/permissions}
</div>