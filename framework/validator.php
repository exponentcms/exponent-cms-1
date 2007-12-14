<?php
##################################################
#
# Copyright (c) 2007-2008 OIC Group, Inc.
# Written and Designed by Adam Kessler
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

class validator {
	public static function validate($vars, $post) {
		if (!is_array($vars)) return false;

		$post['_formError'] = array();
		foreach($vars as $validate_type=>$param) {
			switch($validate_type) {
				case 'capcha':
					$capcha_real = exponent_sessions_get('capcha_string');
					if (SITE_USE_CAPTCHA && strtoupper($post['captcha_string']) != $capcha_real) {
        					unset($post['captcha_string']);
        					$post['_formError'][] = 'Capcha Verification Failed';
					}
				break;
				case 'presense_of':
					if (empty($post[$param])) $post['_formError'][] = $param.' is a required field.';
				break;
				case 'valid_email':
					if (empty($post[$param])) $post['_formError'][] = $param.' is a required field.';
					if (!self::validate_email_address($post[$param])) $post['_formError'][] = $post[$param].' does not appear to be a valid email address.';
				break;
			}
		}

		if (count($post['_formError']) > 0) {
			self::failAndReturnToForm($post['_formError']);
		}
	}

	public static function failAndReturnToForm($msg, $post=null) {
		flash('error', $msg);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}

	public static function validate_email_address($email) {
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
				return false;
			}
		}
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
					return false;
				}
			}
		}
		return true;
	}		
}
?>



