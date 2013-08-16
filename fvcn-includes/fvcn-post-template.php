<?php

/**
 * fvcn-post-template.php
 *
 * Post Template
 *
 * @package		Sigma Video
 * @subpackage	Template
 * @author		Frank Verhoeven
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * fvcn_post_type()
 *
 * @version 20120229
 * @return void
 */
function fvcn_post_type()
{
	echo fvcn_get_post_type();
}
	
	/**
	 * fvcn_get_post_type()
	 *
	 * @version 20120229
	 * @return string
	 */
	function fvcn_get_post_type()
	{
		return apply_filters('fvcn_get_post_type', FvCommunityNews_Registry::get('postType'));
	}


/**
 * fvcn_post_slug()
 *
 * @version 20120321
 * @return void
 */
function fvcn_post_slug()
{
	echo fvcn_get_post_slug();
}
	
	/**
	 * fvcn_get_post_slug()
	 *
	 * @version 20120321
	 * @return string
	 */
	function fvcn_get_post_slug()
	{
		return apply_filters('fvcn_get_post_slug', FvCommunityNews_Registry::get('postSlug'));
	}


/**
 * fvcn_has_posts()
 *
 * @version 20120305
 * @param mixed $args
 * @return object
 */
function fvcn_has_posts($args='')
{
	$defaults = array(
		'post_type'		=> fvcn_get_post_type(),
		'post_status'	=> fvcn_get_public_post_status(),
		'posts_per_page'=> 15,
		'order'			=> 'DESC'
	);
	
	$options = wp_parse_args($args, $defaults);
	$options = apply_filters('fvcn_has_posts_query', $options);
	
	FvCommunityNews_Registry::set('wpQuery', new WP_Query( $options ));
	
	return apply_filters('fvcn_has_posts', FvCommunityNews_Registry::get('wpQuery')->have_posts(), FvCommunityNews_Registry::get('wpQuery'));
}


/**
 * fvcn_posts()
 *
 * @version 20120305
 * @return object
 */
function fvcn_posts()
{
	$have_posts = FvCommunityNews_Registry::get('wpQuery')->have_posts();
	
	if (empty($have_posts)) {
		wp_reset_postdata();
	}
	
	return $have_posts;
}


/**
 * fvcn_the_post()
 *
 * @version 20120305
 * @return object
 */
function fvcn_the_post()
{
	return FvCommunityNews_Registry::get('wpQuery')->the_post();
}


/**
 * fvcn_post_id()
 *
 * @version 20120305
 * @param int $postId
 * @return void
 */
function fvcn_post_id($postId=0)
{
	echo fvcn_get_post_id($postId);
}
	
	/**
	 * fvcn_get_post_id()
	 *
	 * @version 20120325
	 * @param int $postId
	 * @return int
	 */
	function fvcn_get_post_id($postId=0)
	{
		global $wp_query, $post;
		
		if (!empty($postId) && is_numeric($postId)) {
			$id = $postId;
			
		} elseif (!empty(FvCommunityNews_Registry::get('wpQuery')->in_the_loop) && isset(FvCommunityNews_Registry::get('wpQuery')->post->ID)) {
			$id = FvCommunityNews_Registry::get('wpQuery')->post->ID;
			
		} elseif (fvcn_is_single_post() && isset($wp_query->post->ID)) {
			$id = $wp_query->post->ID;
			
		} elseif (isset($post->ID)) {
			$id = $post->ID;
			
		} else {
			$id = 0;
		}
		
		return apply_filters('fvcn_get_post_id', $id, $postId);
	}


/**
 * fvcn_get_post()
 *
 * @version 20120309
 * @param int $postId
 * @return object
 */
function fvcn_get_post($postId=0)
{
	$id = fvcn_get_post_id($postId);
	
	if (empty($id)) {
		return null;
	}
	
	$post = get_post($id, OBJECT);
	
	if (!$post || $post->post_type != fvcn_get_post_type()) {
		return null;
	}
	
	return apply_filters('fvcn_get_post', $post);
}


/**
 * fvcn_post_permalink()
 *
 * @version 20120305
 * @param int $postId
 * @return void
 */
function fvcn_post_permalink($postId=0)
{
	echo fvcn_get_post_permalink($postId);
}
	
	/**
	 * fvcn_get_post_permalink()
	 *
	 * @version 20120305
	 * @param int $postId
	 * @param string $redirect
	 * @return string
	 */
	function fvcn_get_post_permalink($postId=0, $redirect='')
	{
		$id = fvcn_get_post_id($postId);
		
		if (!empty($redirect)) {
			$permalink = esc_url($redirect);
		} else {
			$permalink = get_permalink($id);
		}
		
		return apply_filters('fvcn_get_post_permalink', $permalink, $id);
	}


/**
 * fvcn_has_post_link()
 *
 * @version 20120316
 * @param int $postId
 * @return bool
 */
function fvcn_has_post_link($postId=0)
{
	$link = fvcn_get_post_link($postId);
	
	return !empty($link);
}


/**
 * fvcn_post_link()
 *
 * @version 20120311
 * @param int $postId
 * @return void
 */
function fvcn_post_link($postId=0)
{
	echo fvcn_get_post_link($postId);
}
	
	/**
	 * fvcn_get_post_link()
	 *
	 * @version 20120311
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_link($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		$link = esc_url( get_post_meta($id, '_fvcn_post_url', true) );
		
		return apply_filters('fvcn_get_post_link', $link, $id);
	}


/**
 * fvcn_post_title()
 *
 * @version 20120305
 * @param int $postId
 * @return void
 */
function fvcn_post_title($postId=0)
{
	echo fvcn_get_post_title($postId);
}
	
	/**
	 * fvcn_get_post_title()
	 *
	 * @version 20120305
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_title($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		return apply_filters('fvcn_get_post_title', get_the_title($id), $id);
	}


/**
 * fvcn_post_content()
 *
 * @version 20120305
 * @param int $postId
 * @return void
 */
function fvcn_post_content($postId=0)
{
	echo fvcn_get_post_content($postId);
}
	
	/**
	 * fvcn_get_post_content()
	 *
	 * @version 20120305
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_content($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		if (post_password_required($id)) {
			return get_the_password_form();
		}
		
		$content = get_post_field('post_content', $id);
		
		return apply_filters('fvcn_get_post_content', $content, $id);
	}


/**
 * fvcn_post_excerpt()
 *
 * @version 20120305
 * @param int $postId
 * @param int $length
 * @return void
 */
function fvcn_post_excerpt($postId=0, $length=100) {
	echo fvcn_get_post_excerpt($postId, $length);
}
	
	/**
	 * fvcn_get_post_excerpt()
	 *
	 * @version 20120305
	 * @param int $postId
	 * @param int $length
	 * @return string
	 */
	function fvcn_get_post_excerpt($postId=0, $length=100) {
		$id = fvcn_get_post_id($postId);
		$length = abs((int)$length);
		
		if (post_password_required($id)) {
			return apply_filters('fvcn_get_post_excerpt', '');
		}
		
		$excerpt = get_post_field('post_excerpt', $id);
		
		if (empty($excerpt)) {
			$excerpt = get_post_field('post_content', $id);
		}
		
		$excerpt = trim( strip_tags($excerpt) );
		
		if (!empty($length) && strlen($excerpt) > $length) {
			$string = ''; $i = 0;
			$array = explode(' ', $excerpt);
			
			while (strlen($string) < $length) {
				$string .= $array[ $i ] . ' ';
				$i++;
			}
			
			if (trim($string) != $excerpt) {
				$excerpt = trim($string) . '&hellip;';
			}
		}
		
		return apply_filters('fvcn_get_post_excerpt', $excerpt, $id);
	}


/**
 * fvcn_post_date()
 *
 * @version 20120322
 * @param int $postId
 * @param string $format
 * @return void
 */
function fvcn_post_date($postId=0, $format='') {
	echo fvcn_get_post_date($postId, $format);
}
	
	/**
	 * fvcn_get_post_date()
	 *
	 * @version 20120322
	 * @param int $postId
	 * @param string $format
	 * @return string
	 */
	function fvcn_get_post_date($postId=0, $format='') {
		$id = fvcn_get_post_id($postId);
		
		if (empty($format)) {
			$date = mysql2date(get_option('date_format'), get_post_field('post_date', $id));
		} else {
			$date = mysql2date($format, get_post_field('post_date', $id));
		}
		
		return apply_filters('fvcn_get_post_date', $date, $id);
	}


/**
 * fvcn_post_time()
 *
 * @version 20120322
 * @param int $postId
 * @param string $format
 * @param bool $gmt
 * @return void
 */
function fvcn_post_time($postId=0, $format='', $gmt=false) {
	echo fvcn_get_post_time($postId, $format, $gmt);
}
	
	/**
	 * fvcn_get_post_time()
	 *
	 * @version 20120322
	 * @param int $postId
	 * @param string $format
 	*	@param bool $gmt
	 * @return string
	 */
	function fvcn_get_post_time($postId=0, $format='', $gmt=false) {
		$id = fvcn_get_post_id($postId);
		
		if ($gmt) {
			$date = get_post_field('post_date_gmt', $id);
		} else {
			$date = get_post_field('post_date', $id);
		}
		
		if (empty($format)) {
			$time = mysql2time(get_option('time_format'), $date);
		} else {
			$time = mysql2time($format, $date);
		}
		
		return apply_filters('fvcn_get_post_time', $time, $id);
	}


/**
 * fvcn_has_post_thumbnail()
 *
 * @version 20120801
 * @param int $postId
 * @return bool
 */
function fvcn_has_post_thumbnail($postId=0)
{
	$id = fvcn_get_post_id($postId);
	
	// Double thumbnail display fix.
	if ('the_content' != current_filter() || false === FvCommunityNews_Registry::get('nativeThumbnailSupport')) {
		return has_post_thumbnail($id);
	} else {
		return false;
	}
}


/**
 * fvcn_post_thumbnail()
 *
 * @version 20120311
 * @param int $postId
 * @param string|array $size
 * @param string|array $attributes
 * @return void
 */
function fvcn_post_thumbnail($postId=0, $size='thumbnail', $attributes=array()) {
	echo fvcn_get_post_thumbnail($postId, $size, $attributes);
}
	
	/**
	 * fvcn_get_post_thumbnail()
	 *
	 * @version 20120317
	 * @param int $postId
	 * @param string|array $size
	 * @param string|array $attributes
	 * @return string
	 */
	function fvcn_get_post_thumbnail($postId=0, $size='thumbnail', $attributes=array()) {
		$id = fvcn_get_post_id($postId);
		
		return apply_filters('fvcn_get_post_thumbnail', get_the_post_thumbnail($id, $size, $attributes), $id);
	}


/**
 * fvcn_post_rating()
 *
 * @version 20120321
 * @param int $postId
 * @return void
 */
function fvcn_post_rating($postId=0)
{
	echo fvcn_get_post_rating($postId);
}
	
	/**
	 * fvcn_get_post_rating()
	 *
	 * @version 20120321
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_rating($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		$rating = get_post_meta($id, '_fvcn_post_rating', true);
		
		if (!is_numeric($rating)) {
			$rating = 0;
		}
		
		return apply_filters('fvcn_get_post_rating', $rating, $id);
	}


/**
 * fvcn_post_rating_increment_link()
 *
 * @version 20120321
 * @param int $postId
 * @return void
 */
function fvcn_post_rating_increment_link($postId=0)
{
	echo fvcn_get_post_rating_increment_link($postId);
}
	
	/**
	 * fvcn_get_post_rating_increment_link()
	 *
	 * @version 20120321
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_rating_increment_link($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		$link = wp_nonce_url(add_query_arg(
			array(
				'post_id'					=> $id,
				'fvcn_post_rating_action'	=> 'increase'
			),
			fvcn_get_post_permalink($id)
		), 'fvcn-post-rating');
		
		return apply_filters('fvcn_get_post_rating_increment_link', $link, $id);
	}


/**
 * fvcn_post_rating_decrement_link()
 *
 * @version 20120321
 * @param int $postId
 * @return void
 */
function fvcn_post_rating_decrement_link($postId=0)
{
	echo fvcn_get_post_rating_decrement_link($postId);
}
	
	/**
	 * fvcn_get_post_rating_decrement_link()
	 *
	 * @version 20120321
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_rating_decrement_link($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		$link = wp_nonce_url(add_query_arg(
			array(
				'post_id'					=> $id,
				'fvcn_post_rating_action'	=> 'decrease'
			),
			fvcn_get_post_permalink($id)
		), 'fvcn-post-rating');
		
		return apply_filters('fvcn_get_post_rating_decrement_link', $link, $id);
	}


/**
 * fvcn_is_post_rated_by_current_user()
 *
 * @version 20120321
 * @param int $postId
 * @return bool
 */
function fvcn_is_post_rated_by_current_user($postId=0)
{
	$id = fvcn_get_post_id($postId);
	
	return apply_filters('fvcn_is_post_rated_by_current_user', isset($_COOKIE['fvcn_post_rated_' . $id . '_' . COOKIEHASH]));
}


/**
 * fvcn_post_views()
 *
 * @version 20120622
 * @param int $postId
 * @return int
 */
function fvcn_post_views($postId=0)
{
	echo fvcn_get_post_views($postId);
}
	
	/**
	 * fvcn_get_post_views()
	 *
	 * @version 20120622
	 * @param int $postId
	 * @return int
	 */
	function fvcn_get_post_views($postId=0)
	{
		$postId = fvcn_get_post_id($postId);
		
		$views = get_post_meta($postId, '_fvcn_post_views', true);
		
		if (!is_numeric($views)) {
			$views = 0;
		}
		
		return apply_filters('fvcn_get_post_views', $views, $postId);
	}


/**
 * fvcn_post_status()
 *
 * @version 20120306
 * @param int $postId
 * @return void
 */
function fvcn_post_status($postId=0)
{
	echo fvcn_get_post_status($postId);
}
	
	/**
	 * fvcn_get_post_status()
	 *
	 * @version 20120306
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_status($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		return apply_filters('fvcn_get_post_status', get_post_status($id), $id);
	}


/**
 * fvcn_post_archive_link()
 *
 * @version 20120321
 * @return void
 */
function fvcn_post_archive_link()
{
	echo fvcn_get_post_archive_link();
}
	
	/**
	 * fvcn_get_post_archive_link()
	 *
	 * @version 20120321
	 * @return string
	 */
	function fvcn_get_post_archive_link()
	{
		$link = get_post_type_archive_link( fvcn_get_post_type() );
		
		return apply_filters('fvcn_get_post_archive_link', $link);
	}


/**
 * fvcn_is_post()
 *
 * @version 20120308
 * @param int $postId
 * @return bool
 */
function fvcn_is_post($postId=0)
{
	$is_post = false;
	
	if (!empty($postId) && fvcn_get_post_type() == get_post_type($postId)) {
		$is_post = true;
	}
	
	return (bool) apply_filters('fvcn_is_post', $is_post, $postId);
}


/**
 * fvcn_is_post_published()
 *
 * @version 20120306
 * @param int $postId
 * @return bool
 */
function fvcn_is_post_published($postId=0)
{
	return fvcn_get_public_post_status() == fvcn_get_post_status( fvcn_get_post_id($postId) );
}


/**
 * fvcn_is_post_pending()
 *
 * @version 20120306
 * @param int $postId
 * @return bool
 */
function fvcn_is_post_pending($postId=0)
{
	return fvcn_get_pending_post_status() == fvcn_get_post_status( fvcn_get_post_id($postId) );
}


/**
 * fvcn_is_post_trash()
 *
 * @version 20120306
 * @param int $postId
 * @return bool
 */
function fvcn_is_post_trash($postId=0)
{
	return fvcn_get_trash_post_status() == fvcn_get_post_status( fvcn_get_post_id($postId) );
}


/**
 * fvcn_is_post_spam()
 *
 * @version 20120306
 * @param int $postId
 * @return bool
 */
function fvcn_is_post_spam($postId=0)
{
	return fvcn_get_spam_post_status() == fvcn_get_post_status( fvcn_get_post_id($postId) );
}


/**
 * fvcn_is_post_private()
 *
 * @version 20120306
 * @param int $postId
 * @return bool
 */
function fvcn_is_post_private($postId=0)
{
	return fvcn_get_private_post_status() == fvcn_get_post_status( fvcn_get_post_id($postId) );
}


/**
 * fvcn_is_post_anonymous()
 *
 * @version 20120306
 * @param int $postId
 * @return bool
 */
function fvcn_is_post_anonymous($postId=0)
{
	$id = fvcn_get_post_id($postId);
	
	if (0 !== fvcn_get_post_author_id($id)) {
		return false;
	}
	if (false == get_post_meta($id, '_fvcn_anonymous_author_name', true)) {
		return false;
	}
	if (false == get_post_meta($id, '_fvcn_anonymous_author_email', true)) {
		return false;
	}
	
	return true;
}


/**
 * fvcn_is_single_post()
 *
 * @version 20120319
 * @return bool
 */
function fvcn_is_single_post()
{
	$retval = false;
	
	if (is_singular(fvcn_get_post_type())) {
		$retval = true;
	}
	
	return apply_filters('fvcn_is_single_post', $retval);
}


/**
 * fvcn_is_post_archive()
 *
 * @version 20120322
 * @return bool
 */
function fvcn_is_post_archive()
{
	$retval = false;
	
	if (is_post_type_archive( fvcn_get_post_type() )) {
		$retval = true;
	}
	
	return apply_filters('fvcn_is_post_archive', $retval);
}


/**
 * fv_is_post_tag_archive()
 *
 * @version 20120325
 * @return bool
 */
function fvcn_is_post_tag_archive()
{
	$retval = false;
	
	if (is_tax( fvcn_get_post_tag_id() )) {
		$retval = true;
	}
	
	return apply_filters('fvcn_is_post_tag_archive', $retval);
}


/**
 * fvcn_post_author()
 *
 * @version 20120306
 * @param int $postId
 * @return void
 */
function fvcn_post_author($postId=0)
{
	echo fvcn_get_post_author($postId);
}
	
	/**
	 * fvcn_get_post_author()
	 *
	 * @version 20120306
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_author($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		if (!fvcn_is_post_anonymous($id)) {
			$author = get_the_author_meta('display_name', fvcn_get_post_author_id($id));
		} else {
			$author = get_post_meta($id, '_fvcn_anonymous_author_name', true);
		}
		
		return apply_filters('fvcn_get_post_author', $author, $id);
	}


/**
 * fvcn_post_author_id()
 *
 * @version 20120306
 * @param int $postId
 * @return void
 */
function fvcn_post_author_id($postId=0)
{
	echo fvcn_get_post_author_id($postId);
}
	
	/**
	 * fvcn_get_post_author_id()
	 *
	 * @version 20120306
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_author_id($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		$author = get_post_field('post_author', $id);
		
		return apply_filters('fvcn_get_post_author_id', (int)$author, $id);
	}


/**
 * fvcn_post_author_display_name()
 *
 * @version 20120306
 * @param int $postId
 * @return void
 */
function fvcn_post_author_display_name($postId=0)
{
	echo fvcn_get_post_author_display_name($postId);
}
	
	/**
	 * fvcn_get_post_author_display_name()
	 *
	 * @version 20120306
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_author_display_name($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		if (!fvcn_is_post_anonymous($id)) {
			$author_display_name = get_the_author_meta('display_name', fvcn_get_post_author_id($id));
		} else {
			$author_display_name = get_post_meta($id, '_fvcn_anonymous_author_name', true);
		}
		
		return apply_filters('fvcn_get_post_author_display_name', $author_display_name, $id);
	}


/**
 * fvcn_post_author_email()
 *
 * @version 20120306
 * @param int $postId
 * @return void
 */
function fvcn_post_author_email($postId=0)
{
	echo fvcn_get_post_author_email($postId);
}
	
	/**
	 * fvcn_get_post_author_email()
	 *
	 * @version 20120306
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_author_email($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		if (!fvcn_is_post_anonymous($id)) {
			$email = get_the_author_meta('user_email', fvcn_get_post_author_id($id));
		} else {
			$email = get_post_meta($id, '_fvcn_anonymous_author_email', true);
		}
		
		return apply_filters('fvcn_get_post_author_email', $email, $id);
	}


/**
 * fvcn_post_author_avatar()
 *
 * @version 20120306
 * @param int $postId
 * @param int $size
 * @return void
 */
function fvcn_post_author_avatar($postId=0, $size=40)
{
	echo fvcn_get_post_author_avatar($postId, $size);
}
	
	/**
	 * fvcn_get_post_author_avatar()
	 *
	 * @version 20120701
	 * @param int $postId
	 * @param int $size
	 * @return string
	 */
	function fvcn_get_post_author_avatar($postId=0, $size=40)
	{
		$avatar = get_avatar(fvcn_get_post_author_email($postId), $size);
		
		return apply_filters('fvcn_get_post_author_avatar', $avatar, $postId);
	}


/**
 * fvcn_post_author_website()
 *
 * @version 20120306
 * @param int $postId
 * @return void
 */
function fvcn_post_author_website($postId=0)
{
	echo fvcn_get_post_author_website($postId);
}
	
	/**
	 * fvcn_get_post_author_website()
	 *
	 * @version 20120306
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_author_website($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		if (!fvcn_is_post_anonymous($id)) {
			$website = get_the_author_meta('user_url', fvcn_get_post_author_id($id));
		} else {
			$website = '';
		}
		
		return apply_filters('fvcn_get_post_author_website', $website, $id);
	}


/**
 * fvcn_post_author_link()
 *
 * @version 20120305
 * @param int $postId
 * @return void
 */
function fvcn_post_author_link($postId=0)
{
	echo fvcn_get_post_author_link($postId);
}
	
	/**
	 * fvcn_get_post_author_link()
	 *
	 * @version 20120305
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_author_link($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		if ('' != fvcn_get_post_author_website($postId)) {
			$link = '<a href="' . fvcn_get_post_author_website($id) . '">' . fvcn_get_post_author_display_name($id) . '</a>';
		} else {
			$link = fvcn_get_post_author_display_name($id);
		}
		
		return apply_filters('fvcn_get_post_author_link', $link, $id);
	}


/**
 * fvcn_post_author_ip()
 *
 * @version 20120311
 * @param int $postId
 * @return void
 */
function fvcn_post_author_ip($postId=0)
{
	echo fvcn_get_post_author_ip($postId);
}
	
	/**
	 * fvcn_get_post_author_ip()
	 *
	 * @version 20120311
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_author_ip($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		$ip = get_post_meta($id, '_fvcn_author_ip', true);
		
		return apply_filters('fvcn_get_post_author_ip', $ip, $id);
	}


/**
 * fvcn_post_author_ua()
 *
 * @version 20120322
 * @param int $postId
 * @return void
 */
function fvcn_post_author_ua($postId=0)
{
	echo fvcn_get_post_author_ua($postId);
}
	
	/**
	 * fvcn_get_post_author_ua()
	 *
	 * @version 20120322
	 * @param int $postId
	 * @return string
	 */
	function fvcn_get_post_author_ua($postId=0)
	{
		$id = fvcn_get_post_id($postId);
		
		$ua = get_post_meta($id, '_fvcn_author_ua', true);
		
		return apply_filters('fvcn_get_post_author_ua', $ua, $id);
	}


/**
 * fvcn_post_tag_id()
 *
 * @version 20120229
 * @return void
 */
function fvcn_post_tag_id()
{
	echo fvcn_get_post_tag_id();
}
	
	/**
	 * fvcn_get_post_tag_id()
	 *
	 * @version 20120710
	 * @return string
	 */
	function fvcn_get_post_tag_id()
	{
		return apply_filters('fvcn_get_post_tag_id', FvCommunityNews_Registry::get('postTagId'));
	}


/**
 * fvcn_post_tag_slug()
 *
 * @version 20120325
 * @return void
 */
function fvcn_post_tag_slug()
{
	echo fvcn_get_post_tag_slug();
}
	
	/**
	 * fvcn_get_post_tag_slug()
	 *
	 * @version 20120710
	 * @return string
	 */
	function fvcn_get_post_tag_slug()
	{
		return apply_filters('fvcn_get_post_tag_slug', FvCommunityNews_Registry::get('postTagSlug'));
	}


/**
 * fvcn_post_tag_list()
 *
 * @version 20120311
 * @param int $postId
 * @param string|array $args
 * @return void
 */
function fvcn_post_tag_list($postId=0, $args='')
{
	echo fvcn_get_post_tag_list($postId, $args);
}
	
	/**
	 * fvcn_get_post_tag_list()
	 *
	 * @version 20120311
	 * @param int $postId
	 * @param string|array $args
	 * @return string
	 */
	function fvcn_get_post_tag_list($postId=0, $args='')
	{
		$id = fvcn_get_post_id($postId);
		
		$default = array(
			'before'	=> '<div class="fvcn-post-tags"><p>' . __('Tags:', 'fvcn') . ' ',
			'sep'		=> ', ',
			'after'		=> '</p></div>'
		);
		
		$args = wp_parse_args($args, $default);
		extract($args);
		
		$tag_list = get_the_term_list($id, fvcn_get_post_tag_id(), $before, $sep, $after);
		
		return apply_filters('fvcn_get_post_tag_list', $tag_list, $id);
	}


/**
 * fvcn_post_form_fields()
 *
 * @version 20120307
 * @return void
 */
function fvcn_post_form_fields()
{
?>
	
	<input type="hidden" name="fvcn_post_form_action" id="fvcn_post_form_action" value="fvcn-new-post" />
	<?php wp_nonce_field('fvcn-new-post', 'fvcn_post_form_nonce'); ?>
	
	<?php
	$crypt = new FvCommunityNews_Crypt( hash('sha256', wp_create_nonce('fvcn-post-form-time-key')) );
	
	if ($crypt->canEncrypt()) {
		$value = base64_encode($crypt->getIv()) . ':' . $crypt->encrypt( time() );
	} else {
		$value = base64_encode( time() );
	}
	?>
	
	<input type="hidden" name="fvcn_post_form_time_key" id="fvcn_post_form_time_key" value="<?php echo $value; ?>" />
	
<?php
}


/**
 * fvcn_post_form_field_error()
 *
 * @version 20120706
 * @param string $field
 * @return void
 */
function fvcn_post_form_field_error($field)
{
	$errors = FvCommunityNews_Container::getInstance()->getWpError()->get_error_messages($field);
	
	if (empty($errors)) {
		return;
	}
	
	echo '<ul class="fvcn-template-notice error">';
	
	foreach ($errors as $error) {
		echo '<li>' . $error . '</li>';
	}
	
	echo '</ul>';
}


/**
 * fvcn_post_form_author_name_label()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_author_name_label()
{
	echo fvcn_get_post_form_author_name_label();
}
	
	/**
	 * fvcn_get_post_form_author_name_label()
	 *
	 * @version 20120524
	 * @return string
	 */
	function fvcn_get_post_form_author_name_label()
	{
		$label = esc_attr( fvcn_get_option('_fvcn_post_form_author_name_label') );
		
		return apply_filters('fvcn_get_post_form_author_name_label', $label);
	}

/**
 * fvcn_post_form_author_name()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_author_name()
{
	echo fvcn_get_post_form_author_name();
}
	
	/**
	 * fvcn_get_post_form_author_name()
	 *
	 * @version 20120306
	 * @return string
	 */
	function fvcn_get_post_form_author_name()
	{
		if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
			$value = $_POST['fvcn_post_form_author_name'];
		} else {
			$value = '';
		}
		
		return apply_filters('fvcn_get_post_form_author_name', esc_attr($value));
	}


/**
 * fvcn_post_form_author_email_label()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_author_email_label()
{
	echo fvcn_get_post_form_author_email_label();
}
	
	/**
	 * fvcn_get_post_form_author_email_label()
	 *
	 * @version 20120524
	 * @return string
	 */
	function fvcn_get_post_form_author_email_label()
	{
		$label = esc_attr( fvcn_get_option('_fvcn_post_form_author_email_label') );
		
		return apply_filters('fvcn_get_post_form_author_email_label', $label);
	}

/**
 * fvcn_post_form_author_email()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_author_email()
{
	echo fvcn_get_post_form_author_email();
}
	
	/**
	 * fvcn_get_post_form_author_email()
	 *
	 * @version 20120306
	 * @return string
	 */
	function fvcn_get_post_form_author_email()
	{
		if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
			$value = $_POST['fvcn_post_form_author_email'];
		} else {
			$value = '';
		}
		
		return apply_filters('fvcn_get_post_form_author_email', esc_attr($value));
	}


/**
 * fvcn_post_form_title_label()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_title_label()
{
	echo fvcn_get_post_form_title_label();
}
	
	/**
	 * fvcn_get_post_form_title_label()
	 *
	 * @version 20120524
	 * @return string
	 */
	function fvcn_get_post_form_title_label()
	{
		$label = esc_attr( fvcn_get_option('_fvcn_post_form_title_label') );
		
		return apply_filters('fvcn_get_post_form_title_label', $label);
	}

/**
 * fvcn_post_form_title()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_title()
{
	echo fvcn_get_post_form_title();
}
	
	/**
	 * fvcn_get_post_form_title()
	 *
	 * @version 20120306
	 * @return string
	 */
	function fvcn_get_post_form_title()
	{
		if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
			$value = $_POST['fvcn_post_form_title'];
		} else {
			$value = '';
		}
		
		return apply_filters('fvcn_get_post_form_title', esc_attr($value));
	}


/**
 * fvcn_post_form_link_label()
 *
 * @version 20120307
 * @return void
 */
function fvcn_post_form_link_label()
{
	echo fvcn_get_post_form_link_label();
}
	
	/**
	 * fvcn_get_post_form_link_label()
	 *
	 * @version 20120524
	 * @return string
	 */
	function fvcn_get_post_form_link_label()
	{
		$label = esc_attr( fvcn_get_option('_fvcn_post_form_link_label') );
		
		return apply_filters('fvcn_get_post_form_link_label', $label);
	}

/**
 * fvcn_post_form_link()
 *
 * @version 20120307
 * @return void
 */
function fvcn_post_form_link()
{
	echo fvcn_get_post_form_link();
}
	
	/**
	 * fvcn_get_post_form_link()
	 *
	 * @version 20120307
	 * @return string
	 */
	function fvcn_get_post_form_link()
	{
		if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
			$value = $_POST['fvcn_post_form_link'];
		} else {
			$value = '';
		}
		
		return apply_filters('fvcn_get_post_form_link', esc_attr($value));
	}

/**
 * fvcn_is_post_form_link_required()
 *
 * @version 20120524
 * @return bool
 */
function fvcn_is_post_form_link_required()
{
	return apply_filters('fvcn_is_post_form_link_required', (bool) fvcn_get_option('_fvcn_post_form_link_required'));
}


/**
 * fvcn_post_form_content_label()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_content_label()
{
	echo fvcn_get_post_form_content_label();
}
	
	/**
	 * fvcn_get_post_form_content_label()
	 *
	 * @version 20120524
	 * @return string
	 */
	function fvcn_get_post_form_content_label()
	{
		$label = esc_attr( fvcn_get_option('_fvcn_post_form_content_label') );
		
		return apply_filters('fvcn_get_post_form_content_label', $label);
	}

/**
 * fvcn_post_form_content()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_content()
{
	echo fvcn_get_post_form_content();
}
	
	/**
	 * fvcn_get_post_form_content()
	 *
	 * @version 20120306
	 * @return string
	 */
	function fvcn_get_post_form_content()
	{
		if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
			$value = $_POST['fvcn_post_form_content'];
		} else {
			$value = '';
		}
		
		return apply_filters('fvcn_get_post_form_content', esc_attr($value));
	}


/**
 * fvcn_post_form_tags_label()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_tags_label()
{
	echo fvcn_get_post_form_tags_label();
}
	
	/**
	 * fvcn_get_post_form_tags_label()
	 *
	 * @version 20120524
	 * @return string
	 */
	function fvcn_get_post_form_tags_label()
	{
		$label = esc_attr( fvcn_get_option('_fvcn_post_form_tags_label') );
		
		return apply_filters('fvcn_get_post_form_tags_label', $label);
	}

/**
 * fvcn_post_form_tags()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_tags()
{
	echo fvcn_get_post_form_tags();
}
	
	/**
	 * fvcn_get_post_form_tags()
	 *
	 * @version 20120306
	 * @return string
	 */
	function fvcn_get_post_form_tags()
	{
		if ('post' == strtolower($_SERVER['REQUEST_METHOD'])) {
			$value = $_POST['fvcn_post_form_tags'];
		} else {
			$value = '';
		}
		
		return apply_filters('fvcn_get_post_form_tags', esc_attr($value));
	}

/**
 * fvcn_is_post_form_tags_required()
 *
 * @version 20120524
 * @return bool
 */
function fvcn_is_post_form_tags_required()
{
	return apply_filters('fvcn_is_post_form_tags_required', (bool) fvcn_get_option('_fvcn_post_form_tags_required'));
}


/**
 * fvcn_post_form_thumbnail_label()
 *
 * @version 20120306
 * @return void
 */
function fvcn_post_form_thumbnail_label()
{
	echo fvcn_get_post_form_thumbnail_label();
}
	
	/**
	 * fvcn_get_post_form_thumbnail_label()
	 *
	 * @version 20120524
	 * @return string
	 */
	function fvcn_get_post_form_thumbnail_label()
	{
		$label = esc_attr( fvcn_get_option('_fvcn_post_form_thumbnail_label') );
		
		return apply_filters('fvcn_get_post_form_thumbnail_label', $label);
	}

/**
 * fvcn_is_post_form_thumbnail_enabled()
 *
 * @version 20120524
 * @return bool
 */
function fvcn_is_post_form_thumbnail_enabled()
{
	return apply_filters('fvcn_is_post_form_thumbnail_enabled', (bool) fvcn_get_option('_fvcn_post_form_thumbnail_enabled'));
}

/**
 * fvcn_is_post_form_thumbnail_required()
 *
 * @version 20120524
 * @return bool
 */
function fvcn_is_post_form_thumbnail_required()
{
	return apply_filters('fvcn_is_post_form_thumbnail_required', (bool) fvcn_get_option('_fvcn_post_form_thumbnail_required'));
}


/**
 * fvcn_is_post_added()
 *
 * @version 20120531
 * @return bool
 */
function fvcn_is_post_added()
{
	if (isset($_GET['fvcn_added'])) {
		return true;
	}
	
	return false;
}
	
	/**
	 * fvcn_is_post_added_approved()
	 *
	 * @version 20120531
	 * @return bool
	 */
	function fvcn_is_post_added_approved()
	{
		if (!fvcn_is_post_added()) {
			return false;
		}
		
		return fvcn_get_public_post_status() == fvcn_get_post_status($_GET['fvcn_added']);
	}
