{*
 * __XYZZY_BOILER
 * $Id$
 *}
{pathos_getobject object="file" id=$c->file_id var="file"}
<a href="http://{$smarty.server.HTTP_HOST}{$smarty.const.PATH_RELATIVE}banner_click.php?id={$c->id}">
<img border="0" src="{$smarty.const.PATH_RELATIVE}{$file->directory}/{$file->filename}" />
</a>