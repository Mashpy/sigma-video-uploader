<?php

/**
 *	fvcn-user-template.php
 *
 *	User Template
 *
 *	@package	Sigma Video
 *	@subpackage	Template
 *	@author		Frank Verhoeven
 */

if (!defined('ABSPATH')) {
	die('Direct access is not allowed!');
}

/**
 *	fvcn_user_id()
 *
 *	@version 20120307
 *	@uses fvcn_get_user_id()
 *	@param int $user_id
 *	@return void
 */
function fvcn_user_id($user_id=0) {
	echo fvcn_get_user_id($user_id);
}
	
	/**
	 *	fvcn_get_user_id()
	 *
	 *	@version 20120307
	 *	@uses fvcn_is_anonymous()
	 *	@uses fvcn_get_current_user_id()
	 *	@uses apply_filters()
	 *	@param int $user_id
	 *	@return int
	 */
	function fvcn_get_user_id($user_id=0) {
		if (!empty($user_id) && is_numeric($user_id)) {
			$id = $user_id;
			
		} elseif (!fvcn_is_anonymous()) {
			$id = fvcn_get_current_user_id();
			
		} else {
			$id = 0;
		}
		
		return apply_filters('fvcn_get_user_id', $id);
	}


/**
 *	fvcn_current_user_id()
 *
 *	@version 20120229
 *	@uses fvcn_get_current_user_id()
 *	@return void
 */
function fvcn_current_user_id() {
	echo fvcn_get_current_user_id();
}
	
	/**
	 *	fvcn_get_current_user_id()
	 *
	 *	@version 20120229
	 *	@uses wp_get_current_user()
	 *	@uses apply_filters()
	 *	@return int
	 */
	function fvcn_get_current_user_id() {
		$current_user = wp_get_current_user();
		
		return apply_filters('fvcn_get_current_user_id', $current_user->ID);
	}


/**
 *	fvcn_current_user_name()
 *
 *	@version 20120307
 *	@uses fvcn_get_current_user_name()
 *	@return void
 */
function fvcn_current_user_name() {
	echo fvcn_get_current_user_name();
}
	
	/**
	 *	fvcn_get_current_user_name()
	 *
	 *	@version 20120307
	 *	@uses $user_identity
	 *	@uses apply_filters()
	 *	@return string
	 */
	function fvcn_get_current_user_name() {
		global $user_identity;
		
		return apply_filters('fvcn_get_current_user_name', $user_identity);
	}


/**
 *	fvcn_has_user_posts()
 *
 *	@version 20120323
 *	@uses fvcn_get_user_id()
 *	@uses fvcn_get_public_post_status()
 *	@uses fvcn_has_posts()
 *	@uses apply_filters()
 *	@return bool
 */
function fvcn_has_user_posts($user_id=0, $post_status='') {
	$id = fvcn_get_user_id($user_id);
	
	if (0 == $id) {
		$retval = false;
	} else {
		if (empty($post_status)) {
			$post_status = fvcn_get_public_post_status();
		}
		
		$args = array(
			'author'		=> $id,
			'post_status'	=> $post_status
		);
		
		$retval = fvcn_has_posts($args);
	}
	
	return apply_filters('fvcn_has_user_posts', (bool) $retval);
}













