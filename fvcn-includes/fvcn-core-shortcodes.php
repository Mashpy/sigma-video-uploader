<?php

/**
 * fvcn-core-shortcodes.php
 *
 * Shortcodes
 *
 * @package		Sigma Video
 * @subpackage	Shortcodes
 * @author		Frank Verhoeven
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * FvCommunityNews_Shortcodes
 *
 */
class FvCommunityNews_Shortcodes
{
	/**
	 * @var array
	 */
	private $_codes = array();
	
	/**
	 * __construct()
	 *
	 * @version 20120315
	 * @return void
	 */
	public function __construct()
	{
		$this->_addCodes()
			 ->_registerCodes();
	}
	
	/**
	 * _addCodes()
	 *
	 * @version 20120716
	 * @return FvCommunityNews_Shortcodes
	 */
	private function _addCodes()
	{
		$codes = array(
			'Sigma-uploader--posts'	=> array($this, 'displayRecentPosts'),
			'Sigma-uploader-form'	=> array($this, 'displayPostForm'),
			'Sigma-uploader-cloud'	=> array($this, 'displayTagCloud')
		);
		
		$codes = apply_filters('fvcn_shortcodes', $codes);
		
		foreach ($codes as $code=>$callback) {
			$this->addCode($code, $callback);
		}
		
		return $this;
	}
	
	/**
	 * addCode()
	 *
	 * @version 20120716
	 * @param string $code
	 * @param callback $callback
	 * @return FvCommunityNews_Shortcodes
	 */
	public function addCode($code, $callback)
	{
		if (!isset($this->_codes[ $code ])) {
			$this->_codes[ $code ] = $callback;
		}
		
		return $this;
	}
	
	/**
	 * removeCode()
	 *
	 * @version 20120315
	 * @param string $code
	 * @return FvCommunityNews_Shortcodes
	 */
	public function removeCode($code)
	{
		unset($this->_codes[ $code ]);
		return $this;
	}
	
	/**
	 * getCodes()
	 *
	 * @version 20120315
	 * @return array
	 */
	public function getCodes()
	{
		return $this->_codes;
	}
	
	/**
	 * _registerCodes()
	 *
	 * @version 20120315
	 * @return FvCommunityNews_Shortcodes
	 */
	private function _registerCodes()
	{
		foreach ($this->getCodes() as $code=>$callback) {
			add_shortcode($code, $callback);
		}
		
		do_action('fvcn_register_shortcodes');
		
		return $this;
	}
	
	/**
	 * _obStart()
	 *
	 * @version 20120315
	 * @return void
	 */
	private function _obStart()
	{
		ob_start();
	}
	
	/**
	 * _obEnd()
	 *
	 * @version 20120315
	 * @return string
	 */
	private function _obEnd()
	{
		$output = ob_get_contents();
		
		ob_end_clean();
		
		return $output;
	}
	
	/**
	 * displayRecentPosts()
	 *
	 * @version 20120315
	 * @return string
	 */
	public function displayRecentPosts($attr=array())
	{
		$this->_obStart();
		
		$options = array('posts_per_page' => 10);
		
		if (fvcn_has_posts($options)) {
			fvcn_get_template_part('fvcn/loop', 'posts');
		} else {
			fvcn_get_template_part('fvcn/feedback', 'no-posts');
		}
		
		return $this->_obEnd();
	}
	
	/**
	 * displayPostForm()
	 *
	 * @version 20120315
	 * @return string
	 */
	public function displayPostForm()
	{
		$this->_obStart();
		
		fvcn_get_template_part('fvcn/form', 'post');
		
		return $this->_obEnd();
	}
	
	/**
	 * displayTagCloud()
	 *
	 * @version 20120716
	 * @return string
	 */
	public function displayTagCloud()
	{
		$this->_obStart();
		
		?>
			<div class="fvcn-tag-cloud">
				<?php fvcn_tag_cloud(); ?>
			</div>
		<?php
		
		return $this->_obEnd();
	}
}


/**
 * fvcn_register_shortcodes()
 *
 * @version 20120709
 * @return void
 */
function fvcn_register_shortcodes()
{
	FvCommunityNews_Container::getInstance()->getShortcodes();
}

