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
 
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="{$_TR.alt_userperm}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="{$_TR.alt_groupperm}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=edit_wizard module="wizardmodule" id=$wizard_id"}" title="{$_TR.alt_configure}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
{/permissions}

{if $no_form == 0}
	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.viewdata == 1 && $form->is_saved == 1}<br /><a href="{link action=view_data module=wizardmodule}&id={$form->id}">{$_TR.view_data}</a>{/if}
		{*if $permissions.editformsettings == 1}<br /><a href="{link action=edit_form module=wizardmodule}&id={$form->id}">{$_TR.edit_settings}</a>{/if*}
		{*if $permissions.editform == 1}<br /><a href="{link action=view_form module=wizardmodule}&id={$form->id}">{$_TR.edit_form}</a>{/if*}
		{if $permissions.editform == 1}<br /><a href="{link action=view_form module=wizardmodule wizard_page_id=$page_id}">{$_TR.edit_form}</a>{/if}
		{*if $permissions.editreport == 1}<br /><a href="{link action=edit_report module=wizardmodule}&id={$form->id}">{$_TR.edit_report}</a>{/if*}
	{/permissions}
{/if}

{if $numpages != ""}
<br />
Step {$pagenum} of {$numpages}
{/if}

<br /><br />
{$formmsg}
 <div style="border: padding: 1em;">
{$form_html}
</div>

<!--div align="right">
{if $last_page != ""}
		<a href="{link module=wizardmodule action=pagenav page_id=$last_page}" class="mngmnt_link wizard_next_link">< Back</a>
{/if}
{if $last_page != "" && $next_page != ""}
&nbsp;||&nbsp;
{/if}
{if $next_page != ""}
		<a href="{link module=wizardmodule action=pagenav page_id=$next_page}" class="mngmnt_link wizard_next_link">Next ></a>
{/if}
</div-->
