#!/bin/bash

echo "Developer CVS File Update Tool"
echo ""
echo "by James Hunt"
echo ""
echo ""
echo "This tool restores sanity to the file and"
echo "directory structures in Exponent, and is"
echo "intended to be run by developers checking"
echo "out fresh CVS sources"
echo ""

cd ..

ROOT=`pwd`;

echo "Exponent files root set to $ROOT";

for MODULE in $ROOT/modules/*; do
	if [ -d $MODULE/views ]; then
		echo -ne "Processing module : ";
		echo `basename $MODULE`;
		if [ ! -d $MODULE/views_c ]; then
			mkdir $MODULE/views_c;
		fi
		chmod 777 $MODULE/views_c;
		
		# Generate a .cvsignore file to ignore compiled templates
		echo "*" > $MODULE/views_c/.cvsignore
	fi
done

for THEME in $ROOT/themes/*; do
	if [ -d $THEME/modules/ ]; then
		echo -ne "Processing theme : ";
		echo `basename $THEME`;
		for MODULE in $THEME/modules/*; do
			if [ -d $MODULE/views ]; then
				echo -ne "Processing theme views for module : ";
				echo `basename $MODULE`;
				
				if [ ! -d $MODULE/views_c ]; then
					mkdir $MODULE/views_c;
				fi
				chmod 777 $MODULE/views_c;
				
				# Generate a .cvsignore file to ignore compiled templates
				echo "*" > $MODULE/views_c/.cvsignore
				
			fi
		done
	fi
	if [ -d $THEME/views/ ]; then
		if [ ! -d $THEME/views_c ]; then
			mkdir $THEME/views_c;
		fi
		chmod 777 $THEME/views_c;
		# Generate a .cvsignore file to ignore compiled templates
		echo "*" > $THEME/views_c/.cvsignore
	fi
done

if [ ! -d $ROOT/conf/profiles ]; then
	mkdir $ROOT/conf/profiles
fi
chmod 777 $ROOT/conf/profiles

echo "*" > $ROOT/files/.cvsignore

if [ ! -d $ROOT/extensionuploads ]; then
	mkdir $ROOT/extensionuploads
fi
chmod 777 $ROOT/extensionuploads

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
echo "";
echo "";
echo "======================================";
echo "============ IMPORTANT ==============="
echo "======================================";
echo "";
echo "Don't forget to uncomment the line in pathos_version.php";
echo "that defines the DEVELOPMENT constant.  Strange things";
echo "may happen with regards to CVS directories if you don't.";

cd sdk
