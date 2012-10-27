<?php

/** blocks2 options */
class Blocks2Options {

	function getOptions() {
		$options = get_option('blocks2_options');
		if (!is_array($options)) {
			$options['google_cse'] = false;
			$options['google_cse_cx'] = '';
			$options['menu_type'] = 'pages';
			$options['notice'] = false;
			$options['notice_content'] = '';
			$options['notice_color'] = 2;
			$options['showcase_registered'] = false;
			$options['showcase_commentator'] = false;
			$options['showcase_visitor'] = false;
			$options['showcase_caption'] = true;
			$options['showcase_title'] = '';
			$options['showcase_content'] = '';
			$options['showcase_2_registered'] = false;
			$options['showcase_2_commentator'] = false;
			$options['showcase_2_visitor'] = false;
			$options['showcase_2_caption'] = true;
			$options['showcase_2_title'] = '';
			$options['showcase_2_content'] = '';
			$options['categories'] = false;
			$options['tags'] = true;
			$options['author'] = false;
			$options['ctrlentry'] = false;
			$options['feed'] = false;
			$options['feed_url'] = '';
			$options['feed_readers'] = true;
			$options['twitter'] = false;
			$options['twitter_username'] = '';
			$options['analytics'] = false;
			$options['analytics_content'] = '';
			update_option('blocks2_options', $options);
		}
		return $options;
	}

	function add() {
		if(isset($_POST['blocks2_save'])) {
			$options = Blocks2Options::getOptions();

			// google custom search engine
			if ($_POST['google_cse']) {
				$options['google_cse'] = (bool)true;
			} else {
				$options['google_cse'] = (bool)false;
			}
			$options['google_cse_cx'] = stripslashes($_POST['google_cse_cx']);

			// menu
			$options['menu_type'] = stripslashes($_POST['menu_type']);

			// notice
			if ($_POST['notice']) {
				$options['notice'] = (bool)true;
			} else {
				$options['notice'] = (bool)false;
			}
			$options['notice_color'] = $_POST['notice_color'];
			$options['notice_content'] = stripslashes($_POST['notice_content']);

			// showcase
			if (!$_POST['showcase_registered']) {
				$options['showcase_registered'] = (bool)false;
			} else {
				$options['showcase_registered'] = (bool)true;
			}
			if (!$_POST['showcase_commentator']) {
				$options['showcase_commentator'] = (bool)false;
			} else {
				$options['showcase_commentator'] = (bool)true;
			}
			if (!$_POST['showcase_visitor']) {
				$options['showcase_visitor'] = (bool)false;
			} else {
				$options['showcase_visitor'] = (bool)true;
			}
			if ($_POST['showcase_caption']) {
				$options['showcase_caption'] = (bool)true;
			} else {
				$options['showcase_caption'] = (bool)false;
			}
			$options['showcase_title'] = stripslashes($_POST['showcase_title']);
			$options['showcase_content'] = stripslashes($_POST['showcase_content']);

			// showcase 2
			if (!$_POST['showcase_2_registered']) {
				$options['showcase_2_registered'] = (bool)false;
			} else {
				$options['showcase_2_registered'] = (bool)true;
			}
			if (!$_POST['showcase_2_commentator']) {
				$options['showcase_2_commentator'] = (bool)false;
			} else {
				$options['showcase_2_commentator'] = (bool)true;
			}
			if (!$_POST['showcase_2_visitor']) {
				$options['showcase_2_visitor'] = (bool)false;
			} else {
				$options['showcase_2_visitor'] = (bool)true;
			}
			if ($_POST['showcase_2_caption']) {
				$options['showcase_2_caption'] = (bool)true;
			} else {
				$options['showcase_2_caption'] = (bool)false;
			}
			$options['showcase_2_title'] = stripslashes($_POST['showcase_2_title']);
			$options['showcase_2_content'] = stripslashes($_POST['showcase_2_content']);

			// posts
			if ($_POST['categories']) {
				$options['categories'] = (bool)true;
			} else {
				$options['categories'] = (bool)false;
			}
			if (!$_POST['tags']) {
				$options['tags'] = (bool)false;
			} else {
				$options['tags'] = (bool)true;
			}
			if ($_POST['author']) {
				$options['author'] = (bool)true;
			} else {
				$options['author'] = (bool)false;
			}

			// ctrl + entry
			if ($_POST['ctrlentry']) {
				$options['ctrlentry'] = (bool)true;
			} else {
				$options['ctrlentry'] = (bool)false;
			}

			// feed
			if ($_POST['feed']) {
				$options['feed'] = (bool)true;
			} else {
				$options['feed'] = (bool)false;
			}
			$options['feed_url'] = stripslashes($_POST['feed_url']);
			if (!$_POST['feed_readers']) {
				$options['feed_readers'] = (bool)false;
			} else {
				$options['feed_readers'] = (bool)true;
			}

			// twitter
			if ($_POST['twitter']) {
				$options['twitter'] = (bool)true;
			} else {
				$options['twitter'] = (bool)false;
			}
			$options['twitter_username'] = stripslashes($_POST['twitter_username']);

			// analytics
			if ($_POST['analytics']) {
				$options['analytics'] = (bool)true;
			} else {
				$options['analytics'] = (bool)false;
			}
			$options['analytics_content'] = stripslashes($_POST['analytics_content']);

			update_option('blocks2_options', $options);

		} else {
			Blocks2Options::getOptions();
		}

		add_theme_page(__('Current Theme Options', 'blocks2'), __('Current Theme Options', 'blocks2'), 'edit_themes', basename(__FILE__), array('Blocks2Options', 'display'));
	}

	function display() {
		$options = Blocks2Options::getOptions();
?>

<form action="#" method="post" enctype="multipart/form-data" name="blocks2_form" id="blocks2_form">
	<div class="wrap">
		<h2><?php _e('Current Theme Options', 'blocks2'); ?></h2>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Search', 'blocks2'); ?></th>
					<td>
						<label>
							<input name="google_cse" type="checkbox" value="checkbox" <?php if($options['google_cse']) echo "checked='checked'"; ?> />
							 <?php _e('Using google custom search engine.', 'blocks2'); ?>
						</label>
						<br/>
						<?php _e('CX:', 'blocks2'); ?>
						 <input type="text" name="google_cse_cx" id="google_cse_cx" class="code" size="40" value="<?php echo($options['google_cse_cx']); ?>">
						<br/>
						<?php _e('Find <code>name="cx"</code> in the <strong>Search box code</strong> of <a href="http://www.google.com/coop/cse/">Google Custom Search Engine</a>, and type the <code>value</code> here.<br/>For example: <code>014782006753236413342:1ltfrybsbz4</code>', 'blocks2'); ?>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Menubar', 'blocks2'); ?></th>
					<td>
						<label style="margin-right:20px;">
							<input name="menu_type" type="radio" value="pages" <?php if($options['menu_type'] != 'categories') echo "checked='checked'"; ?> />
							 <?php _e('Show pages as menu.', 'blocks2'); ?>
						</label>
						<label>
							<input name="menu_type" type="radio" value="categories" <?php if($options['menu_type'] == 'categories') echo "checked='checked'"; ?> />
							 <?php _e('Show categories as menu.', 'blocks2'); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<?php _e('Notice', 'blocks2'); ?>
						<br/>
						<small style="font-weight:normal;"><?php _e('HTML enabled', 'blocks2'); ?></small>
					</th>
					<td>
						<!-- notice START -->
						<div style="width:98%;">
							<div style="float:left;">
								<label>
									<input name="notice" type="checkbox" value="checkbox" <?php if($options['notice']) echo "checked='checked'"; ?> />
									 <?php _e('This notice bar will display at the top of posts on homepage.', 'blocks2'); ?>
								</label>
							</div>
							<div style="float:right;">
								<label>
									<?php _e('Color: ', 'blocks2'); ?>
									<select name="notice_color" size="1">
										<option value="1" <?php if($options['notice_color'] == 1) echo ' selected '; ?>><?php _e('Blue', 'blocks2'); ?></option>
										<option value="2" <?php if($options['notice_color'] != 1 && $options['notice_color'] != 3) echo ' selected '; ?>><?php _e('Green', 'blocks2'); ?></option>
										<option value="3" <?php if($options['notice_color'] == 3) echo ' selected '; ?>><?php _e('Red', 'blocks2'); ?></option>
									</select>
								</label>
							</div>
							<div style="clear:both;"></div>
						</div>
						<label>
							<textarea name="notice_content" id="notice_content" cols="50" rows="10" style="width:98%;font-size:12px;" class="code"><?php echo($options['notice_content']); ?></textarea>
						</label>
						<!-- notice END -->
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<?php _e('Showcase', 'blocks2'); ?>
						<br/>
						<small style="font-weight:normal;"><?php _e('HTML enabled', 'blocks2'); ?></small>
					</th>
					<td>
						<!-- showcase START -->
						<?php _e('This showcase will display at the top of sidebar.', 'blocks2'); ?>
						<br/>
						<label>
							<input name="showcase_caption" type="checkbox" value="checkbox" <?php if($options['showcase_caption']) echo "checked='checked'"; ?> />
							 <?php _e('Title:', 'blocks2'); ?>
						</label>
						 <input type="text" name="showcase_title" id="showcase_title" class="code" size="40" value="<?php echo($options['showcase_title']); ?>">
						<br />
						<?php _e('Who can see?', 'blocks2'); ?>
						<label style="margin-left:10px;">
							<input name="showcase_registered" type="checkbox" value="checkbox" <?php if($options['showcase_registered']) echo "checked='checked'"; ?> />
							 <?php _e('Registered Users', 'blocks2'); ?>
						</label>
						<label style="margin-left:10px;">
							<input name="showcase_commentator" type="checkbox" value="checkbox" <?php if($options['showcase_commentator']) echo "checked='checked'"; ?> />
							 <?php _e('Commentator', 'blocks2'); ?>
						</label>
						<label style="margin-left:10px;">
							<input name="showcase_visitor" type="checkbox" value="checkbox" <?php if($options['showcase_visitor']) echo "checked='checked'"; ?> />
							 <?php _e('Visitors', 'blocks2'); ?>
						</label>
						<br />
						<label>
							<textarea name="showcase_content" id="showcase_content" cols="50" rows="10" style="width:98%;font-size:12px;" class="code"><?php echo($options['showcase_content']); ?></textarea>
						</label>
						<!-- showcase END -->

						<br/><br/>

						<!-- showcase 2 START -->
						<?php _e('This showcase will display at the bottom of sidebar.', 'blocks2'); ?>
						<br/>
						<label>
							<input name="showcase_2_caption" type="checkbox" value="checkbox" <?php if($options['showcase_2_caption']) echo "checked='checked'"; ?> />
							 <?php _e('Title:', 'blocks2'); ?>
						</label>
						 <input type="text" name="showcase_2_title" id="showcase_2_title" class="code" size="40" value="<?php echo($options['showcase_2_title']); ?>">
						<br />
						<?php _e('Who can see?', 'blocks2'); ?>
						<label style="margin-left:10px;">
							<input name="showcase_2_registered" type="checkbox" value="checkbox" <?php if($options['showcase_2_registered']) echo "checked='checked'"; ?> />
							 <?php _e('Registered Users', 'blocks2'); ?>
						</label>
						<label style="margin-left:10px;">
							<input name="showcase_2_commentator" type="checkbox" value="checkbox" <?php if($options['showcase_2_commentator']) echo "checked='checked'"; ?> />
							 <?php _e('Commentator', 'blocks2'); ?>
						</label>
						<label style="margin-left:10px;">
							<input name="showcase_2_visitor" type="checkbox" value="checkbox" <?php if($options['showcase_2_visitor']) echo "checked='checked'"; ?> />
							 <?php _e('Visitors', 'blocks2'); ?>
						</label>
						<br />
						<label>
							<textarea name="showcase_2_content" id="showcase_2_content" cols="50" rows="10" style="width:98%;font-size:12px;" class="code"><?php echo($options['showcase_2_content']); ?></textarea>
						</label>
						<!-- showcase 2 END -->

					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Posts', 'blocks2'); ?></th>
					<td>
						<label style="margin-right:20px;">
							<input name="author" type="checkbox" value="checkbox" <?php if($options['author']) echo "checked='checked'"; ?> />
							 <?php _e('Show author on posts.', 'blocks2'); ?>
						</label>
						<label style="margin-right:20px;">
							<input name="categories" type="checkbox" value="checkbox" <?php if($options['categories']) echo "checked='checked'"; ?> />
							 <?php _e('Show categories on posts.', 'blocks2'); ?>
						</label>
						<label>
							<input name="tags" type="checkbox" value="checkbox" <?php if($options['tags']) echo "checked='checked'"; ?> />
							 <?php _e('Show tags on posts.', 'blocks2'); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Comments', 'blocks2'); ?></th>
					<td>
						<label>
							<input name="ctrlentry" type="checkbox" value="checkbox" <?php if($options['ctrlentry']) echo "checked='checked'"; ?> />
							 <?php _e('Submit comments with Ctrl+Enter.', 'blocks2'); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Feed', 'blocks2'); ?></th>
					<td>
						<label>
							<input name="feed" type="checkbox" value="checkbox" <?php if($options['feed']) echo "checked='checked'"; ?> />
							 <?php _e('Using custom feed.', 'blocks2'); ?>
						</label>
						<br />
						<?php _e('Feed URL:', 'blocks2'); ?>
						 <input type="text" name="feed_url" id="feed_url" class="code" size="40" value="<?php echo($options['feed_url']); ?>">
						<br/>
						<label>
							<input name="feed_readers" type="checkbox" value="checkbox" <?php if($options['feed_readers']) echo "checked='checked'"; ?> />
							 <?php _e('Show the feed reader list when mouse over on feed button.', 'blocks2'); ?>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Twitter', 'blocks2'); ?></th>
					<td>
						<label>
							<input name="twitter" type="checkbox" value="checkbox" <?php if($options['twitter']) echo "checked='checked'"; ?> />
							 <?php _e('Add Twitter button to menubar.', 'blocks2'); ?>
						</label>
						<br />
						 <?php _e('Twitter username:', 'blocks2'); ?>
						 <input type="text" name="twitter_username" id="twitter_username" class="code" size="40" value="<?php echo($options['twitter_username']); ?>">
						<br />
						<a href="http://twitter.com/neoease/" onclick="window.open(this.href);return false;">Follow NeoEase</a>
						 | <a href="http://twitter.com/mg12/" onclick="window.open(this.href);return false;">Follow MG12</a>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<?php _e('Web Analytics', 'blocks2'); ?>
						<br/>
						<small style="font-weight:normal;"><?php _e('HTML enabled', 'blocks2'); ?></small>
					</th>
					<td>
						<label>
							<input name="analytics" type="checkbox" value="checkbox" <?php if($options['analytics']) echo "checked='checked'"; ?> />
							 <?php _e('Add web analytics code to your site. (e.g. Google Analytics, Yahoo! Web Analytics, ...)', 'blocks2'); ?>
						</label>
						<label>
							<textarea name="analytics_content" cols="50" rows="10" id="analytics_content" class="code" style="width:98%;font-size:12px;"><?php echo($options['analytics_content']); ?></textarea>
						</label>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input class="button-primary" type="submit" name="blocks2_save" value="<?php _e('Save Changes', 'blocks2'); ?>" />
		</p>
	</div>

</form>

<!-- donation -->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<div class="wrap" style="background:#E3E3E3; margin-bottom:1em;">

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Donation</th>
					<td>
						If you find my work useful and you want to encourage the development of more free resources, you can do it by donating...
						<br />
						<input type="hidden" name="cmd" value="_s-xclick" />
						<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" />
						<input style="border:none;" type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" name="submit" alt="" />
						<img alt="" src="https://www.paypal.com/zh_XC/i/scr/pixel.gif" width="1" height="1" />
					</td>
				</tr>
			</tbody>
		</table>

	</div>
</form>

<?php
	}
}

// register functions
add_action('admin_menu', array('Blocks2Options', 'add'));


/** l10n */
function theme_init(){
	load_theme_textdomain('blocks2', get_template_directory() . '/languages');
}
add_action ('init', 'theme_init');

/** widgets */
if( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}

/** Comments */
if (function_exists('wp_list_comments')) {
	// comment count
	function comment_count( $commentcount ) {
		global $id;
		$_comments = get_comments('status=approve&post_id=' . $id);
		$comments_by_type = &separate_comments($_comments);
		return count($comments_by_type['comment']);
	}
}

// custom comments
function custom_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $commentcount;
	if(!$commentcount) {
		$commentcount = 0;
	}
?>
	<li id="comment-<?php comment_ID() ?>" class="comment<?php if($comment->comment_type == 'pingback' || $comment->comment_type == 'trackback') {echo ' pingcomment';} else if($comment->comment_author_email == get_the_author_email()) {echo ' admincomment';} else {echo ' regularcomment';} ?>">

		<div class="info<?php if($comment->comment_type == 'pingback' || $comment->comment_type == 'trackback') {echo ' pinginfo';} else if($comment->comment_author_email == get_the_author_email()) {echo ' admininfo';} else {echo ' regularinfo';} ?>">
			<?php
				if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') {
					// Support avatar for WordPress 2.5 or higher
					if (function_exists('get_avatar') && get_option('show_avatars')) {
						echo '<div class="pic">';
						echo get_avatar($comment, 24);
						echo '</div>';
					// Support Gravatar for WordPress 2.3.3 or lower
					} else if (function_exists('gravatar')) {
						echo '<div class="pic"><img class="avatar" src="';
						gravatar("G", 24);
						echo '" alt="avatar" /></div>';
					}
				}
			?>
			<div class="author">
				<?php if (get_comment_author_url()) : ?>
					<a class="authorname" id="commentauthor-<?php comment_ID() ?>" href="<?php comment_author_url() ?>" rel="external nofollow">
				<?php else : ?>
					<span class="authorname" id="commentauthor-<?php comment_ID() ?>">
				<?php endif; ?>

				<?php comment_author() ?>

				<?php if(get_comment_author_url()) : ?>
					</a>
				<?php else : ?>
					</span>
				<?php endif; ?>
				<div class="date"><?php printf( __('%1$s at %2$s', 'blocks2'), get_comment_time(__('M jS, Y', 'blocks2')), get_comment_time(__('H:i', 'blocks2')) ); ?></div>
			</div>
			<div class="count">
				<?php if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') : ?>
					<?php if (!get_option('thread_comments')) : ?>
						<a href="javascript:void(0);" onclick="MGJS_CMT.reply('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'comment');"><?php _e('Reply', 'blocks2'); ?></a> | 
					<?php else : ?>
						<?php comment_reply_link(array('depth' => $depth, 'max_depth'=> $args['max_depth'], 'reply_text' => __('Reply', 'blocks2'), 'after' => ' | '));?>
					<?php endif; ?>
					<a href="javascript:void(0);" onclick="MGJS_CMT.quote('commentauthor-<?php comment_ID() ?>', 'comment-<?php comment_ID() ?>', 'commentbody-<?php comment_ID() ?>', 'comment');"><?php _e('Quote', 'blocks2'); ?></a> | 
				<?php endif; ?>
				<?php edit_comment_link(__('Edit', 'blocks2'), '', ' | '); ?>
				<a href="#comment-<?php comment_ID() ?>"><?php printf('#%1$s', ++$commentcount); ?></a>
			</div>
			<div class="fixed"></div>
		</div>

		<?php if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') : ?>
			<div class="content">
				<?php if ($comment->comment_approved == '0') : ?>
					<p><small>Your comment is awaiting moderation.</small></p>
				<?php endif; ?>

				<div id="commentbody-<?php comment_ID() ?>">
					<?php comment_text() ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="fixed"></div>

<?php
}
?>
