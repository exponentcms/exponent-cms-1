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
 * $Id: Default.tpl,v 1.1 2005/04/10 23:24:02 filetreefrog Exp $
 *}
 
<div class="simplepollmodule default">
	{if $moduletitle != ''}<h2>{$moduletitle}</h2>{/if}

	{if $have_answers != 0}
		<form method="post" action="{$smarty.const.URL_FULL}index.php">
			<input type="hidden" name="module" value="simplepollmodule" />
			<input type="hidden" name="action" value="vote" />
			<b>{$question->question}</b><br />
			{foreach from=$answers item=answer}
				<input type="radio" name="choice" value="{$answer->id}" />{$answer->answer}<br />
			{/foreach}
			
			{if $question->open_voting}
				<input type="submit" value="Vote!" />
			{else}
				Voting has closed for this poll.<br />
				<input type="submit" value="Vote!" disabled="disabled" />
			{/if}
			<br />
			{if $question->open_results}
				<a href="{link action=results id=$question->id}">Results</a>
			{/if}
		</form>
	{/if}

	{permissions level=$smarty.const.UILEVEL_NORMAL}
		{if $permissions.manage_question == 1 || $permissions.manage_answer == 1}
			<a href="{link action=manage_questions}">Manage Questions</a>
		{/if}
	{/permissions}
</div>
