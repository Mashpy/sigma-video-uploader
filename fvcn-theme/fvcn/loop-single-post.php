<?php

/**
 *	loop-single-post.php
 *
 *	@version	20120716
 *	@package	Sigma Video
 *	@subpackage	Theme
 */

?>

<article id="fvcn-post-<?php fvcn_post_id(); ?>" class="fvcn-post">
	<header>
		<h2><a href="<?php fvcn_post_permalink(); ?>"><?php fvcn_post_title(); ?></a></h2>
	</header>
	
	<div class="entry-content fvcn-post-content">
		<?php if (fvcn_has_post_thumbnail()) : ?>
			<div class="fvcn-post-thumbnail">
				<?php fvcn_post_thumbnail(0, array(100, 100)); ?>
			</div>
		<?php endif; ?>
		
		<?php fvcn_post_content(); ?>
		
		<?php if (fvcn_has_post_link()) : ?>
			<div class="fvcn-post-link">
				<p><?php printf(__('Read article: %s', 'fvcn'),
					'<a href="' . fvcn_get_post_link() . '">' . fvcn_get_post_title() . '</a>'); ?></p>
			</div>
		<?php endif; ?>
	</div>
	
	<footer class="entry-meta fvcn-post-meta">
		<div class="fvcn-post-tags">
			<?php fvcn_post_tag_list(); ?>
		</div>
	</footer>
</article><!-- #fvcn-post-<?php fvcn_post_id(); ?> -->
