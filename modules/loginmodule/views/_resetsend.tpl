{*
 *
 *}
{if $state == 'unable'}Your password cannot be reset.  Please contact an administrator.
{elseif $state == 'smtp_error'}Error sending confirmation message.
{elseif $state == 'sent'}Confirmation message sent.
{else}Strange error.
{/if}