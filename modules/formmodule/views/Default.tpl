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
	<a href="{link action=userperms _common=1}" title="{$_TR.alt_userperm}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}userperms.png" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="{$_TR.alt_groupperm}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.png" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="{$_TR.alt_configure}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}configure.png" /></a>
{/if}
{/permissions}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.viewdata == 1 && $form->is_saved == 1}<br /><a href="{link action=view_data module=formbuilder}&id={$form->id}">{$_TR.view_data}</a>{/if}
{if $permissions.editformsettings == 1}<br /><a href="{link action=edit_form module=formbuilder}&id={$form->id}">{$_TR.edit_settings}</a>{/if}
{if $permissions.editform == 1}<br /><a href="{link action=view_form module=formbuilder}&id={$form->id}">{$_TR.edit_form}</a>{/if}
{if $permissions.editreport == 1}<br /><a href="{link action=edit_report module=formbuilder}&id={$form->id}">{$_TR.edit_report}</a>{/if}
{/permissions}
{if $formmsg != "" }
<br><br>
{$formmsg}
{/if}
 <div style="border: padding: 1em;">
{$form_html}
</div>
