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
{if $template_count == 0}
<div style="font-style: italic;">{$_TR.note}</div>
<hr size="1"/>
{/if}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="5%" style="border-right: 2px solid darkgrey;">&nbsp;</td>
	<td width="65%" class="header htmltemplate_header" style="padding-left: 10px;">{$_TR.template}</td>
	<td width="30%" colspan="2" class="header htmltemplate_header" style="padding-left: 10px; border-right: 2px solid darkgrey;"></td>
</tr>
{foreach name=s from=$modules item=module key=class}
<tr>
<td colspan="2" style="padding: 4px; background-color: lightgrey; font-weight: bold">{$module->name}</td>
<td colspan="2" align="right" style="padding: 4px; background-color: lightgrey;">
	{if $template_count != 0}
	<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=edit_assoc mod=$class}">{$_TR.associate}</a>
	{/if}
</td>
</tr>
	{foreach from=$module->associated item=template_assoc}
		{assign var=tid value=$template_assoc->template_id}
		{assign var=template value=$templates[$tid]}
		<tr>
			<td width="5%" style="border-right: 2px solid darkgrey;">&nbsp;</td>
			<td style="padding: 2px; padding-left: 10px;{if $smarty.foreach.s.last == 1} border-bottom: 2px solid darkgrey;{/if}">{$template->title}</td>
			<td colspan="2" style="padding-left: 10px; border-left: 1px dashed lightgrey;{if $smarty.foreach.s.last == 1} border-bottom: 2px solid darkgrey;{/if}">
				<a class="mngmntlink htmltemplate_mngmntlink" href="{link action=delete_assoc tid=$template_assoc->template_id mod=$class}">
					<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
				</a>
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td width="5%" style="border-right: 2px solid darkgrey;">&nbsp;</td>
			<td colspan="3" align="center" style="font-style: italic; border-right: 2px solid darkgrey;{if $smarty.foreach.s.last == 1} border-bottom: 2px solid darkgrey;{/if}">
				{$_TR.no_assocs}<br />
			</td>
		</tr>
	{/foreach}
<tr><td></td></tr>
{/foreach}
</table>