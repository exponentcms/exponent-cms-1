{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
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
 * $Id$
 *}
<div class="form_title">Manage Group Accounts</div>
<div class="form_header">Groups are used to treat a set of users as a single entity, mostly for permission management.  This form allows you to determine which users belong to which groups, create new groups, modify existing groups, and remove groups.
<br /><br />
When a new user account is created, it will be automatically added to all groups with a Type of 'Default'
<br /><br />
To create a new group, use the <a class="mngmntlink administration_mngmntlink" href="{link action=gmgr_editprofile id=0}">New Group Account</a> form.</div>
{paginate name="groups" objects=$groups modulePrefix="administration" rowsPerPage=20}{literal}

	paginate.noRecords = "No groups exist.";

	function links(object) {
		var out = '';
		{/literal}
			out = '<a class="mngmntlink administration_mngmntlink" href="'+makeLink('module','administrationmodule','action','gmgr_editprofile','id',object.var_id) +'"><img border="0" src="{$smarty.const.ICON_RELATIVE}edit.gif" /></a>'+
			'<a class="mngmntlink administration_mngmntlink" href="'+makeLink('module','administrationmodule','action','gmgr_delete','id',object.var_id) +'" onClick="return confirm(\'Are you sure you want to delete the group \\\'' + object.var_name + '\\\'?\');"><img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" /></a>' +
			'<a class="mngmntlink administration_mngmntlink" href="'+makeLink('module','administrationmodule','action','gmgr_membership','id',object.var_id) +'">Members</a>';
		{literal}
		return out;
	}
	
	function type(object) {
		if (object.var_inclusive == 1) return '<b>Default</b>';
		else return 'Normal';
	}
	
	function sortType(a,b) {
		return (a.var_inclusive > b.var_inclusive ? -1 : 1);
	}

	paginate.columns = new Array(
		new cColumn("Group Name","name",null,null),
		new cColumn("Type","",type,sortType),
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
