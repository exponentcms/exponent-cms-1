{*
 *
 *}
{if $state == 'expired'}Your token has expired.
{elseif $state == 'smtp_error'}Error sending confirmation message.  Contact an administrator.
{elseif $state == 'sent'}Your new password has been emailed to you.
{else}Strange error occurred.
{/if}