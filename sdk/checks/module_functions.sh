#!/bin/bash

echo "Module Implementation Compliance Checker (MICC)"
echo "  version 0.1"
echo ""
echo "  Written by James Hunt, for the Exponent CMS"
echo ""
echo "This tool looks through every module found in"
echo "../../modules and finds any implementation errors"
echo "(like missing methods implementations)"
echo ""
echo "------------------------------------------------"
echo "Running Tests"
echo ""

# Source the library
. ../lib.sh

# Process our custom options

OPT_SHOW_SUCCESS=1
OPT_TEST_SUSPECT=1
OPT_VERBOSE=0
for ARG in $@; do
	case $ARG in
	--ignore-success)
		OPT_SHOW_SUCCESS=0
	;;
	--skip-suspect)
		OPT_TEST_SUSPECT=0
	;;
	--verbose)
		OPT_VERBOSE=1
	esac
done

PADDING_WIDTH=22

FUNCTIONS="name description author hasContent hasSources hasViews supportsWorkflow deleteIn copyContent permissions show spiderContent"
#SUSPECT_FUNCTIONS="getContent getContentType"
SUSPECT_FUNCTIONS="getLocationHierarchy"
DEPRECATED_FUNCTIONS="getContent getContentType"

MODCOUNT=0
FAILEDCOUNT=0

for MODULE in ../../modules/*module; do
	if [ -d $MODULE ]; then
		MODNAME=`basename $MODULE`
		MODCOUNT=`expr $MODCOUNT + 1`
		FAILED=0
		if [[ $OPT_VERBOSE == 1 ]]; then
			echo "Testing Compliance of $MODNAME"
		fi
		for F in $FUNCTIONS; do
			if [[ `grep "function $F(" $MODULE/class.php | wc -l` = 0 ]]; then
				if [[ $OPT_VERBOSE == 1 ]]; then
					echo -n "    Checking for existence of $F: "
					paddingSpaces $F $PADDING_WIDTH;
					failMessage
				fi
				FAILED=1
	                elif [[ $OPT_SHOW_SUCCESS = 1 ]]; then
				if [[ $OPT_VERBOSE == 1 ]]; then
					echo -n "    Checking for existence of $F: "
					paddingSpaces $F $PADDING_WIDTH;
					successMessage
				fi
			fi
		done

		if [[ $OPT_TEST_SUSPECT = 1 ]]; then
			if [[ $OPT_VERBOSE = 1 ]]; then
				echo "  Testing Suspect Functions in $MODNAME"
			fi
			for F in $SUSPECT_FUNCTIONS; do
	                        if [[ `grep "function $F(" $MODULE/class.php | wc -l` = 0 ]]; then
					if [[ $OPT_VERBOSE == 1 ]]; then
						echo -n "    Checking for existence of $F: "
						paddingSpaces $F $PADDING_WIDTH;
						failMessage
					fi
					FAILED=1
	                        elif [[ $OPT_SHOW_SUCCESS = 1 ]]; then
					if [[ $OPT_VERBOSE == 1 ]]; then
						echo -n "    Checking for existence of $F: "
						paddingSpaces $F $PADDING_WIDTH;
						successMessage
					fi
	                        fi
			done
		fi

		if [[ $OPT_VERBOSE = 1 ]]; then
			echo "Testing for absence of deprecated functions"
		fi
		for F in $DEPRECATED_FUNCTIONS; do
			if [[ `grep "function $F(" $MODULE/class.php | wc -l` != 0 ]]; then
				if [[ $OPT_VERBOSE == 1 ]]; then
					echo -n "    Checking for absence of $F: "
					paddingSpaces $F $PADDING_WIDTH;
					failMessage
				fi
				FAILED=1
			elif [[ $OPT_SHOW_SUCCESS = 1 ]]; then
				if [[ $OPT_VERBOSE == 1 ]]; then
					echo -n "    Checking for absence of $F: "
					paddingSpaces $F $PADDING_WIDTH;
					successMessage
				fi
			fi
		done

		if [[ $FAILED = 1 ]]; then
			echo "Module $MODNAME Failed Compliance Tests."
			FAILEDCOUNT=`expr $FAILEDCOUNT + 1`
		fi
	fi

done

PASSEDCOUNT=`expr $MODCOUNT - $FAILEDCOUNT`
echo "Tested compliance for $MODCOUNT module(s)."
echo "  -  $FAILEDCOUNT failed"
echo "  -  $PASSEDCOUNT passed"
