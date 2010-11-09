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
<div class="form_title">{$form_title}</div>
<div class="form_header"><p>
{$form_header}
</p></div>
<form name="form" method="post" action="{$smarty.const.PATH_RELATIVE}index.php" enctype="">
<input type="hidden" name="collection_id" id="collection_id" value="{$tag_collection}" />
<input type="hidden" name="module" id="module" value="tags" />
<input type="hidden" name="action" id="action" value="save_tag" />
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td style="width:75px; text-align: left; vertical-align: middle; padding-left: 25px;">
		{$tag_label}:&nbsp;&nbsp;
		<input type="text" name="name" value="" size="20" maxsize="30">
		{literal}
		<input type="submit" value="Save" onclick="if (checkRequired(this.form)) { return true; } else { return false; }" style="margin-bottom:-3px;"/>
		<input type="button" value="Cancel" onclick="document.location.href='index.php?module=tags&action=manage'" style="margin-bottom:-3px;"/>
		{/literal}
	</td>
</tr>
</table>
</form>
{br}
<div align="center">
<table cellspacing="0" cellpadding="0" border="0" width="75%">
<tr>
        <td class="header" style="padding-left: 5px;" colspan="2">{$tag_label}s</td>
</tr>
{foreach name=a from=$existing_tags item=tag}
<tr class="row {cycle values=odd_row,even_row}">
        <td style="padding-left: 5px;">
                <b>{$tag->name}</b>
        </td>
	<td style="text-align: right; padding-right: 5px;">
		<a href="{link module=tags action=delete_tag id=$tag->id}" title="Delete this tag" style="border: 0px solid black;">
                        <img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />
                </a>
	</td>
<tr>
{foreachelse}
<tr>
        <td align="center"><i>{$_TR.no_tags}</i></td>
</tr>
{/foreach}
</table>
</div>
