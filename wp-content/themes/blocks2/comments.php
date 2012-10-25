<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/comment.js"></script>

<?php
	// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
		die (__('Please do not load this page directly. Thanks!', 'blocks2'));
	}

	$options = get_option('blocks2_options');
?>

<?php if ( !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
	<div class="errorbox">
		<?php _e('Enter your password to view comments.', 'blocks2'); ?>
	</div>
<?php return; endif; ?>

<?php if ($comments) : ?>
	<ol class="commentlist">
		<?php
			// for WordPress 2.7 or higher
			if (function_exists('wp_list_comments')) {
				wp_list_comments('callback=custom_comments');
			// for WordPress 2.6.3 or lower
			} else {
				foreach ($comments as $comment) {
					custom_comments($comment, null, null);
					echo '</li>';
				}
			}
		?>
	</ol>

<?php
	if (get_option('page_comments')) {
		$comment_pages = paginate_comments_links('echo=0');
		if ($comment_pages) {
?>
		<div id="commentnavi">
			<span class="pages"><?php _e('Comment pages', 'blocks2'); ?></span>
			<div id="commentpager">
				<?php echo $comment_pages; ?>
			</div>
			<div class="fixed"></div>
		</div>
<?php
		}
	}
?>

<?php elseif (comments_open()) : // If there are no comments yet. ?>
	<div class="messagebox">
		<?php _e('No comments yet.', 'blocks2'); ?>
	</div>

<?php endif; ?>

<?php if (!comments_open()) : // If comments are closed. ?>
	<div class="messagebox">
		<?php _e('Comments are closed.', 'blocks2'); ?>
	</div>

<?php elseif ( get_option('comment_registration') && !$user_ID ) : // If registration required and not logged in. ?>
	<div class="messagebox">
		<?php
			if (function_exists('wp_login_url')) {
				$login_link = wp_login_url();
			} else {
				$login_link = get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink());
			}
		?>
		<?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'blocks2'), $login_link); ?>
	</div>

<?php else : ?>
	<div id="respond">
	<form id="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">

		<?php if (function_exists('wp_list_comments')) : ?>
			<?php cancel_comment_reply_link(__('Cancel reply', 'blocks2')) ?>
		<?php endif; ?>

		<!-- comment info -->
		<?php if ( $user_ID ) : ?>
			<?php
				if (function_exists('wp_logout_url')) {
					$logout_link = wp_logout_url();
				} else {
					$logout_link = get_option('siteurl') . '/wp-login.php?action=logout';
				}
			?>
			<div class="row"><?php _e('Logged in as', 'blocks2'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><strong><?php echo $user_identity; ?></strong></a>. <a href="<?php echo $logout_link; ?>" title="<?php _e('Log out of this account', 'blocks2'); ?>"><?php _e('Logout &raquo;', 'blocks2'); ?></a></div>

		<?php else : ?>
			<?php if ( $comment_author != "" ) : ?>
				<div class="row">
					<?php printf(__('Welcome back <strong>%s</strong>.', 'blocks2'), $comment_author) ?>
					<span id="show_author_info"><a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('author_info','');MGJS.setStyleDisplay('show_author_info','none');MGJS.setStyleDisplay('hide_author_info','');"><?php _e('Change &raquo;'); ?></a></span>
					<span id="hide_author_info"><a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('author_info','none');MGJS.setStyleDisplay('show_author_info','');MGJS.setStyleDisplay('hide_author_info','none');"><?php _e('Close &raquo;'); ?></a></span>
				</div>
			<?php endif; ?>

			<div id="author_info">
				<div class="row">
					<input type="text" name="author" id="author" class="textfield" value="<?php echo $comment_author; ?>" size="24" tabindex="1" />
					<label for="author" class="small"><?php _e('Name', 'blocks2'); ?> <?php if ($req) _e('(required)', 'blocks2'); ?></label>
				</div>
				<div class="row">
					<input type="text" name="email" id="email" class="textfield" value="<?php echo $comment_author_email; ?>" size="24" tabindex="2" />
					<label for="email" class="small"><?php _e('E-Mail (will not be published)', 'blocks2');?> <?php if ($req) _e('(required)', 'blocks2'); ?></label>
				</div>
				<div class="row">
					<input type="text" name="url" id="url" class="textfield" value="<?php echo $comment_author_url; ?>" size="24" tabindex="3" />
					<label for="url" class="small"><?php _e('Website', 'blocks2'); ?></label>
				</div>
			</div>

			<?php if ( $comment_author != "" ) : ?>
				<script type="text/javascript">MGJS.setStyleDisplay('hide_author_info','none');MGJS.setStyleDisplay('author_info','none');</script>
			<?php endif; ?>

		<?php endif; ?>

	<!-- comment input -->
	<div class="row">
		<textarea name="comment" id="comment" tabindex="4" rows="8" cols="50"></textarea>
	</div>

	<!-- comment submit and rss -->
	<div id="submitbox">
		<a class="feed" href="feed:<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Subscribe to comments feed', 'blocks2'); ?></a>
		<input name="submit" type="submit" id="submit" class="button" tabindex="5" value="<?php _e('Submit Comment', 'blocks2'); ?>" />
		<?php if (function_exists('highslide_emoticons')) : ?>
			<div id="emoticon"><?php highslide_emoticons(); ?></div>
		<?php endif; ?>
		<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
		<div class="fixed"></div>
	</div>

	<?php if (function_exists('wp_list_comments')) : ?>
		<?php comment_id_fields(); ?>
	<?php endif; ?>

	<?php do_action('comment_form', $post->ID); ?>

	</form>
	</div>

	<?php if ($options['ctrlentry']) : ?>
		<script type="text/javascript">MGJS.loadCommentShortcut();</script>
	<?php endif; ?>

<?php endif; ?>
