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
 
<div class="formmodule view-form">
	<div class="form_title">
		{$_TR.form_title}
	</div>
	<div class="form_header">
		<p>{$_TR.form_header}</p>
	</div>

{$form_html}
<script language="JavaScript">
	function pickSource() {ldelim}
		window.open('{$pickerurl}','sourcePicker','title=no,toolbar=no,width=640,height=480,scrollbars=yes');
	 {rdelim}
</script>
{if $edit_mode != 1}
	<table cellpadding="5" cellspacing="0" border="0">
		<tr>
			<td>
				<form method="post" action="{$smarty.const.URL_FULL}index.php">
					<input type="hidden" name="module" value="formbuilder" />
					<input type="hidden" name="action" value="edit_control" />
					<input type="hidden" name="form_id" value="{$form->id}" />
					{$_TR.add_a} <select name="control_type" onchange="this.form.submit()">
						{foreach from=$types key=value item=caption}
							<option value="{$value}">{$caption}</option>
						{/foreach}
					</select>
				</form>
			</td>
		</tr>
	</table>
	<p><a href="JavaScript: pickSource();">{$_TR.append_existing}</a></p>
	<p><a href="{$backlink}">{$_TR.done}</a></p>
{/if}

</div>
