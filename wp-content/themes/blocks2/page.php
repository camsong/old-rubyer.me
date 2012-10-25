<?php get_header(); ?>

<div class="post" id="post-<?php the_ID(); ?>">
	<h2 class="title"><?php the_title(); ?></h2>
	<div class="meta">
		<div class="info"><?php _e('Update: ', 'blocks2'); ?><?php the_modified_time(__('M jS, Y', 'blocks2')); ?></div>
		<div class="comments"><?php edit_post_link(__('Edit', 'blocks2'), '', ''); ?></div>
		<div class="fixed"></div>
	</div>

	<div class="content">
		<?php the_content(); ?>
		<div class="fixed"></div>
	</div>
</div>


<?php
	if (function_exists('wp_list_comments')) {
		comments_template('', true);
	} else {
		comments_template();
	}
?>

<?php get_footer(); ?>