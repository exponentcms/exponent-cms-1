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
        <h1>{$_TR.confirm_header}</h1>
        <table width="90%">
        <th>{$_TR.field}</th>
        <th>{$_TR.response}</th>
        {foreach from=$responses item=response key=name}
                <tr>
                        <td><strong>{$response.caption}: </strong>
                        <td>{$response.value}</td>
                </tr>
        {/foreach}
        </table>

	{if $smarty.const.SITE_USE_CAPTCHA == 1}
		<p>{$_TR.confirm_security}</p>
        {else}
                <p>{$_TR.confirm}</p>
        {/if}

        {form action=submit_form}
                {foreach from=$postdata item=data key=name}
                        {control type=hidden name=$name value=$data}
                {/foreach}
                {control type=captcha}

		{control type=buttongroup submit=$_TR.submit_form cancel=$_TR.change_responses}
        {/form}
</div>