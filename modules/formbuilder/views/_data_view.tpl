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
{paginate objects=$items paginateName="dataView" modulePrefix="data" rowsPerPage=20}

function links(object) {literal}{{/literal}
	out = '<a href="{link action=view_record module=formbuilder}&id=' + object.var_id + '&form_id={$f->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}view.png" /></a>'; 
	out += '{if $permissions.editdata == 1}<a href="{link action=edit_record module=formbuilder}&id=' + object.var_id + '&form_id={$f->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" /></a>{/if}'; 
	out += '{if $permissions.deletedata == 1}<a href="{link action=delete_record module=formbuilder}&id=' + object.var_id + '&form_id={$f->id}" onClick="return confirm(\'{$_TR.delete_confirm}\');"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" /></a>{/if}'; 
	
	return out;
{literal}}{/literal}

{$sortfuncs}

{$columdef}

{/paginate}
<table cellspacing="0" cellpadding="2" border="0" width="100%">
	<tbody id="dataTable">
	</tbody>
</table>
<br>
<table width="100%">
<tr><td align="left" valign="bottom">
<script language="JavaScript">document.write(paginate.drawPageStats(""));</script>
</td><td align="right" valign="bottom">
<script language="Javascript">document.write(paginate.drawPageTextPicker(3));</script>
</td></tr>
</table>
<script language="JavaScript">
	paginate.drawTable();
</script>
<a href="{$backlink}">{$_TR.back}</a>