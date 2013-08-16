<?php



/**

 *	form-post.php

 *

 *	The form for submitting Sigma Video.

 *

 *	@version	20120316

 *	@package	Sigma Video

 *	@subpackage	Theme

 */



?>



<?php if (fvcn_is_post_added()) : ?>



<?php do_action('fvcn_post_added_before'); ?>

<div class="fvcn-post-added">

	<?php if (fvcn_is_post_added_approved()) : ?>

		<p><?php _e('Your post has been added.', 'fvcn'); ?></p>

	<?php else : ?>

		<a href="http://six-sigma.tv/test/upload-video/"><p><?php _e('Your post has been added and is pending review. Click to add new VIDEO.', 'fvcn'); ?></p></a>

	<?php endif; ?>

</div>

<?php do_action('fvcn_post_added_after'); ?>



<?php else : ?>



<?php do_action('fvcn_post_form_before'); ?>

<?php if (!fvcn_is_anonymous()) : ?>

<div class="fvcn-post-form">

	<div class="fvcn-error">

		<?php fvcn_post_form_field_error('fvcn_post_form'); ?>

	</div>

	

	<form class="fvcn-post-form-new-post" method="post" action=""<?php if (fvcn_is_post_form_thumbnail_enabled()) : ?> enctype="multipart/form-data"<?php endif; ?>>

		<?php do_action('fvcn_post_form_extras_top'); ?>

		



			

			<?php do_action('fvcn_post_form_before_author_name'); ?>
			<div class="rules_regu" style="color: #C2291E; font-size: 16px; padding-top:5px;">
				<h4>1. Author details: </h4>
				</div>

			<div class="fvcn-post-form-author-name" style="padding-bottom: 25px;">
				
				<div class="author_name" style="float: left;">
				<label for="fvcn_post_form_author_name"><?php fvcn_post_form_author_name_label(); ?></label><br />
				</div>

				<div class="author_name_field" style="">
				<input type="text" name="fvcn_post_form_author_name" id="fvcn_post_form_author_name" value="<?php fvcn_post_form_author_name(); ?>" />
				</div>
				<div class="fvcn-error">


					<?php fvcn_post_form_field_error('fvcn_post_form_author_name'); ?>

				</div>

			</div>

			<?php do_action('fvcn_post_form_after_author_name'); ?>

			

			<?php do_action('fvcn_post_form_before_author_email'); ?>

			<div class="fvcn-post-form-author-email">

				<div class="author_email" style="float: left; padding-top: 20px;">
				<label for="fvcn_post_form_author_email"><?php fvcn_post_form_author_email_label(); ?></label><br />
				</div>
				<div class="author_email_field" style="padding:0px;">
				<input type="text" name="fvcn_post_form_author_email" id="fvcn_post_form_author_email" value="<?php fvcn_post_form_author_email(); ?>" />
				</div>
				<div class="fvcn-error">

					<?php fvcn_post_form_field_error('fvcn_post_form_author_email'); ?>

				</div>

			</div>
			<div class="rules_regu" style="color: #C2291E; font-size: 16px; padding-top:50px;">
				<h4>2: Upload Your Video File:</h4>
				</div>


			<?php do_action('fvcn_post_form_after_author_email'); ?>

			

	

			

			<div class="fvcn-post-form-author-logged-in">

				<?php printf(__('Currently logged in as <a href="%1$s">%2$s</a>. <a href="%3$s">Log out</a>', 'fvcn'), admin_url('profile.php'), fvcn_get_current_user_name(), wp_logout_url(apply_filters('the_permalink', get_permalink(home_url('/'))))); ?>

			</div>

			<div class="rules_regu" style="color: #C2291E; font-size: 16px;">
			<h4>1: Upload Your Video File:</h4>
			</div>


			

		<div id="uploader" style="width: 450px; height: 330px;">Your browser doesn't support upload.</div>

		

		
	<?php do_action('fvcn_post_form_before_link'); ?>
		<div class="fvcn-post-form-link">
			<div class="rules_regu" style="color: #C2291E; font-size: 16px;">
			<h4>3. Provide YouTube Url:</h4>
			</div>
			<label for="fvcn_post_form_link"><?php fvcn_post_form_link_label(); ?></label><br />

			<input type="text" name="fvcn_post_form_link" id="fvcn_post_form_link" value="<?php fvcn_post_form_link(); ?>" />

			<div class="fvcn-error">

				<?php fvcn_post_form_field_error('fvcn_post_form_link'); ?>

			</div>

		</div>

		<?php do_action('fvcn_post_form_after_link'); ?>


		<?php do_action('fvcn_post_form_before_title'); ?>

		<div class="fvcn-post-form-title">
			<div class="rules_regu" style="color: #C2291E; font-size: 16px;">
			<h4>4. Video Description Details:</h4>
			</div>

			<label for="fvcn_post_form_title"><?php fvcn_post_form_title_label(); ?></label><br />

			<input type="text" name="fvcn_post_form_title" id="fvcn_post_form_title" value="<?php fvcn_post_form_title(); ?>" />

			<div class="fvcn-error">

				<?php fvcn_post_form_field_error('fvcn_post_form_title'); ?>

			</div>

		</div>

		<?php do_action('fvcn_post_form_after_title'); ?>


	

	

		<?php do_action('fvcn_post_form_before_content'); ?>

		<div class="fvcn-post-form-content">

			<label for="fvcn_post_form_content"><?php fvcn_post_form_content_label(); ?></label><br />

			<textarea name="fvcn_post_form_content" id="fvcn_post_form_content" rows="3"><?php fvcn_post_form_content(); ?></textarea>

			<div class="fvcn-error">

				<?php fvcn_post_form_field_error('fvcn_post_form_content'); ?>

			</div>

		</div>

		<?php do_action('fvcn_post_form_after_content'); ?>

		
	
		<?php do_action('fvcn_post_form_before_tags'); ?>

		<div class="fvcn-post-form-tags">

			<label for="fvcn_post_form_tags"><?php fvcn_post_form_tags_label(); ?></label><br />

			<input type="text" name="fvcn_post_form_tags" id="fvcn_post_form_tags" value="<?php fvcn_post_form_tags(); ?>" />

			<div class="fvcn-error">

				<?php fvcn_post_form_field_error('fvcn_post_form_tags'); ?>

			</div>

		</div>

		<?php do_action('fvcn_post_form_after_tags'); ?>


		<?php if (fvcn_is_post_form_thumbnail_enabled()) : ?>

			

			<?php do_action('fvcn_post_form_before_thumbnail'); ?>

			<div class="fvcn-post-form-thumbnail">

				<label for="fvcn_post_form_thumbnail"><?php fvcn_post_form_thumbnail_label(); ?></label><br />

				<input type="file" name="fvcn_post_form_thumbnail" id="fvcn_post_form_thumbnail" value="" />

			<div class="fvcn-error">

				<?php fvcn_post_form_field_error('fvcn_post_form_thumbnail'); ?>

			</div>

			</div>

			<?php do_action('fvcn_post_form_after_thumbnail'); ?>

			

		<?php endif; ?>
		<?php do_action('fvcn_post_form_before_submit'); ?>

		<div class="fvcn-post-form-submit">
			<div class="submit_rule" style="font-size: 13px; margin-bottom: -18px; margin-top: 20px; font-weight: normal;"><p>By clicking the submit button, you acknowledge you have read & agree to the upload rules below.</p>
			</div>
			<input type="submit" name="fvcn_post_form_submit" id="fvcn_post_form_submit" value="<?php _e('Submit', 'fvcn'); ?>" />
			</div>

		<?php do_action('fvcn_post_form_after_submit'); ?>



		<?php fvcn_post_form_fields(); ?>

		

		<?php do_action('fvcn_post_form_extras_bottom'); ?>

	</form>

</div>



<?php do_action('fvcn_post_form_after'); ?>

	<?php else : ?>
	<p>Please Login to upload videos.</p>
	<?php endif; ?>
<?php endif; ?>


