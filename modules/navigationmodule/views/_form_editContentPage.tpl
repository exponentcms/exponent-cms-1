{*
 *
 *}
<div class="form_title">{if $is_edit == 1}Edit Existing Content Page{else}Create New Content Page{/if}</div>
<div class="form_header">
{if $is_edit == 1}
Use the form below to change the details of this content page.
{else}
Use the form below to enter the information about your new content page.
{/if}
</div>
{$form_html}