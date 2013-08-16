<?php

/**
 *	widget-loop-posts.php
 *
 *	@version	20120716
 *	@package	Sigma Video
 *	@subpackage	Theme
 */

?>

<ul class="fvcn-list-posts-widget">
	
	<?php while (fvcn_posts()) : fvcn_the_post(); ?>
		
		<?php fvcn_get_template_part('fvcn/widget', 'loop-single-post'); ?>
		
	<?php endwhile; ?>
	
</ul>

<?php if (fvcn_show_widget_view_all()) : ?>
	<p class="fvcn-view-all">
		<a href="<?php fvcn_post_archive_link(); ?>">View All</a>
	</p>
<?php endif; ?>
