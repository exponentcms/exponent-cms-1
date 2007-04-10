{permissions level=$smarty.const.UILEVEL_PERMISSIONS}
{if $permissions.administrate == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
	<a href="{link action=userperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}userperms.png" title="{$_TR.alt_userperm}" alt="{$_TR.alt_userperm}" /></a>
	<a href="{link action=groupperms _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}groupperms.png" title="{$_TR.alt_groupperm}" alt="{$_TR.alt_groupperm}" /></a>
{/if}
{/permissions}
<p>

{* if $error display the error 
  elseif parsed RSS object display the RSS 
  else solicit user for a URL 
*}

{if $error }
<b>Error:</b> {$error}
{elseif $rss}
<table border=1>
  <tr>
    <th colspan=3>
      <a href="{$rss->channel.link}">{$rss->channel.title}</a>
    </th>
  </tr>
  	{foreach from=$rss->items item=item}
  	 <tr>
	  <td>
	  <a href="{$item.link}">{$item.title}</a>
  	  </td>
	  <td>
	   {$item.dc.date|format_date:$smarty.const.DISPLAY_DATETIME_FORMAT}
	  </td>
	  </tr>
	  
	{/foreach}
</table>
{/if}

