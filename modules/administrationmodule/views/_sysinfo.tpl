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
 * $Id$
 *}
{if $override_style == 1}
<div class="form_title">System Information</div>
<div class="form_header">This information may be useful for support reasons and reporting bugs.
<br /><br />
<a href="{link action=sysinfo_download}">Export this information</a> and send it to an Exponent Developer or Support Technician by.
</div>
<style type="text/css">
{literal}
div.center {
	text-align:center;
}

.p {
	text-align: left;
}

.e {
	background-color: #ccccff;
	font-weight: bold;
	color: #000000;
}

.h {
	background-color: #9999cc; 
	font-weight: bold; 
	color: #000000;
}
.v {
	background-color: #cccccc;
	color: #000000;
}

.center table {
	margin-left: auto;
	margin-right: auto;
	text-align: left;
}

{/literal}
</style>
{/if}
{$phpinfo}
<br />

<div class="center">

<table border="0" cellpadding="3" width="600">
<tr class="h"><td>
<!--<a href="http://www.exponentcms.org/">[Insert Logo Here]</a>--><h1 class="p">Exponent CMS</h1>
</td></tr>
</table><br />
<table border="0" cellpadding="3" width="600">
<tr><td class="e">Software Version</td><td class="v">{$smarty.const.PATHOS_VERSION_MAJOR}.{$smarty.const.PATHOS_VERSION_MINOR}.{$smarty.const.PATHOS_VERSION_REVISION}{if $smarty.const.PATHOS_VERSION_TYPE != ''}-{$smarty.const.PATHOS_VERSION_TYPE}{$smarty.const.PATHOS_VERSION_ITERATION}{/if}</td></tr>
<tr><td class="e">Build Date </td><td class="v">{if $smarty.const.PATHOS_VERSION_BUILDDATE == "%%BUILDDATE%%"}<i>Development Version</i>{else}{$smarty.const.PATHOS_VERSION_BUILDDATE|format_date:"%a %d %Y %H:%M:%S"}{/if}</td></tr>
<tr><td class="e">PATH_RELATIVE</td><td class="v">{$smarty.const.PATH_RELATIVE}</td></tr>
<tr><td class="e">BASE</td><td class="v">{$smarty.const.BASE}</td></tr>
</table><br />

<h1>Installed Modules</h1>
{foreach from=$modules item=mod key=class}
<h2><a name="module_{$class}">{$class}</a></h2>
<table border="0" cellpadding="3" width="600">
<tr><td class="e">name</td><td class="v">{$mod.name}</td></tr>
<tr><td class="e">author</td><td class="v">{$mod.author}</td></tr>
</table><br />
{/foreach}

<h1>Installed Subsystems</h1>

{foreach from=$subsystems item=sys key=class}
<h2><a name="subsystem_{$class}">{$class}</a></h2>
<table border="0" cellpadding="3" width="600">
<tr><td class="e">name</td><td class="v">{$sys.name}</td></tr>
<tr><td class="e">author</td><td class="v">{$sys.author}</td></tr>
<tr><td class="e">version</td><td class="v">{$sys.version}</td></tr>
</table><br />
{/foreach}
</div>