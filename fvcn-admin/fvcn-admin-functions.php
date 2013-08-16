<?php

/**
 * fvcn-admin-functions.php
 *
 * Admin Area
 *
 * @package		Sigma Video
 * @subpackage	Admin Functions
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * fvcn_form_option()
 *
 * @version 20120524
 * @uses fvcn_get_form_option()
 * @param string $option
 * @param bool $slug
 * @return void
 */
function fvcn_form_option($option, $slug=false)
{
	echo fvcn_get_form_option($option, $slug);
}
	
	/**
	 * fvcn_get_form_option()
	 *
	 * @version 20120524
	 * @param string $option
	 * @param bool $slug
	 * @return mixed
	 */
	function fvcn_get_form_option($option, $slug=false)
	{
		$value = fvcn_get_option($option);
		
		if (true === $slug) {
			$value = apply_filters('editable_slug', $value);
		}
		
		return apply_filters('fvcn_get_form_option', esc_attr($value));
	}

