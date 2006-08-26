<html>
	<head>
		<title>{$smarty.const.SITE_TITLE} :: Down for Maintenance</title>
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
			<form method="post" action="login.php">
				<input type="hidden" name="action" value="login" />
				<input type="hidden" name="module" value="loginmodule" />
				<input type="text" name="username" id="login_username" size="15" /><br />
				<input type="password" name="password" id="login_password" size="15" /><br />
				<input type="submit"/>
			</form>
		</p>
	</div>
	</body>
</html>
