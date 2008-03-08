YAHOO.util.Event.onDOMReady(function () {
		imagepanel = new YAHOO.widget.Panel("imagepanel", {
													zIndex:90000,
													constraintoviewport:true,
													fixedcenter:true,
													draggable:true,
													modal:true,
													underlay:"shadow",
													close:true,
													visible:false
													} );
		imagepanel.setHeader('Photo'); 
		imagepanel.setBody('&nbsp;');
		imagepanel.setFooter('&nbsp;');
		imagepanel.render(document.body);
	}
);

function popImage(id,width,height) {
	imagepanel.setBody('<strong>Loading Image...</strong>');
	imagepanel.show();
	YAHOO.util.Connect.asyncRequest('GET', 'index.php?ajax_action=1&module=imagegallerymodule&action=image_to_panel&id='+id, {
		success : function(o){
			var img = YAHOO.lang.JSON.parse(o.responseText);
			imagepanel.cfg.setProperty("width",width+20+"px");
			//imagepanel.cfg.setProperty("height",height+20+"px");
			imagepanel.setHeader(img.name);
			imagepanel.setBody('<img src="'+img.file.directory+'/'+img.enlarged+'" /> ');
			imagepanel.setFooter('<div id="gallerypopfooter">'+img.description+'</div>');
			setTimeout(function(){ imagepanel.cfg.setProperty("fixedcenter",true); },"30");
		},
		failure : function(o){
		},
		timeout : 5000
	});		
}	
