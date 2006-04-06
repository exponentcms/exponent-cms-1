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
<script type="text/javascript">
{literal}
var selectionCount = 0;

function registerCheck(cb) {
	if (cb.checked == true) selectionCount++;
	else selectionCount--;
}

function confirmDelete() {
	if (selectionCount == 0) {
		alert("{/literal}{$_TR.none_selected}{literal}");
		return false;
	}
	return confirm('{/literal}{$_TR.delete_confirm}{literal}');
}
{/literal}
</script>
<form method="post" action="">
<input type="hidden" name="module" value="workflow" />
<input type="hidden" name="action" value="revisions_delete" />
<input type="hidden" name="datatype" value="{$datatype}" />
{foreach from=$revisions item=revision}
{assign var=type value=$revision->wf_type}
<div class="{$css[$type]} {if $revision->wf_minor == 0}workflow_approved{else}workflow_inapproval{/if}">
	<div width="100%" style="width: 100%">
	<table cellpadding="2" cellspacing="0" border="0" width="100%">
		<tr>
			<td style="font-weight: bold">{if $revision->title != ""}'{$revision->title}' v{else}V{/if}ersion {$revision->wf_major}.{$revision->wf_minor} :: {attribution user_id=$revision->wf_user_id}
			{if $revision->wf_minor == 0 && $revision->wf_major != $current}
			<input type="checkbox" name="d[{$revision->id}]" onClick="registerCheck(this)" /> {$_TR.delete}
			{/if}</td>
			<td align="right">{$revision->wf_updated|format_date:$smarty.const.DISPLAY_DATETIME_FORMAT}</td>
		</tr>
		<tr>
			<td colspan="2">{$revision->wf_comment}</td>
		</tr>
		<tr>
			<td colspan="2">
				<a class="mngmntlink workflow_mngmntlink" href="{link datatype=$smarty.get.datatype m=$smarty.get.m s=$smarty.get.s action=revisions_viewrevision id=$revision->id}">{$_TR.view}</a>
				{if $revision->wf_minor == 0 && $revision->wf_major != $current}
					&nbsp;|&nbsp;
					<a class="mngmntlink workflow_mngmntlink" href="{link datatype=$smarty.get.datatype m=$smarty.get.m s=$smarty.get.s action=revisions_restore major=$revision->wf_major id=$revision->wf_original}">{$_TR.restore}</a>
				{/if}				
			</td>
		</tr>
	</table>
	</div>
</div>
{foreachelse}
<div align="center"><i>{$_TR.revisions}</i></div>
{/foreach}
<input type="submit" value="{$_TR.delete}" onClick="return confirmDelete();" />
</form>
