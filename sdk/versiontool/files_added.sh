#!/bin/bash

OLDDIR=$1
NEWDIR=$2

diff -r --brief $OLDDIR $NEWDIR | \
grep "Only in $NEWDIR" | \
grep -v 'manifest.php' | \
grep -v 'deps.php' | \
sed -e 's%: %/%' | \
sed -e "s%Only in $NEWDIR%%"