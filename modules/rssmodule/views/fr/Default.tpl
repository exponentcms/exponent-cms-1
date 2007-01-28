
{if $permissions.configure == 1}
	<a href="{link action=configure _common=1}"><img class="mngmnt_icon" style="border:none;" src="{$smarty.const.ICON_RELATIVE}configure.png" title="{$_TR.alt_configure}" alt="{$_TR.alt_configure}" /></a>
{/if}
<b>Displaying:</b> {$rss_url}
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
    <th colspan=2>
      <a href="{$rss->channel.link}">{$rss->channel.title}</a>
    </th>
  </tr>
  	{foreach from=$rss->items item=item}
  	 <tr>
	  <td>
	  <a href="{$item.link}">{$item.title}</a>
  	  </td>
	  <td>
	   {$item.dc.date|rss_date_parse|date_format:"%A, %B %e, %Y"}
	  </td>
	</tr>
	{/foreach}
</table>
{/if}

