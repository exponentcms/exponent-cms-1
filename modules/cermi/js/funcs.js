function uploadFile() {
        var url='http://adam/exponent-themes/index.php';
        var frmObject = document.getElementById('uploadFrm');
        YAHOO.util.Connect.setForm(frmObject, true);
	console.debug('here');
        YAHOO.util.Connect.asyncRequest('POST', url, {
                upload : function(o){
                        console.debug("uploaded");
			var mainPane = document.getElementById('scroller');
			mainPane.innerHTML = o.responseText;
			return false;
                },
                success : function(o){
                        console.debug("success");
			return false;
                },
                failure : function(o){
                        console.debug("failure");
			return false;
                },
                timeout : 5000

        });
        return false;
}

