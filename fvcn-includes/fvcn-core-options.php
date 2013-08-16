<?php

/**
 *	fvcn-core-options.php
 *
 *	Options
 *
 *	@package	Sigma Video
 *	@subpackage	Options
 *	@author		Frank Verhoeven
 */

if (!defined('ABSPATH')) {
	die('Direct access is not allowed!');
}


class FvCommunityNews_Options
{
	/**
	 * @var array
	 */
	protected $_defaultOptions	= array();
	
	/**
	 * @var array
	 */
	protected $_options			= array();
	
	/**
	 * __construct()
	 *
	 * @version 20120710
	 * @return void
	 */
	public function __construct()
	{
		$this->_setDefaultOptions();
	}
	
	/**
	 * _setDefaultOptions()
	 *
	 * @version 20120710
	 * @return FvCommunityNews_Options
	 */
	protected function _setDefaultOptions()
	{
		$this->_defaultOptions = apply_filters('fvcn_get_default_options', array(
			'_fvcn_version'								=> SigmaVideo::getInstance()->version,
			
			'_fvcn_admin_moderation'					=> false,
			'_fvcn_user_moderation'						=> true,
			'_fvcn_mail_on_submission'					=> false,
			'_fvcn_mail_on_moderation'					=> true,
			'_fvcn_is_anonymous_allowed'				=> true,
			
			'_fvcn_base_slug'							=> 'SigmaVideo',
			'_fvcn_post_slug'							=> 'post',
			'_fvcn_post_tag_slug'						=> 'tag',
			'_fvcn_post_archive_slug'					=> 'archive',
			
			'_fvcn_post_form_author_name_label'			=> __('Author Name', 'fvcn'),
			'_fvcn_post_form_author_email_label'		=> __('Author Email', 'fvcn'),
			'_fvcn_post_form_title_label'				=> __('Title', 'fvcn'),
			'_fvcn_post_form_link_label'				=> __('Link', 'fvcn'),
			'_fvcn_post_form_link_required'				=> true,
			'_fvcn_post_form_content_label'				=> __('Description', 'fvcn'),
			'_fvcn_post_form_tags_label'				=> __('Tags', 'fvcn'),
			'_fvcn_post_form_tags_required'				=> true,
			'_fvcn_post_form_thumbnail_enabled'			=> true,
			'_fvcn_post_form_thumbnail_label'			=> __('Thumbnail', 'fvcn'),
			'_fvcn_post_form_thumbnail_required'		=> false,
			
			'_fvcn_sync_key'							=> false,
			
			'_fvcn_dashboard_rp_num'					=> 5
		));
		
		return $this;
	}
	
	/**
	 * getDefaultOptions()
	 *
	 * @version 20120710
	 * @return array
	 */
	public function getDefaultOptions()
	{
		return $this->_defaultOptions;
	}
	
	/**
	 * getDefaultOption()
	 *
	 * @version 20120716
	 * @param string $key
	 * @return mixed
	 */
	public function getDefaultOption($key)
	{
		if (!isset($this->_defaultOptions[ $key ])) {
			return null;
		}
		
		return $this->_defaultOptions[ $key ];
	}
	
	/**
	 * addOptions()
	 * 
	 * @version 20120710
	 * @return FvCommunityNews_Options
	 */
	public function addOptions()
	{
		foreach ($this->getDefaultOptions() as $key=>$value) {
			$this->addOption($key, $value);
		}
		
		return $this;
	}
	
	/**
	 * addOption()
	 * 
	 * @version 20120710
	 * @param string $key
	 * @param mixed $value
	 * @return FvCommunityNews_Options
	 */
	public function addOption($key, $value)
	{
		add_option($key, $value);
		$this->_options[ $key ] = $value;
		
		return $this;
	}
	
	/**
	 * updateOption()
	 *
	 * @version 20120716
	 * @param string $key
	 * @param mixed $value
	 * @return FvCommunityNews_Options
	 */
	public function updateOption($key, $value)
	{
		update_option($key, $value);
		$this->_options[ $key ] = $value;
		
		return $this;
	}
	
	/**
	 * getOption()
	 * 
	 * @version 20120716
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getOption($key, $default=null)
	{
		if (isset($this->_options[ $key ])) {
			return $this->_options[ $key ];
		}
		
		if (null === $default) {
			return $this->_options[ $key ] = get_option($key, $this->getDefaultOption($key));
		}
		
		return $this->_options[ $key ] = get_option($key, $default);
	}
	
	/**
	 * deleteOptions()
	 * 
	 * @version 20120710
	 * @return FvCommunityNews_Options
	 */
	public function deleteOptions()
	{
		foreach ($this->getDefaultOptions() as $key=>$value) {
			$this->deleteOption($key);
		}
		
		return $this;
	}
	
	/**
	 * deleteOption()
	 * 
	 * @version 20120710
	 * @param string $key
	 * @return FvCommunityNews_Options
	 */
	public function deleteOption($key)
	{
		delete_option($key);
		unset($this->_options[ $key ]);
		
		return $this;
	}
}



/**
 *	fvcn_get_default_options()
 *
 *	@version 20120710
 *	@return array
 */
function fvcn_get_default_options()
{
	return FvCommunityNews_Container::getInstance()->getOptions()->getDefaultOptions();
}

/**
 *	fvcn_get_default_option()
 *
 *	@version 20120710
 *	@param string $key
 *	@return mixed
 */
function fvcn_get_default_option($key)
{
	return FvCommunityNews_Container::getInstance()->getOptions()->getDefaultOption($key);
}

/**
 *	fvcn_get_option()
 *
 *	@version 20120710
 *	@param string $key
 *	@return mixed
 */
function fvcn_get_option($key)
{
	return FvCommunityNews_Container::getInstance()->getOptions()->getOption($key);
}

/**
 *	fvcn_add_options()
 *
 *	@version 20120710
 *	@return void
 */
function fvcn_add_options()
{
	FvCommunityNews_Container::getInstance()->getOptions()->addOptions();
	
	do_action('fvcn_add_options');
}

/**
 *	fvcn_delete_options()
 *
 *	@version 20120710
 *	@return void
 */
function fvcn_delete_options()
{
	FvCommunityNews_Container::getInstance()->getOptions()->deleteOptions();
	
	do_action('fvcn_delete_options');
}


/**
 *	fvcn_admin_moderation()
 *
 *	@version 20120524
 *	@uses fvcn_get_option()
 *	@uses apply_filters()
 *	@return bool
 */
function fvcn_admin_moderation()
{
	return apply_filters('fvcn_admin_moderation', (bool) fvcn_get_option('_fvcn_admin_moderation'));
}


/**
 *	fvcn_user_moderation()
 *
 *	@version 20120524
 *	@uses fvcn_get_option()
 *	@uses apply_filters()
 *	@return bool
 */
function fvcn_user_moderation()
{
	return apply_filters('fvcn_user_moderation', (bool) fvcn_get_option('_fvcn_user_moderation'));
}


/**
 *	fvcn_mail_on_submission()
 *
 *	@version 20120524
 *	@uses fvcn_get_option()
 *	@uses apply_filters()
 *	@return bool
 */
function fvcn_mail_on_submission()
{
	return apply_filters('fvcn_mail_on_submission', (bool) fvcn_get_option('_fvcn_mail_on_submission'));
}


/**
 *	fvcn_mail_on_moderation()
 *
 *	@version 20120524
 *	@uses fvcn_get_option()
 *	@uses apply_filters()
 *	@return bool
 */
function fvcn_mail_on_moderation()
{
	return apply_filters('fvcn_mail_on_moderation', (bool) fvcn_get_option('_fvcn_mail_on_moderation'));
}


/**
 *	fvcn_is_anonymous_allowed()
 *
 *	@version 20120524
 *	@uses fvcn_get_option()
 *	@uses apply_filters()
 *	@return bool
 */
function fvcn_is_anonymous_allowed()
{
	return apply_filters('fvcn_is_anonymous_allowed', (bool) fvcn_get_option('_fvcn_is_anonymous_allowed'));
}

