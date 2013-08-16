<?php

/**
 * fvcn-extend-akismet.php
 *
 * Akismet Plugin
 *
 * @package		Sigma Video
 * @subpackage	Akismet
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * FvCommunityNews_Akismet
 *
 */
class FvCommunityNews_Akismet
{
	/**
	 * @var string
	 */
	protected $_apiKey;
	
	/**
	 * @var string
	 */
	protected $_blogUrl;
	
	/**
	 * __construct()
	 * 
	 * @version 20120711
	 * @param string $apiKey
	 * @param string $blogUrl
	 * @return void
	 */
	public function __construct($apiKey, $blogUrl)
	{
		$this->setApiKey($apiKey)
			 ->setBlogUrl($blogUrl);
	}
	
	/**
	 * setApiKey()
	 * 
	 * @version 20120711
	 * @param string $apiKey
	 * @return FvCommunityNews_Akismet
	 */
	public function setApiKey($apiKey)
	{
		$this->_apiKey = $apiKey;
		return $this;
	}
	
	/**
	 * getApiKey()
	 * 
	 * @version 20120711
	 * @return string
	 */
	public function getApiKey()
	{
		return $this->_apiKey;
	}
	
	/**
	 * setBlogUrl()
	 * 
	 * @version 20120711
	 * @param string $blogUrl
	 * @return FvCommunityNews_Akismet
	 */
	public function setBlogUrl($blogUrl)
	{
		$this->_blogUrl = $blogUrl;
		return $this;
	}
	
	/**
	 * getBlogUrl()
	 * 
	 * @version 20120711
	 * @return string
	 */
	public function getBlogUrl()
	{
		return $this->_blogUrl;
	}
	
	/**
	 * _post()
	 * 
	 * @version 20120711
	 * @param string $host
	 * @param string $path
	 * @param array $params
	 * @return string
	 */
	protected function _post($host, $path, array $params)
	{
		$uri	 = 'http://' . $host . $path;
		$request = array(
			'body'				=> $params,
			'headers'			=> array(
				'Content-Type'	=> 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
				'Host'			=> $host,
				'User-Agent'	=> 'Sigma Video/' . fvcn_get_version() . ' | Akismet/20120711'
			),
			'httpversion'		=> '1.0',
			'timeout'			=> 15
		);
		
		$response = wp_remote_post($uri, $request);
		
		if (is_wp_error($response)) {
			throw new Exception('Error while accessing Akismet');
		}
		
		return trim($response['body']);
	}
	
	/**
	 * _makeApiCall()
	 * 
	 * @version 20120711
	 * @param string $path
	 * @param array $params
	 * @return string
	 */
	protected function _makeApiCall($path, array $params)
	{
		if (isset($params['user_ip'], $params['user_agent'])) {
			$params['blog'] = $this->getBlogUrl();
			
			return $this->_post($this->getApiKey() . '.rest.akismet.com', $path, $params);
		}
		
		throw new Exception('Missing required Akismet params (user_ip, user_agent)');
	}
	
	/**
	 * verifyKey()
	 * 
	 * @version 20120711
	 * @param string $key
	 * @param string $blog
	 * @return bool
	 */
	public function verifyKey($key=null, $blog=null)
	{
		if (null === $key) {
			$key = $this->getApiKey();
		}
		if (null === $blog) {
			$blog = $this->getBlogUrl();
		}
		
		return ('valid' == $this->_post('rest.akismet.com', '/1.1/verify-key', array('key' => $key, 'blog' => $blog)));
	}
	
	/**
	 * isSpam()
	 * 
	 * @version 20120711
	 * @param array $params
	 * @return bool
	 */
	public function isSpam(array $params)
	{
		$response = $this->_makeApiCall('/1.1/comment-check', $params);
		
		if ('invalid' == $response) {
			throw new Exception('Invalid API key');
		}
		if ('true' == $response) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * submitSpam()
	 * 
	 * @version 20120711
	 * @param array $params
	 * @return void
	 */
	public function submitSpam(array $params)
	{
		$this->_makeApiCall('/1.1/submit-spam', $params);
	}
	
	/**
	 * submitHam()
	 * 
	 * @version 20120711
	 * @param array $params
	 * @return void
	 */
	public function submitHam(array $params)
	{
		$this->_makeApiCall('/1.1/submit-ham', $params);
	}
}


/**
 * FvCommunityNews_Akismet_Handler
 *
 */
class FvCommunityNews_Akismet_Handler
{
	/**
	 * @var int
	 */
	protected $_currentPostId;
	
	/**
	 * @var FvCommunityNews_Akismet
	 */
	protected $_akismet;
	
	/**
	 * __construct()
	 * 
	 * @version 20120711
	 * @param FvCommunityNews_Akismet $akismet
	 * @return void
	 */
	public function __construct(FvCommunityNews_Akismet $akismet)
	{
		$this->_akismet = $akismet;
	}
	
	/**
	 * _getParams()
	 * 
	 * @version 20120711
	 * @param int $postId
	 * @return array
	 */
	protected function _getParams($postId)
	{
		$params = array(
			'user_ip'				=> fvcn_get_post_author_ip($postId),
			'user_agent'			=> fvcn_get_post_author_ua($postId),
			'referer'				=> $_SERVER['HTTP_REFERER'],
			'permalink'				=> fvcn_get_post_permalink($postId),
			'comment_type'			=> 'SigmaVideo',
			'comment_author'		=> fvcn_get_post_author_display_name($postId),
			'comment_author_email'	=> fvcn_get_post_author_email($postId),
			'comment_author_url'	=> fvcn_get_post_link($postId),
			'comment_content'		=> fvcn_get_post_content($postId),
			'blog_charset'			=> get_option('blog_charset'),
			'blog_lang'				=> get_locale()
		);
		
		$ignore = array('HTTP_COOKIE', 'HTTP_COOKIE2', 'PHP_AUTH_PW');
		foreach ($_SERVER as $key=>$value) {
			if (!in_array($key, $ignore) && is_string($value)) {
				$params[ $key ] = $value;
			}
		}
		
		return $params;
	}
	
	/**
	 * checkPost()
	 * 
	 * @version 20120711
	 * @param int $postId
	 * @return FvCommunityNews_Akismet_Handler
	 */
	public function checkPost($postId)
	{
		if ($this->_akismet->isSpam( $this->_getParams($postId) )) {
			$this->_currentPostId = $postId;
			fvcn_spam_post($postId);
		}
		
		return $this;
	}
	
	/**
	 * submitPost()
	 * 
	 * @version 20120711
	 * @param int $postId
	 * @return FvCommunityNews_Akismet_Handler
	 */
	public function submitPost($postId)
	{
		$filter = current_filter();
		
		if ('fvcn_spam_post' == $filter) {
			if ($this->_currentPostId == $postId) {
				return;
			}
			
			$method = 'submitSpam';
		} elseif ('fvcn_publish_post' == $filter) {
			if (fvcn_get_spam_post_status() != fvcn_get_post_status($postId)) {
				return;
			}
			
			$method = 'submitHam';
		} else {
			return;
		}
		
		$this->_akismet->$method( $this->_getParams($postId) );
		
		return $this;
	}
	
	/**
	 * registerSettings()
	 *
	 * @version 20120322
	 * @return void
	 */
	public function registerSettings()
	{
		add_settings_section('fvcn_settings_akismet', __('Akismet', 'fvcn'), array($this, 'settingsCallbackSection'), 'fvcn-settings');
		
		add_settings_field('_fvcn_akismet_enabled', __('Enabled', 'fvcn'), array($this, 'settingsCallbackEnabled'), 'fvcn-settings', 'fvcn_settings_akismet');
		register_setting('fvcn-settings', '_fvcn_akismet_enabled', 'intval');
	}
	
	/**
	 * settingsCallbackSection()
	 *
	 * @version 20120322
	 * @return void
	 */
	public function settingsCallbackSection()
	{
	?>
		
		<p><?php _e('Keep in mind that you must have/keep the Akismet plugin enabled with a valid API key.', 'fvcn'); ?></p>
		
	<?php
	}
	
	/**
	 * settingsCallbackEnabled()
	 *
	 * @version 20120711
	 * @return void
	 */
	public function settingsCallbackEnabled()
	{
	?>
		
		<input type="checkbox" name="_fvcn_akismet_enabled" id="_fvcn_akismet_enabled" value="1" <?php checked( get_option('_fvcn_akismet_enabled', false) ); ?> />
		<label for="_fvcn_akismet_enabled"><?php _e('Enable Akismet spam protection for community posts.', 'fvcn'); ?></label>
		
		<?php if ($this->_akismet->verifyKey()) : ?>
			
			<p class="description"><?php _e('Your current API key appears to be <strong>valid</strong>.', 'fvcn'); ?></p>
			
		<?php else : ?>
			
			<p class="description"><?php _e('Your current API key appears to be <strong>invalid</strong>.', 'fvcn'); ?></p>
			
		<?php endif;
		
	}
}


/**
 * fvcn_akismet_check_post()
 * 
 * @version 20120711
 * @param int $postId
 * @return void
 */
function fvcn_akismet_check_post($postId)
{
	try {
		FvCommunityNews_Container::getInstance()->getAkismetHandler()->checkPost($postId);
	} catch (Exception $e) {}
}

/**
 * fvcn_akismet_submit_post()
 * 
 * @version 20120711
 * @param int $postId
 * @return void
 */
function fvcn_akismet_submit_post($postId)
{
	try {
		FvCommunityNews_Container::getInstance()->getAkismetHandler()->submitPost($postId);
	} catch (Exception $e) {}
}

/**
 * fvcn_akismet_register_settings()
 * 
 * @version 20120711
 * @return void
 */
function fvcn_akismet_register_settings()
{
	FvCommunityNews_Container::getInstance()->getAkismetHandler()->registerSettings();
}

/**
 * fvcn_akismet()
 *
 * @version 20120719
 * @return void
 */
function fvcn_akismet()
{
	if (!defined('AKISMET_VERSION')) {
		return;
	}
	
	if (get_option('_fvcn_akismet_enabled', false)) {
		add_action('fvcn_insert_post',	'fvcn_akismet_check_post' );
		add_action('fvcn_spam_post',	'fvcn_akismet_submit_post');
		add_action('fvcn_publish_post',	'fvcn_akismet_submit_post');
	}
	
	if (is_admin()) {
		add_action('fvcn_register_admin_settings', 'fvcn_akismet_register_settings');
	}
}

