sure:
	find . -name '*.php' -print0 | xargs -0 -n1 php -l
	cd sdk && php unclaimed_files.php
