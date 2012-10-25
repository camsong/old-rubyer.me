<?php $options = get_option('blocks2_options'); ?>
	
<!-- sidebar START -->
<div id="sidebar">

	<!-- showcase -->
	<?php if( $options['showcase_content'] && (
		($options['showcase_registered'] && $user_ID) || 
		($options['showcase_commentator'] && !$user_ID && isset($_COOKIE['comment_author_'.COOKIEHASH])) || 
		($options['showcase_visitor'] && !$user_ID && !isset($_COOKIE['comment_author_'.COOKIEHASH]))
	) ) : ?>
		<div class="widget">
			<?php if($options['showcase_caption']) : ?>
				<h3><?php if($options['showcase_title']){echo($options['showcase_title']);}else{_e('Showcase', 'blocks2');} ?></h3>
			<?php endif; ?>
			<div class="itembox">
				<?php echo($options['showcase_content']); ?>
			</div>
		</div>
	<?php endif; ?>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

	<!-- recent posts -->
	<div class="widget widget_pages">
		<h3>Recent Posts</h3>
		<ul>
			<?php $posts = get_posts('numberposts=5&orderby=post_date'); foreach($posts as $post) : setup_postdata($post); ?>
			<li><a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; $post = $posts[0]; ?>
		</ul>
	</div>

	<!-- recent comments -->
	<?php if( function_exists('wp_recentcomments') ) : ?>
		<div class="widget">
			<h3>Recent Comments</h3>
			<ul>
				<?php wp_recentcomments('limit=5&length=15&pingback=false&trackback=false&post=false&avatar_position=right'); ?>
			</ul>
		</div>
	<?php endif; ?>

	<!-- tag cloud -->
	<div class="widget widget_tag_cloud">
		<h3>Tag Cloud</h3>
		<?php wp_tag_cloud('smallest=8&largest=16'); ?>
	</div>

	<!-- categories -->
	<div class="widget widget_categories">
		<h3>Categories</h3>
		<ul>
			<?php wp_list_cats('sort_column=name&optioncount=1'); ?>
		</ul>
	</div>

	<!-- archives -->
	<div id="archives" class="widget widget_archive">
		<h3>Archives</h3>
		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</div>

	<!-- blogroll -->
	<div class="widget widget_links">
		<h3>Blogroll</h3>
		<ul>
			<?php wp_list_bookmarks('title_li=&categorize=0'); ?>
		</ul>
	</div>

<?php endif; ?>

<!-- showcase 2 -->
<?php if( $options['showcase_2_content'] && (
	($options['showcase_2_registered'] && $user_ID) || 
	($options['showcase_2_commentator'] && !$user_ID && isset($_COOKIE['comment_author_'.COOKIEHASH])) || 
	($options['showcase_2_visitor'] && !$user_ID && !isset($_COOKIE['comment_author_'.COOKIEHASH]))
) ) : ?>
	<div class="widget">
		<?php if($options['showcase_2_caption']) : ?>
			<h3><?php if($options['showcase_2_title']){echo($options['showcase_2_title']);}else{_e('Showcase', 'blocks2');} ?></h3>
		<?php endif; ?>
		<div class="itembox">
			<?php echo($options['showcase_2_content']); ?>
		</div>
	</div>
<?php endif; ?>

</div>
<!-- sidebar END -->
