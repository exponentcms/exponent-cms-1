{*
 * __XYZZY_BOILER
 * $Id$
 *}
{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=userperms _common=1}" title="Assign permissions on this Banner Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}userperms.gif" /></a>&nbsp;
	<a href="{link action=groupperms _common=1}" title="Assign group permissions on this Banner Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}groupperms.gif" /></a>
{/if}
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}" title="Configure this Banner Module"><img border="0" src="{$smarty.const.ICON_RELATIVE}configure.gif" /></a>
{/if}
{if $permissions.configure == 1 or $permissions.administrate == 1}
	<br />
{/if}
{/permissions}
{foreach from=$banners item=banner}
<a href="banner_click.php?id={$banner->id}">
<!--{$banner->name}-->
<img src="{$smarty.const.PATH_RELATIVE}{$banner->file->directory}/{$banner->file->filename}" border="0" />
</a>
<br />
{/foreach}
{permissions level=$smarty.const.UILEVEL_NORMAL}
{if $permissions.manage}
<a class="mngmntlink banner_mngmntlink" href="{link action=ad_edit}">New Banner</a>
{/if}
{/permissions}