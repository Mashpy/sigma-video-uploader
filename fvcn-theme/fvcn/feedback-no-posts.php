<?php

/**
 *	feedback-no-posts.php
 *
 *	The message that will be displayed when there are no posts.
 *
 *	@version	20120318
 *	@package	Sigma Video
 *	@subpackage	Theme
 */

?>

<div class="fvcn-post-form-no-posts-message">
	<?php do_action('fvcn_theme_before_no_posts_message'); ?>
	
	<p><?php _e('There are no posts yet. Be the first to add one!', 'fvcn'); ?></p>
	
	<?php do_action('fvcn_theme_after_no_posts_message'); ?>
</div>
