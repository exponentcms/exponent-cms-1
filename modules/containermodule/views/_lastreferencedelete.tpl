{*
 *
 * Copyright 2004 James Hunt and OIC Group, Inc.
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * Exponent is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU
 * General Public License along with Exponent; if
 * not, write to:
 *
 * Free Software Foundation, Inc.,
 * 59 Temple Place,
 * Suite 330,
 * Boston, MA 02111-1307  USA
 *
 * $Id$
 *}
<script type="text/javascript">
{literal}
if (confirm("It appears that no one else is using the content of this module.  Would you like to remove this content from the database?\n\nIf you do not remove it, it will be archived, and still available for reuse later.")) {
	document.location = {/literal}"{link m=$iloc->mod s=$iloc->src i=$iloc->int action=delete_content}";{literal}
} else document.location = {/literal}"{$redirect}";{literal}
{/literal}
</script>