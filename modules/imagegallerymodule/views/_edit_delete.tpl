{permissions level=$smarty.const.UILEVEL_NORMAL}
	{if $permissions.edit == 1}
		<a  class="mngmntlink imagegallery_mngmntlink" href="{link action=view_gallery id=$gallery->id}">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}manage_images.png" title="{$_TR.alt_manage}" alt="{$_TR.alt_manage}" />{$_TR.add_reorder_images}
		</a>
	{/if}
	{if $permissions.edit == 1}
		<a class="mngmntlink imagegallery_mngmntlink" href="{link action=edit_gallery id=$gallery->id}">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}edit.png" title="{$_TR.alt_edit}" alt="{$_TR.alt_edit}" />{$_TR.edit_gallery}
		</a>
	{/if}
	{if $permissions.delete == 1}
		<a class="mngmntlink imagegallery_mngmntlink" href="{link action=delete_gallery id=$gallery->id}">
			<img class="mngmnt_icon" src="{$smarty.const.ICON_RELATIVE}delete.png" title="{$_TR.alt_delete}" alt="{$_TR.alt_delete}" />{$_TR.delete_gallery}
		</a>
	{/if}
{/permissions}
