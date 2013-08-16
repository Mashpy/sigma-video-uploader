<?php

/**
 * fvcn-settings.php
 *
 * Settings
 *
 * @package	Sigma Video
 * @subpackage	Admin
 * @author		Frank Verhoeven
 */

if (!defined('ABSPATH')) {
	die('Direct access is not allowed!');
}


/**
 * fvcn_register_admin_settings()
 *
 * @version 20120524
 * @uses add_settings_section()
 * @uses add_settings_field()
 * @uses register_setting()
 * @uses do_action()
 * @return void
 */
function fvcn_register_admin_settings()
{
	// General
	add_settings_section('fvcn_settings_general', __('General Settings', 'fvcn'), 'fvcn_admin_settings_callback_general_section', 'fvcn-settings');
	
	add_settings_field('_fvcn_moderation', __('Before a post appears&hellip;', 'fvcn'), 'fvcn_admin_settings_callback_moderation', 'fvcn-settings', 'fvcn_settings_general');
	register_setting('fvcn-settings', '_fvcn_admin_moderation', 'intval');
	register_setting('fvcn-settings', '_fvcn_user_moderation', 'intval');
	
	add_settings_field('_fvcn_mail', __('Send a notification mail&hellip;', 'fvcn'), 'fvcn_admin_settings_callback_mail', 'fvcn-settings', 'fvcn_settings_general');
	register_setting('fvcn-settings', '_fvcn_mail_on_submission', 'intval');
	register_setting('fvcn-settings', '_fvcn_mail_on_moderation', 'intval');
	
	add_settings_field('_fvcn_is_anonymous_allowed', __('Authentication', 'fvcn'), 'fvcn_admin_settings_callback_is_anonymous_allowed', 'fvcn-settings', 'fvcn_settings_general');
	register_setting('fvcn-settings', '_fvcn_is_anonymous_allowed', 'intval');
	
	
	add_settings_section('fvcn_settings_permalinks', __('Permalinks', 'fvcn'), 'fvcn_admin_settings_callback_permalinks_section', 'fvcn-settings');
	
	add_settings_field('_fvcn_base_slug', __('Base Slug', 'fvcn'), 'fvcn_admin_settings_callback_post_base_slug', 'fvcn-settings', 'fvcn_settings_permalinks');
	register_setting('fvcn-settings', '_fvcn_base_slug', 'esc_sql');
	
	add_settings_field('_fvcn_post_slug', __('Post Slug', 'fvcn'), 'fvcn_admin_settings_callback_post_slug', 'fvcn-settings', 'fvcn_settings_permalinks');
	register_setting('fvcn-settings', '_fvcn_post_slug', 'esc_sql');
	
	add_settings_field('_fvcn_post_tag_slug', __('Tag Slug', 'fvcn'), 'fvcn_admin_settings_callback_post_tag_slug', 'fvcn-settings', 'fvcn_settings_permalinks');
	register_setting('fvcn-settings', '_fvcn_post_tag_slug', 'esc_sql');
	
	add_settings_field('_fvcn_post_archive_slug', __('Archive Slug', 'fvcn'), 'fvcn_admin_settings_callback_post_archive_slug', 'fvcn-settings', 'fvcn_settings_permalinks');
	register_setting('fvcn-settings', '_fvcn_post_archive_slug', 'esc_sql');
	
	
	do_action('fvcn_register_admin_settings');
}


/**
 * fvcn_admin_settings()
 *
 * @version 20120324
 * @uses screen_icon()
 * @return void
 */
function fvcn_admin_settings()
{
	flush_rewrite_rules();
?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e('Sigma Video Settings', 'fvcn'); ?></h2>
		
		<form action="options.php" method="post">
			<?php settings_fields('fvcn-settings'); ?>
			
			<?php do_settings_sections('fvcn-settings'); ?>
			
			<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes', 'fvcn'); ?>" />
			</p>
		</form>
	</div>
<?php
}


/**
 * fvcn_admin_settings_callback_general_section()
 *
 * @version 20120322
 * @return void
 */
function fvcn_admin_settings_callback_general_section()
{
?>
	<!--<p><?php _e('General plugin settings.', 'fvcn'); ?></p>-->
<?php
}


/**
 * fvcn_admin_settings_callback_moderation()
 *
 * @version 20120322
 * @return void
 */
function fvcn_admin_settings_callback_moderation()
{
?>
	
	<input type="checkbox" name="_fvcn_admin_moderation" id="_fvcn_admin_moderation" value="1" <?php checked( fvcn_admin_moderation() ); ?> />
	<label for="_fvcn_admin_moderation"><?php _e('an administrator must always approve the post.', 'fvcn'); ?></label>
	<br />
	<input type="checkbox" name="_fvcn_user_moderation" id="_fvcn_user_moderation" value="1" <?php checked( fvcn_user_moderation() ); ?> />
	<label for="_fvcn_user_moderation"><?php _e('the user must have a previously approved post (authentication required).', 'fvcn'); ?></label>
	
<?php
}


/**
 * fvcn_admin_settings_callback_mail()
 *
 * @version 20120322
 * @return void
 */
function fvcn_admin_settings_callback_mail()
{
?>
	
	<input type="checkbox" name="_fvcn_mail_on_submission" id="_fvcn_mail_on_submission" value="1" <?php checked( fvcn_mail_on_submission() ); ?> />
	<label for="_fvcn_mail_on_submission"><?php _e('when a post is submitted.', 'fvcn'); ?></label>
	<br />
	<input type="checkbox" name="_fvcn_mail_on_moderation" id="_fvcn_mail_on_moderation" value="1" <?php checked( fvcn_mail_on_moderation() ); ?> />
	<label for="_fvcn_mail_on_moderation"><?php _e('when a post is held for moderation.', 'fvcn'); ?></label>
	
<?php
}


/**
 * fvcn_admin_settings_callback_is_anonymous_allowed()
 *
 * @version 20120322
 * @return void
 */
function fvcn_admin_settings_callback_is_anonymous_allowed()
{
?>
	
	<input type="checkbox" name="_fvcn_is_anonymous_allowed" id="_fvcn_is_anonymous_allowed" value="1" <?php checked( fvcn_is_anonymous_allowed() ); ?> />
	<label for="_fvcn_is_anonymous_allowed"><?php _e('Anyone can add an article.', 'fvcn'); ?></label>
	
<?php
}


/**
 * fvcn_admin_settings_callback_permalinks_section()
 *
 * @version 20120322
 * @return void
 */
function fvcn_admin_settings_callback_permalinks_section()
{
?>
	<p><?php printf(__('Here you can set the <a href="%s">permalink</a> structure bases.', 'fvcn'), get_admin_url(null, 'options-permalink.php')); ?></p>
<?php
}


/**
 * fvcn_admin_settings_callback_post_base_slug()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_settings_callback_post_base_slug()
{
?>
	
	<input type="text" name="_fvcn_base_slug" id="_fvcn_base_slug" value="<?php fvcn_form_option('_fvcn_base_slug', true); ?>" class="reqular-text" />
	
<?php
}


/**
 * fvcn_admin_settings_callback_post_slug()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_settings_callback_post_slug()
{
?>
	
	<input type="text" name="_fvcn_post_slug" id="_fvcn_post_slug" value="<?php fvcn_form_option('_fvcn_post_slug', true); ?>" class="reqular-text" />
	
<?php
}


/**
 * fvcn_admin_settings_callback_post_tag_slug()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_settings_callback_post_tag_slug()
{
?>
	
	<input type="text" name="_fvcn_post_tag_slug" id="_fvcn_post_tag_slug" value="<?php fvcn_form_option('_fvcn_post_tag_slug', true); ?>" class="reqular-text" />
	
<?php
}


/**
 * fvcn_admin_settings_callback_post_archive_slug()
 *
 * @version 20120524
 * @return void
 */
function fvcn_admin_settings_callback_post_archive_slug()
{
?>
	
	<input type="text" name="_fvcn_post_archive_slug" id="_fvcn_post_archive_slug" value="<?php fvcn_form_option('_fvcn_post_archive_slug', true); ?>" class="reqular-text" />
	
<?php
}

