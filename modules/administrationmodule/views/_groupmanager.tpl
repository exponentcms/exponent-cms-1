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
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header">{$_TR.form_header}
{if $perm_level == 2}
<br /><br />
<a class="mngmntlink administration_mngmntlink" href="{link action=gmgr_editprofile id=0}">{$_TR.new_group}</a>
{/if}
</div>
{paginate name="groups" objects=$groups modulePrefix="administration" rowsPerPage=20}{literal}

	paginate.noRecords = "{/literal}{$_TR.no_group_exist}{literal}";

	function links(object) {
		var out = '';
		{/literal}
			{if $perm_level == 2}
			// Edit link
			out += '<a class="mngmntlink administration_mngmntlink" href="'+makeLink('module','administrationmodule','action','gmgr_editprofile','id',object.var_id) +'"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" /></a>';
			// Delete link
			out += '<a class="mngmntlink administration_mngmntlink" href="'+makeLink('module','administrationmodule','action','gmgr_delete','id',object.var_id) +'" onclick="return confirm(\'{$_TR.sure_to_delete_group} \\\'' + object.var_name + '\\\'?\');"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" /></a>';
			{/if}
			// Members link
			out += '<a class="mngmntlink administration_mngmntlink" href="'+makeLink('module','administrationmodule','action','gmgr_membership','id',object.var_id) +'">{$_TR.members}</a>';
		{literal}
		return out;
	}

	function type(object) {
		if (object.var_inclusive == 1) return '<b>{/literal}{$_TR.default}{literal}</b>';
		else return 'Normal';
	}

	function sortType(a,b) {
		return (a.var_inclusive > b.var_inclusive ? -1 : 1);
	}

	paginate.columns = new Array(
		new cColumn("{/literal}{$_TR.group_name}{literal}","name",null,null),
		new cColumn("{/literal}{$_TR.signup_code}{literal}","code",null,null),
		new cColumn("{/literal}{$_TR.type}{literal}","",type,sortType),
		new cColumn("","",links,null)
	);
{/literal}{/paginate}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody id="dataTable">

	</tbody>
</table>
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
