// Initialize the temporary Panel to display while waiting for external content to load
YAHOO.util.Event.on("gallery","submit",function(e){
	YAHOO.util.Event.stopEvent(e);
	YAHOO.util.Dom.get('description').value = FCKeditorAPI.GetInstance('description').GetXHTML();
	var postvars = YAHOO.util.Connect.setForm("gallery");
	var wait = 
			new YAHOO.widget.Panel("wait",	
				{ width:"300px", 
				  fixedcenter:true, 
				  close:false, 
				  draggable:false, 
				  zindex:9999,
				  modal:true,
				  visible:false
				} 
			);
			//alert(postvars)
	wait.setHeader("Rebuilding thumbnail and popup images");
	wait.setBody('<div id="messagebox">Saving gallery settings...</div><img src="http://us.i1.yimg.com/us.yimg.com/i/us/per/gr/gp/rel_interstitial_loading.gif" />');
	wait.render(document.body);
	wait.show();
	
	var messagebox = YAHOO.util.Dom.get("messagebox")

	var sUrl = eXp.URL_FULL+"index.php?ajax_action=1";
	YAHOO.util.Connect.asyncRequest('POST', sUrl, 
	{
		success : function (o){
			if(o.responseText == "no-resize"){
				messagebox.innerHTML = "<h4>Complete!</h4>";
				setTimeout(function(){ wait.hide();YAHOO.exp.flowredirect();},"1000");
			}else{
				updateImages(o);
			}
		},
		failure : function(o){
		},
		timeout : 50000
	});
	
	function updateImages (o) {
		var gallery = YAHOO.lang.JSON.parse(o.responseText);
		//alert(gallery.images.length);
		//for (i=0; i<=gallery.images.length; i++){
		var i=0;
		recursiveUpdate(gallery.images[0],i);
		function recursiveUpdate(img,i){
			messagebox.innerHTML = "Updating "+ i + " of " + gallery.images.length + " images";
			var iUrl = eXp.URL_FULL+"index.php?ajax_action=1&module=imagegallerymodule&action=rebuild_images";
			YAHOO.util.Connect.asyncRequest('POST', iUrl, 
			{
				success : function (o){
					//console.debug(o.responseText);
					if(i < gallery.images.length){
						i++;
						recursiveUpdate(gallery.images[i],i)
					} else {
						messagebox.innerHTML = "<h4>Complete!</h4>";
						setTimeout(function(){ wait.hide();YAHOO.exp.flowredirect();},"1000");
					}
				},
				failure : function(o){
				},
				timeout : 50000
			},"galobject="+YAHOO.lang.JSON.stringify(img));			
		}
		//}
		
	}
	
	
	
});

