<?php 
/*
Plugin Name: 中文 WordPress 工具箱
Plugin URI: http://yanfeng.org/blog/wordpress/kit
Description: 用来解决官方 WordPress 没有照顾到的中文相关问题。使用这个插件，你可以显示随机文章，最新留言（最新引用），留言最多文章，发表评论最多的网友，还有真正的文章摘要，等等，真正截断，没有乱码。
Version: 1.2
Author: 桑葚
Author URI: http://yanfeng.org/blog
*/

function get_recent_comments($no_comments = 5, $before = '<li> ', $after = '</li>', $show_pass_post = false) {

	global $wpdb, $tablecomments, $tableposts;
	$request = "SELECT ID, comment_ID, comment_content, comment_author FROM $tableposts, $tablecomments WHERE $tableposts.ID=$tablecomments.comment_post_ID AND (post_status = 'publish' OR post_status = 'static')";

if(!$show_pass_post) { $request .= "AND post_password ='' "; }

    $request .= "AND comment_approved = '1' ORDER BY $tablecomments.comment_date DESC LIMIT 

$no_comments";
    $comments = $wpdb->get_results($request);
    $output = '';
    foreach ($comments as $comment) {
       $comment_author = stripslashes($comment->comment_author);
       $comment_content = strip_tags($comment->comment_content);
       $comment_content = stripslashes($comment_content);
       $comment_excerpt =substr($comment_content,0,50);
       $comment_excerpt = utf8_trim($comment_excerpt);
       $permalink = get_permalink($comment->ID)."#comment-".$comment->comment_ID;
       $output .= $before . '<a href="' . $permalink . '" title="View the entire comment by ' . $comment_author . '">' . $comment_author . '</a>: ' . $comment_excerpt . '...' . $after;
       }
       echo $output;
}

function get_recent_comments_only($no_comments = 5, $before = '<li> ', $after = '</li>', $show_pass_post = false) {

	global $wpdb, $tablecomments, $tableposts;
	$request = "SELECT ID, comment_ID, comment_content, comment_author FROM $tableposts, $tablecomments WHERE $tableposts.ID=$tablecomments.comment_post_ID AND (post_status = 'publish' OR post_status = 'static') AND comment_type = ''";

if(!$show_pass_post) { $request .= "AND post_password ='' "; }

    $request .= "AND comment_approved = '1' ORDER BY $tablecomments.comment_date DESC LIMIT 

$no_comments";
    $comments = $wpdb->get_results($request);
    $output = '';
    foreach ($comments as $comment) {
       $comment_author = stripslashes($comment->comment_author);
       $comment_content = strip_tags($comment->comment_content);
       $comment_content = stripslashes($comment_content);
       $comment_excerpt =substr($comment_content,0,50);
       $comment_excerpt = utf8_trim($comment_excerpt);
       $permalink = get_permalink($comment->ID)."#comment-".$comment->comment_ID;
       $output .= $before . '<a href="' . $permalink . '" title="View the entire comment by ' . $comment_author . '">' . $comment_author . '</a>: ' . $comment_excerpt . '...' . $after;
       }
       echo $output;
}

function get_recent_trackbacks($no_comments = 5, $before = '<li> ', $after = '</li>', $show_pass_post = false) {

	global $wpdb, $tablecomments, $tableposts;
	$request = "SELECT ID, comment_ID, comment_content, comment_author FROM $tableposts, $tablecomments WHERE $tableposts.ID=$tablecomments.comment_post_ID AND (post_status = 'publish' OR post_status = 'static') AND (comment_type = 'trackback' OR comment_type ='pingback')";

if(!$show_pass_post) { $request .= "AND post_password ='' "; }

    $request .= "AND comment_approved = '1' ORDER BY $tablecomments.comment_date DESC LIMIT 

$no_comments";
    $comments = $wpdb->get_results($request);
    $output = '';
    foreach ($comments as $comment) {
       $comment_author = stripslashes($comment->comment_author);
       $comment_content = strip_tags($comment->comment_content);
       $comment_content = stripslashes($comment_content);
       $comment_excerpt =substr($comment_content,0,50);
       $comment_excerpt = utf8_trim($comment_excerpt);
       $permalink = get_permalink($comment->ID)."#comment-".$comment->comment_ID;
       $output .= $before . '<a href="' . $permalink . '" title="View the entire comment by ' . $comment_author . '">' . $comment_author . '</a>: ' . $comment_excerpt . '...' . $after;
       }
       echo $output;
}

// A trim function to remove the last character of a utf-8 string
// by following instructions on http://en.wikipedia.org/wiki/UTF-8
// dotann

function utf8_trim($str) {

	$len = strlen($str);

	for ($i=strlen($str)-1; $i>=0; $i-=1){
		$hex .= ' '.ord($str[$i]);
		$ch = ord($str[$i]);
        if (($ch & 128)==0) return(substr($str,0,$i));
		if (($ch & 192)==192) return(substr($str,0,$i));
	}
	return($str.$hex);
}

// Get Top Commented Posts
function get_mostcommented($limit = 5) {
    global $wpdb, $post, $tableposts, $tablecomments, $time_difference, $post;
    $mostcommenteds = $wpdb->get_results("SELECT  $tableposts.ID as ID, post_title, post_name, COUNT($tablecomments.comment_post_ID) AS 'comment_total' FROM $tableposts LEFT JOIN $tablecomments ON $tableposts.ID = $tablecomments.comment_post_ID WHERE comment_approved = '1' AND post_date < '".date("Y-m-d H:i:s", (time() + ($time_difference * 3600)))."' AND post_status = 'publish' AND post_password = '' GROUP BY $tablecomments.comment_post_ID ORDER  BY comment_total DESC LIMIT $limit");
    foreach ($mostcommenteds as $post) {
			$post_id = (int) $post->post_id;
			$post_title = htmlspecialchars(stripslashes($post->post_title));
			$comment_total = (int) $post->comment_total;
                        $permalink = get_permalink($post->ID);
			echo "+ <a href=\"$permalink\">$post_title</a> ($comment_total)<br />";
    }
}

// Get Comments' Members Stats
// Treshhold = Number Of Posts User Must Have Before It Will Display His Name Out
// 5 = Default Treshhold; -1 = Disable Treshhold
function get_commentmembersstats($threshhold = 5) {
	global $wpdb, $tablecomments;
	$comments = $wpdb->get_results("SELECT comment_author, comment_author_url, COUNT(comment_ID) AS 'comment_total' FROM $tablecomments WHERE comment_approved = '1' AND (comment_author != 'blogmaster') AND (comment_author != '')GROUP BY comment_author ORDER BY comment_total DESC");
	$no = 1;

    foreach ($comments as $comment) {
			$comment_author = htmlspecialchars(stripslashes($comment->comment_author));
			$comment_author_url =stripslashes($comment->comment_author_url);
			$comment_total = (int) $comment->comment_total;
				if ($comment_author_url) {
					$comment_author_link = "<a href='$comment_author_url' target='_blank'>$comment_author</a>";
				} else {
					$comment_author_link = "<a href='mailto:$comment_author_email'>$comment_author</a>";
				}
			echo "<a href=\"$comment_author_url\" target=\"_blank\">$comment_author</a> ($comment_total)  ";
			$no++;

			// If Total Comments Is Below Threshold
			if($comment_total <= $threshhold && $threshhold != -1) {
				return;
			}
    }
}


function random_posts ($limit = 5, $length = 400, $before = '<li>', $after = '</li>', $show_pass_post = false, $show_excerpt_in_title = true) {
    global $wpdb, $tableposts;
    $sql = "SELECT ID, post_title, post_date, post_content FROM $tableposts WHERE post_status = 'publish' ";
	if(!$show_pass_post) $sql .= "AND post_password ='' ";
	$sql .= "ORDER BY RAND() LIMIT $limit";
    $posts = $wpdb->get_results($sql);
	$output = '';
    foreach ($posts as $post) {
       $post_title = stripslashes($post->post_title);
	$post_date = mysql2date('j.m.Y', $post->post_date);
       $permalink = get_permalink($post->ID);
	$post_content = strip_tags($post->post_content); 
	$post_content = stripslashes($post_content); 
	$post_strip = substr($post_content,0,$length);
       $post_strip = utf8_trim($post_strip);
	$post_strip = str_replace('"', '', $post_strip);
	$output .= $before . '<a href="' . $permalink . '" rel="bookmark" title="';
	if($show_excerpt_in_title) {
		$output .= $post_strip . '...  ';
      	   } else  {
		$output .= 'Permanent Link: ' . str_replace('"', '', $post_title) . '...   ';
	}
	$output .= $post_date . '">' . $post_title . '</a>';
	if(!$show_excerpt_in_title) {
		$output .= ': ' . $post_strip . '...  ';
      	   }
	$output .= $after;
	}
	echo $output;
}

function get_recent_posts($no_posts = 5, $before = '<li>+ ', $after = '</li>', $show_pass_post = false, $skip_posts = 0) {
    global $wpdb, $tableposts;
    $request = "SELECT ID, post_title, post_date, post_content FROM $tableposts WHERE post_status = 'publish' ";
        if(!$show_pass_post) { $request .= "AND post_password ='' "; }
    $request .= "ORDER BY post_date DESC LIMIT $skip_posts, $no_posts";
    $posts = $wpdb->get_results($request);
    $output = '';
    foreach ($posts as $post) {
        $post_title = stripslashes($post->post_title);
//	 $post_date = mysql2date('j.m.Y', $post->post_date);
        $permalink = get_permalink($post->ID);
        $output .= $before . '<a href="' . $permalink . '" rel="bookmark" title="Permanent Link: ' . $post_title . '">' . $post_title . '</a>'. $after;
    }
    echo $output;
}

function mul_excerpt ($excerpt) {
     $myexcerpt = substr($excerpt,0,255);
     return utf8_trim($myexcerpt) . '... ';
}

add_filter('the_excerpt', 'mul_excerpt');
add_filter('the_excerpt_rss', 'mul_excerpt');
?>