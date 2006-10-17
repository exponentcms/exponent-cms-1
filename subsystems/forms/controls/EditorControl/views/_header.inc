{*
##################################################
#
# Copyright (c) 2005-2006  Maxim Mueller
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################
*}
{* find a few good definitions for classes on different scopes*}
<div class="">
	<script type="text/javascript">
	/* <![CDATA[ */
{literal}
		//initialize the namespace object
		if(! eXp.WYSIWYG) {
			eXp.WYSIWYG = new Object();
		}
{/literal}
{if $toolbar != NULL}
		eXp.WYSIWYG.toolbar = {$toolbar};
{/if}

	/* ]]> */
	</script>
