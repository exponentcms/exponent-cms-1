{paginate objects=$items paginateName="dataView" modulePrefix="data" rowsPerPage=20}

function links(object) {literal}{{/literal}
	out = '<a href="{link action=view_record module=formbuilder}&id=' + object.var_id + '&form_id={$f->id}"><img border="0" src="{$smarty.const.ICON_RELATIVE}view.gif" /></a>'; 
	out += '{if $permissions.editdata == 1}<a href="{link action=edit_record module=formbuilder}&id=' + object.var_id + '&form_id={$f->id}"><img border="0" src="{$smarty.const.ICON_RELATIVE}edit.gif" /></a>{/if}'; 
	out += '{if $permissions.deletedata == 1}<a href="{link action=delete_record module=formbuilder}&id=' + object.var_id + '&form_id={$f->id}" onClick="return confirm(\'Are you sure you want to delete this record?\');"><img border="0" src="{$smarty.const.ICON_RELATIVE}delete.gif" /></a>{/if}'; 
	
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
<a href="{$backlink}">Back</a>