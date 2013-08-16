<?php

/**
 *	feedback-no-anonymous.php
 *
 *	The message that will be displayed when an author has to be
 *		logged in, in order to post.
 *
 *	@version	20120317
 *	@package	Sigma Video
 *	@subpackage	Theme
 */

?>

<div class="fvcn-post-form-no-anonymous-message">
	<?php do_action('fvcn_theme_before_no_anonymous_message'); ?>
	
	<p><?php _e('You have to be logged in in order to post.', 'fvcn'); ?></p>
	
	<?php do_action('fvcn_theme_after_no_anonymous_message'); ?>
</div>
