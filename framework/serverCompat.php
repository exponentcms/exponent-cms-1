<?php
class exponent_server_compat {
	public $sefPath = null;
	
	function __construct() {
	// Finally have the compat layer.
		$_SERVER['QUERY_STRING'] = !isset($_SERVER['QUERY_STRING']) ? '' : $_SERVER['QUERY_STRING'];
		$_SERVER['REQUEST_URI'] = SCRIPT_RELATIVE.SCRIPT_FILENAME . '?' . $_SERVER['QUERY_STRING'];
		
		$this->buildSEFPath();
	}
	
	private function buildSEFPath () {
		// Apache
		if (strpos($_SERVER['SERVER_SOFTWARE'],'Apache') === 0) {
			switch(php_sapi_name()) {
				case "cgi":
					$this->sefPath = $_ENV['REQUEST_URI'];
					break;
				default:
					$this->sefPath = $_SERVER['REDIRECT_URL'];
					break;
			}
		// Lighty
		} elseif (strpos($_SERVER['SERVER_SOFTWARE'],'lighttpd') === 0) {
			if (isset($_SERVER['ORIG_PATH_INFO'])) {
				$this->sefPath = $_SERVER['ORIG_PATH_INFO'];
			} elseif (isset($_SERVER['REDIRECT_URI'])){
				$this->sefPath = substr($_SERVER['REDIRECT_URI'],9);
			}
		}
	}
}

$exponent_server = new exponent_server_compat();
global $exponent_server;
?>