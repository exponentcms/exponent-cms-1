<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>{$smarty.const.SITE_TITLE} :: {$_TR.down}</title>
		<style type="text/css">{literal}
			div {
				font-size: 10pt;
				font-family: Arial, sans-serif;
				font-weight: normal;
				color: #333;
			}
		{/literal}</style>
	</head>
	<body>
	
	<div style="border: 1px solid black; margin: 25%; padding: 3em;">
	{$smarty.const.MAINTENANCE_MSG_HTML}
		<p>
			<a href="login.php">{$_TR.login}</a>
		</p>
	</div>
	</body>
</html>
