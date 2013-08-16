<?php

/**
 * fvcn-core-hooks.php
 *
 * Common Functions
 *
 * @package		Sigma Video
 * @subpackage	Hooks
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * Hook the plugin to wordpress.
 */
add_action('plugins_loaded',		'fvcn_loaded',						10 	);
add_action('init',					'fvcn_init',						10 	);
add_action('widgets_init',			'fvcn_widgets_init',				10  );
add_action('wp_enqueue_scripts',	'fvcn_enqueue_scripts',				10  );
add_action('after_setup_theme',		'fvcn_add_thumbnail_theme_support',	999 );
add_filter('template_include',		'fvcn_template_include',			10  );


/**
 * fvcn_init
 */
add_action('fvcn_init', 			'fvcn_load_textdomain',				2   );
add_action('fvcn_init', 			'fvcn_register_post_type',			10  );
add_action('fvcn_init',				'fvcn_register_post_statuses',		12  );
add_action('fvcn_init', 			'fvcn_register_taxonomy',			14  );
add_action('fvcn_init',				'fvcn_register_shortcodes',			16  );
add_action('fvcn_init',				'fvcn_javascript',					18	);
add_action('fvcn_init', 			'fvcn_ready',						999 );


add_action('fvcn_ready',			'fvcn_akismet'							);


/**
 * fvcn_widgets_init
 */
add_action('fvcn_widgets_init',	array('FvCommunityNews_Widget_ListPosts',	'register_widget'),	10  );
add_action('fvcn_widgets_init', array('FvCommunityNews_Widget_Form',		'register_widget'),	10  );
add_action('fvcn_widgets_init', array('FvCommunityNews_Widget_TagCloud',	'register_widget'),	10  );

add_action('wp_head',				'fvcn_head'								);

add_action('fvcn_enqueue_scripts',	'fvcn_theme_enqueue_css',			10  );


/**
 * fvcn_(de)activation
 */
add_action('fvcn_activation',		'fvcn_install'							);
add_action('fvcn_deactivation',		'flush_rewrite_rules'					);
add_action('fvcn_uninstall',		'fvcn_delete_options'					);


/**
 * fvcn_sync
 */
add_action('fvcn_insert_post',				'fvcn_sync_submit_post',				999	);
add_action('fvcn_publish_post',				'fvcn_sync_submit_post',				999	);
add_action('fvcn_increase_post_view_count',	'fvcn_sync_increase_post_view_count',	999 );
add_action('fvcn_post_rating_increase',		'fvcn_sync_increase_post_rating',		999 );
add_action('fvcn_post_rating_decrease',		'fvcn_sync_decrease_post_rating',		999 );


add_action('template_redirect',		'fvcn_new_post_handler'					);
add_action('template_redirect',		'fvcn_post_rating_handler'				);


add_action('fvcn_insert_post',		'fvcn_send_notification_mail',		999	);


add_filter('single_template',		'fvcn_increase_post_view_count'			);

	
add_filter('fvcn_get_form_option',	'stripslashes'							);


/**
 * fvcn_template_include
 */
add_filter('fvcn_template_include',	'fvcn_theme_compat_template_include',	4,	2 );


/**
 * fvcn_get_post_form
 */
add_filter('fvcn_get_post_form_author_name',			'stripslashes'				);
add_filter('fvcn_get_post_form_author_email',			'stripslashes'				);
add_filter('fvcn_get_post_form_title',					'stripslashes'				);
add_filter('fvcn_get_post_form_link',					'stripslashes'				);
add_filter('fvcn_get_post_form_content',				'stripslashes'				);
add_filter('fvcn_get_post_form_tags',					'stripslashes'				);


/**
 * fvcn_new_post_filters
 */
add_filter('fvcn_new_post_data_pre_insert',				'fvcn_filter_new_post_data'	);
add_filter('fvcn_new_post_meta_pre_insert',				'fvcn_filter_new_post_data'	);


add_filter('fvcn_new_post_pre_anonymous_author_name',	'sanitize_text_field',	10  );
add_filter('fvcn_new_post_pre_anonymous_author_name',	'_wp_specialchars',		30  );

add_filter('fvcn_new_post_pre_anonymous_author_email',	'trim',					10  );
add_filter('fvcn_new_post_pre_anonymous_author_email',	'sanitize_email',		10  );
add_filter('fvcn_new_post_pre_anonymous_author_email',	'wp_filter_kses',		10  );

add_filter('fvcn_new_post_pre_post_title',				'trim',					10  );
add_filter('fvcn_new_post_pre_post_title',				'wp_strip_all_tags',	10  );
add_filter('fvcn_new_post_pre_post_title',				'wp_filter_kses',		10  );

add_filter('fvcn_new_post_pre_post_url',				'trim',					10  );
add_filter('fvcn_new_post_pre_post_url',				'wp_strip_all_tags',	10  );
add_filter('fvcn_new_post_pre_post_url',				'esc_url_raw',			10  );
add_filter('fvcn_new_post_pre_post_url',				'wp_filter_kses',		10  );

add_filter('fvcn_new_post_pre_post_content',			'trim',					10  );
add_filter('fvcn_new_post_pre_post_content',			'balanceTags',			10  );
add_filter('fvcn_new_post_pre_post_content',			'wp_rel_nofollow',		10  );
add_filter('fvcn_new_post_pre_post_content',			'wp_filter_kses',		10  );


/**
 * fvcn_get_post
 */
add_filter('fvcn_get_post_author_link',			'wp_rel_nofollow'			);
add_filter('fvcn_get_post_author_link',			'stripslashes'				);

add_filter('fvcn_get_post_content',				'capital_P_dangit'			);
add_filter('fvcn_get_post_content',				'wptexturize',			3   );
add_filter('fvcn_get_post_content',				'convert_chars',		5   );
add_filter('fvcn_get_post_content',				'make_clickable',		9   );
add_filter('fvcn_get_post_content',				'force_balance_tags',	25  );
add_filter('fvcn_get_post_content',				'convert_smilies',		20  );
add_filter('fvcn_get_post_content',				'wpautop',				30  );


add_filter('wp_insert_post_data',				'fvcn_fix_post_author',	30,	2);


/**
 * fvcn_admin
 */
if (is_admin()) {
	add_action('fvcn_init', 'fvcn_admin');
}


/**
 * fvcn_activation()
 *
 * @version 20120229
 * @return void
 */
function fvcn_activation()
{
	register_uninstall_hook(__FILE__, 'fvcn_uninstall');
	do_action('fvcn_activation');
}

/**
 * fvcn_deactivation()
 *
 * @version 20120229
 * @return void
 */
function fvcn_deactivation()
{
	do_action('fvcn_deactivation');
}

/**
 * fvcn_uninstall()
 *
 * @version 20120229
 * @return void
 */
function fvcn_uninstall()
{
	do_action('fvcn_uninstall');
}

/**
 * fvcn_loaded()
 *
 * @version 20120229
 * @return void
 */
function fvcn_loaded()
{
	do_action('fvcn_loaded');
}

/**
 * fvcn_init()
 *
 * @version 20120229
 * @return void
 */
function fvcn_init()
{
	do_action('fvcn_init');
}

/**
 * fvcn_widgets_init()
 *
 * @version 20120305
 * @return void
 */
function fvcn_widgets_init()
{
	do_action('fvcn_widgets_init');
}

/**
 * fvcn_load_textdomain()
 *
 * @version 20120229
 * @return void
 */
function fvcn_load_textdomain()
{
	do_action('fvcn_load_textdomain');
}

/**
 * fvcn_register_post_type()
 *
 * @version 20120229
 * @return void
 */
function fvcn_register_post_type()
{
	do_action('fvcn_register_post_type');
}

/**
 * fvcn_register_post_statuses()
 *
 * @version 20120308
 * @return void
 */
function fvcn_register_post_statuses()
{
	do_action('fvcn_register_post_statuses');
}

/**
 * fvcn_register_taxonomy()
 *
 * @version 20120229
 * @return void
 */
function fvcn_register_taxonomy()
{
	do_action('fvcn_register_taxonomy');
}

/**
 * fvcn_enqueue_scripts()
 *
 * @version 20120314
 * @return void
 */
function fvcn_enqueue_scripts()
{
	do_action('fvcn_enqueue_scripts');
}

/**
 * fvcn_ready()
 *
 * @version 20120229
 * @return void
 */
function fvcn_ready()
{
	do_action('fvcn_ready');
}

/**
 * fvcn_template_include()
 *
 * @version 20120319
 * @param string $template
 * @return string
 */
function fvcn_template_include($template='')
{
	return apply_filters('fvcn_template_include', $template);
}

