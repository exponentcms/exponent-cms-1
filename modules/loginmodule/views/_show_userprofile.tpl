{*
 *
 * Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
 * $Id: Default.tpl,v 1.7 2005/04/08 15:45:48 filetreefrog Exp $
 *}
{$_TR.form_title|sprintf:$profile->firstname:$profile->lastname}

<table class="userprofile" summary="{$_TR.summary}">
<tbody>
<tr>
  <th scope="row" class="label">
    {if $profile->image_url != ""}
      <img src="{$smarty.const.URL_FULL}{$profile->image_url}" alt="{$_TR.alt_avatar}" />
    {else}
      <img src="{$smarty.const.URL_FULL}themes/common/images/icons/not_available.jpeg" alt="{$_TR.alt_no_avatar}" />
    {/if}
  </th>
  <td><h2>{$profile->username}</h2></td>
</tr>
<tr>
  <th scope="row" class="email">{$_TR.emailaddr}</th>
  <td>{$profile->email}</td>
</tr>
<tr>
  <th scope="row" class="icq">{$_TR.icq_number}</th>
  <td>{$profile->icq_num}</td>
</tr>
<tr>
  <th scope="row" class="aim">{$_TR.aim_addr}</th>
  <td>{$profile->aim_addy}</td>
</tr>
<tr>
  <th scope="row" class="msm">{$_TR.msm_id}</th>
  <td>{$profile->msn_addy}</td>
</tr>
<tr>
  <th scope="row" class="yahoo">{$_TR.yahoo_id}</th>
  <td>{$profile->yahoo_addy}</td>
</tr>
<tr>
  <th scope="row" class="web">{$_TR.web_url}</th>
  <td>{$profile->website}</td>
</tr>
<tr>
  <th scope="row" class="location">{$_TR.location}</th>
  <td>{$profile->location}</td>
</tr>
<tr>
  <th scope="row" class="occupation">{$_TR.occupation}</th>
  <td>{$profile->occupation}</td>
</tr>
<tr>
  <th scope="row" class="interests">{$_TR.interests}</th>
  <td>{$profile->interests}</td>
</tr>
</tbody>
</table>
