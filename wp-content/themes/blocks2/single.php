<?php get_header(); ?>
<?php $options = get_option('blocks2_options'); ?>

<?php if (have_posts()) : the_post(); ?>
<div class="post" id="post-<?php the_ID(); ?>">
	<h2 class="title"><?php the_title(); ?></h2>
	<div class="meta">
		<div class="info">
			<?php the_time(__('M jS, Y', 'blocks2')) ?>
			<?php if ($options['author']) : ?>
				 | <?php _e('Posted by ', 'blocks2'); the_author_posts_link(); ?>
			<?php endif; ?>
			<?php if ($options['categories']) : ?>
				 | <?php _e('Filed under ', 'blocks2'); the_category(', '); ?>
			<?php endif; ?>
		</div>
		<div class="comments">
			<a href="#respond"><?php _e('Leave a comment', 'blocks2'); ?></a>
			<?php if(pings_open()) : ?>
			 | <a href="<?php trackback_url(); ?>" rel="trackback"><?php _e('Trackback', 'blocks2'); ?></a>
			<?php endif; ?>
			<?php edit_post_link(__('Edit', 'blocks2'), ' | ', ''); ?>
		</div>
		<div class="fixed"></div>
	</div>

	<div class="content">
		<?php
			the_content();
			// add bookmarks
			if (function_exists('wp_addbookmarks')) {
				wp_addbookmarks();
			}
		?>
		<div class="fixed"></div>
		<?php if ($options['tags']) : ?>
			<div class="tags"><?php _e('Tags: ', 'blocks2'); the_tags('', ', ', ''); ?></div>
		<?php endif; ?>
	</div>

<div style="padding: 5px 10px 5px 15px;
margin-bottom: 10px;background: #F6F9E7;color:green;">
&gt;&gt;原创文章，欢迎转载。转载请注明：转载自<a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?>" target="_blank"><?php bloginfo('name'); ?></a>，谢谢！<br/>
&gt;&gt;原文链接地址：<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" target="_blank"><?php the_title(); ?></a><br/>
</div>

</div>

<?php else : ?>
	<div class="errorbox">
		<?php _e('Sorry, no posts matched your criteria.', 'blocks2'); ?>
	</div>
<?php endif; ?>

<!-- related posts -->
<?php
	if(function_exists('wp23_related_posts')) {
		echo '<div id="related_posts">';
		wp23_related_posts();
		echo '</div>';
		echo '<div class="fixed"></div>';
	}
?>

<div id="postnavi">
	<span class="alignleft floatleft"><?php next_post_link('%link') ?></span>
	<span class="alignright floatright"><?php previous_post_link('%link') ?></span>
	<div class="fixed"></div>
</div>

<?php
	if (function_exists('wp_list_comments')) {
		comments_template('', true);
	} else {
		comments_template();
	}
?>

<?php get_footer(); ?>
