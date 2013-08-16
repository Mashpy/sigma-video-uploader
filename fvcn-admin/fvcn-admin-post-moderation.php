<?php

/**
 * fvcn-post-moderation.php
 *
 * Post Moderation
 *
 * @package		Sigma Video
 * @subpackage	Admin Post Moderation
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


class FvCommunityNews_Admin_PostModeration
{
	/**
	 * @var string
	 */
	protected $_postType	= '';
	
	/**
	 * __construct()
	 *
	 * @version 20120414
	 * @return void
	 */
	public function __construct()
	{
		$this->_postType = FvCommunityNews_Registry::get('postType');
		
		$this->_setupActions()
			 ->_processBulkActions();
	}
	
	/**
	 * _setupActions()
	 *
	 * @version 20120308
	 * @return FvCommunityNews_Admin_PostModeration
	 */
	private function _setupActions()
	{
		add_action('fvcn_admin_enqueue_scripts', array($this, 'enqueueScripts'));
		
		add_filter('post_row_actions', array($this, 'postRowActions'), 10, 2);
		
		add_filter('manage_' . $this->_postType . '_posts_columns', array($this, 'columnHeaders'));
		add_action('manage_' . $this->_postType . '_posts_custom_column', array($this, 'columnData'), 10, 2);
		
		add_filter('restrict_manage_posts', array($this, 'filterDropdown'));
		
		add_action('all_admin_notices', array($this, 'displayNotice'));
		
		$this->_togglePost();
		
		return $this;
	}
	
	/**
	 * enqueueScripts()
	 *
	 * @version 20120730
	 * @return FvCommunityNews_Admin_PostModeration
	 */
	public function enqueueScripts()
	{
		wp_enqueue_script(
			'fvcn-admin-post-moderation-js',
			FvCommunityNews_Registry::get('adminUrl') . 'js/post-moderation.js',
			array('jquery'),
			'20120730'
		);
		
		$registry = FvCommunityNews_Registry::getInstance();
		wp_localize_script(
			'fvcn-admin-post-moderation-js',
			'FvCommunityNewsAdminPostModeration',
			array(
				'ps'	=> array(
					'all'		=> 'all',
					'public'	=> $registry->psPublic,
					'pending'	=> $registry->psPending,
					'spam'		=> $registry->psSpam,
					'trash'		=> $registry->psTrash
				),
				'locale'=> array(
					'publish'	=> __('Publish', 'fvcn'),
					'unpublish'	=> __('Unpublish', 'fvcn'),
					'spam'		=> __('Mark as spam', 'fvcn')
				)
			)
		);
	}
	
	/**
	 * addContextualHelp()
	 *
	 * @version 20120308
	 * @return FvCommunityNews_Admin_PostModeration
	 */
	public function addContextualHelp()
	{
		
		return $this;
	}
	
	/**
	 * _processBulkActions()
	 *
	 * @version 20120730
	 * @return void
	 */
	protected function _processBulkActions()
	{
		if (isset($_GET['fvcn-remove-all-spam-submit'], $_GET['_fvcn_bulk_action'])) {
			check_admin_referer('fvcn-bulk-action', '_fvcn_bulk_action');
			
			if (fvcn_has_posts(array('post_status'=>fvcn_get_spam_post_status(), 'posts_per_page'=>-1))) {
				while (fvcn_posts()) {
					fvcn_the_post();
					
					wp_delete_post( fvcn_get_post_id() );
				}
			}
			
			wp_redirect(
				add_query_arg(array('fvcn-updated' => 'bulk-remove-all-spam'),
				remove_query_arg(array('action', 'action2', 'm', 's', 'mode', '_fvcn_post_status', '_wpnonce', '_wp_http_referer', '_fvcn_bulk_action', 'fvcn-remove-all-spam-submit')))
			);
			exit;
		}
		
		if (isset($_GET['action']) && '-1' != $_GET['action']) {
			$action = $_GET['action'];
		} else if (isset($_GET['action2']) && '-1' != $_GET['action2']) {
			$action = $_GET['action2'];
		} else {
			$action = false;
		}
		
		if (isset($_GET['_fvcn_bulk_action'], $_GET['post']) && !empty($_GET['post']) && false !== $action) {
			check_admin_referer('fvcn-bulk-action', '_fvcn_bulk_action');
			
			switch ($action) {
				case 'fvcn-bulk-publish' :
					$method = 'publishPost';
				break;
				case 'fvcn-bulk-unpublish' :
					$method = 'unpublishPost';
				break;
				case 'fvcn-bulk-spam' :
					$method = 'spamPost';
				break;
				default :
					$method = false;
			}
			
			if (false !== $method) {
				$postMapper = new FvCommunityNews_PostMapper();
				
				foreach ((array)$_GET['post'] as $postId) {
					$postMapper->$method( $postId );
				}
				
				wp_redirect(
					add_query_arg(array('fvcn-updated' => str_replace('fvcn-', '', $action), 'fvcn-bulk-count' => count($_GET['post'])),
					remove_query_arg(array('action', 'action2', 'm', 's', 'mode', 'post', '_fvcn_post_status', '_wpnonce', '_wp_http_referer', '_fvcn_bulk_action')))
				);
				exit;
			}
		}
	}
	
	/**
	 * _togglePost()
	 *
	 * @version 20120729
	 * @return void
	 */
	protected function _togglePost()
	{
		if (!isset($_GET['post_id'], $_GET['action']) || false === strpos($_GET['action'], 'fvcn')) {
			return;
		}
		
		$postId = (int)$_GET['post_id'];
		
		if (!fvcn_get_post($postId)) {
			wp_die( __('The post was not found!', 'fvcn') );
		}
		if (!current_user_can('edit_posts', $postId)) {
			wp_die( __('You do not have permissions to do that!', 'fvcn') );
		}
		
		$updated = false;
		
		switch ($_GET['action']) {
			case 'fvcn_toggle_post_spam_status' :
				check_admin_referer('fvcn-spam-post_' . $postId);
				
				if (fvcn_is_post_spam($postId)) {
					fvcn_publish_post($postId);
					$updated = 'unspam';
				} else {
					fvcn_spam_post($postId);
					$updated = 'spam';
				}
			break;
			
			case 'fvcn_toggle_post_publish_status' :
				check_admin_referer('fvcn-publish-post_' . $postId);
				
				if (fvcn_is_post_published($postId)) {
					fvcn_unpublish_post($postId);
					$updated = 'unpublish';
				} else {
					fvcn_publish_post($postId);
					$updated = 'publish';
				}
			break;
		}
		
		if (false !== $updated) {
			wp_redirect( add_query_arg(array('fvcn-updated' => $updated), remove_query_arg(array('fvcn-updated', 'action', 'post_id', '_wpnonce'))) );
			exit;
		}
	}
	
	/**
	 * columnHeaders()
	 *
	 * @version 20120310
	 * @return void
	 */
	public function columnHeaders()
	{
		$columns = array(
			'cb'				=> '<input type="checkbox" />',
			'title'				=> __('Title', 'fvcn'),
			'fvcn_post_details'	=> __('Post Details', 'fvcn'),
			'fvcn_tags'			=> __('Tags', 'fvcn'),
			'comments'			=> '<span class="vers"><img alt="' . esc_attr__('Comments', 'fvcn') . '" src="' . esc_url(admin_url('images/comment-grey-bubble.png')) . '" /></span>',
			'date'				=> __('Date', 'fvcn')
		);

		return apply_filters('fvcn_admin_postmoderation_column_headers', $columns);
	}
	
	/**
	 * columnData()
	 *
	 * @version 20120805
	 * @param string $column
	 * @param int $postId
	 * @return void
	 */
	public function columnData($column, $postId)
	{
		switch ($column) {
			case 'fvcn_post_details' :
				if (fvcn_has_post_thumbnail($postId)) {
					fvcn_post_thumbnail($postId, array(46, 46), 'class=fvcn-post-thumbnail');
				}
				
				echo '<span class="fvcn-post-author-details">';
					
					echo '<strong>' . fvcn_get_post_author_link($postId) . '</strong><br />';
					echo '<a href="mailto:' . fvcn_get_post_author_email($postId) . '">' . fvcn_get_post_author_email($postId) . '</a><br />';
					if (fvcn_has_post_link($postId)) {
						echo '<a href="' . fvcn_get_post_link($postId) . '">' . fvcn_get_post_link($postId) . '</a>';
					}
					
				echo '</span>';
			break;
			
			case 'fvcn_tags' :
				
				fvcn_post_tag_list($postId, 'before=&after=');
				
			break;
			
			default :
				do_action('fvcn_admin_postmoderation_column_data', $column, $postId);
			break;
		}
	}
	
	/**
	 * postRowActions()
	 *
	 * @version 20120730
	 * @param array $actions
	 * @param object $post
	 * @return array
	 */
	public function postRowActions($actions, $post)
	{
		if ($post->post_type == $this->_postType) {
			unset( $actions['inline hide-if-no-js'] );
			
			if (isset($actions['trash'])) {
				$trash = $actions['trash'];
				unset($actions['trash']);
			} else {
				$trash = $actions['delete'];
				unset($actions['delete']);
			}
			
			$spamUri = esc_url( wp_nonce_url( add_query_arg(array(
				'post_id'	=> $post->ID,
				'action'	=> 'fvcn_toggle_post_spam_status'
			),
			remove_query_arg(array(
				'fvcn-updated',
				'post_id',
				'failed',
				'super'
			))), 'fvcn-spam-post_' . $post->ID));
			if (fvcn_is_post_spam()) {
				$actions['spam'] = '<a href="' . $spamUri . '">' . __('Not Spam', 'fvcn') . '</a>';
			} else {
				$publishUri = esc_url( wp_nonce_url( add_query_arg(array(
					'post_id'	=> $post->ID,
					'action'	=> 'fvcn_toggle_post_publish_status'
				),
				remove_query_arg(array(
					'fvcn-updated',
					'post_id',
					'failed',
					'super'
				))), 'fvcn-publish-post_' . $post->ID));
				if (fvcn_is_post_published()) {
					$actions['publish'] = '<a href="' . $publishUri . '">' . __('Unpublish', 'fvcn') . '</a>';
				} else {
					$actions['publish'] = '<a href="' . $publishUri . '">' . __('Publish', 'fvcn') . '</a>';
				}
				
				$actions['spam'] = '<a href="' . $spamUri . '">' . __('Spam', 'fvcn') . '</a>';
			}
			
			
			$actions['trash'] = $trash;
		}
		
		return apply_filters('fvcn_admin_postmoderation_row_actions', $actions);
	}
	
	/**
	 * filterDropdown()
	 *
	 * @version 20120730
	 * @return void
	 */
	public function filterDropdown()
	{
		if (!isset($_GET['post_type']) || $_GET['post_type'] != $this->_postType) {
			return;
		}
		
		?>
		<input type="hidden" name="_fvcn_post_status" id="_fvcn_post_status" value="<?php echo isset($_GET['post_status']) ? esc_attr($_GET['post_status']) : 'all'; ?>" />
		<?php
		wp_nonce_field('fvcn-bulk-action', '_fvcn_bulk_action');
		
		if (isset($_GET['post_status']) && fvcn_get_spam_post_status() == $_GET['post_status']) {
			submit_button(__('Remove All Spam', 'fvcn'), 'button-secondary apply', 'fvcn-remove-all-spam-submit', false);
		}
	}
	
	/**
	 * displayNotice()
	 *
	 * @version 20120730
	 * @return void
	 */
	public function displayNotice()
	{
		if (!isset($_GET['fvcn-updated']) || isset($_GET['trashed'])) {
			return;
		}
		
		if (isset($_GET['fvcn-bulk-count'])) {
			$count = absint($_GET['fvcn-bulk-count']);
		} else {
			$count = 0;
		}
		
		switch ($_GET['fvcn-updated']) {
			case 'publish' :
				$message = __('One post published.', 'fvcn');
			break;
			case 'unpublish' :
				$message = __('One post unpublished.', 'fvcn');
			break;
			case 'spam' :
				$message = __('One post marked as spam.', 'fvcn');
			break;
			case 'unspam' :
				$message = __('One post cleared from spam.', 'fvcn');
			break;
			case 'bulk-remove-all-spam' :
				$message = sprintf(__('Removed all spam.', 'fvcn'));
			break;
			case 'bulk-publish' :
				$message = sprintf(_n('One post published.', '%s posts published.', $count, 'fvcn'), number_format_i18n($count));
			break;
			case 'bulk-unpublish' :
				$message = sprintf(_n('One post unpublished.', '%s posts unpublished.', $count, 'fvcn'), number_format_i18n($count));
			break;
			case 'bulk-spam' :
				$message = sprintf(_n('One post marked as spam.', '%s posts marked as spam.', $count, 'fvcn'), number_format_i18n($count));
			break;
			default :
				$message = false;
		}
		
		if ($message) :
		?>
		<div id="message" class="updated"><p>
			
			<?php echo $message; ?>
			
		</p></div>
		<?php
		endif;
	}
}

