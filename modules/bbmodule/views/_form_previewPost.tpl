<table border="0" cellpadding="0" cellspacing="2" width="100%">
	<tr>
		<td align="left" bgcolor="#444444">Post Preview:</td>
	</tr>
	<tr>
		<td align="center" style="border-width:0px;" width="99%" valign="top">
			<table border="0" cellpadding="0" cellspacing="2" width="100%">


	<tr>
		<td align="center">Author</td>
		<td align="left">Topic: {$thread->subject|escape:"html"}</td>
	</tr>
	<tr>
		<td valign="top" align="center" width="125">

		<!-- User profile table -->
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td  align="center" style="border-width:0px;">{$thread->poster->total_posts}&nbsp;{plural count=$thread->poster->total_posts plural='Posts' singular='Post'}
</td>
		</tr>
		{if $thread->poster->total_posts > 100000}
 				<tr><td style="border-width:0px;" align="center">XTB <font color="#b70202">GOD</FONT></td></tr>
				<tr><td style="border-width:0px;" align="center">XXXXXX</td>	</tr>
{elseif $thread->poster->total_posts > 50000}
 				<tr><td style="border-width:0px;" align="center">XTB Master</td></tr>
				<tr><td style="border-width:0px;" align="center">XXXXX</td>	</tr>
{elseif $thread->poster->total_posts > 10000}
 				<tr><td style="border-width:0px;" align="center">XTB Guru</td></tr>
				<tr><td style="border-width:0px;" align="center">XXXX</td>	</tr>
{elseif $thread->poster->total_posts > 2500}
 				<tr><td style="border-width:0px;" align="center">XTB Captain</td></tr>
				<tr><td style="border-width:0px;" align="center">XXX</td>	</tr>
{elseif $thread->poster->total_posts > 500}
 				<tr><td style="border-width:0px;" align="center">XTB Apprentice</td></tr>
				<tr><td style="border-width:0px;" align="center">XX</td>	</tr>
{else}
 				<tr><td style="border-width:0px;" align="center">XTB Newbie</td></tr>
				<tr><td style="border-width:0px;" align="center">X</td>	</tr>
{/if}

		<tr>
		<td align="center" style="border-width:0px;">
		{if $thread->poster->forumPic != null}
			<img border="0" src="thumb.php?base={$smarty.const.BASE}&file={$thread->poster->forumPic->file->directory}/{$thread->poster->forumPic->file->filename}&width=50" alt="{$thread->poster->username}" title="{$thread->poster->username}" />
		{else}
			&nbsp;
		{/if}
		</td>
		</tr>
		<tr>
		<td align="center" style="border-width:0px;" bgcolor="#b70202">{$thread->poster->username}</td>
		</tr>
		<tr>
		<td align="center" style="border-width:0px;">{if $thread->poster->is_online == 1}<font color="#009933">ON-LINE</font>{else}<font color="#b70202">OFF-LINE</font>{/if}
</td>
		</tr>
		</table>
		</td>

		<td align="left" style="border-width:0px;" valign="top"><br><br>
				{$thread->body}
		</td>
	</tr>
</table>

</td>
</tr>
</table>

{$form_html}
<br>
*Use shift-enter to insert a single line.