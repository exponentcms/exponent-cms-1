{*
 *
 *}
<div class="form_title">{if $is_edit == 1}Edit Existing Internal Alias{else}New Internal Alias{/if}</div>
<div class="form_header">
Select which internal page you want this section to link to.  If you link to another internal alias, the aliases will all be dereferenced, and the original destination used.  If you link to an external alias, then this section will point to the external aliases external web address.
</div>
{$form_html}