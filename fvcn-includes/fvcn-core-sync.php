<?php

/**
 * fvcn-core-sync.php
 *
 * Sync Functions
 *
 * @package		Sigma Video
 * @subpackage	Sync
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	die('Direct access is not allowed!');
}


/**
 * FvCommunityNews_Crypt
 *
 * Rijndael 128 bit encryption.
 *
 * @author Frank Verhoeven <info@frank-verhoeven.com>
 */
class FvCommunityNews_Crypt
{
	const CIPHER = MCRYPT_RIJNDAEL_128;
	const MODE	 = MCRYPT_MODE_CBC;
	
	/**
	 * @var string
	 */
	protected $_key	= null;
	
	/**
	 * @var string
	 */
	protected $_iv	= null;
	
	/**
	 * __construct()
	 *
	 * @version 20120701
	 * @param string $key
	 * @param string $iv
	 */
	public function __construct($key, $iv=null)
	{
		$this->setKey($key);
		
		if (null !== $iv) {
			$this->setIv($iv);
		}
	}
	
	/**
	 * canEncrypt()
	 *
	 * @version 20120701
	 * @return bool
	 */
	public function canEncrypt()
	{
		return extension_loaded('mcrypt');
	}
	
	/**
	 * setKey()
	 *
	 * @version 20120701
	 * @param string $key
	 * @return FvCommunityNews_Crypt
	 */
	public function setKey($key)
	{
		$this->_key = (string) $key;
		return $this;
	}
	
	/**
	 * getKey()
	 *
	 * @version 20120701
	 * @return string
	 */
	public function getKey()
	{
		if (strlen($this->_key) > mcrypt_get_key_size(self::CIPHER, self::MODE)) {
			return substr($this->_key, 1, mcrypt_get_key_size(self::CIPHER, self::MODE));
		}
		
		return $this->_key;
	}
	
	/**
	 * createIv()
	 *
	 * @version 20120701
	 * @return string
	 */
	public function createIv()
	{
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER, self::MODE), MCRYPT_RAND);
		
		if (false === $iv) {
			throw new Exception('Failed to create an initialization vector.');
		}
		
		return $this->setIv( $iv )->getIv();
	}
	
	/**
	 * setIv()
	 *
	 * @version 20120701
	 * @param string $iv
	 * @return FvCommunityNews_Crypt
	 */
	public function setIv($iv)
	{
		if (0 !== mcrypt_get_iv_size(self::CIPHER, self::MODE) && strlen($iv) != mcrypt_get_iv_size(self::CIPHER, self::MODE)) {
			throw new Exception('Invallid IV size.');
		}
		
		$this->_iv = $iv;
		return $this;
	}
	
	/**
	 * getIv()
	 *
	 * @version 20120701
	 * @return string
	 */
	public function getIv()
	{
		if (null !== $this->_iv) {
			return $this->_iv;
		}
		
		return $this->createIv();
	}
	
	/**
	 * encrypt()
	 *
	 * @version 20120701
	 * @param string $value
	 * @return string
	 */
	public function encrypt($value)
	{
		$encrypted = mcrypt_encrypt(self::CIPHER, $this->getKey(), trim($value), self::MODE, $this->getIv());
		
		return base64_encode( $encrypted );
	}
	
	/**
	 * decrypt()
	 *
	 * @version 20120701
	 * @param string $value
	 * @return string
	 */
	public function decrypt($value)
	{
		$decrypted = mcrypt_decrypt(self::CIPHER, $this->getKey(), base64_decode($value), self::MODE, $this->getIv());
		
		return rtrim($decrypted, "\0\4");
	}
}


/**
 * FvCommunityNews_Sync
 *
 * Synchronisation
 *
 * @author Frank Verhoeven <info@frank-verhoeven.com>
 */
class FvCommunityNews_Sync
{
	const API_REGISTER				= 'http://fvcn.frank-verhoeven.com/api/register-site/';
	const API_SUBMIT_POST			= 'http://fvcn.frank-verhoeven.com/api/submit-post/';
	const API_INC_POST_VIEW_COUNT	= 'http://fvcn.frank-verhoeven.com/api/increase-post-view-count/';
	const API_INC_POST_RATING		= 'http://fvcn.frank-verhoeven.com/api/increase-post-rating/';
	const API_DEC_POST_RATING		= 'http://fvcn.frank-verhoeven.com/api/decrease-post-rating/';
	
	/**
	 * @var array
	 */
	protected $_options	= array();
	
	/**
	 * @var bool
	 */
	protected $_enabled	= true;
	
	/**
	 * @var bool
	 */
	protected $_registered = false;
	
	/**
	 * @var FvCommunityNews_Crypt
	 */
	protected $_crypt	= null;
	
	/**
	 * __construct()
	 *
	 * @version 20120716
	 * @param FvCommunityNews_Crypt $crypt
	 */
	public function __construct(FvCommunityNews_Crypt $crypt)
	{
		$this->_crypt = $crypt;
		$this->_setupOptions();
		
		if (!$this->isSiteRegistered()) {
			$this->registerSite();
		}
	}
	
	/**
	 * _setupOptions()
	 *
	 * @version 20120701
	 * @return FvCommunityNews_Sync
	 */
	protected function _setupOptions()
	{
		$this->_options = array(
			'method'		=> 'POST',
			'timeout'		=> 10,
			'redirection'	=> 1,
			'user-agent'	=> 'WordPress/' . get_bloginfo('version') . ';SigmaVideo/' . fvcn_get_version() . ';' . home_url('/'),
			'blocking'		=> true,
			'compress'		=> true,
			'decompress'	=> true,
			'headers'		=> array(),
			'body'			=> array(),
			'cookies'		=> array()
		);
		
		return $this;
	}
	
	/**
	 * isEnabled()
	 *
	 * @version 20120701
	 * @return bool
	 */
	public function isEnabled()
	{
		return $this->_enabled;
	}
	
	/**
	 * isSiteRegistered()
	 *
	 * @version 20120701
	 * @return bool
	 */
	public function isSiteRegistered()
	{
		$key = fvcn_get_option('_fvcn_sync_key');
		
		if (false !== strpos($key, 'inactive') || false !== strpos($key, 'invallid') ) {
			$this->_enabled = false;
		}
		
		return (bool)$key;
	}
	
	/**
	 * _encryptData()
	 *
	 * @version 20120701
	 * @param array $data
	 * @param bool $root
	 * @return array
	 */
	protected function _encryptData(array $data, $root=true)
	{
		if (!$this->_crypt->canEncrypt()) {
			if ($root) {
				$data['encrypted'] = false;
			}
			
			return $data;
		}
		
		foreach ($data as $key=>$val) {
			if (is_array($val)) {
				$data[ $key ] = $this->_encryptData($val, false);
			} else {
				$data[ $key ] = $this->_crypt->encrypt($val);
			}
		}
		
		if ($root) {
			$data['encrypted']	= true;
			$data['validator']	= $this->_crypt->encrypt( home_url('/') );
			$data['iv']			= base64_encode( $this->_crypt->getIv() );
		}
		
		return $data;
	}
	
	/**
	 * _makeApiCall()
	 * 
	 * @version 20120716
	 * @param string $uri
	 * @param array $data
	 * @param array $options
	 * @param bool $encrypt
	 * @return bool|string
	 */
	protected function _makeApiCall($uri, array $data, array $options=array(), $encrypt=true)
	{
		if (!$this->isEnabled()) {
			return false;
		}
		
		$options = wp_parse_args($options, $this->_options);
		
		if (true === $encrypt) {
			$options['body'] = $this->_encryptData( $data );
		} else {
			$options['body'] = $data;
		}
		
		$response = wp_remote_post($uri, $options);
		
		if (is_wp_error($response)) {
			return false;
		}
		
		return $response['body'];
	}
	
	/**
	 * registerSite()
	 *
	 * @version 20120716
	 * @return FvCommunityNews_Sync
	 */
	public function registerSite()
	{
		if (true === $this->_registered) {
			return $this;
		}
		
		$data = array(
			'blog_name'			=> get_bloginfo('name'),
			'blog_description'	=> get_bloginfo('description'),
			'blog_url'			=> home_url('/'),
			'blog_language'		=> get_bloginfo('language')
		);
		
		if (false === ($key = $this->_makeApiCall(self::API_REGISTER, $data, array(), false))) {
			$this->_enabled = false;
		} else {
			update_option('_fvcn_sync_key', $key);
			$this->_registered = true;
		}
		
		return $this;
	}
	
	/**
	 * submitPost()
	 *
	 * @version 20120712
	 * @param array $postData
	 * @return FvCommunityNews_Sync
	 */
	public function submitPost(array $postData)
	{
		$this->_makeApiCall(self::API_SUBMIT_POST, $postData, array('blocking' => false));
		
		return $this;
	}
	
	/**
	 * increasePostViewCount()
	 *
	 * @version 20120712
	 * @paran int $postId
	 * @return FvCommunityNews_Sync
	 */
	public function increasePostViewCount($postId)
	{
		$data = array(
			'post_link'		=> fvcn_get_post_link($postId),
			'post_title'	=> fvcn_get_post_title($postId)
		);
		
		$this->_makeApiCall(self::API_INC_POST_VIEW_COUNT, $data, array('blocking' => false));
		
		return $this;
	}
	
	/**
	 * increasePostRating()
	 * 
	 * @version 20120712
	 * @param int $postId
	 * @return FvCommunityNews_Sync
	 */
	public function increasePostRating($postId)
	{
		$data = array(
			'post_link'		=> fvcn_get_post_link($postId),
			'post_title'	=> fvcn_get_post_title($postId)
		);
		
		$this->_makeApiCall(self::API_INC_POST_RATING, $data, array('blocking' => false));
		
		return $this;
	}
	
	/**
	 * decreasePostRating()
	 * 
	 * @version 20120712
	 * @param int $postId
	 * @return FvCommunityNews_Sync
	 */
	public function decreasePostRating($postId)
	{
		$data = array(
			'post_link'		=> fvcn_get_post_link($postId),
			'post_title'	=> fvcn_get_post_title($postId)
		);
		
		$this->_makeApiCall(self::API_DEC_POST_RATING, $data, array('blocking' => false));
		
		return $this;
	}
}


/**
 * fvcn_sync_submit_post()
 *
 * @version 20120729
 * @param int $postId
 * @return void
 */
function fvcn_sync_submit_post($postId)
{
	if (fvcn_get_public_post_status() != fvcn_get_post_status($postId) && 'fvcn_publish_post' != current_filter()) {
		return;
	}
	
	$data = array(
		'post_id'		=> $postId,
		'post_title'	=> fvcn_get_post_title($postId),
		'post_content'	=> strip_tags( fvcn_get_post_content($postId) ),
		'post_url'		=> fvcn_get_post_link($postId),
		'post_tags'		=> strip_tags( fvcn_get_post_tag_list($postId, array('before'=>'', 'sep'=>';', 'after'=>'')) ),
		'post_rating'	=> fvcn_get_post_rating($postId),
		'post_views'	=> fvcn_get_post_views($postId),
		'post_status'	=> fvcn_get_public_post_status(),
		'post_author'	=> array(
			'author_name'	=> fvcn_get_post_author_display_name($postId),
			'author_email'	=> fvcn_get_post_author_email($postId)
		)
	);
	
	if (fvcn_has_post_thumbnail($postId)) {
		$data['post_thumbnail'] = wp_get_attachment_url( get_post_thumbnail_id($postId) );
	} else {
		$data['post_thumbnail'] = '';
	}
	
	FvCommunityNews_Container::getInstance()->getSync()->submitPost($data);
}

/**
 * fvcn_sync_increase_post_view_count()
 *
 * @version 20120702
 * @param int $postId
 * @return void
 */
function fvcn_sync_increase_post_view_count($postId)
{
	FvCommunityNews_Container::getInstance()->getSync()->increasePostViewCount($postId);
}

/**
 * fvcn_sync_increase_post_rating()
 * 
 * @version 20120712
 * @param int $postId
 * @return void
 */
function fvcn_sync_increase_post_rating($postId)
{
	FvCommunityNews_Container::getInstance()->getSync()->increasePostRating($postId);
}

/**
 * fvcn_sync_decrease_post_rating()
 * 
 * @version 20120712
 * @param int $postId
 * @return void
 */
function fvcn_sync_decrease_post_rating($postId)
{
	FvCommunityNews_Container::getInstance()->getSync()->decreasePostRating($postId);
}

