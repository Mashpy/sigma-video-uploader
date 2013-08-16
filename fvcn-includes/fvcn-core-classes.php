<?php

/**
 * fvcn-core-classes.php
 *
 * Core Classes
 *
 * @package		Sigma Video
 * @subpackage	Core Classes
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * FvCommunityNews_Container
 *
 */
class FvCommunityNews_Container
{
	/**
	 * @var array
	 */
	protected $_options = array();
	
	/**
	 * @var array
	 */
	protected $_objects = array();
	
	/**
	 * @var FvCommunityNews_Container
	 */
	private static $_instance;
	
	/**
	 * __construct()
	 *
	 * @version 20120709
	 * @param array $options
	 */
	public function __construct(array $options=array())
	{
		$this->_options = $options;
	}
	
	
	/**
	 * getAdmin()
	 *
	 * @version 20120710
	 * @return FvCommunityNews_Admin
	 */
	public function getAdmin()
	{
		if (isset($this->_objects['admin'])) {
			return $this->_objects['admin'];
		}
		
		return $this->_objects['admin'] = new FvCommunityNews_Admin( new FvCommunityNews_AdminFactory() );
	}
	
	/**
	 * getAkismet()
	 *
	 * @version 20120711
	 * @return FvCommunityNews_Akismet
	 */
	public function getAkismet()
	{
		if (isset($this->_objects['akismet'])) {
			return $this->_objects['akismet'];
		}
		
		return $this->_objects['akismet'] = new FvCommunityNews_Akismet(akismet_get_key(), get_option('home'));
	}
	
	/**
	 * getAkismetHandler()
	 *
	 * @version 20120711
	 * @return FvCommunityNews_Akismet_Handler
	 */
	public function getAkismetHandler()
	{
		if (isset($this->_objects['akismetHandler'])) {
			return $this->_objects['akismetHandler'];
		}
		
		return $this->_objects['akismetHandler'] = new FvCommunityNews_Akismet_Handler( $this->getAkismet() );
	}
	
	/**
	 * getCrypt()
	 *
	 * @version 20120709
	 * @return FvCommunityNews_Crypt
	 */
	public function getCrypt()
	{
		if (isset($this->_objects['crypt'])) {
			return $this->_objects['crypt'];
		}
		
		return $this->_objects['crypt'] = new FvCommunityNews_Crypt( fvcn_get_option('_fvcn_sync_key') );
	}
	
	/**
	 * getJavascript()
	 *
	 * @version 20120714
	 * @return FvCommunityNews_Javascript
	 */
	public function getJavascript()
	{
		if (isset($this->_objects['javascript'])) {
			return $this->_objects['javascript'];
		}
		
		return $this->_objects['javascript'] = new FvCommunityNews_Javascript();
	}
	
	/**
	 * getOptions()
	 *
	 * @version 20120710
	 * @return FvCommunityNews_Options
	 */
	public function getOptions()
	{
		if (isset($this->_objects['options'])) {
			return $this->_objects['options'];
		}
		
		return $this->_objects['options'] = new FvCommunityNews_Options();
	}
	
	/**
	 * getShortcodes()
	 *
	 * @version 20120709
	 * @return FvCommunityNews_Shortcodes
	 */
	public function getShortcodes()
	{
		if (isset($this->_objects['shortcodes'])) {
			return $this->_objects['shortcodes'];
		}
		
		return $this->_objects['shortcodes'] = new FvCommunityNews_Shortcodes();
	}
	
	/**
	 * getSync()
	 *
	 * @version 20120709
	 * @return FvCommunityNews_Sync
	 */
	public function getSync()
	{
		if (isset($this->_objects['sync'])) {
			return $this->_objects['sync'];
		}
		
		return $this->_objects['sync'] = new FvCommunityNews_Sync( $this->getCrypt() );
	}
	
	/**
	 * getWpError()
	 *
	 * @version 20120709
	 * @return WP_Error
	 */
	public function getWpError()
	{
		if (isset($this->_objects['wperror'])) {
			return $this->_objects['wperror'];
		}
		
		return $this->_objects['wperror'] = new WP_Error();
	}
	
	
	/**
	 * setInstance()
	 *
	 * @version 20120710
	 * @param FvCommunityNews_Container $instance
	 */
	public static function setInstance(FvCommunityNews_Container $instance=null)
	{
		if (null === self::$_instance) {
			if (null === $instance) {
				self::$_instance = new FvCommunityNews_Container();
			} else {
				self::$_instance = $instance;
			}
		}
	}
	
	/**
	 * getInstance()
	 *
	 * @version 20120710
	 * @return FvCommunityNews_Container
	 */
	public static function getInstance()
	{
		self::setInstance();
		return self::$_instance;
	}
}


/**
 * FvCommunityNews_Registry
 *
 */
class FvCommunityNews_Registry
{
	/**
	 * @var array
	 */
	private $_options			= array();
	
	/**
	 * @var object
	 */
	private static $_instance;
	
	/**
	 * __construct()
	 *
	 * @version 20120710
	 * @param array $options
	 */
	public function __construct(array $options=null)
	{
		if (!empty($options)) {
			$this->setOptions($options);
		}
	}
	
	/**
	 * __set()
	 *
	 * @version 20120710
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key, $value)
	{
		$this->_options[ $key ] = $value;
	}
	
	/**
	 * __get()
	 *
	 * @version 20120710
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		if (!isset($this->_options[ $key ])) {
			return;
		}
		
		return $this->_options[ $key ];
	}
	
	/**
	 * setOptions()
	 *
	 * @version 20120710
	 * @param array $options
	 * @return FvCommunityNews_Registry
	 */
	public function setOptions(array $options)
	{
		foreach ($options as $key=>$value) {
			$this->$key = $value;
		}
	}
	
	/**
	 * setInstance()
	 *
	 * @version 20120710
	 * @param FvCommunityNews_Registry $instance
	 */
	public static function setInstance(FvCommunityNews_Registry $instance=null)
	{
		if (null === self::$_instance) {
			if (null === $instance) {
				self::$_instance = new FvCommunityNews_Registry();
			} else {
				self::$_instance = $instance;
			}
		}
	}
	
	/**
	 * getInstance()
	 *
	 * @version 20120710
	 * @return FvCommunityNews_Registry
	 */
	public static function getInstance()
	{
		self::setInstance();
		return self::$_instance;
	}
	
	/**
	 * set()
	 *
	 * @version 20120710
	 * @param string $key
	 * @param mixed $value
	 */
	public static function set($key, $value)
	{
		self::getInstance()->$key = $value;
	}
	
	/**
	 * get()
	 *
	 * @version 20120710
	 * @param string $key
	 * @return mixed
	 */
	public static function get($key)
	{
		return self::getInstance()->$key;
	}
}

