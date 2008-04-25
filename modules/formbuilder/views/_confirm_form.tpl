{*
 * Copyright (c) 2004-2006 OIC Group, Inc.
 * Written and Designed by Adam Kessler
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

<div class="formbuilder confirm-form">
	<h1>Please confirm your submission</h1>
	<table width="90%">
	<th>Field</th>
	<th>Your Response</th>
	{foreach from=$responses item=response key=name}
		<tr>
			<td><strong>{$name}: </strong>
			<td>{$response}</td>
		</tr>
	{/foreach}	
	</table>

	<p>If the information above looks correct, fill out the security question below to submit your form submission</p>
	{form action=submit_form}
		{foreach from=$postdata item=data key=name}
			{control type=hidden name=$name value=$data}
		{/foreach}
		{control type=capcha}
		{control type=buttongroup submit="Submit Form" cancel="Change Responses"}
	{/form}
</div> 
