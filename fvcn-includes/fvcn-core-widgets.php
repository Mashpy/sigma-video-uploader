<?php

/**
 *	fvcn-core-widgets.php
 *
 *	Widgets
 *
 *	@package	Sigma Video
 *	@subpackage	Widgets
 *	@author		Frank Verhoeven
 */

if (!defined('ABSPATH')) {
	die('Direct access is not allowed!');
}

/**
 *	FvCommunityNews_Widget_ListPosts
 *
 *	@version 20120305
 *	@uses WP_Widget
 */
class FvCommunityNews_Widget_ListPosts extends WP_Widget {
	
	/**
	 *	register_widget()
	 *
	 *	@version 20120305
	 *	@uses register_widget()
	 *	@return void
	 */
	public function register_widget() {
		register_widget('FvCommunityNews_Widget_ListPosts');
	}
	
	/**
	 *	FvCommunityNews_Widget_ListPosts()
	 *
	 *	@version 20120305
	 *	@uses apply_filters()
	 *	@uses $this->WP_Widget()
	 *	@return void
	 */
	public function FvCommunityNews_Widget_ListPosts() {
		$options = apply_filters('fvcn_list_posts_widget_options', array(
			'classname'		=> 'fvcn_list_posts_widget',
			'description'	=> __('A list of the most recent Sigma Video.', 'fvcn')
		));
		
		$this->WP_Widget(false, __('Sigma Video Posts', 'fvcn'), $options);
	}
	
	/**
	 *	widget()
	 *
	 *	@version 20120710
	 *	@param mixed $args
	 *	@param array $instance
	 *	@return void
	 */
	public function widget($args, $instance)
	{
		extract($args);
		
		$title		= apply_filters('fvcn_list_posts_widget_title', $instance['title']);
		$num_posts	= !empty( $instance['num_posts'] ) ? $instance['num_posts'] : '5';
		
		$registry = FvCommunityNews_Registry::getInstance();
		$registry->widgetShowThumbnail	= !empty( $instance['thumbnail'] ) ? true : false;
		$registry->widgetShowViewAll	= !empty( $instance['view_all']  ) ? true : false;
		
		$options = array(
			'posts_per_page'	=> $num_posts
		);
		
		if (fvcn_has_posts($options)) {
			
			echo $before_widget;
			echo $before_title . $title . $after_title;
			
			fvcn_get_template_part('fvcn/widget', 'loop-posts');
			
			echo $after_widget;
		}
	}
	
	/**
	 *	update()
	 *
	 *	@version 20120305
	 *	@uses 
	 *	@param array $new_instance
	 *	@param array $old_instance
	 *	@return array
	 */
	public function update($new_instance, $old_instance) {
		$instance					= $old_instance;
		$instance['title']			= strip_tags($new_instance['title']);
		$instance['thumbnail']		= strip_tags($new_instance['thumbnail']);
		$instance['view_all']		= strip_tags($new_instance['view_all']);
		$instance['num_posts']		= $new_instance['num_posts'];
		
		if (empty($instance['num_posts']) || !is_numeric($instance['num_posts'])) {
			$instance['num_posts'] = 5;
		} else {
			$instance['num_posts'] = abs($instance['num_posts']);
		}
		
		return $instance;
	}
	
	/**
	 *	form()
	 *
	 *	@version 20120305
	 *	@uses 
	 *	@param array $instance
	 *	@return void
	 */
	public function form($instance) {
		$title		= !empty($instance['title'])	? esc_attr($instance['title'])		: 'Sigma Video';
		$num_posts	= !empty($instance['num_posts'])? esc_attr($instance['num_posts'])	: '5';
		$thumbnail	= !empty($instance['thumbnail'])? esc_attr($instance['thumbnail'])	: '';
		$view_all	= !empty($instance['view_all'])	? esc_attr($instance['view_all'])	: '';
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'fvcn'); ?>
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('num_posts'); ?>"><?php _e('Number of posts to show:', 'fvcn'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" value="<?php echo $num_posts; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('thumbnail'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('thumbnail'); ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" <?php checked('on', $thumbnail); ?> />
				<?php _e('Show thumbnails', 'fvcn'); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('view_all'); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id('view_all'); ?>" name="<?php echo $this->get_field_name('view_all'); ?>" <?php checked('on', $view_all); ?> />
				<?php _e('Show "view all" link', 'fvcn'); ?>
			</label>
		</p>
		
		<?php
	}
	
}



/**
 *	FvCommunityNews_Widget_Form
 *
 *	@version 20120306
 *	@uses WP_Widget
 */
class FvCommunityNews_Widget_Form extends WP_Widget {
	
	/**
	 *	register_widget()
	 *
	 *	@version 20120306
	 *	@uses register_widget()
	 *	@return void
	 */
	public function register_widget() {
		register_widget('FvCommunityNews_Widget_Form');
	}
	
	/**
	 *	FvCommunityNews_Widget_Form()
	 *
	 *	@version 20120306
	 *	@uses apply_filters()
	 *	@uses $this->WP_Widget()
	 *	@return void
	 */
	public function FvCommunityNews_Widget_Form() {
		$options = apply_filters('fvcn_form_widget_options', array(
			'classname'		=> 'fvcn_form_widget',
			'description'	=> __('A form to add Sigma Video.', 'fvcn')
		));
		
		$this->WP_Widget(false, __('Video uploader Form', 'fvcn'), $options);
	}
	
	/**
	 *	widget()
	 *
	 *	@version 20120306
	 *	@uses 
	 *	@param mixed $args
	 *	@param array $instance
	 *	@return void
	 */
	public function widget($args, $instance) {
		extract($args);
		
		$title = apply_filters('fvcn_form_widget_title', $instance['title']);
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		?>
		
		<?php if (fvcn_is_anonymous_allowed() || !fvcn_is_anonymous()) : ?>
			
			<?php fvcn_get_template_part('fvcn/form', 'post'); ?>
			
		<?php else : ?>
			
			<?php fvcn_get_template_part('fvcn/feedback', 'no-anonymous'); ?>
			
		<?php endif; ?>
		
		<?php
		echo $after_widget;
	}
	
	/**
	 *	update()
	 *
	 *	@version 20120306
	 *	@uses 
	 *	@param array $new_instance
	 *	@param array $old_instance
	 *	@return array
	 */
	public function update($new_instance, $old_instance) {
		$instance			= $old_instance;
		$instance['title']	= strip_tags($new_instance['title']);
		
		return $instance;
	}
	
	/**
	 *	form()
	 *
	 *	@version 20120306
	 *	@uses 
	 *	@param array $instance
	 *	@return void
	 */
	public function form($instance) {
		$title = !empty($instance['title']) ? esc_attr($instance['title']) : 'Add Sigma Video';
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'fvcn'); ?>
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" />
			</label>
		</p>
		
		<?php
	}
	
}



/**
 *	FvCommunityNews_Widget_TagCloud
 *
 *	@version 20120411
 *	@uses WP_Widget
 */
class FvCommunityNews_Widget_TagCloud extends WP_Widget {
	
	/**
	 *	register_widget()
	 *
	 *	@version 20120411
	 *	@uses register_widget()
	 *	@return void
	 */
	public function register_widget() {
		register_widget('FvCommunityNews_Widget_TagCloud');
	}
	
	/**
	 *	FvCommunityNews_Widget_TagCloud()
	 *
	 *	@version 20120411
	 *	@uses apply_filters()
	 *	@uses $this->WP_Widget()
	 *	@return void
	 */
	public function FvCommunityNews_Widget_TagCloud() {
		$options = apply_filters('fvcn_form_widget_options', array(
			'classname'		=> 'fvcn_tag_cloud',
			'description'	=> __('A tag cloud with tags from Sigma Video.', 'fvcn')
		));
		
		$this->WP_Widget(false, __('Sigma Video Tag Cloud', 'fvcn'), $options);
	}
	
	/**
	 *	widget()
	 *
	 *	@version 20120411
	 *	@uses 
	 *	@param mixed $args
	 *	@param array $instance
	 *	@return void
	 */
	public function widget($args, $instance) {
		extract($args);
		
		$title = apply_filters('fvcn_form_widget_title', $instance['title']);
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		?>
		<div class="fvcn-tag-cloud">
			<?php fvcn_tag_cloud(); ?>
		</div>
		<?php
		echo $after_widget;
	}
	
	/**
	 *	update()
	 *
	 *	@version 20120411
	 *	@uses 
	 *	@param array $new_instance
	 *	@param array $old_instance
	 *	@return array
	 */
	public function update($new_instance, $old_instance) {
		$instance			= $old_instance;
		$instance['title']	= strip_tags($new_instance['title']);
		
		return $instance;
	}
	
	/**
	 *	form()
	 *
	 *	@version 20120411
	 *	@uses 
	 *	@param array $instance
	 *	@return void
	 */
	public function form($instance) {
		$title = !empty($instance['title']) ? esc_attr($instance['title']) : 'Sigma Video Tags';
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'fvcn'); ?>
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" />
			</label>
		</p>
		
		<?php
	}
	
}
