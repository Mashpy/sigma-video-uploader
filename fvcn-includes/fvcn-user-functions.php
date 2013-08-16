<?php

/**
 *	fvcn-user-functions.php
 *
 *	User Functions
 *
 *	@package	Sigma Video
 *	@subpackage	Functions
 *	@author		Frank Verhoeven
 */

if (!defined('ABSPATH')) {
	die('Direct access is not allowed!');
}

/**
 *	fvcn_is_anonymous()
 *
 *	@version 20120229
 *	@uses is_user_logged_in()
 *	@uses apply_filters()
 *	@return bool
 */
function fvcn_is_anonymous() {
	if (!is_user_logged_in()) {
		$is_anonymous = true;
	} else {
		$is_anonymous = false;
	}
	
	return apply_filters('fvcn_is_anonymous', $is_anonymous);
}


/**
 *	fvcn_get_current_author_ip()
 *
 *	@version 20120229
 *	@uses apply_filters()
 *	@return string
 */
function fvcn_get_current_author_ip() {
	$ip = preg_replace('/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR']);
	
	return apply_filters('fvcn_get_current_author_ip', $ip);
}


/**
 *	fvcn_get_current_author_ua()
 *
 *	@version 20120229
 *	@uses apply_filters()
 *	@return string
 */
function fvcn_get_current_author_ua() {
	if (!empty($_SERVER['HTTP_USER_AGENT'])) {
		$ua = substr($_SERVER['HTTP_USER_AGENT'], 0, 254);
	} else {
		$ua = '';
	}
	
	return apply_filters('fvcn_get_current_author_ua', $ua);
}













