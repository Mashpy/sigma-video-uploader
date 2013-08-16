<?php

/**
 *	loop-posts.php
 *
 *	@version	20120716
 *	@package	Sigma Video
 *	@subpackage	Theme
 */

?>

<?php while (fvcn_posts()) : fvcn_the_post(); ?>
	
	<?php fvcn_get_template_part('fvcn/loop', 'single-post'); ?>
	
<?php endwhile; ?>
