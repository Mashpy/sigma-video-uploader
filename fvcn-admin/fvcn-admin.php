<?php

/**
 * fvcn-admin.php
 *
 * Admin Area
 *
 * @package		Sigma Video
 * @subpackage	Admin
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * FvCommunityNews_Admin
 *
 */
class FvCommunityNews_Admin
{
	/**
	 * @var object
	 */
	public $posts = null;
	
	/**
	 * @var FvCommunityNews_AdminFactory
	 */
	protected $_factory;
	
	/**
	 * __construct()
	 *
	 * @version 20120719
	 * @param FvCommunityNews_AdminFactory $factory
	 * @return void
	 */
	public function __construct($factory)
	{
		$this->_factory = $factory;
		
		$this->_setupVariables()
			 ->_setupActions();
	}
	
	/**
	 * _setupVariables()
	 *
	 * @version 20120721
	 * @return FvCommunityNews_Admin
	 */
	private function _setupVariables()
	{
		$registry = FvCommunityNews_Registry::getInstance();
		
		$registry->adminDir = trailingslashit($registry->pluginDir . 'fvcn-admin');
		$registry->adminUrl = trailingslashit($registry->pluginUrl . 'fvcn-admin');
		
		return $this;
	}
	
	/**
	 * _setupActions()
	 *
	 * @version 20120720
	 * @return FvCommunityNews_Admin
	 */
	private function _setupActions()
	{
		add_action('admin_init',			array($this, 'init'));
		add_action('admin_head',			array($this, 'adminHead'));
		add_action('admin_menu',			array($this, 'adminMenu'));
		add_action('admin_enqueue_scripts',	array($this, 'enqueueScripts'));
	
		add_action('fvcn_admin_init',		array($this, 'factory'));
		
		return $this;
	}
	
	/**
	 * factory()
	 *
	 * @version 20120808
	 * @return FvCommunityNews_Admin
	 */
	public function factory()
	{
		$pageId = substr(basename( parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ), 0, -4);
		
		if (isset($_GET['post_type'])) {
			$pageId .= '-' . $_GET['post_type'];
		}
		if (isset($_GET['page'])) {
			$pageId .= '-' . $_GET['page'];
		}
		if ('index' == $pageId || 'wp-a' == $pageId) {
			$pageId = 'dashboard';
		}
		
		$postType = fvcn_get_post_type();
		
		switch ($pageId) {
			case 'dashboard' :
			case 'admin-ajax' :
				$this->_factory->getDashboard();
			break;
			
			case 'edit-' . $postType :
				$this->_factory->getPostModeration();
			break;
			
			case 'post' :
			case 'post-new-' . $postType :
				$this->_factory->getPostEdit();
			break;
			
			case 'admin-fvcn-settings' :
			case 'edit-' . $postType . '-fvcn-settings' :
				$this->_factory->getSettings();
			break;
			
			case 'admin-fvcn-form' :
			case 'edit-' . $postType . '-fvcn-form' :
				$this->_factory->getForm();
			break;
			
			case 'options' :
				$this->_factory->getSettings();
				$this->_factory->getForm();
			break;
		}
		
		do_action('fvcn_admin_factory', $pageId);
		
		return $this;
	}
	
	/**
	 * init()
	 *
	 * @version 20120129
	 * @return void
	 */
	public function init()
	{
		do_action('fvcn_admin_init');
	}
	
	/**
	 * adminHead()
	 *
	 * @version 20120721
	 * @return void
	 */
	public function adminHead()
	{
		$menuIconUrl = FvCommunityNews_Registry::get('adminUrl') . 'images/menu.png';
		$postClass   = sanitize_html_class( fvcn_get_post_type() );
		
		?>
		<style type="text/css">
			#menu-posts-<?php echo $postClass; ?> .wp-menu-image {
				background: url(<?php echo $menuIconUrl; ?>) no-repeat 0px 0px;
			}
			#menu-posts-<?php echo $postClass; ?>:hover .wp-menu-image,
			#menu-posts-<?php echo $postClass; ?>.wp-has-current-submenu .wp-menu-image {
				background: url(<?php echo $menuIconUrl; ?>) no-repeat 0px -32px;
			}
			#icon-edit.icon32-posts-<?php echo $postClass; ?> {
				background: url(<?php echo ''; ?>) no-repeat -4px 0px;
			}
			
			.column-fvcn_tags {
				width: 15%;
			}
			.column-fvcn_post_details {
				width: 35%;
				clear: both;
			}
			.fvcn-post-thumbnail {
				float: left;
				margin-right: 8px;
			}
		</style>
		<?php
		
		do_action('fvcn_admin_head');
	}
	
	/**
	 * enqueueScripts()
	 *
	 * @version 20120721
	 * @return void
	 */
	public function enqueueScripts()
	{
		do_action('fvcn_admin_enqueue_scripts');
	}
	
	/**
	 * adminMenu()
	 *
	 * @version 20120721
	 * @return void
	 */
	public function adminMenu()
	{
		$adminFormPage = add_submenu_page(
			'edit.php?post_type=' . fvcn_get_post_type(),
			__('Sigma Video Form', 'fvcn'),
			__('Form', 'fvcn'),
			'manage_options',
			'fvcn-form',
			'fvcn_admin_form'
		);
		add_action('load-' . $adminFormPage, 'fvcn_admin_form_help');
		
		add_submenu_page(
			'edit.php?post_type=' . fvcn_get_post_type(),
			__('Sigma Video Settings', 'fvcn'),
			__('Settings', 'fvcn'),
			'manage_options',
			'fvcn-settings',
			'fvcn_admin_settings'
		);
		
		do_action('fvcn_admin_menu');
	}
}


/**
 * FvCommunityNews_AdminFactory
 *
 */
class FvCommunityNews_AdminFactory
{
	/**
	 * __construct()
	 *
	 * @version 20120728
	 * @return void
	 */
	public function __construct()
	{
		$this->_loadFile('fvcn-admin-functions.php');
	}
	
	/**
	 * _loadFile()
	 *
	 * @version 20120721
	 * @param string $filename
	 * @return bool
	 */
	protected function _loadFile($filename)
	{
		$file = FvCommunityNews_Registry::get('pluginDir') . 'fvcn-admin/' . $filename;
		
		if (file_exists($file)) {
			return require_once $file;
		}
		
		return false;
	}
	
	/**
	 * getDashboard()
	 *
	 * @version 20120721
	 * @return FvCommunityNews_Admin_Dashboard
	 */
	public function getDashboard()
	{
		$this->_loadFile('fvcn-admin-dashboard.php');
		return new FvCommunityNews_Admin_Dashboard();
	}
	
	/**
	 * getPostModeration()
	 *
	 * @version 20120721
	 * @return FvCommunityNews_Admin_PostModeration
	 */
	public function getPostModeration()
	{
		$this->_loadFile('fvcn-admin-post-moderation.php');
		return new FvCommunityNews_Admin_PostModeration();
	}
	
	/**
	 * getPostEdit()
	 *
	 * @version 20120721
	 * @return FvCommunityNews_Admin_PostModeration
	 */
	public function getPostEdit()
	{
		$this->_loadFile('fvcn-admin-post-edit.php');
		return new FvCommunityNews_Admin_PostEdit();
	}
	
	/**
	 * getForm()
	 *
	 * @version 20120721
	 * @return FvCommunityNews_Admin_Form
	 */
	public function getForm()
	{
		$this->_loadFile('fvcn-admin-form.php');
		return fvcn_register_admin_form();
	}
	
	/**
	 * getSettings()
	 *
	 * @version 20120721
	 * @return FvCommunityNews_Admin_Settings
	 */
	public function getSettings()
	{
		$this->_loadFile('fvcn-admin-settings.php');
		return fvcn_register_admin_settings();
	}
}


/**
 * fvcn_admin()
 *
 * @version 20120710
 * @return void
 */
function fvcn_admin()
{
	FvCommunityNews_Container::getInstance()->getAdmin();
}

