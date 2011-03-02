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
 {if $mode==import}
<div class="form_title">{$_TR.form_import_title}</div>
<div class="form_header"><p>{$_TR.form_import_header}</p></div>
{/if}
 {if $mode==export}
<div class="form_title">{$_TR.form_export_title}</div>
<div class="form_header"><p>{$_TR.form_export_header}</p></div>
{/if}
{$form_html}