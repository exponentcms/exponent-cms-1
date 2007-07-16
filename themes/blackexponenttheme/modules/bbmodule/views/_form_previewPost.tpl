<table border="0" cellpadding="0" cellspacing="2" width="100%">
	<tr>
		<td align="left" bgcolor="#444444">Post Preview:</td>
	</tr>
	<tr>
		<td style="border:none; text-align:center" width="99%" valign="top">
			<table style="border:none;" cellpadding="0" cellspacing="2" width="100%">


	<tr>
		<td align="center">Author</td>
		<td align="left">Topic: {$thread->subject|escape:"html"}</td>
	</tr>
	<tr>
		<td valign="top" align="center" width="125">

		<!-- User profile table -->
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td  style="border:none; text-align:center">{$thread->poster->total_posts}&nbsp;{plural count=$thread->poster->total_posts plural='Posts' singular='Post'}
</td>
		</tr>
		{if $thread->poster->total_posts > 100000}
 				<tr><td style="border:none; text-align:center;">XTB <font color="#b70202">GOD</FONT></td></tr>
				<tr><td style="border:none; text-align:center;">XXXXXX</td>	</tr>
{elseif $thread->poster->total_posts > 50000}
 				<tr><td style="border:none; text-align:center;">XTB Master</td></tr>
				<tr><td style="border:none; text-align:center;">XXXXX</td>	</tr>
{elseif $thread->poster->total_posts > 10000}
 				<tr><td style="border:none; text-align:center;">XTB Guru</td></tr>
				<tr><td style="border:none; text-align:center;">XXXX</td>	</tr>
{elseif $thread->poster->total_posts > 2500}
 				<tr><td style="border:none; text-align:center;">XTB Captain</td></tr>
				<tr><td style="border:none; text-align:center;">XXX</td>	</tr>
{elseif $thread->poster->total_posts > 500}
 				<tr><td style="border:none; text-align:center;">XTB Apprentice</td></tr>
				<tr><td style="border:none; text-align:center;">XX</td>	</tr>
{else}
 				<tr><td style="border:none; text-align:center;">XTB Newbie</td></tr>
				<tr><td style="border:none; text-align:center;">X</td>	</tr>
{/if}

		<tr>
		<td style="border:none; text-align:center">
		{if $thread->poster->forumPic != null}
			<img style="border:none" src="thumb.php?base={$smarty.const.BASE}&amp;file={$thread->poster->forumPic->file->directory}/{$thread->poster->forumPic->file->filename}&amp;width=50" alt="{$thread->poster->username}" title="{$thread->poster->username}" />
		{else}
			&nbsp;
		{/if}
		</td>
		</tr>
		<tr>
		<td style="border:none; text-align:center; background-color:#b70202">{$thread->poster->username}</td>
		</tr>
		<tr>
		<td style="border:none; text-align:center;">{if $thread->poster->is_online == 1}<font color="#009933">ON-LINE</font>{else}<font color="#b70202">OFF-LINE</font>{/if}
</td>
		</tr>
		</table>
		</td>

		<td align="left" style="border:none;" valign="top"><br /><br />
				{$thread->body}
		</td>
	</tr>
</table>

</td>
</tr>
</table>

{$form_html}
<br />
*Use shift-enter to insert a single line.