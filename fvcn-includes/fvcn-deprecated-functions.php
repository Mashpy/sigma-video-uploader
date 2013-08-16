<?php

/**
 * fvcn-deprecated-functions.php
 *
 * Deprecated Functions
 *	The functions defined below will still (partially)
 *	work, but you should never use them! They are not
 *	supported anymore and might be removed in the next
 *	release.
 *
 * @package		Sigma Video
 * @subpackage	Deprecated Functions
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/*************************************************
 *		1.x
 */

/**
 * fvCommunityNewsForm()
 *
 * @version 20120322
 * @return void
 */
function fvCommunityNewsForm()
{
	trigger_error('The function "fvCommunityNewsForm()" is deprecated!', E_USER_WARNING);
	
	fvcn_get_template_part('fvcn/form', 'post');
}

/**
 * fvCommunityNewsGetSubmissions()
 *
 * @version 20120322
 * @param int $num
 * @param string $format
 * @return string
 */
function fvCommunityNewsGetSubmissions($num=5, $format='<li><strong><a href="%submission_url%" title="%submission_title%">%submission_title%</a></strong><small>%submission_date%</small><br />%submission_description%</li>')
{
	trigger_error('The function "fvCommunityNewsGetSubmissions()" is deprecated!', E_USER_WARNING);
	
	if (fvcn_has_posts(array('posts_per_page'=>$num))) {
		$posts = '<ul>';
		
		while (fvcn_posts()) {
			fvcn_the_post();
			
			$post = $format;
			$post = str_replace('%submission_author%',			fvcn_get_post_author(),			$post);
			$post = str_replace('%submission_author_email%',	fvcn_get_post_author_email(),	$post);
			$post = str_replace('%submission_title%',			fvcn_get_post_title(),			$post);
			$post = str_replace('%submission_url%',				fvcn_get_post_link(),			$post);
			$post = str_replace('%submission_description%',		fvcn_get_post_content(),		$post);
			$post = str_replace('%submission_date%',			fvcn_get_post_date(),			$post);
			
			if (fvcn_has_post_thumbnail()) {
				$post = str_replace('%submission_image%',		fvcn_get_post_thumbnail(0, array(45, 45)),	$post);
			} else {
				$post = str_replace('%submission_image%',		'',	$post);
			}
			
			$posts .= $post;
		}
		
		$posts .= '</ul>';
		
		return $posts;
	} else {
		
		return '<p>' . __('No Sigma Video found.', 'fvcn') . '</p>';
		
	}
}



/*************************************************
 *		2.x
 */

/**
 * fvcn_list_posts()
 *
 * @version 20120322
 * @return void
 */
function fvcn_list_posts()
{
	trigger_error('The function "fvcn_list_posts()" is deprecated!', E_USER_NOTICE);
	
	fvcn_get_template_part('fvcn/loop', 'posts');
}

/**
 * fvcn_post_archives()
 *
 * @version 20120322
 * @return void
 */
function fvcn_post_archives()
{
	trigger_error('The function "fvcn_post_archives()" is deprecated!', E_USER_NOTICE);
	
	fvcn_get_template_part('fvcn/loop', 'posts');
}

/**
 * fvcn_form()
 *
 * @version 20120322
 * @return void
 */
function fvcn_form()
{
	trigger_error('The function "fvcn_form()" is deprecated!', E_USER_NOTICE);
	
	fvcn_get_template_part('fvcn/form', 'post');
}

