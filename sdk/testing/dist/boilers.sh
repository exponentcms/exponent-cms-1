#!/bin/bash

NEEDLE="# General Public License along with Exponent; if"

(
find $1 -name "*.php" -print0 2>/dev/null | xargs -0 -n1 sh boiler-php.sh
) | grep -v '/external/' | grep -v '/compat/' | grep -v '/views_c/' \
| grep -v 'conf/profiles' | grep -v '/sdk/' | grep -v 'manifest.php' \
| grep -v 'conf/config.php' | grep -v 'nusoap.php' | grep -v 'overrides.php'

echo ""
echo ""

(
find $1 -name "*.tpl" -print0 2>/dev/null | xargs -0 -n1 sh boiler-tpl.sh
) | grep -v '/external/' | grep -v '/sdk/'