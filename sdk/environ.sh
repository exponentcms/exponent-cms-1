#!/bin/bash

cd ..

ROOT=`pwd`;

echo "ROOT set to $ROOT";

for MODULE in $ROOT/modules/*; do
	echo -ne "Processing module : ";
	echo `basename $MODULE`;
	if [ -d $MODULE/views ]; then
		if [ ! -d $MODULE/views_c ]; then
			mkdir $MODULE/views_c;
		fi
		chmod 777 $MODULE/views_c;
		
		echo "*" > $MODULE/views_c/.cvsignore
		(
		echo ".cvsignore";
		echo "views_c"
		) > $MODULE/.cvsignore
	fi
done

for THEME in $ROOT/themes/*; do
	echo -ne "Processing theme : ";
	echo `basename $THEME`;
	if [ -d $THEME/modules/ ]; then
		for MODULE in $THEME/modules/*; do
			echo -ne "Processing theme views for module : ";
			echo `basename $MODULE`;
			if [ -d $MODULE/views ]; then
				if [ ! -d $MODULE/views_c ]; then
					mkdir $MODULE/views_c;
				fi
				chmod 777 $MODULE/views_c;
				echo "*" > $MODULE/views_c/.cvsignore
				(
				echo ".cvsignore";
				echo "views_c"
				) > $MODULE/.cvsignore
			fi
		done
	fi
done

if [ ! -d $ROOT/conf/profiles ]; then
	mkdir $ROOT/conf/profiles
fi
chmod 777 $ROOT/conf/profiles

echo "*" > $ROOT/files/.cvsignore


if [ ! -f $ROOT/conf/config.php ]; then
	touch $ROOT/conf/config.php;
fi
chmod 777 $ROOT/conf/config.php

(
echo "config.php";
echo ".cvsignore";
)> $ROOT/conf/.cvsignore
echo "*" > $ROOT/conf/profiles/.cvsignore


if [ ! -d $ROOT/files ]; then
	mkdir $ROOT/files
fi
chmod -R 777 $ROOT/files

echo "*" > $ROOT/files/.cvsignore


echo "Finished setting up Exponent Environment.";

cd sdk
