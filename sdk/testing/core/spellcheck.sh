#!/bin/bash

php spellcheck.php /var/www/localhost/htdocs/exponent/ \
| grep -v '\[\[spellcheck\]\]:' \
| aspell -l \
| sort \
| uniq \
| less
