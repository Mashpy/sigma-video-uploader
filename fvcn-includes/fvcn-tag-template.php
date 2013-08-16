<?php

/**
 * fvcn-tag-template.php
 *
 * Tag Template
 *
 * @package		Sigma Video
 * @subpackage	Template
 * @author		Frank Verhoeven
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * fvcn_tag_cloud()
 *
 * @version 20120716
 * @param string|array $args
 * @return void
 */
function fvcn_tag_cloud($args='')
{
	$default = array('taxonomy'=>fvcn_get_post_tag_id());
	$args = wp_parse_args($args, $default);
	
	wp_tag_cloud($args);
}

