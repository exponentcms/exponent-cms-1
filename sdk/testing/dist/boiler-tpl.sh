#!/bin/bash

NEEDLE="* General Public License along with Exponent; if"

# $@ will be the filename to check
COUNT=`grep "$NEEDLE" "$@" | wc -l`
if [[ $COUNT = 0 ]]; then
	echo $@
fi