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

<div class="administrationmodule usermanager">
<div class="form_title">
    {$_TR.form_title}
</div>
<div class="form_header">
	<p>{$_TR.form_header}</p>
	{if $smarty.const.SITE_ALLOW_REGISTRATION == 0}
		<blockquote class="error"><i>{$_TR.no_registration}</i></blockquote>
	{/if}
	<a href="{link action=umgr_editprofile id=0}">{$_TR.new_user}</a>
</div>

 {paginate objects=$users paginateName="useradmin" modulePrefix="administration" rowsPerPage=20}{literal}
	function links(object) {
		var out = '';
		if (object.var_is_admin == 0) {
		{/literal}
			out = '<a class="mngmntlink administration_mngmntlink" href="'+eXp.makeLink('module','administrationmodule','action','umgr_edit','id',object.var_id) +'"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" /></a>'+
			  '<a class="mngmntlink administration_mngmntlink" href="'+eXp.makeLink('module','administrationmodule','action','umgr_delete','id',object.var_id) +'" onclick="return confirm(\'{$_TR.sure_delete_user} \\\'' + object.var_firstname + ' ' + object.var_lastname + ' ('+object.var_username+')\\\'?\');"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" /></a>';
			if (object.var_is_locked == 1) {ldelim}
			   out += '<a class="mngmntlink administration_mngmntlink" href="'+eXp.makeLink('module','administrationmodule','action','umgr_lockuser','id',object.var_id,'value',0) +'"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}lock.png" title="{$_TR.alt_unlock}" alt="{$_TR.alt_unlock}" />';
			{rdelim} else {ldelim}
			   out += '<a class="mngmntlink administration_mngmntlink" href="'+eXp.makeLink('module','administrationmodule','action','umgr_lockuser','id',object.var_id,'value',1) +'"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}unlock.png" title="{$_TR.alt_lock}" alt="{$_TR.alt_lock}" />';
			{rdelim}
		   {literal}
		} else {
		{/literal}
			out = '<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}edit.disabled.png" title="{$_TR.alt_edit_disabled}" alt="{$_TR.alt_edit_disabled}" />' +
				'<img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}delete.disabled.png" title="{$_TR.alt_delete_disabled}" alt="{$_TR.alt_delete_disabled}" />';
		{literal}
		}
		return out;
	}

	function realName(object) {
		return object.var_firstname + ' ' + object.var_lastname;
	}

	function sortRealname(a,b) {
		return (a.var_firstname.toLowerCase() + ", " + a.var_lastname.toLowerCase() > b.var_firstname.toLowerCase() + ", " + b.var_lastname.toLowerCase() ? -1 : 1);
	}


	paginate.columns = new Array(
		new cColumn("{/literal}{$_TR.real_name}{literal}","",realName,sortRealname),
		new cColumn("{/literal}{$_TR.username}{literal}","username",null,null),
		new cColumn("{/literal}{$_TR.email}{literal}","email",null,null),
		new cColumn("","",links,null)
	);

	function hideAdmins(object) {
		if (object.var_is_admin == 0) return true;
		else return false;
	}

	function hideNullEmails(object) {
		if (object.var_email.length == 0) return false;
		else return true;
	}

	paginate.filters = new Array(
		new cFilter("{/literal}{$_TR.hide_admins}{literal}",hideAdmins),
		new cFilter("{/literal}{$_TR.hide_empty_emails}{literal}",hideNullEmails)
	);
 {/literal}{/paginate}

<table cellpadding="0" cellspacing="0" style="border:none;" width="100%">
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
<hr size="1" />
<div style="font-weight: bold">{$_TR.filtering}</div>
<script language="JavaScript">
	document.write( paginate.drawFilterForm());
	paginate.drawTable();
</script>
</div>
