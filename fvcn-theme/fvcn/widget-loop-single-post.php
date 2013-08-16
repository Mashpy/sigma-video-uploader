<?php

/**
 *	widget-loop-single-post.php
 *
 *	@version	20120716
 *	@package	Sigma Video
 *	@subpackage	Theme
 */

?>

<li id="fvcn-post-<?php fvcn_post_id(); ?>" class="fvcn-post">
	<header>
		<h4><a href="<?php fvcn_post_permalink(); ?>"><?php fvcn_post_title(); ?></a></h4>
	</header>
	
	<div class="fvcn-post-content">
		<?php if (fvcn_has_post_thumbnail() && fvcn_show_widget_thumbnail()) : ?>
			<div class="fvcn-post-thumbnail">
				<?php fvcn_post_thumbnail(0, array(50, 50)); ?>
			</div>
		<?php endif; ?>
		
		<?php fvcn_post_excerpt(); ?>
	</div>
</li><!-- #fvcn-post-<?php fvcn_post_id(); ?> -->
