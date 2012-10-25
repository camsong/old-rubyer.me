<?php get_header(); ?>

<div class="post" id="post-<?php the_ID(); ?>">
	<h2 class="title">
		<?php
			if (is_search()) {
				_e('Search Results', 'blocks2');
			} else {
				_e('Archives', 'blocks2');
			}
		?>
	</h2>
	<div class="meta">
		<div class="info">
<?php
// If this is a search
if (is_search()) {
	printf( __('Keyword: &#8216;%1$s&#8217;', 'blocks2'), wp_specialchars($s, 1) );
// If this is a category archive
} elseif (is_category()) {
	printf( __('Archive for the &#8216;%1$s&#8217; Category', 'blocks2'), single_cat_title('', false) );
// If this is a tag archive
} elseif (is_tag()) {
	printf( __('Posts Tagged &#8216;%1$s&#8217;', 'blocks2'), single_tag_title('', false) );
// If this is a daily archive
} elseif (is_day()) {
	printf( __('Archive for %1$s', 'blocks2'), get_the_time(__('F jS, Y', 'blocks2')) );
// If this is a monthly archive
} elseif (is_month()) {
	printf( __('Archive for %1$s', 'blocks2'), get_the_time(__('F, Y', 'blocks2')) );
// If this is a yearly archive
} elseif (is_year()) {
	printf( __('Archive for %1$s', 'blocks2'), get_the_time(__('Y', 'blocks2')) );
// If this is an author archive
} elseif (is_author()) {
	printf( __('Archive by %1$s', 'blocks2'), get_the_author() );
// If this is a paged archive
} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
	_e('Blog Archives', 'blocks2');
}
?>
		</div>
		<div class="fixed"></div>
	</div>

	<div class="content">

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<div class="boxcaption">
				<h3><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
			</div>
			<div class="box">
				<div class="excerpt">
					<?php the_excerpt(); ?>
				</div>
				<div class="small">
					<?php
						if (function_exists('the_views')) the_views(true, '', ' | ');
						comments_popup_link(__('No comments', 'blocks2'), __('1 comment', 'blocks2'), __('% comments', 'blocks2'));
						edit_post_link(__('Edit', 'blocks2'), ' | ', '');
					?>
				</div>
				<div class="small">
					<?php
						the_time(__('M jS, Y', 'blocks2'));
						printf(__(' | Filed under %1$s', 'blocks2'), get_the_category_list(', '));
					?>
				</div>
				<div class="small"><?php the_tags(__('Tags: ', 'blocks2'), ', ', ''); ?></div>
			</div>
		<?php endwhile; ?>
	<?php else : ?>		<div class="errorbox">			<?php _e('Sorry, no posts matched your criteria.', 'blocks2'); ?>		</div>
	<?php endif; ?>
	</div>
</div>

<?php comments_template(); ?>

<div id="pagenavi">
	<?php if(function_exists('wp_pagenavi')) : ?>
		<?php wp_pagenavi() ?>
	<?php else : ?>
		<span class="alignleft floatleft"><?php previous_posts_link(__('&laquo; Newer Entries', 'blocks2')); ?></span>
		<span class="alignright floatright"><?php next_posts_link(__('Older Entries &raquo;', 'blocks2')); ?></span>
	<?php endif; ?>
	<div class="fixed"></div>
</div>

<?php get_footer(); ?>
