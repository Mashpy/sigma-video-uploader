<?php

/**
 * fvcn-admin-post-edit.php
 *
 * Admin Post Edit
 *
 * @package		Sigma Video
 * @subpackage	Admin Post Edit
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * FvCommunityNews_Admin_PostEdit
 *
 */
class FvCommunityNews_Admin_PostEdit
{
	/**
	 * @var string
	 */
	protected $_postType;
	
	/**
	 * __construct()
	 *
	 * @version 20120721
	 * @return void
	 */
	public function __construct()
	{
		$this->_postType = fvcn_get_post_type();
		
		add_action('add_meta_boxes',	array($this, 'registerMetaboxPostInfo')	);
		add_action('save_post',			array($this, 'saveMetaboxPostInfo')		);
	}
	
	/** 
	 * registerMetaboxPostInfo()
	 *
	 * @version 20120721
	 * @return void
	 */
	public function registerMetaboxPostInfo()
	{
		if (empty($_GET['action']) || 'edit' != $_GET['action'] || get_post_type() != $this->_postType) {
			return;
		}
		
		add_meta_box(
			'fvcn_post_info_metabox',
			__('Post Information', 'fvcn'),
			array($this, 'metaboxPostInfo'),
			$this->_postType,
			'side',
			'high'
		);

		do_action('fvcn_register_metabox_post_info', get_the_ID());
	}
	
	/**
	 * post_info_metabox_save()
	 *
	 * @version 20120721
	 * @param int $postId
	 * @return int
	 */
	public function saveMetaboxPostInfo($postId=0)
	{
		if (empty($postId)) {
			return $postId;
		}
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $postId;
		}
		if ('post' != strtolower($_SERVER['REQUEST_METHOD'])) {
			return $postId;
		}
		if (get_post_type($postId) != $this->_postType) {
			return $postId;
		}
		if (!current_user_can('edit_posts')) {
			return $postId;
		}
		if (!isset($_REQUEST['action']) || 'edit' != $_REQUEST['action']) {
			return $postId;
		}
		
		
		if (isset($_POST['fvcn_anonymous_author_name']))
			update_post_meta($postId, '_fvcn_anonymous_author_name', $_POST['fvcn_anonymous_author_name']);
		if (isset($_POST['fvcn_anonymous_author_email']))
			update_post_meta($postId, '_fvcn_anonymous_author_email', $_POST['fvcn_anonymous_author_email']);
		if (isset($_POST['fvcn_post_url']))
			update_post_meta($postId, '_fvcn_post_url', apply_filters('fvcn_new_post_pre_url', esc_url(strip_tags($_POST['fvcn_post_url']))));
		
		
		do_action('fvcn_save_metabox_post_info', $postId, $anonymous_data);
		
		return $postId;
	}
	
	/**
	 * metaboxPostInfo()
	 *
	 * @version 20120721
	 * @return void
	 */
	public function metaboxPostInfo()
	{
		$id = get_the_ID();
		
		if (fvcn_is_post_anonymous($id)) : ?>
			
			<p><strong><?php _e('Author Name', 'fvcn'); ?></strong></p>
			<p>
				<label for="fvcn_post_form_author_name" class="screen-reader-text"><?php _e('Name', 'fvcn'); ?></label>
				<input type="text" name="fvcn_post_form_author_name" id="fvcn_post_form_author_name" value="<?php echo get_post_meta($id, '_fvcn_anonymous_author_name', true); ?>" size="30" />
			</p>
			
			<p><strong><?php _e('Author Email', 'fvcn'); ?></strong></p>
			<p>
				<label for="fvcn_post_form_author_email" class="screen-reader-text"><?php _e('Email', 'fvcn'); ?></label>
				<input type="text" name="fvcn_post_form_author_email" id="fvcn_post_form_author_email" value="<?php echo get_post_meta($id, '_fvcn_anonymous_author_email', true); ?>" size="30" />
			</p>
			
		<?php endif; ?>
		
		<p><strong><?php _e('Author IP Address', 'fvcn'); ?></strong></p>
		<p>
			<label for="fvcn_post_author_ip" class="screen-reader-text"><?php _e('IP Address', 'fvcn'); ?></label>
			<input type="text" name="fvcn_post_author_ip" id="fvcn_post_author_ip" value="<?php echo get_post_meta($id, '_fvcn_author_ip', true); ?>" size="30" disabled />
		</p>
			
		<p><strong><?php _e('Post Link', 'fvcn'); ?></strong></p>
		<p>
			<label for="fvcn_post_url" class="screen-reader-text"><?php _e('Post URL', 'fvcn'); ?></label>
			<input type="text" name="fvcn_post_url" id="fvcn_post_url" value="<?php echo get_post_meta($id, '_fvcn_post_url', true); ?>" size="30" />
		</p>
		
		<p><strong><?php _e('Post Rating', 'fvcn'); ?></strong></p>
		<p>
			<label for="fvcn_post_rating" class="screen-reader-text"><?php _e('IP Address', 'fvcn'); ?></label>
			<input type="text" name="fvcn_post_rating" id="fvcn_post_rating" value="<?php echo get_post_meta($id, '_fvcn_post_rating', true); ?>" size="30" disabled />
		</p>
		
		<?php
		
		do_action('fvcn_metabox_post_info', $id);
	}
}

