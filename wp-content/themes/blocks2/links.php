<?php
/*
Template Name: Links
*/
?>

<?php
	get_header();
	$linkcats = $wpdb->get_results("SELECT T1.name AS name FROM $wpdb->terms T1, $wpdb->term_taxonomy T2 WHERE T1.term_id = T2.term_id AND T2.taxonomy = 'link_category'");
?>
<?php if (have_posts()) : the_post(); ?>
<div class="post" id="post-<?php the_ID(); ?>">
	<h2 class="title"><?php the_title(); ?></h2>
	<div class="meta">
		<div class="info"><?php _e('Update: ', 'blocks2'); ?><?php the_modified_time(__('M jS, Y', 'blocks2')); ?></div>
		<div class="comments">
			<?php if ( $user_ID ) : ?>
				<a href="<?php echo get_settings('siteurl'); ?>/wp-admin/link-add.php"><?php _e('Add link', 'blocks'); ?></a> | 
				<a href="<?php echo get_settings('siteurl'); ?>/wp-admin/link-manager.php"><?php _e('Edit links', 'blocks'); ?></a> | 
			<?php endif; ?>
			<?php edit_post_link(__('Edit', 'blocks2'), '', ''); ?>
		</div>
		<div class="fixed"></div>
	</div>

	<div class="content">
		<?php if($linkcats) : foreach($linkcats as $linkcat) : ?>
			<div class="boxcaption"><h3><?php echo $linkcat->name; ?></h3></div>
			<div class="box linkcat">
				<ul>
					<?php
						$bookmarks = get_bookmarks('orderby=rand&category_name=' . $linkcat->name);
						if ( !empty($bookmarks) ) {
							foreach ($bookmarks as $bookmark) {
								echo '<li><a href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '">' . $bookmark->link_name . '</a></li>';
							}
						}
					?>
				</ul>
				<div class="fixed"></div>
			</div>
		<?php endforeach; endif; ?>

		<?php the_content(); ?>
		<div class="fixed"></div>
	</div>
</div>
<?php else : ?>	<div class="errorbox">		<?php _e('Sorry, no posts matched your criteria.', 'blocks2'); ?>	</div><?php endif; ?>

<?php
	// Support comments for WordPress 2.7 or higher
	if (function_exists('wp_list_comments')) {
		comments_template('', true);
	} else {
		comments_template();
	}
?>

<?php get_footer(); ?>
