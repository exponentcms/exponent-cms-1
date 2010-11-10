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

<div class="formmodule default"> 
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.viewdata == 1 && $form->is_saved == 1}<a class="addnew mngmntlink" href="{link action=view_data module=formbuilder id=$form->id}">{$_TR.view_data}</a>&nbsp;&nbsp;{/if}
		{if $permissions.viewdata == 1 && $form->is_saved == 1}|&nbsp;&nbsp;<a class="addnew mngmntlink" href="{link action=export_csv module=formbuilder id=$form->id}">{$_TR.export_cvs}</a>&nbsp;&nbsp;{/if}
		{if $permissions.editformsettings == 1}|&nbsp;&nbsp;<a class="addnew mngmntlink" href="{link action=edit_form module=formbuilder id=$form->id}">{$_TR.edit_settings}</a>&nbsp;&nbsp;{/if}
		{if $permissions.editform == 1}|&nbsp;&nbsp;<a class="addnew mngmntlink" href="{link action=view_form module=formbuilder id=$form->id}">{$_TR.edit_form}</a>&nbsp;&nbsp;{/if}
		{if $permissions.editreport == 1}|&nbsp;&nbsp;<a class="addnew mngmntlink" href="{link action=edit_report module=formbuilder id=$form->id}">{$_TR.edit_report}</a>&nbsp;&nbsp;{/if}		
	{/permissions}
	<h2>{$moduletitle}</h2>
	{if $formmsg != "" }
		<br /><br />
		{$formmsg}
	{/if}
	 <div style="border: padding: 1em;">
		{$form_html}
	</div>
</div>
