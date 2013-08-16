<?php

/**
 *	fvcn-common-template.php
 *
 *	Common Template
 *
 *	@package	Sigma Video
 *	@subpackage	Template
 *	@author		Frank Verhoeven
 */

if (!defined('ABSPATH')) {
	die('Direct access is not allowed!');
}


/**
 *	fvcn_version()
 *
 *	@version 20120322
 *	@uses fvcn_get_version()
 *	@return void
 */
function fvcn_version()
{
	echo fvcn_get_version();
}
	/**
	 *	fvcn_get_version()
	 *
	 *	@version 20120711
	 *	@return string
	 */
	function fvcn_get_version()
	{
		return fvcn_get_option('_fvcn_version');
	}

/**
 *	fvcn_head()
 *
 *	@version 20120314
 *	@uses do_action()
 *	@return void
 */
function fvcn_head()
{
	echo '<meta name="generator" content="Sigma Video" />' . "\n";
	
	do_action('fvcn_head');
	
}

/**
 *	fvcn_template_notices()
 *
 *	@version 20120713
 *	@uses fvcn_has_errors()
 *	@uses $fvcn->errors
 *	@return void
 */
function fvcn_template_notices()
{
	if (!fvcn_has_errors()) {
		return;
	}
	
	$errors = $messages = array();
	
	foreach (FvCommunityNews_Container::getInstance()->getWpError()->get_error_codes() as $code) {
		$severity = FvCommunityNews_Container::getInstance()->getWpError()->get_error_data($code);
		
		foreach (FvCommunityNews_Container::getInstance()->getWpError()->get_error_messages($code) as $error) {
			if ('message' == $severity) {
				$messages[] = $error;
			} else {
				$errors[] = $error;
			}
		}
	}
	
	if (!empty($errors)) : ?>
		
		<div class="fvcn-template-notice error">
			<span>
				<?php echo implode("</span><br />\n<span>", $errors); ?>
			</span>
		</div>
		
	<?php else : ?>
		
		<div class="fvcn-template-notice">
			<span>
				<?php echo implode("</span><br />\n<span>", $messages); ?>
			</span>
		</div>
		
	<?php endif;
}

/**
 *	is_FvCommunityNews::getInstance()
 *
 *	@version 20120622
 *	@return bool
 */
function is_fvcn()
{
	if (fvcn_is_single_post()) {
		return true;
	}
	if (fvcn_is_post_archive()) {
		return true;
	}
	if (fvcn_is_post_tag_archive()) {
		return true;
	}
	
	return false;
}

/**
 *	fvcn_show_widget_thumbnail()
 *
 *	@version 20120710
 *	@return bool
 */
function fvcn_show_widget_thumbnail()
{
	return FvCommunityNews_Registry::getInstance()->widgetShowThumbnail;
}

/**
 *	fvcn_show_widget_view_all()
 *
 *	@version 20120710
 *	@return bool
 */
function fvcn_show_widget_view_all()
{
	return FvCommunityNews_Registry::getInstance()->widgetShowViewAll;
}

