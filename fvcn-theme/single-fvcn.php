<?php

/**
 *	The Template for displaying a single community post.
 *
 *	@version	20120716
 *	@package	Sigma Video
 *	@subpackage	Theme
 */

get_header(); ?>

<div id="primary">
	<div id="content" role="main">
		
		<?php while (have_posts()) : the_post(); ?>
			
			<nav id="nav-single">
				<h3 class="assistive-text"><?php _e('Post navigation', 'twentyeleven'); ?></h3>
				<span class="nav-previous"><?php previous_post_link('%link', __('<span class="meta-nav">&larr;</span> Previous', 'twentyeleven')); ?></span>
				<span class="nav-next"><?php next_post_link('%link', __('Next <span class="meta-nav">&rarr;</span>', 'twentyeleven')); ?></span>
			</nav><!-- #nav-single -->
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php fvcn_post_title(); ?></h1>
					
					<div class="entry-meta">
						<span class="sep"><?php _e('Posted on', 'fvcn'); ?></span>
						<a href="<?php fvcn_post_permalink(); ?>" rel="bookmark"><time class="entry-date"><?php fvcn_post_date(); ?></time></a>
						<span class="by-author">
							<span class="sep"><?php _e('by', 'fvcn'); ?></span>
							<span class="author vcard">
								<?php fvcn_post_author_link(); ?>
							</span>
						</span>
					</div><!-- .entry-meta -->
				</header><!-- .entry-header -->
				
				<?php fvcn_get_template_part('fvcn/content', 'single-post'); ?>
				
				<div class="entry-meta">
					<?php edit_post_link(__('Edit', 'twentyeleven'), '<span class="edit-link">', '</span>'); ?>
				</div>
			</article><!-- #post-<?php the_ID(); ?> -->
			
			
			<?php comments_template('', true); ?>
			
		<?php endwhile; // end of the loop. ?>
		
	</div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>