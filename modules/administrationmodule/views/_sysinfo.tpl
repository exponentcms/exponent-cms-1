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
<div class="moduletitle">System Information</div>

<div style="border-top: 1px solid black; margin-top: 1.5em;">
<b>Exponent Information:</b><br />
<table cellspacing="2" cellpadding="0" border="0">
<tr><td>Version:&nbsp;&nbsp;</td><td>{$smarty.const.PATHOS_VERSION_MAJOR}.{$smarty.const.PATHOS_VERSION_MINOR}.{$smarty.const.PATHOS_VERSION_REVISION}</td></tr>
<tr><td>Type of Release:&nbsp;&nbsp;</td><td>{$smarty.const.PATHOS_VERSION_TYPE}{if $smarty.const.PATHOS_VERSION_TYPE == ""}stable{else}-{$smarty.const.PATHOS_VERSION_ITERATION}{/if}</td></tr>
<tr><td>Build Date:&nbsp;&nbsp;</td><td>{$smarty.const.PATHOS_VERSION_BUILDDATE|date_format:"%D %T"}</td></tr>
</table>
</div>

<div style="border-top: 1px solid black; margin-top: 1.5em;">
<b>PHP Information:</b><br />
<img src="{$php.logo_src}" /><br />
Version {$php.version}
</div>

<div style="border-top: 1px solid black; margin-top: 1.5em;">
<b>Zend Information:</b><br />
<img src="{$zend.logo_src}" /><br />
Version {$zend.version}
</div>

<div style="border-top: 1px solid black; margin-top: 1.5em;">
<b>Server Information:</b><br />
<table cellspacing="2" cellpadding="0" border="0">
<tr><td>Hostname:&nbsp;&nbsp;</td><td>{$server.hostname}</td></tr>
<tr><td>Operating System:&nbsp;&nbsp;</td><td>{$server.os}</td></tr>
<tr><td>Version:&nbsp;&nbsp;</td><td>{$server.version} {$server.release}</td></tr>
<tr><td>Machine Type:&nbsp;&nbsp;</td><td>{$server.machine}</td></tr>
</table>
</div>