function setContent(imagePath,relative) {
	if ((imagePath != null) && (imagePath != "")) {
		opener.window.document.getElementById('f_url').focus()
		opener.window.document.getElementById('f_url').value = imagePath;
		opener.window.relativePathos = relative;
		opener.onPreview();
	}
	window.close();
}