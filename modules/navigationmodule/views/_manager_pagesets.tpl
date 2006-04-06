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
<table cellpadding="0" cellspacing="0">
<tr><td class="tab_btn">
<a href="{link action=manage}">{$_TR.hierarchy}</a>
</td><td class="tab_btn tab_btn">
<a href="{link action=manage_standalone}">{$_TR.standalone_pages}</a>
</td><td class="tab_btn tab_btn_current">
<a href="{link action=manage_pagesets}">{$_TR.pagesets}</a>
</td><td class="tab_spacer" width="50%">
&nbsp;
</td></tr>
<tr><td colspan="4" class="tab_main">
 
<div class="moduletitle navigation_moduletitle">{$_TR.form_title}</div>
<div class="form_header">
{$_TR.form_header}
<br />
<a class="mngmntlink navigation_mngmntlink" href="{link action=edit_template}">{$_TR.new}</a>
</div>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="header navigation_header"></td>
	<td class="header navigation_header"></td>
</tr>
{foreach from=$templates item=t}
<tr class="row {cycle values='odd,even'}_row">
<td style="padding-left: 10px">
<b>{$t->name}</b>
</td><td>

[ <a class="mngmntlink navigation_mngmntlink" href="{link action=view_template id=$t->id}">{$_TR.view}</a> ]
[ <a class="mngmntlink navigation_mngmntlink" href="{link action=edit_template id=$t->id}">{$_TR.properties}</a> ]
[ <a class="mngmntlink navigation_mngmntlink" href="{link action=delete_template id=$t->id}" onClick="return confirm('{$_TR.delete_confirm}');">{$_TR.delete}</a> ]
</td></tr>
{foreachelse}
<tr><td><i>{$_TR.no_pagesets}</i></td></tr>
{/foreach}
</table>

</td></tr>
</table>