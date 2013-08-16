<?php

/**
 *	content-single-post.php
 *
 *	Displays a single posts content'
 *
 *	@version	20120716
 *	@package	Sigma Video
 *	@subpackage	Theme
 */

?>

<div class="entry-content fvcn-post-content">
	<?php if (fvcn_has_post_thumbnail()) : ?>
		<div class="fvcn-post-thumbnail">
			<?php fvcn_post_thumbnail(0, array(110, 110)); ?>
		</div>
	<?php endif; ?>
	
	<?php fvcn_post_content(); ?>
	
	<?php if (fvcn_has_post_link()) : ?>
		<div class="fvcn-post-link">
			<p><?php printf(__('Read full article: %s', 'fvcn'),
				'<a href="' . fvcn_get_post_link() . '">' . fvcn_get_post_title() . '</a>'); ?></p>
		</div>
	<?php endif; ?>
</div><!-- .entry-content -->

<footer class="fvcn-post-meta">
	<span class="fvcn-post-tags">
		<?php fvcn_post_tag_list(0, array('before'=>__('Tags: ', 'fvcn'), 'after'=>'')); ?>
	</span>
	|
	<span class="fvcn-post-rating">
		<?php if (fvcn_is_post_rated_by_current_user()) : ?>
			<?php _e('Rating:', 'fvcn'); ?>
			<strong><?php fvcn_post_rating(); ?></strong>
		<?php else : ?>
			<?php _e('Rate this post:', 'fvcn'); ?>
			<a href="<?php fvcn_post_rating_decrement_link(); ?>" rel="nofollow">-</a>
			<strong><?php fvcn_post_rating(); ?></strong>
			<a href="<?php fvcn_post_rating_increment_link(); ?>" rel="nofollow">+</a>
		<?php endif; ?>
	</span>
</footer><!-- .entry-meta -->
