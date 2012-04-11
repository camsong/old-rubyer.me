<?php get_header(); ?>
<?php $options = get_option('blocks2_options'); ?>
<?php if ($options['notice'] && $options['notice_content']) : ?>	<div class="
	<?php if($options['notice_color'] == 1) {echo 'box';}
		else if($options['notice_color'] == 3){echo 'errorbox';}
		else{echo 'messagebox';}
	?> normalfont">		<?php echo($options['notice_content']); ?>	</div><?php endif; ?><?php if (have_posts()) : ?>	<?php while (have_posts()) : the_post(); update_post_caches($posts); ?>
<div class="post" id="post-<?php the_ID(); ?>">
	<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
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
			<?php
				if (function_exists('the_views')) the_views(true, '', ' | ');
				comments_popup_link(__('No comments', 'blocks2'), __('1 comment', 'blocks2'), __('% comments', 'blocks2'));
				edit_post_link(__('Edit', 'blocks2'), ' | ', '');
			?>
		</div>
		<div class="fixed"></div>
	</div>

	<div class="content">
		<?php the_content(__('Read more...', 'blocks2')); ?>
		<div class="fixed"></div>
		<?php if ($options['tags']) : ?>
			<div class="tags"><?php _e('Tags: ', 'blocks2'); the_tags('', ', ', ''); ?></div>
		<?php endif; ?>
	</div>
</div>
	<?php endwhile; ?>
<?php else : ?>	<div class="errorbox">		<?php _e('Sorry, no posts matched your criteria.', 'blocks2'); ?>	</div><?php endif; ?>
<div id="pagenavi">	<?php if(function_exists('wp_pagenavi')) : ?>		<?php wp_pagenavi() ?>	<?php else : ?>
		<span class="alignleft floatleft"><?php previous_posts_link(__('&laquo; Newer Entries', 'blocks2')); ?></span>		<span class="alignright floatright"><?php next_posts_link(__('Older Entries &raquo;', 'blocks2')); ?></span>
	<?php endif; ?>
	<div class="fixed"></div></div>

<?php get_footer(); ?>
