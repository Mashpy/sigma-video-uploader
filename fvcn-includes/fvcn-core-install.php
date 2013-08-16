<?php

/**
 * fvcn-core-install.php
 *
 * Installation
 *
 * @package		Sigma Video
 * @subpackage	Install
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * FvCommunityNews_Install
 *
 */
class FvCommunityNews_Install
{
	/**
	 * @var FvCommunityNews_Options
	 */
	protected $_options;
	
	/**
	 * __construct()
	 *
	 * @version 20120716
	 * @return void
	 */
	public function __construct()
	{
		$this->_options = FvCommunityNews_Container::getInstance()->getOptions();
	}
	
	/**
	 * isInstall()
	 *
	 * @version 20120716
	 * @return bool
	 */
	public function isInstall()
	{
		return (false === $this->_options->getOption('_fvcn_version', false));
	}
	
	/**
	 * isUpdate()
	 *
	 * @version 20120716
	 * @return bool
	 */
	public function isUpdate()
	{
		return (1 == version_compare($this->_options->getDefaultOption('_fvcn_version'), $this->_options->getOption('_fvcn_version')));
	}
	
	/**
	 * doInstall()
	 *
	 * @version 20120716
	 * @return FvCommunityNews_Install
	 */
	public function doInstall()
	{
		$this->addOptions()
			 ->registerSite();
		
		return $this;
	}
	
	/**
	 * doUpdate()
	 *
	 * @version 20120716
	 * @return FvCommunityNews_Install
	 */
	public function doUpdate()
	{
		$this->addOptions()
			 ->registerSite();
		
		$this->_options->updateOption('_fvcn_version', $this->_options->getDefaultOption('_fvcn_version'));
		
		return $this;
	}
	
	/**
	 * addOptions()
	 *
	 * @version 20120716
	 * @return FvCommunityNews_Install
	 */
	public function addOptions()
	{
		$this->_options->addOptions();
		return $this;
	}
	
	/**
	 * registerSite()
	 *
	 * @version 20120716
	 * @return FvCommunityNews_Install
	 */
	public function registerSite()
	{
		FvCommunityNews_Container::getInstance()->getSync()->registerSite();
		return $this;
	}
}


/**
 * fvcn_install()
 *
 * @version 20120716
 * @return FvCommunityNews_Install
 */
function fvcn_install()
{
	$install = new FvCommunityNews_Install();
	
	if ($install->isInstall()) {
		$install->doInstall();
	} elseif ($install->isUpdate()) {
		$install->doUpdate();
	}
	
	SigmaVideo::getInstance()
		->registerPostType()
		->registerPostStatuses()
		->registerTaxonomy();
	
	flush_rewrite_rules();
}

