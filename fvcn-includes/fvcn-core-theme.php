<?php

/**
 * fvcn-core-theme.php
 *
 * Theme
 *
 * @package		Sigma Video
 * @subpackage	Theme
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * FvCommunityNews_Theme
 *
 */
class FvCommunityNews_Theme
{
	/**
	 * @var bool
	 */
	protected $_compatActive = true;
	
	/**
	 * __construct()
	 *
	 * @version 20120719
	 * @return void
	 */
	public function __construct()
	{
		
	}
	
	/**
	 * isCompatActive()
	 *
	 * @version 20120719
	 * @return bool
	 */
	public function isCompatActive()
	{
		return $this->_compatActive;
	}
}


/**
 * fvcn_get_theme_dir()
 *
 * @version 20120531
 * @return string
 */
function fvcn_get_theme_dir()
{
	return apply_filters('fvcn_get_theme_dir', FvCommunityNews_Registry::get('themeDir'));
}


/**
 * fvcn_get_theme_url()
 *
 * @version 20120531
 * @return string
 */
function fvcn_get_theme_url()
{
	return apply_filters('fvcn_get_theme_url', FvCommunityNews_Registry::get('themeUrl'));
}


/**
 * fvcn_theme_get_template_part()
 *
 * @version 20120716
 * @param string $slug
 * @param string $name
 * @return void
 */
function fvcn_get_template_part($slug, $name=null)
{
	if (null === $name) {
		$file = $slug . '.php';
	} else {
		$file = $slug . '-' . $name . '.php';
	}
	
	if (fvcn_theme_is_compat_active() || !file_exists(get_stylesheet_directory() . '/' . $file)) {
		load_template(fvcn_get_theme_dir() . '/' . $file, false);
	} else {
		get_template_part($slug, $name);
	}
}


/**
 * fvcn_get_query_template()
 *
 * @version 20120716
 * @param string $type
 * @param array $templates
 * @return string
 */
function fvcn_get_query_template($type, $templates)
{
	$templates = apply_filters('fvcn_get_' . $type . '_template', $templates);
	
	if ('' == ($template = locate_template($templates))) {
		FvCommunityNews_Registry::set('themeCompatActive', true);
	} else {
		FvCommunityNews_Registry::set('themeCompatActive', false);
	}
	
	return apply_filters('fvcn_' . $type . '_template', $template);
}


/**
 * fvcn_theme_get_single_post_template()
 *
 * @version 20120806
 * @return string
 */
function fvcn_theme_get_single_post_template()
{
	return fvcn_get_query_template('single_post', array(
		'single-' . fvcn_get_post_type() . '.php',
		'single-fvcn.php'
	));
}


/**
 * fvcn_theme_get_post_archive_template()
 *
 * @version 20120806
 * @return string
 */
function fvcn_theme_get_post_archive_template()
{
	return fvcn_get_query_template('post_archive', array(
		'archive-' . fvcn_get_post_type() . '.php',
		'archive-fvcn.php'
	));
}


/**
 * fvcn_theme_get_post_tag_archive_template()
 *
 * @version 20120806
 * @return string
 */
function fvcn_theme_get_post_tag_archive_template()
{
	return fvcn_get_query_template('post_tag', array(
		'taxonomy-' . fvcn_get_post_tag_id() . '.php',
		'taxonomy-fvcn.php'
	));
}


/**
 * fvcn_enqueue_theme_css()
 *
 * @version 20120717
 * @return void
 */
function fvcn_theme_enqueue_css()
{
	if (!fvcn_theme_is_compat_active() && file_exists(get_stylesheet_directory() . '/fvcn/css/fvcn-theme.css')) {
		$uri = get_stylesheet_directory_uri();
	} else {
		$uri = fvcn_get_theme_url();
	}
	
	wp_enqueue_style('fvcn-theme', $uri . '/fvcn/css/fvcn-theme.css', '', fvcn_get_version(), 'all');
	wp_enqueue_style('jquery.plupload.queue', $uri . '/fvcn/css/drag/jquery.plupload.queue.css', '', fvcn_get_version(), 'all');
}


/**
 * fvcn_theme_compat_active()
 *
 * @version 20120716
 * @return bool
 */
function fvcn_theme_is_compat_active()
{
	$active = true;
	
	if (false === FvCommunityNews_Registry::get('themeCompatActive')) {
		$active = false;
	}
	
	return apply_filters('fvcn_theme_is_compat_active', $active);
}


/**
 * fvcn_theme_compat_template_include()
 *
 * @version 20120716
 * @param string $template
 * @return string
 */
function fvcn_theme_compat_template_include($template)
{
	if (!is_fvcn()) {
		return $template;
	}
	
	if (fvcn_is_single_post()) {
		$newTemplate = fvcn_theme_get_single_post_template();
	} elseif (fvcn_is_post_archive()) {
		$newTemplate = fvcn_theme_get_post_archive_template();
	} elseif (fvcn_is_post_tag_archive()) {
		$newTemplate = fvcn_theme_get_post_tag_archive_template();
	}
	
	if (fvcn_theme_is_compat_active()) {
		add_filter('the_content', 'fvcn_theme_compat_replace_the_content');
	} else {
		$template = $newTemplate;
	}
	
	return apply_filters('fvcn_theme_compat_template_include', $template);
}


/**
 * fvcn_theme_compat_replace_the_content()
 *
 * @version 20120707
 * @param string $content
 * @return string
 */
function fvcn_theme_compat_replace_the_content($content)
{
	if (fvcn_theme_is_compat_active()) {
		if (fvcn_is_single_post()) {
			ob_start();
			
			fvcn_get_template_part('fvcn/content', 'single-post');
			$newContent = ob_get_contents();
			
			ob_end_clean();
		} elseif (fvcn_is_post_archive() || fvcn_is_post_tag_archive()) {
			ob_start();
			
			fvcn_get_template_part('fvcn/content', 'archive-post');
			$newContent = ob_get_contents();
			
			ob_end_clean();
		}
		
		if (isset($newContent)) {
			$content = apply_filters('fvcn_theme_compat_replace_the_content', $newContent, $content);
		}
	}
	
	return $content;
}

