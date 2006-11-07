{*
 *
 * Copyright (c) 2004-2005 OIC Group, Inc.
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
 * $Id: _manageQuestion.tpl,v 1.2 2005/04/18 01:23:57 filetreefrog Exp $
 *}

<div class="moduletitle">{$question->question}</div>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr><td class="header">Answer</td><td class="header"></td></tr>
{foreach name=loop from=$answers item=answer}
<tr><td>
{$answer->answer}
</td><td>
{if $permissions.manage_answer == 1}
<a href="{link action=edit_answer id=$answer->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}edit.png" /></a>
<a href="{link action=delete_answer id=$answer->id}"><img class="mngmnt_icon" border="0" src="{$smarty.const.ICON_RELATIVE}delete.png" /></a>
{if $smarty.foreach.loop.first}
<img src="{$smarty.const.ICON_RELATIVE}up.disabled.png" />
{else}
{math assign=prev equation="x-1" x=$answer->rank}
<a href="{link action=order_switch a=$answer->rank b=$prev qid=$question->id}"><img border="0" src="{$smarty.const.ICON_RELATIVE}up.png" /></a>
{/if}

{if $smarty.foreach.loop.last}
<img src="{$smarty.const.ICON_RELATIVE}down.disabled.png" />
{else}
{math assign=next equation="x+1" x=$answer->rank}
<a href="{link action=order_switch a=$answer->rank b=$next qid=$question->id}"><img border="0" src="{$smarty.const.ICON_RELATIVE}down.png" /></a>
{/if}
{/if}
</td></tr>
{foreachelse}
<tr><td colspan="2" align="center"><i>No answers found</i></td></tr>
{/foreach}
</table>
<br />
{if $permissions.manage_answer == 1}
<a href="{link action=edit_answer question_id=$question->id}">New Answer</a>
<br />
{/if}
<br />
<a href="{link action=manage_questions}">Back to Manager</a>