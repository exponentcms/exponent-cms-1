{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
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
<div class="form_title">Trim Database</div>
<div class="form_header">Exponent is removing tables that are no longer used from its database.  Shown below is a summay of the actions that occured.</div>
<table cellpadding="2" cellspacing="0" width="100%" border="0">
{foreach from=$status item=table}
<tr class="row {cycle values='odd,even'}_row"><td>
{$table}
</td><td>
<div style="color: red; font-weight: bold">Dropped</div>
</td></tr>
{foreachelse}
<b>No unused tables were found.</b>
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