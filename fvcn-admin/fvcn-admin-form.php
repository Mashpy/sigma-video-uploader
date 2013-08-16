<?php

/**
 * fvcn-admin-form.php
 *
 * Form Options
 *
 * @package		Sigma Video
 * @subpackage	Admin Form Options
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * fvcn_register_admin_form()
 *
 * @version 20120403
 * @return void
 */
function fvcn_register_admin_form()
{
	// Author Name
	add_settings_section('fvcn_form_author_name', __('Author Name', 'fvcn'), 'fvcn_admin_form_callback_author_name_section', 'fvcn-form');
	
	add_settings_field('_fvcn_post_form_author_name_label', __('Label', 'fvcn'), 'fvcn_admin_form_callback_author_name_label', 'fvcn-form', 'fvcn_form_author_name');
	register_setting('fvcn-form', '_fvcn_post_form_author_name_label', 'esc_sql');
	
	
	// Author Email
	add_settings_section('fvcn_form_author_email', __('Author Email', 'fvcn'), 'fvcn_admin_form_callback_author_email_section', 'fvcn-form');
	
	add_settings_field('_fvcn_post_form_author_email_label', __('Label', 'fvcn'), 'fvcn_admin_form_callback_author_email_label', 'fvcn-form', 'fvcn_form_author_email');
	register_setting('fvcn-form', '_fvcn_post_form_author_email_label', 'esc_sql');
	
	
	// Title
	add_settings_section('fvcn_form_title', __('Title', 'fvcn'), 'fvcn_admin_form_callback_title_section', 'fvcn-form');
	
	add_settings_field('_fvcn_post_form_title_label', __('Label', 'fvcn'), 'fvcn_admin_form_callback_title_label', 'fvcn-form', 'fvcn_form_title');
	register_setting('fvcn-form', '_fvcn_post_form_title_label', 'esc_sql');
	
	
	// Link
	add_settings_section('fvcn_form_link', __('Link', 'fvcn'), 'fvcn_admin_form_callback_link_section', 'fvcn-form');
	
	add_settings_field('_fvcn_post_form_link_label', __('Label', 'fvcn'), 'fvcn_admin_form_callback_link_label', 'fvcn-form', 'fvcn_form_link');
	register_setting('fvcn-form', '_fvcn_post_form_link_label', 'esc_sql');
	
	add_settings_field('_fvcn_post_form_link_required', __('Required', 'fvcn'), 'fvcn_admin_form_callback_link_required', 'fvcn-form', 'fvcn_form_link');
	register_setting('fvcn-form', '_fvcn_post_form_link_required', 'intval');
	
	
	// Content
	add_settings_section('fvcn_form_content', __('Content', 'fvcn'), 'fvcn_admin_form_callback_content_section', 'fvcn-form');
	
	add_settings_field('_fvcn_post_form_content_label', __('Label', 'fvcn'), 'fvcn_admin_form_callback_content_label', 'fvcn-form', 'fvcn_form_content');
	register_setting('fvcn-form', '_fvcn_post_form_content_label', 'esc_sql');
	
	
	// Tags
	add_settings_section('fvcn_form_tags', __('Tags', 'fvcn'), 'fvcn_admin_form_callback_tags_section', 'fvcn-form');
	
	add_settings_field('_fvcn_post_form_tags_label', __('Label', 'fvcn'), 'fvcn_admin_form_callback_tags_label', 'fvcn-form', 'fvcn_form_tags');
	register_setting('fvcn-form', '_fvcn_post_form_tags_label', 'esc_sql');
	
	add_settings_field('_fvcn_post_form_tags_required', __('Required', 'fvcn'), 'fvcn_admin_form_callback_tags_required', 'fvcn-form', 'fvcn_form_tags');
	register_setting('fvcn-form', '_fvcn_post_form_tags_required', 'intval');
	
	
	// Thumbnail
	add_settings_section('fvcn_form_thumbnail', __('Thumbnail', 'fvcn'), 'fvcn_admin_form_callback_thumbnail_section', 'fvcn-form');
	
	add_settings_field('_fvcn_post_form_thumbnail_enabled', __('Enabled', 'fvcn'), 'fvcn_admin_form_callback_thumbnail_enabled', 'fvcn-form', 'fvcn_form_thumbnail');
	register_setting('fvcn-form', '_fvcn_post_form_thumbnail_enabled', 'intval');
	
	
	add_settings_field('_fvcn_post_form_thumbnail_label', __('Label', 'fvcn'), 'fvcn_admin_form_callback_thumbnail_label', 'fvcn-form', 'fvcn_form_thumbnail');
	register_setting('fvcn-form', '_fvcn_post_form_thumbnail_label', 'esc_sql');
	
	add_settings_field('_fvcn_post_form_thumbnail_required', __('Required', 'fvcn'), 'fvcn_admin_form_callback_thumbnail_required', 'fvcn-form', 'fvcn_form_thumbnail');
	register_setting('fvcn-form', '_fvcn_post_form_thumbnail_required', 'intval');
	
	
	do_action('fvcn_register_admin_form_settings');
}


/**
 * fvcn_admin_form()
 *
 * @version 20120301
 * @uses screen_icon()
 * @uses settings_fields()
 * @uses do_settings_sections()
 * @return void
 */
function fvcn_admin_form()
{
?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e('Sigma Video Form', 'fvcn'); ?></h2>
		
		<form action="options.php" method="post">
			<?php settings_fields('fvcn-form'); ?>
			
			<?php do_settings_sections('fvcn-form'); ?>
			
			<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes', 'fvcn'); ?>" />
			</p>
		</form>
	</div>
<?php
}


/**
 * fvcn_admin_form_help()
 *
 * @version 20120728
 * @return void
 */
function fvcn_admin_form_help()
{
	$screen = get_current_screen();
	
	// Pre 3.3
	if (!method_exists($screen, 'add_help_tab')) {
		return;
	}
	
	$content  = '<p>' . __('This screen provides access to the form configuration.', 'fvcn') . '</p>';
	$content .= '<ul><li>' . __('Change the label of a form field, this is the value displayed above the field. Note that it is <em>not</em> possible to use any html.', 'fvcn') . '</li>';
	$content .= '<li>' . __('Make a field required or optional.', 'fvcn') . '</li></ul>';
	$content .= '<p>' . __('Remember to save your settings when you are done!', 'fvcn') . '</p>';
	
	$screen->add_help_tab( array(
		'id'		=> 'fvcn-admin-form-help',
		'title'		=> __('Overview', 'fvcn'),
		'content'	=> $content
	) );
}


/**
 * fvcn_admin_form_callback_author_section()
 *
 * @version 20120302
 * @return void
 */
function fvcn_admin_form_callback_author_name_section()
{
?>
	
	<p><?php _e('Author name field settings.', 'fvcn'); ?></p>
	
<?php
}

/**
 * fvcn_admin_form_callback_author_label()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_form_callback_author_name_label()
{
?>
	
	<input type="text" name="_fvcn_post_form_author_name_label" id="_fvcn_post_form_author_name_label" value="<?php fvcn_form_option('_fvcn_post_form_author_name_label'); ?>" class="reqular-text" />
	
<?php
}


/**
 * fvcn_admin_form_callback_author_section()
 *
 * @version 20120302
 * @return void
 */
function fvcn_admin_form_callback_author_email_section()
{
?>
	
	<p><?php _e('Author email field settings.', 'fvcn'); ?></p>
	
<?php
}

/**
 * fvcn_admin_form_callback_author_label()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_form_callback_author_email_label()
{
?>
	
	<input type="text" name="_fvcn_post_form_author_email_label" id="_fvcn_post_form_author_email_label" value="<?php fvcn_form_option('_fvcn_post_form_author_email_label'); ?>" class="reqular-text" />
	
<?php
}


/**
 * fvcn_admin_form_callback_title_section()
 *
 * @version 20120302
 * @return void
 */
function fvcn_admin_form_callback_title_section()
{
?>
	
	<p><?php _e('Title field settings.', 'fvcn'); ?></p>
	
<?php
}

/**
 * fvcn_admin_form_callback_title_label()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_form_callback_title_label()
{
?>
	
	<input type="text" name="_fvcn_post_form_title_label" id="_fvcn_post_form_title_label" value="<?php fvcn_form_option('_fvcn_post_form_title_label'); ?>" class="reqular-text" />
	
<?php
}


/**
 * fvcn_admin_form_callback_link_section()
 *
 * @version 20120302
 * @return void
 */
function fvcn_admin_form_callback_link_section()
{
?>
	
	<p><?php _e('Link field settings.', 'fvcn'); ?></p>
	
<?php
}

/**
 * fvcn_admin_form_callback_link_label()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_form_callback_link_label()
{
?>
	
	<input type="text" name="_fvcn_post_form_link_label" id="_fvcn_post_form_link_label" value="<?php fvcn_form_option('_fvcn_post_form_link_label'); ?>" class="reqular-text" />
	
<?php
}

/**
 * fvcn_admin_form_callback_link_required()
 *
 * @version 20120302
 * @return void
 */
function fvcn_admin_form_callback_link_required()
{
?>
	
	<input type="checkbox" name="_fvcn_post_form_link_required" id="_fvcn_post_form_link_required" value="1" <?php checked( get_option('_fvcn_post_form_link_required', true) ); ?> />
	<label for="_fvcn_post_form_link_required"><?php _e('Make the link field a required field.', 'fvcn'); ?></label>
	
<?php
}


/**
 * fvcn_admin_form_callback_content_section()
 *
 * @version 20120302
 * @return void
 */
function fvcn_admin_form_callback_content_section()
{
?>
	
	<p><?php _e('Content field settings.', 'fvcn'); ?></p>
	
<?php
}

/**
 * fvcn_admin_form_callback_content_label()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_form_callback_content_label()
{
?>
	
	<input type="text" name="_fvcn_post_form_content_label" id="_fvcn_post_form_content_label" value="<?php fvcn_form_option('_fvcn_post_form_content_label'); ?>" class="reqular-text" />
	
<?php
}


/**
 * fvcn_admin_form_callback_tags_section()
 *
 * @version 20120302
 * @return void
 */
function fvcn_admin_form_callback_tags_section()
{
?>
	
	<p><?php _e('Tags field settings.', 'fvcn'); ?></p>
	
<?php
}

/**
 * fvcn_admin_form_callback_tags_label()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_form_callback_tags_label()
{
?>
	
	<input type="text" name="_fvcn_post_form_tags_label" id="_fvcn_post_form_tags_label" value="<?php fvcn_form_option('_fvcn_post_form_tags_label'); ?>" class="reqular-text" />
	
<?php
}

/**
 * fvcn_admin_form_callback_tags_required()
 *
 * @version 20120302
 * @return void
 */
function fvcn_admin_form_callback_tags_required()
{
?>
	
	<input type="checkbox" name="_fvcn_post_form_tags_required" id="_fvcn_post_form_tags_required" value="1" <?php checked( get_option('_fvcn_post_form_tags_required', true) ); ?> />
	<label for="_fvcn_post_form_tags_required"><?php _e('Make the tags field a required field.', 'fvcn'); ?></label>
	
<?php
}


/**
 * fvcn_admin_form_callback_thumbnail_section()
 *
 * @version 20120306
 * @return void
 */
function fvcn_admin_form_callback_thumbnail_section()
{
?>
	
	<p><?php _e('Thumbnail field settings.', 'fvcn'); ?></p>
	
<?php
}


/**
 * fvcn_admin_form_callback_thumbnail_enabled()
 *
 * @version 20120306
 * @return void
 */
function fvcn_admin_form_callback_thumbnail_enabled()
{
?>
	
	<input type="checkbox" name="_fvcn_post_form_thumbnail_enabled" id="_fvcn_post_form_thumbnail_enabled" value="1" <?php checked( get_option('_fvcn_post_form_thumbnail_enabled', true) ); ?> />
	<label for="_fvcn_post_form_thumbnail_enabled"><?php _e('Enable the thumbnail field.', 'fvcn'); ?></label>
	
<?php
}
/**
 * fvcn_admin_form_callback_thumbnail_label()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_form_callback_thumbnail_label()
{
?>
	
	<input type="text" name="_fvcn_post_form_thumbnail_label" id="_fvcn_post_form_thumbnail_label" value="<?php fvcn_form_option('_fvcn_post_form_thumbnail_label'); ?>" class="reqular-text" />
	
<?php
}

/**
 * fvcn_admin_form_callback_thumbnail_required()
 *
 * @version 20120306
 * @return void
 */
function fvcn_admin_form_callback_thumbnail_required()
{
?>
	
	<input type="checkbox" name="_fvcn_post_form_thumbnail_required" id="_fvcn_post_form_thumbnail_required" value="1" <?php checked( get_option('_fvcn_post_form_thumbnail_required', false) ); ?> />
	<label for="_fvcn_post_form_thumbnail_required"><?php _e('Make the thumbnail field a required field.', 'fvcn'); ?></label>
	
<?php
}

