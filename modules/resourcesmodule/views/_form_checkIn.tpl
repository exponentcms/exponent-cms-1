{*
 * Copyright (c) 2004-2011 OIC Group, Inc.
 * Written and Designed by James Hunt
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 *}
{literal} 
<script type="text/javascript">
	function init(){
		var els=document.getElementsByTagName('*');
		var reg=/(^| )kfm($| )/;
		for(i in els){
			var el=els[i];
			if(reg.test(el.className))el.onclick=function(){
				window.SetUrl=(function(id){
					return function(value){
						value=value.replace(/[a-z]*:\/\/[^\/]*/,'');
						document.getElementById(id).value=value;
					}
				})(this.id);
				var kfm_url = "/apps/fckeditor/editor/plugins/kfm/index.php";
				window.open(kfm_url,'kfm','modal,width=800,height=600');
			}
		}
	}
</script>
{/literal} 
<div class="form_title">{$_TR.form_title}</div>
<div class="form_header"><p>{$_TR.form_header}</p></div>
{$form_html}
<script type="text/javascript">
	init();
</script>