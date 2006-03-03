<?php
define("SCRIPT_EXP_RELATIVE","external/htmlarea/popups/");
define("SCRIPT_FILENAME","content_linked.php");

include_once("../../../exponent.php");
?>
<html>
	<body>
	<script type="text/javascript">
	var f_url = window.opener.document.getElementById("f_href");
	f_url.value = "?section=<?php echo exponent_sessions_get("last_section"); ?>#mod_<?php echo $_GET['cid']; ?>";
	var f_extern = window.opener.document.getElementById("f_extern");
	f_extern.checked = false;
	window.close();
	</script>
	</body>
</html>