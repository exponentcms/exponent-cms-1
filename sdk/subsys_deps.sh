#!/bin/bash

CORE=`php core_deps.php`

echo "---------------------------------"
echo "Core Dependencies:"

for DEP in $CORE; do
	echo $DEP
done

for FILE in ../modules/*module; do
	echo "---------------------------------"
	echo -n "Deps for "
	basename $FILE
	grep -rn 'pathos_' $FILE | sed -e 's/.*pathos_/pathos_/' | cut -d _ -f 2 | sort | uniq | grep -v "[^A-Z^a-z]"
	echo ""
done