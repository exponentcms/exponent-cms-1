{*
 *
 * Copyright (c) 2004-2011 OIC Group, Inc.
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
 
<div class="faqmodule default-with-toc">
	{if $moduletitle!=""}<h2>{$moduletitle}</h2>{/if}
	<div style="margin: 25px 0px 45px 0px;">
		<ul>
			<a name="top"></a>
			{foreach from=$qnalist item=qna}
				<li><a href="#{$qna->rank}">{$qna->question}</a></li>
			{/foreach}
		</ul>
	</div>
	<table cellspacing="0" cellpadding="0" style="border:none;" width="100%">
		{foreach name=c from=$data key=catid item=qnas}

			{if $hasCategories != 0}
				<tr><td><hr size="1"></td></tr>
				<tr>
					<td colspan="2" class="category_title"><h3>{$categories[$catid]->name}</h3></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			{/if}

			{*
			{assign var=qna_found value=0}

			*}
			{foreach name=a from=$qnas item=qna}
				{assign var=qna_found value=0}
				{math equation="x-1" x=$qna->rank assign=prev}
				{math equation="x+1" x=$qna->rank assign=next}

				<!--tr>
					<td colspan="2"><hr size="1"></td></tr-->
				<tr>
					<td style="padding-left:1.5em" class="question">
						<h4><a name="{$qna->rank}" href="#top">{$qna->question}</a></h4>{br}
					</td>
					{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
						{if $permissions.configure == 1 or $permissions.administrate == 1}
							<td align="right">
								<a href="{link action=edit_faq id=$qna->id}" title="{$_TR.alt_edit}">
									<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />
								</a>
								<a href="{link action=delete_faq id=$qna->id}" onclick="return confirm('{$_TR.delete_confirm}');" title="{$_TR.alt_delete}">
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
								
							</td>
						{/if}
					{/permissions}
				</tr>
				<tr>
					<td style="padding-left:1.5em" colspan="2">
						{$qna->answer}
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				{assign var=qna_found value=1}

			{foreachelse}
				{ if ($config->enable_categories == 1 && $catid != 0) || ($config->enable_categories==0)}
					<tr>
						<td align="center"><i>&nbsp;&nbsp;{$_TR.no_entry}</i></td>
					</tr>
				{/if}
			{/foreach}
		{foreachelse}
			BLAH
		{/foreach}
	</table>
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
