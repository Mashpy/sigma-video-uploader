<?php

/**
 * fvcn-post-functions.php
 *
 * Post Functions
 *
 * @package		Sigma Video
 * @subpackage	Functions
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * fvcn_insert_post()
 *
 * @version 20120322
 * 
 * @param array $post_data
 * @param array $post_meta
 * @return int
 */
function fvcn_insert_post(array $post_data, array $post_meta)
{
	$default_post = array(
		'post_author'	=> 0,
		'post_title'	=> '',
		'post_content'	=> '',
		'post_status'	=> fvcn_get_pending_post_status(),
		'post_type'		=> fvcn_get_post_type(),
		'post_password'	=> '',
		'tax_input'		=> ''
	);
	$post_data = wp_parse_args($post_data, $default_post);
	
	$postId = wp_insert_post($post_data);
	
	// Anonymous tags fix
	if (!empty($post_data['tax_input']) && is_array($post_data['tax_input']) && !empty($post_data['tax_input'][ fvcn_get_post_tag_id() ])) {
		wp_set_post_terms($postId, $post_data['tax_input'][ fvcn_get_post_tag_id() ], fvcn_get_post_tag_id());
	}
	
	
	$default_meta = array(
		'_fvcn_anonymous_author_name'	=> '',
		'_fvcn_anonymous_author_email'	=> '',
		'_fvcn_post_url'				=> '',
		'_fvcn_post_rating'				=> 0,
		'_fvcn_author_ip'				=> fvcn_get_current_author_ip(),
		'_fvcn_author_au'				=> fvcn_get_current_author_ua()
	);
	
	$post_meta = wp_parse_args($post_meta, $default_meta);
	
	foreach ($post_meta as $meta_key=>$meta_value) {
		update_post_meta($postId, $meta_key, $meta_value);
	}
	
	do_action('fvcn_insert_post', $postId, $post_data, $post_meta);
	
	return $postId;
}


/**
 * fvcn_insert_post_thumbnail()
 *
 * @version 20120307
 * @param int $postId
 * @return int
 */
function fvcn_insert_post_thumbnail($postId)
{
	require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
	
    $attach_id = media_handle_upload('fvcn_post_form_thumbnail', $postId);
	
    update_post_meta($postId, '_thumbnail_id', $attach_id);
	
    return $attach_id;
}


/**
 * fvcn_new_post_handler()
 *
 * @version 20120808
 * @return void
 */
function fvcn_new_post_handler()
{
	if ('post' != strtolower($_SERVER['REQUEST_METHOD'])) {
		return;
	}
	if (!isset($_POST['fvcn_post_form_action']) || 'fvcn-new-post' != $_POST['fvcn_post_form_action']) {
		return;
	}
	if (fvcn_is_anonymous() && !fvcn_is_anonymous_allowed()) {
		return;
	}
	if (!wp_verify_nonce($_POST['fvcn_post_form_nonce'], 'fvcn-new-post')) {
		return;
	}
	
	
	$postData	= array(
		'author'			=> 0,
		'post_author_name'	=> '',
		'post_author_email'	=> '',
		'post_link'			=> '',
		'post_tags'			=> '',
		'post_status'		=> fvcn_get_pending_post_status()
	);
	$validator	= new FvCommunityNews_Validate();
	
	// Timeout
	apply_filters('fvcn_post_form_time_key', $validator->setValidators(array(
		'FvCommunityNews_Validate_NotEmpty',
		'FvCommunityNews_Validate_Timeout'
	)));
	
	if (!$validator->isValid($_POST['fvcn_post_form_time_key'])) {
		fvcn_add_error('fvcn_post_form', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
	}
	
	if (!fvcn_is_anonymous()) {
		// Author Name
		apply_filters('fvcn_post_author_name_validators', $validator->setValidators(array(
			'FvCommunityNews_Validate_NotEmpty',
			'FvCommunityNews_Validate_Name',
			new FvCommunityNews_Validate_MinLength(2),
			new FvCommunityNews_Validate_MaxLength(40)
		)));
		
		if ($validator->isValid($_POST['fvcn_post_form_author_name'])) {
			$postData['post_author_name'] = $_POST['fvcn_post_form_author_name'];
		} else {
			fvcn_add_error('fvcn_post_form_author_name', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
		}
		
		// Author Email
		apply_filters('fvcn_post_author_email_validators', $validator->setValidators(array(
			'FvCommunityNews_Validate_NotEmpty',
			'FvCommunityNews_Validate_Email',
			new FvCommunityNews_Validate_MinLength(10),
			new FvCommunityNews_Validate_MaxLength(60)
		)));
		
		if ($validator->isValid($_POST['fvcn_post_form_author_email'])) {
			$postData['post_author_email'] = $_POST['fvcn_post_form_author_email'];
		} else {
			fvcn_add_error('fvcn_post_form_author_email', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
		}
	} else {
		$postData['author'] = fvcn_get_current_user_id();
	}
	
	// Status
	if (!fvcn_admin_moderation()) {
		if (fvcn_user_moderation()) {
			if (fvcn_has_user_posts()) {
				$postData['post_status'] = fvcn_get_public_post_status();
			}
		} else {
			$postData['post_status'] = fvcn_get_public_post_status();
		}
	}
	
	// Title
	apply_filters('fvcn_post_title_validators', $validator->setValidators(array(
		'FvCommunityNews_Validate_NotEmpty',
		new FvCommunityNews_Validate_MinLength(8),
		new FvCommunityNews_Validate_MaxLength(70)
	)));

$retval = mysql_query( "SELECT * FROM wp_posts ORDER BY id DESC LIMIT 1");
while($row = mysql_fetch_array($retval))
  {

  $test_try= $row['ID'];
    $test_try=   $test_try+1;


  }	
if ($validator->isValid($_POST['fvcn_post_form_title'])) {


	
		$postData['post_title'] = 'post_no_'.$test_try.' Title- '.$_POST['fvcn_post_form_title'];
	} else {
		fvcn_add_error('fvcn_post_form_title', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
	}
	
	// Link
	if (fvcn_is_post_form_link_required()) {
		apply_filters('fvcn_post_link_validators', $validator->setValidators(array(
			'FvCommunityNews_Validate_NotEmpty',
			'FvCommunityNews_Validate_Url',
			new FvCommunityNews_Validate_MinLength(6)
		)));
		
		if (false === strpos($_POST['fvcn_post_form_link'], 'http://')) {
			$_POST['fvcn_post_form_link'] = 'http://' . $_POST['fvcn_post_form_link'];
		}
		if ($validator->isValid($_POST['fvcn_post_form_link'])) {
			$postData['post_link'] = $_POST['fvcn_post_form_link'];
		} else {
			fvcn_add_error('fvcn_post_form_link', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
		}
	} else {
		$validator->setValidators(array('FvCommunityNews_Validate_NotEmpty'));
		
		if ($validator->isValid($_POST['fvcn_post_form_link'])) {
			apply_filters('fvcn_post_link_validators', $validator->setValidators(array(
				'FvCommunityNews_Validate_Url',
				new FvCommunityNews_Validate_MinLength(6)
			)));
			
			if (false === strpos($_POST['fvcn_post_form_link'], 'http://')) {
				$_POST['fvcn_post_form_link'] = 'http://' . $_POST['fvcn_post_form_link'];
			}
			if ($validator->isValid($_POST['fvcn_post_form_link'])) {
				$postData['post_link'] = $_POST['fvcn_post_form_link'];
			} else {
				fvcn_add_error('fvcn_post_form_link', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
			}
		}
	}
	
	// Content
/*	apply_filters('fvcn_post_content_validators', $validator->setValidators(array(
		'FvCommunityNews_Validate_NotEmpty',
		new FvCommunityNews_Validate_MinLength(80)
	)));
	
	if ($validator->isValid($_POST['fvcn_post_form_content'])) {
		$postData['post_content'] = $_POST['fvcn_post_form_content'];
	} else {
		fvcn_add_error('fvcn_post_form_content', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
	}
*/	
	// Tags
	if (fvcn_is_post_form_tags_required()) {
		apply_filters('fvcn_post_tags_validators', $validator->setValidators(array(
			'FvCommunityNews_Validate_NotEmpty',
			'FvCommunityNews_Validate_Tags',
			new FvCommunityNews_Validate_MinLength(2)
		)));
		
		if ($validator->isValid($_POST['fvcn_post_form_tags'])) {
			if (false !== strpos($_POST['fvcn_post_form_tags'], ',')) {
				$_POST['fvcn_post_form_tags'] = explode(',', $_POST['fvcn_post_form_tags']);
			}
			$postData['post_tags'] = array(fvcn_get_post_tag_id() => $_POST['fvcn_post_form_tags']);
		} else {
			fvcn_add_error('fvcn_post_form_tags', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
		}
	} else {
		$validator->setValidators(array('FvCommunityNews_Validate_NotEmpty'));
		
		if ($validator->isValid($_POST['fvcn_post_form_link'])) {
			apply_filters('fvcn_post_tags_validators', $validator->setValidators(array(
				'FvCommunityNews_Validate_Tags',
				new FvCommunityNews_Validate_MinLength(2)
			)));
			
			if ($validator->isValid($_POST['fvcn_post_form_tags'])) {
				if (false !== strpos($_POST['fvcn_post_form_tags'], ',')) {
					$_POST['fvcn_post_form_tags'] = explode(',', $_POST['fvcn_post_form_tags']);
				}
				$postData['post_tags'] = array(fvcn_get_post_tag_id() => $_POST['fvcn_post_form_tags']);
			} else {
				fvcn_add_error('fvcn_post_form_tags', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
			}
		}
	}
	
	// Thumbnail
	if (fvcn_is_post_form_thumbnail_required()) {
		apply_filters('fvcn_post_title_validators', $validator->setValidators(array(
			'FvCommunityNews_Validate_ImageUpload'
		)));
		
		if ($validator->isValid($_FILES['fvcn_post_form_thumbnail'])) {
			add_action('fvcn_insert_post', 'fvcn_insert_post_thumbnail');
		} else {
			fvcn_add_error('fvcn_post_form_thumbnail', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
		}
	} else if (!empty($_FILES['fvcn_post_form_thumbnail']['tmp_name'])) {
		apply_filters('fvcn_post_title_validators', $validator->setValidators(array(
			'FvCommunityNews_Validate_ImageUpload'
		)));
		
		if ($validator->isValid($_FILES['fvcn_post_form_thumbnail'])) {
			add_action('fvcn_insert_post', 'fvcn_insert_post_thumbnail');
		} else {
			fvcn_add_error('fvcn_post_form_thumbnail', sprintf(__('<strong>ERROR</strong>: %s', 'fvcn'), $validator->getMessage()));
		}
	}
	
	
	do_action('fvcn_new_post_pre_extras');
	
	
	if (!fvcn_has_errors()) {
		$post_data = apply_filters('fvcn_new_post_data_pre_insert', array(
			'post_author'	=> $postData['author'],
			'post_title'	=> $postData['post_title'],
			'post_content'	=> $postData['post_content'],
			'tax_input'		=> $postData['post_tags'],
			'post_status'	=> $postData['post_status'],
			'post_type'		=> fvcn_get_post_type()
		));
		$post_meta = apply_filters('fvcn_new_post_meta_pre_insert', array(
			'_fvcn_anonymous_author_name'	=> $postData['post_author_name'],
			'_fvcn_anonymous_author_email'	=> $postData['post_author_email'],
			'_fvcn_post_url'				=> $postData['post_link']
		));
		
		do_action('fvcn_new_post_pre_insert', $post_data, $post_meta);
		
		$postId = fvcn_insert_post($post_data, $post_meta);
		
		if ('template_redirect' == current_filter()) {
			if (fvcn_get_public_post_status() == fvcn_get_post_status($postId)) {
				wp_redirect( add_query_arg(array('fvcn_added'=>$postId), fvcn_get_post_permalink($postId)) );
			} else {
				wp_redirect( add_query_arg(array('fvcn_added'=>$postId), home_url('/')) );
			}
		} else {
			return $postId;
		}
	}
}


/**
 * fvcn_filter_new_post_data()
 *
 * @version 20120710
 * @param array $data
 * @return array
 */
function fvcn_filter_new_post_data(array $data)
{
	foreach ($data as $key=>$value) {
		$filter = str_replace('_fvcn_', '', $key);
		$data[ $key ] = apply_filters('fvcn_new_post_pre_' . $filter, $value);
	}
	
	return $data;
}


/**
 * fvcn_increase_post_view_count()
 *
 * @version 20120722
 * @param string $template
 * @return string
 */
function fvcn_increase_post_view_count($template)
{
	if (!fvcn_is_single_post()) {
		return $template;
	}
	
	$id = (int) fvcn_get_post_id();
	if (isset($_COOKIE['fvcn_post_viewed_' . $id . '_' . COOKIEHASH])) {
		return $template;
	}
	
	$postMapper = new FvCommunityNews_PostMapper();
	$postMapper->increasePostViewCount($id);
	
	setcookie('fvcn_post_viewed_' . $id . '_' . COOKIEHASH, 'true', 0, COOKIEPATH, COOKIE_DOMAIN);
	
	return $template;
}


/**
 * fvcn_publish_post()
 *
 * @version 20120722
 * @param int $postId
 * @return int
 */
function fvcn_publish_post($postId)
{
	$postMapper = new FvCommunityNews_PostMapper();
	return $postMapper->publishPost($postId);
}


/**
 * fvcn_unpublish_post()
 *
 * @version 20120722
 * @param int $postId
 * @return int
 */
function fvcn_unpublish_post($postId)
{
	$postMapper = new FvCommunityNews_PostMapper();
	return $postMapper->unpublishPost($postId);
}


/**
 * fvcn_spam_post()
 *
 * @version 20120722
 * @param int $postId
 * @return int
 */
function fvcn_spam_post($postId)
{
	$postMapper = new FvCommunityNews_PostMapper();
	return $postMapper->spamPost($postId);
}


/**
 * fvcn_post_rating_handler()
 *
 * @version 20120722
 * @return void
 */
function fvcn_post_rating_handler()
{
	$actions = array(
		'increase',
		'decrease'
	);
	
	if (!isset($_REQUEST['fvcn_post_rating_action'], $_REQUEST['post_id']) || !in_array($_REQUEST['fvcn_post_rating_action'], $actions)) {
		return;
	}
	if (0 === ($id = fvcn_get_post_id($_REQUEST['post_id']))) {
		return;
	}
	if (fvcn_is_post_rated_by_current_user($id)) {
		return;
	}
	
	check_admin_referer('fvcn-post-rating');
	
	$postMapper = new FvCommunityNews_PostMapper();
	if ('increase' == $_REQUEST['fvcn_post_rating_action']) {
		$postMapper->increasePostRating($id);
	} else {
		$postMapper->decreasePostRating($id);
	}
	
	setcookie('fvcn_post_rated_' . $id . '_' . COOKIEHASH, 'true', time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
	
	wp_redirect( fvcn_get_post_permalink($id) );
}


/**
 * FvCommunityNews_PostMapper
 *
 */
class FvCommunityNews_PostMapper
{
	/**
	 * _changePostStatus()
	 *
	 * @version 20120722
	 * @param int $postId
	 * @param string $status
	 * @return int
	 */
	protected function _changePostStatus($postId, $status)
	{
		$post = array();
		$post['ID'] = $postId;
		$post['post_status'] = $status;
		
		return wp_update_post($post);
	}
	
	/**
	 * publishPost()
	 *
	 * @version 20120722
	 * @param int $postId
	 * @return int
	 */
	public function publishPost($postId)
	{
		do_action('fvcn_publish_post', $postId);
		
		return $this->_changePostStatus( $postId, fvcn_get_public_post_status() );
	}
	
	/**
	 * unpublishPost()
	 *
	 * @version 20120722
	 * @param int $postId
	 * @return int
	 */
	public function unpublishPost($postId)
	{
		do_action('fvcn_unpublish_post', $postId);
		
		return $this->_changePostStatus( $postId, fvcn_get_pending_post_status() );
	}
	
	/**
	 * spamPost()
	 *
	 * @version 20120728
	 * @param int $postId
	 * @return int
	 */
	public function spamPost($postId)
	{
		do_action('fvcn_spam_post', $postId);
		
		return $this->_changePostStatus( $postId, fvcn_get_spam_post_status() );
	}
	
	/**
	 * increasePostRating()
	 *
	 * @version 20120722
	 * @param int $postId
	 * @return void
	 */
	public function increasePostRating($postId)
	{
		do_action('fvcn_increase_post_rating', $postId);
		
		update_post_meta($postId, '_fvcn_post_rating', fvcn_get_post_rating($postId)+1);
	}
	
	/**
	 * decreasePostRating()
	 *
	 * @version 20120722
	 * @param int $postId
	 * @return void
	 */
	public function decreasePostRating($postId)
	{
		do_action('fvcn_decrease_post_rating', $postId);
		
		update_post_meta($postId, '_fvcn_post_rating', fvcn_get_post_rating($postId)-1);
	}
	
	/**
	 * increasePostViewCount()
	 *
	 * @version 20120724
	 * @param int $postId
	 * @return void
	 */
	public function increasePostViewCount($postId)
	{
		do_action('fvcn_increase_post_view_count', $postId);
		
		update_post_meta($postId, '_fvcn_post_views', fvcn_get_post_views($postId)+1);
	}
}

