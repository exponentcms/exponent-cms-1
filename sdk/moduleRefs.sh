#!/bin/bash

if [ "$1" = "" ]; then
	echo "No module specified.";
	exit;
fi

BASEDIR=../modules/$1

php buildFileReferences.php -n $1 -a dir -f $BASEDIR
php buildFileReferences.php -n $1 -a add -f $BASEDIR/class.php
php buildFileReferences.php -n $1 -a dir -f $BASEDIR/views

