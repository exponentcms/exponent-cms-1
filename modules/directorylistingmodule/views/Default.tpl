{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Directory Listing Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Directory Listing Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="Configure this Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}

<br /><br />
{if $error_mes != ""}
	<div class="moduletitle">{$error_mes}</div>
{else}
	<table width="100%" border="0" cellspacing="0" >
	<tr bgcolor="#CCCCCC">
		<td id="filecol">&nbsp;</td>
		<td id="filecol" class="moduletitle">File</td>
		<td align="right" class="moduletitle" id="filesize">Size</td>	
		<td align="right" class="moduletitle" id="filedate">Date</td>
	</tr>	
	<tr>
		<td rowspan="2">&nbsp;</td>
		<td colspan="3"><a href="{link action=list view=$__view title=$moduletitle}">Top</a></td>		
	</tr>
	<tr>		
		<td colspan="3"><a href="{link action=list path=$backpath view=$__view title=$moduletitle}">..</a></td>			</tr>	
		{section name=dir loop=$dirs}
			<tr>
				<td align="left" width="20"><img src="{$imageroot}{$dirs[dir].type}"></td>
				<td><a href="{link action=list view=$__view title=$moduletitle path=$dirs[dir].path}">{$dirs[dir].name}<a/></td>
				<td align="right" width="80">{$dirs[dir].size}</td>
				<td align="right" width="150">{$dirs[dir].date|format_date:"%m/%d/%Y %H:%M:%S %p"}</td>
			</tr>
		{/section}
		{section name=file loop=$files}	
			<tr>
				<td align="left" width="20"><img src="{$imageroot}{$files[file].type}"></td>
				<td><a href="{link action=viewcode module=filemanager file=$files[file].path}">{$files[file].name}</td>
				<td align="right" width="80">{$files[file].size}</td>
				<td align="right" width="150">{$files[file].date|format_date:"%m/%d/%Y %l:%M:%S %p"}</td>
			</tr>
		{/section}
	</table>

{/if}
