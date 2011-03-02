{*
 * Copyright (c) 2011 Eric Lestrade 
 *
 * This file is part of Exponent Linkmodule
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *}
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header"><p>
{$_TR.form_header}
</p></div>
{if $permissions.import == 1}
	{br}
	<a href="{link action=export_import_linklist mode=import src=$src}"> {$_TR.import_from_linklist}</a>{br}
	<a href="{link action=export_import_linklist mode=export src=$src}"> {$_TR.export_to_linklist}</a>{br} 
	<a href="{link action=import_from_html mode=webpage src=$src}"> {$_TR.import_from_webpage}</a>{br}
{/if}

{if $permissions.import == 1 && $permissions.edit == 1}
	{br}
	<a href="{link action=delete_all mode=preview src=$src}">{$_TR.delete_all}</a>{br}
{/if}

{br}
<a href="{link action=return}"> {$_TR.return}</a>
{br}