{*
 * Copyright (c) 2005-2006 Dirk Olten, http://www.extrabyte.de
 *
 * This file is part of Exponent Guestbookmodule
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *}

<div class="form_title">{if $is_edit == 1}{$_TR.form_title_edit}{else}{$_TR.form_title_new}{/if}</div>
{if $is_edit == 0}
<div class="form_header">
{$_TR.gotm_header}
</div>
{else}
<hr size="1" />
{/if}
{$form_html}