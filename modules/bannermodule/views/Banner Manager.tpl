{*
 * __XYZZY_BOILER
 * $Id$
 *}
<table cellpadding="2" cellspacing="0" width="100%" border="0">
<tr>
	<td class="header banner_header">Banner</td>
	<td class="header banner_header">Affiliate</td>
	<td class="header banner_header">&nbsp;</td>
</tr>
{foreach from=$banners item=banner}
{assign var=aid value=$banner->affiliate_id}
{assign var=fid value=$banner->file_id}
<tr>
	<td valign="top">
		{$banner->name}<br />
		<img src="{$smarty.const.PATH_RELATIVE}{$files[$fid]->directory}/{$files[$fid]->filename}" />
	</td>
	<td valign="top">{$affiliates[$aid]}</td>
	<td valign="top">
		<a class="mngmntlink banner_mngmntlink" href="{link action=ad_edit id=$banner->id}">
			<img src="{$smarty.const.ICON_RELATIVE}edit.gif" border="0" />
		</a>
		<a class="mngmntlink banner_mngmntlink" href="{link action=ad_delete id=$banner->id}" onClick="return confirm('Are you sure you want to delete \'{$banner->name}\'?');">
			<img src="{$smarty.const.ICON_RELATIVE}delete.gif" border="0" />
		</a>
	</td>
</tr>
{/foreach}
</table>
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.manage}
<a class="mngmntlink banner_mngmntlink" href="{link action=ad_edit}">New Banner</a>
{/if}
{/permissions}