{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
 * All Changes as of 6/1/05 Copyright 2005 James Hunt
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 *}
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header">{$_TR.form_header}</div>
<table cellpadding="2" cellspacing="0" width="100%" border="0">
{foreach from=$dropped_tables item=table}
<tr class="row {cycle values='odd,even'}_row"><td>
{$table}
</td><td>
<div style="color: red; font-weight: bold">{$_TR.dropped}</div>
</td></tr>
{foreachelse}
<b>{$_TR.no_unused}</b>
{/foreach}
</table>
{if $real_dropped != 0}
<hr size="1">
Dropped a total of {$dropped} tables.<br />
{math assign=diff equation="x-y" x=$dropped y=$real_dropped}
{if $diff != 0}
{$diff} empty table{if $diff != 1}s{/if} were re-created.<br />
{/if}
{/if}