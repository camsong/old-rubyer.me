<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
	$options = get_option('blocks2_options');
	if (is_home()) {
		$home_menu = 'current_page_item';
	} else {
		$home_menu = 'page_item';
	}
	if($options['feed'] && $options['feed_url']) {
		if (substr(strtoupper($options['feed_url']), 0, 7) == 'HTTP://') {
			$feed = $options['feed_url'];
		} else {
			$feed = 'http://' . $options['feed_url'];
		}
	} else {
		$feed = get_bloginfo('rss2_url');
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />

	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	<link rel="alternate" type="application/rss+xml" title="<?php _e('RSS 2.0 - all posts', 'blocks2'); ?>" href="<?php echo $feed; ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php _e('RSS 2.0 - all comments', 'blocks2'); ?>" href="<?php bloginfo('comments_rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<!-- style START -->
	<style type="text/css" media="screen">@import url( <?php bloginfo('stylesheet_url'); ?> );</style>
	<?php if (strtoupper(get_locale()) == 'ZH_CN') : ?>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/chinese.css" type="text/css" media="screen" />
	<?php endif; ?>
	<!--[if IE]>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie.css" type="text/css" media="screen" />
	<![endif]-->
	<!-- style END -->

	<!-- script START -->
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/base.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/menu.js"></script>
	<!-- script END -->

	<?php if(is_singular()) wp_enqueue_script('comment-reply'); ?>
	<?php wp_head(); ?>
</head>

<?php flush(); ?>

<body>
	<div id="wrap">
		<div id="container">

<!-- header START -->
<div id="header">
	<div id="title">
		<h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div id="tagline"><?php bloginfo('description'); ?></div>
	</div>

<?php if($options['google_cse'] && $options['google_cse_cx']) : ?>
	<!-- WordPress searchbox -->
	<div id="wp-search-box" class="searchbox"<?php if($_COOKIE['searchbox'] != 'wordpress-search'){echo(' style="display:none;"');} ?>>
		<form action="<?php bloginfo('home'); ?>/" method="get">
			<div class="content">
				<a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('wp-search-box', 'none');MGJS.setStyleDisplay('cse-search-box', '');MGJS.setCookie('searchbox','google-custom-search');"><img src="<?php bloginfo('template_url'); ?>/images/search_switcher.gif" alt="G" /></a>
				<input type="text" class="textfield" name="s" size="24" value="<?php echo wp_specialchars($s, 1); ?>" />
				<input type="submit" class="button" value="<?php _e('Search', 'blocks2'); ?>" />
			</div>
		</form>
	</div>
	<!-- Google Custom searchbox -->
	<div id="cse-search-box" class="searchbox"<?php if($_COOKIE['searchbox'] != 'google-custom-search' && $_COOKIE['searchbox'] == 'wordpress-search'){echo(' style="display:none;"');} ?>>
		<form action="http://www.google.com/cse" method="get">
			<div class="content">
				<a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('cse-search-box', 'none');MGJS.setStyleDisplay('wp-search-box', '');MGJS.setCookie('searchbox','wordpress-search');"><img src="<?php bloginfo('template_url'); ?>/images/search_switcher.gif" alt="W" /></a>
				<input type="text" class="textfield" name="q" size="24" />
				<input type="submit" class="button" name="sa" value="<?php _e('Search', 'blocks2'); ?>" />
				<input type="hidden" name="cx" value="<?php echo $options['google_cse_cx']; ?>" />
				<input type="hidden" name="ie" value="UTF-8" />
			</div>
		</form>
	</div>
<?php else : ?>
	<!-- WordPress searchbox -->
	<div class="searchbox">
		<form action="<?php bloginfo('home'); ?>/" method="get">
			<div class="content">
				<input type="text" class="textfield" name="s" size="24" value="<?php echo wp_specialchars($s, 1); ?>" />
				<input type="submit" class="button" value="<?php _e('Search', 'blocks2'); ?>" />
			</div>
		</form>
	</div>
<?php endif; ?>

	<div class="fixed"></div>
</div>
<!-- header END -->

<!-- content START -->
<div id="content">

	<!-- menubar START -->
	<div id="navigation">
		<ul id="menubar">
			<li class="<?php echo($home_menu); ?>"><a title="<?php _e('Home', 'blocks2'); ?>" href="<?php echo get_settings('home'); ?>/"><?php _e('Home', 'blocks2'); ?></a></li>
			<?php
				if($options['menu_type'] == 'categories') {
					wp_list_categories('title_li=0&orderby=count&show_count=0&order=desc');
				} else {
					wp_list_pages('title_li=0&sort_column=menu_order');
				}
			?>
                        <li><a href="/about">关于我</a></li>
		</ul>
		<div id="subscribe" class="feed">
			<a title="<?php _e('Subscribe to this blog...', 'blocks2'); ?>" class="feedlink" href="<?php echo $feed; ?>"><?php _e('<abbr title="Really Simple Syndication">RSS</abbr> feed', 'blocks2'); ?></a>
			<?php if($options['feed_readers']) : ?>
				<ul>
					<li><a rel="external nofollow" title="<?php _e('Subscribe with ', 'blocks2'); _e('Xian Guo', 'blocks2'); ?>"	href="http://www.xianguo.com/subscribe.php?url=<?php echo $feed; ?>">					<?php _e('Xian Guo', 'blocks2'); ?>	</a></li>
					<li><a rel="external nofollow" title="<?php _e('Subscribe with ', 'blocks2'); _e('Zhua Xia', 'blocks2'); ?>"	href="http://www.zhuaxia.com/add_channel.php?url=<?php echo $feed; ?>">					<?php _e('Zhua Xia', 'blocks2'); ?>	</a></li>
					<li><a rel="external nofollow" title="<?php _e('Subscribe with ', 'blocks2'); _e('Yodao', 'blocks2'); ?>"		href="http://reader.yodao.com/#url=<?php echo $feed; ?>">								<?php _e('Yodao', 'blocks2'); ?>	</a></li>
					<li><a rel="external nofollow" title="<?php _e('Subscribe with ', 'blocks2'); _e('Google', 'blocks2'); ?>"		href="http://fusion.google.com/add?feedurl=<?php echo $feed; ?>">						<?php _e('Google', 'blocks2'); ?>	</a></li>
					<li><a rel="external nofollow" title="<?php _e('Subscribe with ', 'blocks2'); _e('netvibes', 'blocks2'); ?>"	href="http://www.netvibes.com/subscribe.php?url=<?php echo $feed; ?>">					<?php _e('netvibes', 'blocks2'); ?>	</a></li>
					<li><a rel="external nofollow" title="<?php _e('Subscribe with ', 'blocks2'); _e('newsgator', 'blocks2'); ?>"	href="http://www.newsgator.com/ngs/subscriber/subfext.aspx?url=<?php echo $feed; ?>">	<?php _e('newsgator', 'blocks2'); ?></a></li>
					<li><a rel="external nofollow" title="<?php _e('Subscribe with ', 'blocks2'); _e('Bloglines', 'blocks2'); ?>"	href="http://www.bloglines.com/sub/<?php echo $feed; ?>">								<?php _e('Bloglines', 'blocks2'); ?></a></li>
					<li><a rel="external nofollow" title="<?php _e('Subscribe with ', 'blocks2'); _e('iNezha', 'blocks2'); ?>"		href="http://inezha.com/add?url=<?php echo $feed; ?>">									<?php _e('iNezha', 'blocks2'); ?>	</a></li>
				</ul>
			<?php endif; ?>
		</div>
		<?php if($options['twitter'] && $options['twitter_username']) : ?>
			<div>
				<a title="<?php _e('Follow me!', 'blocks2'); ?>" class="twitterlink" href="http://twitter.com/<?php echo $options['twitter_username']; ?>/"><?php _e('Twitter', 'blocks2'); ?></a>
			</div>
		<?php endif; ?>
		<?php if ( $user_ID ) : ?>
			<div>
				<a title="<?php _e('Write a NEW post', 'blocks2'); ?>" class="greedlink" href="<?php echo get_option('siteurl'); ?>/wp-admin/post-new.php"><?php _e('NEW post', 'blocks2'); ?></a>
			</div>
		<?php endif; ?>

		<span id="copyright">
			<?php
				global $wpdb;
				$post_datetimes = $wpdb->get_row($wpdb->prepare("SELECT YEAR(min(post_date_gmt)) AS firstyear, YEAR(max(post_date_gmt)) AS lastyear FROM $wpdb->posts WHERE post_date_gmt > 1970"));
				if ($post_datetimes) {
					$firstpost_year = $post_datetimes->firstyear;
					$lastpost_year = $post_datetimes->lastyear;

					$copyright = '&copy; ' . $firstpost_year;
					if($firstpost_year != $lastpost_year) {
						$copyright .= '-'. $lastpost_year;
					}
					$copyright .= ' ';

					echo $copyright;
					bloginfo('name');
				}
			?>
		</span>
		<div class="fixed"></div>
	</div>
	<!-- menubar END -->

	<!-- main START -->
	<div id="main">
