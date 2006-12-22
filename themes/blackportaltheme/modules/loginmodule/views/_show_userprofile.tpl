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
 {literal}
 <style type="text/css">
 .bio h2{
 	color:#333333;
 	width:500px;
	text-align:left;
	margin-top:0px;
 }

 .bio img{
 	float:left;
	margin-right:10px;
 }
 .bio span{
 	border-bottom:1px dashed #333333;
 }
 
 </style>
  {/literal}
<div class="bio pad">


<table cellspacing="8">
<tr>
  <td>
    {if $profile->image_url != ""}
      <img src="{$profile->image_url}" border="0">
    {else}
      <img src="{$smarty.const.THEME_RELATIVE}images/not_available.jpeg" border="0">
    {/if}
	</td><td valign="top">
    <h2><span>{$profile->username} - {$profile->firstname} {$profile->lastname}</span></h2>
    {$profile->signature}</td>
  </tr>
<tr>
  <td align="right" nowrap="nowrap" width="100">
  <div style=" float:right; width:25px; height:15px; padding-left:5px; ">
		<img align="left" src="{$smarty.const.ICON_RELATIVE}email.png" />
		</div>Email</td>
  <td><a href="mailto:{$profile->email}">{$profile->email}</a></td>
</tr>
<tr>
  <td align="right">  <div style=" float:right; width:25px; height:15px; padding-left:5px; ">
		<img align="left" src="{$smarty.const.ICON_RELATIVE}icq.png" />
		</div>ICQ</td>
  <td>{$profile->icq_num}</td>
</tr>
<tr>
  <td align="right"><div style=" float:right; width:25px; height:15px; padding-left:5px; ">
		<img align="left" src="{$smarty.const.ICON_RELATIVE}aim.png" />
		</div>AIM</td>
  <td>{$profile->aim_addy}</td>
</tr>
<tr>
  <td align="right">
  <div style=" float:right; width:25px; height:15px; padding-left:5px; ">
		<img align="left" src="{$smarty.const.ICON_RELATIVE}msn.png" />
		</div>Windows</td>
  <td>{$profile->msn_addy}</td>
</tr>
<tr>
  <td align="right"><div style=" float:right; width:25px; height:15px; padding-left:5px; ">
		<img align="left" src="{$smarty.const.ICON_RELATIVE}yahoo.png" />
		</div>Yahoo</td>
  <td>{$profile->yahoo_addy}</td>
</tr>
<tr>
  <td align="right"><div style=" float:right; width:25px; height:15px; padding-left:5px; ">
		<img align="left" src="{$smarty.const.ICON_RELATIVE}website.png" />
		</div>Website</td>
  <td><a target"_blank" href="http://{$profile->website}">{$profile->website}</a></td>
</tr>
<tr>
  <td align="right" nowrap="nowrap"><div style=" float:right; width:25px; height:15px; padding-left:5px; ">
		<img align="left" src="{$smarty.const.ICON_RELATIVE}globe.png" />
		</div>Location</td>
  <td>{$profile->location}</td>
</tr>
<tr>
  <td align="right" nowrap="nowrap"><div style=" float:right; width:25px; height:15px; padding-left:5px; ">
		<img align="left" src="{$smarty.const.ICON_RELATIVE}globe.png" />
		</div>Occupation</td>
  <td>{$profile->occupation}</td>
</tr>
<tr>
  <td align="right"><div style=" float:right; width:25px; height:15px; padding-left:5px; ">
		<img align="left" src="{$smarty.const.ICON_RELATIVE}globe.png" />
		</div>Interests</td>
  <td>{$profile->interests}</td>
</tr>
</table>


</div>