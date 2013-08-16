<?php

/**
 * fvcn-admin-dashboard.php
 *
 * Admin Dashboard
 *
 * @package		Sigma Video
 * @subpackage	Admin Dashboard
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * FvCommunityNews_Admin_Dashboard
 *
 */
class FvCommunityNews_Admin_Dashboard
{
	/**
	 * __construct()
	 *
	 * @version 20120729
	 * @return void
	 */
	public function __construct()
	{
		$this->registerWidgets();
	}
	
	/**
	 * registerWidgets()
	 *
	 * @version 20120719
	 * @return FvCommunityNews_Admin_Dashboard
	 */
	public function registerWidgets()
	{
		add_action('wp_dashboard_setup', array(new FvCommunityNews_Admin_Dashboard_Widget_RecentPosts(), 'register'));
		
		do_action('fvcn_register_dashboard_widgets');
		
		return $this;
	}
}


/**
 * FvCommunityNews_Admin_Dashboard_Widget_RecentPosts_Interface
 *
 */
interface FvCommunityNews_Admin_Dashboard_Widget_RecentPosts_Interface
{
	/**
	 * register()
	 *
	 * @return FvCommunityNews_Admin_Dashboard_Widget_RecentPosts_Interface
	 */
	public function register();
	
	/**
	 * widget()
	 *
	 * @return void
	 */
	public function widget();
}


/**
 * FvCommunityNews_Admin_Dashboard_Widget_RecentPosts
 *
 */
class FvCommunityNews_Admin_Dashboard_Widget_RecentPosts implements FvCommunityNews_Admin_Dashboard_Widget_RecentPosts_Interface
{
	/**
	 * __construct()
	 *
	 * @version 20120729
	 * @return void
	 */
	public function __construct()
	{
		add_action('fvcn_admin_enqueue_scripts',			array($this, 'enqueueScripts'));
		add_action('fvcn_admin_head',						array($this, 'dashboardHead'));
		
		add_action('wp_ajax_fvcn-dashboard-widget-rp-ajax',	array($this, 'ajaxResponse'));
		
		$this->response();
	}
	
	/**
	 * enqueueScripts()
	 *
	 * @version 20120729
	 * @return void
	 */
	public function enqueueScripts()
	{
		wp_enqueue_script(
			'fvcn-dashboard-widget-rp-js',
			FvCommunityNews_Registry::get('adminUrl') . 'js/dashboard.js',
			array('jquery'),
			'20120721'
		);
		
		wp_localize_script(
			'fvcn-dashboard-widget-rp-js',
			'FvCommunityNewsAdminDashboardOptions',
			array(
				'ajaxurl'	=> esc_url( admin_url('admin-ajax.php') ),
				'action'	=> 'fvcn-dashboard-widget-rp-ajax',
				'nonce'		=> wp_create_nonce('fvcn-dashboard')
			)
		);
		
		do_action('fvcn_enqueue_dashboard_widget_recent_posts_scripts');
	}
	
	/**
	 * dashboardHead()
	 *
	 * @version 20120729
	 * @return void
	 */
	public function dashboardHead()
	{
		?>
		<style type="text/css">
			#fvcn-dashboard-recent-posts .inside {
				margin: 0 auto;
				padding: 0;
			}
			#fvcn-dashboard-recent-posts .inside .dashboard-widget-control-form {
				padding: 0 10px 10px;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post {
				clear: both;
				padding: 10px;
				border-top: 1px solid #dfdfdf;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post:first-child {
				border-top: none;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post.pending {
				background-color: #ffffe0;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post .avatar,
			#fvcn-dashboard-recent-posts-list .fvcn-post .wp-post-image {
				float: left;
				margin: 0 10px 10px 0;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post .fvcn-dashboard-post-wrap blockquote {
				margin: 0 auto;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post .fvcn-row-actions {
				visibility: hidden;
				margin: 3px 0 0;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post:hover .fvcn-row-actions {
				visibility: visible;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post .fvcn-row-actions .publish a {
				color: #006505;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post .fvcn-row-actions .unpublish a {
				color: #d98500;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post .fvcn-row-actions .spam a {
				color: #bc0b0b;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post .fvcn-row-actions .trash a {
				color: #bc0b0b;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post .fvcn-row-actions .trash a:hover,
			#fvcn-dashboard-recent-posts-list .fvcn-post .fvcn-row-actions .spam a:hover,
			#fvcn-dashboard-recent-posts-list .fvcn-post .fvcn-row-actions .unpublish a:hover,
			#fvcn-dashboard-recent-posts-list .fvcn-post .fvcn-row-actions .publish a:hover {
				color: #d54e21;
			}
			#fvcn-dashboard-recent-posts-list .fvcn-post.approved .fvcn-row-actions .publish,
			#fvcn-dashboard-recent-posts-list .fvcn-post.pending .fvcn-row-actions .unpublish {
				display: none;
			}
			#fvcn-dashboard-view-links {
				margin: 10px 10px;
			}
		</style>
		<?php
		
		do_action('fvcn_dashboard_widget_recent_posts_styles');
	}
	
	/**
	 * response()
	 *
	 * @version 20120729
	 * @return void
	 */
	public function response()
	{
		if (!isset($_GET['action'], $_GET['post_id']) || false === strpos($_GET['action'], 'fvcn')) {
			return;
		}
		
		$this->_updatePostStatus($_GET['post_id'], $_GET['action']);
	}
	
	/**
	 * ajaxResponse()
	 *
	 * @version 20120729
	 * @return void
	 */
	public function ajaxResponse()
	{
		$success = false;
		$message = false;
		
		if (!isset($_POST['nonce'], $_POST['post_id'], $_POST['fvcn_action'])
			|| !wp_verify_nonce($_POST['nonce'], 'fvcn-dashboard')
			|| !is_numeric($_POST['post_id'])
			|| !fvcn_get_post($_POST['post_id'])
			|| !current_user_can('edit_posts', $_POST['post_id']))
		{	
			$message = __('Are you sure?', 'fvcn');
		}
		
		if (false === $message) {
			$success = $this->_updatePostStatus($_POST['post_id'], $_POST['fvcn_action']);
			
			if (false === $success) {
				$message = __('Invallid action', 'fvcn');
			}
		}
		
		if (false === $success) {
			$response = array(
				'success'	=> false,
				'message'	=> $message
			);
		} else {
			$response = array(
				'success'	=> true
			);
		}
		
		echo json_encode( $response );
		
		exit;
	}
	
	/**
	 * _updatePostStatus()
	 *
	 * @version 20120729
	 * @param string $action
	 * @return bool
	 */
	protected function _updatePostStatus($postId, $action)
	{
		switch ($action) {
			case 'fvcn_toggle_post_spam_status' :
				check_admin_referer('fvcn-spam-post_' . $postId);
				return fvcn_is_post_spam($postId) ? fvcn_publish_post($postId) : fvcn_spam_post($postId);
			break;
			
			case 'fvcn_toggle_post_publish_status' :
				check_admin_referer('fvcn-publish-post_' . $postId);
				return fvcn_is_post_published($postId) ? fvcn_unpublish_post($postId) : fvcn_publish_post($postId);
			break;
			
			default:
				return false;
		}
	}
	
	/**
	 * register()
	 *
	 * @version 20120719
	 * @return FvCommunityNews_Admin_Dashboard_Widget_RecentPosts
	 */
	public function register()
	{
		if (current_user_can('edit_posts')) {
			wp_add_dashboard_widget(
				'fvcn-dashboard-recent-posts',
				__('Recent Sigma Video', 'fvcn'),
				array($this, 'widget'),
				array($this, 'control')
			);
		}
		
		return $this;
	}
	
	/**
	 * widget()
	 *
	 * @version 20120721
	 * @return void
	 */
	public function widget()
	{
		$options = array(
			'posts_per_page'	=> fvcn_get_option('_fvcn_dashboard_rp_num'),
			'post_status'		=> fvcn_get_public_post_status() . ',' . fvcn_get_pending_post_status()
		);
		
		if (fvcn_has_posts($options)) :
			$alt = 'odd alt'; ?>
			
			<div id="fvcn-dashboard-recent-posts-list">
				<?php while (fvcn_posts()) : fvcn_the_post(); ?>
					
					<?php
					$class = 'fvcn-post ';
					
					if (fvcn_get_pending_post_status() == fvcn_get_post_status()) {
						$class .= 'pending ';
					} else {
						$class .= 'approved ';
					}
					
					$class .= $alt;
					?>
					
					<div id="fvcn-post-<?php fvcn_post_id(); ?>" class="<?php echo $class; ?>">
						<?php
						if (fvcn_has_post_thumbnail()) {
							fvcn_post_thumbnail(0, array(50, 50));
						} else {
							fvcn_post_author_avatar(0, 50);
						}
						?>
						
						<div class="fvcn-dashboard-post-wrap">
							<h4 class="fvcn-post-title">
								<?php if (fvcn_has_post_link()) : ?>
									<a href="<?php fvcn_post_link(); ?>"><?php fvcn_post_title(); ?></a>
								<?php else : ?>
									<?php fvcn_post_title(); ?>
								<?php endif; ?>
								<a href="<?php fvcn_post_permalink(); ?>">#</a>
							</h4>
							
							<blockquote><?php fvcn_post_excerpt(); ?></blockquote>
							
							<p class="fvcn-row-actions">
								<?php
								$publish_uri = esc_url( wp_nonce_url( add_query_arg(array(
									'post_id'	=> fvcn_get_post_id(),
									'action'	=> 'fvcn_toggle_post_publish_status'
								), 'index.php'), 'fvcn-publish-post_' . fvcn_get_post_id()));
								$edit_uri = esc_url( add_query_arg(array(
									'post'		=> fvcn_get_post_id(),
									'action'	=> 'edit'
								), 'post.php'));
								$spam_uri = esc_url( wp_nonce_url( add_query_arg(array(
									'post_id'	=> fvcn_get_post_id(),
									'action'	=> 'fvcn_toggle_post_spam_status'
								), 'index.php'), 'fvcn-spam-post_' . fvcn_get_post_id()));
								$trash_uri = esc_url( wp_nonce_url( add_query_arg(array(
									'post'		=> fvcn_get_post_id(),
									'action'	=> 'trash'
								), 'post.php'), 'trash-' . fvcn_get_post_type() . '_' . fvcn_get_post_id()));
								?>
								<span class="publish"><a href="<?php echo $publish_uri; ?>"><?php _e('Publish', 'fvcn'); ?></a></span>
								<span class="unpublish"><a href="<?php echo $publish_uri; ?>"><?php _e('Unpublish', 'fvcn'); ?></a></span>
								<span class="edit"> | <a href="<?php echo $edit_uri; ?>"><?php _e('Edit', 'fvcn'); ?></a></span>
								<span class="spam"> | <a href="<?php echo $spam_uri; ?>"><?php _e('Spam', 'fvcn'); ?></a></span>
								<span class="trash"> | <a href="<?php echo $trash_uri; ?>"><?php _e('Trash', 'fvcn'); ?></a></span>
							</p>
						</div>
					</div>
					
				<?php
				$alt = ($alt == 'even' ? 'odd alt' : 'even');
				endwhile; ?>
			</div>
			
			<p id="fvcn-dashboard-view-links">
				<a href="edit.php?post_type=<?php fvcn_post_type(); ?>"><?php _e('View All', 'fvcn'); ?></a>
			</p>
		<?php else : ?>
			
			<p><?php _e('No posts found, yet.', 'fvcn'); ?></p>
			
		<?php endif;
	}
	
	/**
	 * control()
	 *
	 * @version 20120729
	 * @return void
	 */
	public function control()
	{
		if ('post' == strtolower($_SERVER['REQUEST_METHOD']) && isset($_POST['_fvcn_dashboard_rp'])) {
			update_option('_fvcn_dashboard_rp_num', min( max((int) $_POST['_fvcn_dashboard_rp_num'], 1), 30));
		}
		
		?>
		<p>
			<label for="_fvcn_dashboard_rp_num"><?php _e('Number of posts to show:', 'fvcn'); ?></label>
			<input type="text" name="_fvcn_dashboard_rp_num" id="_fvcn_dashboard_rp_num" value="<?php echo fvcn_form_option('_fvcn_dashboard_rp_num'); ?>" size="3" />
			<small><?php _e('(1 - 30)', 'fvcn'); ?></small>
		</p>
		
		<input type="hidden" name="_fvcn_dashboard_rp" id="_fvcn_dashboard_rp" value="1" />
		<?php
	}
}

