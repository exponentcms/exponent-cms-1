#!/bin/bash

OLDDIR=$2
NEWDIR=$1

diff -r --brief $OLDDIR $NEWDIR | \
grep "Only in $NEWDIR" | \
grep -v 'manifest.php' | \
grep -v 'deps.php' | \
sed -e 's%: %/%' | \
sed -e "s%Only in $NEWDIR%%"