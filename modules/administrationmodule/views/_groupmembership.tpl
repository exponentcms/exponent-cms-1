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
<div class="form_title">Group Membership</div>
<div class="form_header">Use this form to manage which user accounts belong to the group  '{$group->name}'</div>


{paginate objects=$users}
	{literal}
	
	function serializeData() {
		elem = document.getElementById("membdata");
		arr = new Array();
		for (i = 0; i < paginate.allData.length; i++) {
			if (paginate.allData[i].var_is_member == 1) arr.push(paginate.allData[i].var_id);
		}
		elem.value = arr.join(",");
	}
	
	function makeMember(id,checked) {
		paginate.allData[id].var_is_member = (checked ? 1 : 0);
	}
	
	function changeAll(checked) {
		for (i = 0; i < paginate.allData.length; i++) {
			paginate.allData[i].var_is_member = ( checked ? 1 : 0 );
		}
		paginate.drawTable();
	}
	
	function isMember(object) {
		html = '<input type="checkbox" ';
		if (object.var_is_member == 1) {
			html += 'checked ';
		}
		html += 'onClick="makeMember('+object.__ID+',this.checked) />';
		return html;
	}
	
	function sortMember(a,b) {
		return (a.var_is_member > b.var_is_member ? -1 : 1);
	}
	
	paginate.columns = new Array(
		new cColumn("User","username",null,null),
		new cColumn("Is Member?","",isMember,sortMember)
	);
	
	{/literal}
{/paginate}

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
<br />
<form method="post">
<input type="hidden" name="module" value="administrationmodule" />
<input type="hidden" name="action" value="gmgr_savemembers" />
<input type="hidden" name="id" value="{$group->id}"/>
<input type="hidden" id="membdata" name="membdata" value="" />
<input type="submit" value="Save" onClick="serializeData(); return true;" />
<input type="button" value="Cancel" onClick="document.location.href = '{$__redirect}';" />
</form>
<br />

<a class="mngmntlink administration_mngmntlink" href="#" onClick="changeAll(true); return false;">Select All</a>
&nbsp;&nbsp;|&nbsp;&nbsp;
<a class="mngmntlink administration_mngmntlink" href="#" onClick="changeAll(false); return false;">Unselect All</a>
