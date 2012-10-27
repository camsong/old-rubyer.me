<?php
/*
Plugin Name: 日志自动截断limit posts automatically
Plugin URI: http://leo108.com/pid-213.asp
Description: 自动截断日志文字的插件，使用此插件后，撰写日志时无需再加入more标签进行文字截断操作。采用UTF-8模式截取，中文无乱码。
Version: 1.0
Author: leo108
Author URI: http://leo108.com

修改日期: 2011-04-10
修改说明: 在去除html标签时保留了<p><br>两个标签，这样截断出来的文章仍然是有段落的，不然会很难看。

Org. Name: WP Limit Posts Automatically
Org. Author: Jens T&ouml;rnell
Org. URI: http://www.jenst.se
*/

function lpa_replace_content($content)
{
	// Get data from database
	$lpa_post_wordcut = get_option("lpa_post_wordcut");
	
	$lpa_post_letters = get_option("lpa_post_letters");
	$lpa_post_linktext = get_option("lpa_post_linktext");
	$lpa_post_ending = get_option("lpa_post_ending");
	
	$lpa_post_home = get_option("lpa_post_home");
	$lpa_post_category = get_option("lpa_post_category");
	$lpa_post_archive = get_option("lpa_post_archive");
	$lpa_post_search = get_option("lpa_post_search");
	$lpa_striptags = get_option("lpa_striptags");

	// If post letters are not set, default is set to 300
	if ($lpa_post_letters == ""){
		$lpa_post_letters = 300;
	}
	if ($lpa_post_wordcut == "Wordcut")
	{
		// Check what options is set
		if ( (is_home() && $lpa_post_home == "on") || (is_category() && $lpa_post_category == "on") || (is_archive() && $lpa_post_archive == "on") || (is_search() && $lpa_post_search == "on") ) {
		
			// Get data to see if more tag is used
			global $post;
			$ismoretag = explode('<!--',$post->post_content);
			$ismoretag2 = explode('-->', $ismoretag[1]);
			
			if ($lpa_striptags == "on") {
				$content2 = "<p>" . strip_tags($content, '<p><br>');
			}
		
			// Limit the post by wordwarp to check for more tag
			$prev_content = cut_word_utf8($content, $lpa_post_letters, $lpa_post_ending);
			$cuttext = explode ('[lpa]', $prev_content);
			$end_string = substr($cuttext[0], -5);
			$endingp = "";
			
			// Limit the post by wordwarp
			$prev_content2 = cut_word_utf8($content2, $lpa_post_letters, $lpa_post_ending);
			$cuttext2 = explode ('[lpa]', $prev_content2);
			$end_string2 = substr($cuttext2[0], -5);
			$endingp2 = "";
			
			// If end of p-tag is missing create one
			if ($end_string == "</p>\n") {
				$cuttext[0]=substr($cuttext[0],0,(strlen($cuttext[0])-5));
			}
			// Check if more tag is used
			if ($ismoretag2[0] != "more") {
				if ($lpa_striptags == "on") {
					echo $cuttext2[0]; // Add limited post
				}
				else {
					echo $cuttext[0]; // Add limited post
				}
				// Add link if link text exists
				if ($lpa_post_linktext != ""){
					//mark080717 echo " <a href='" .get_permalink(). "' rel=\"nofollow\">".utf8_encode($lpa_post_linktext)."</a>";
					echo "<a href='" .get_permalink(). "' rel=\"nofollow\">".$lpa_post_linktext."</a>";
				}
				echo "</p>";
			}
			else {
				return $content;
			}
		}
		else {
			return $content;
		}
	}
	else if ($lpa_post_wordcut == "Lettercut") {
		// Check what options is set
		if ( (is_home() && $lpa_post_home == "on") || (is_category() && $lpa_post_category == "on") || (is_archive() && $lpa_post_archive == "on") || (is_search() && $lpa_post_search == "on") ) {
			
			// Get data to see if more tag is used
			global $post;
			$ismoretag = explode('<!--',$post->post_content);
			$ismoretag2 = explode('-->', $ismoretag[1]);
			
			if ($lpa_striptags == "on") {
				$content2 = "<p>" . strip_tags($content, '<p><br>');
			}
			
			// Limit the post by letter to check for more tag
			$new_string2 = substr($content2, 0,$lpa_post_letters+3);
			$end_string2 = substr($new_string2, -5);
			$endingp = "";
			
			// Limit the post by letter
			$new_string = substr($content, 0,$lpa_post_letters+3);
			$end_string = substr($new_string, -5);
			$endingp = "";
			
			// If end of p-tag is missing create one
			if ($end_string == "</p>\n") {
				$new_string=substr($new_string,0,(strlen($new_string)-5));
			}

			// Check if more tag is used
			if ($ismoretag2[0] != "more") {
				
				if ($lpa_striptags == "on") {
					echo $new_string2; // Add limited post
				}
				else {
					echo $new_string; // Add limited post
				}
				
				echo $lpa_post_ending; // Add limited ending
				// Add link if link text exists
				if ($lpa_post_linktext != ""){
					//mark080717 echo " <a href='" .get_permalink(). "' rel=\"nofollow\">".utf8_encode($lpa_post_linktext)."</a>";
					echo "<a href='" .get_permalink(). "' rel=\"nofollow\">".$lpa_post_linktext."</a>";
				}
				echo "</p>";
			}
			else {
				return $content;
			}
		}
		else {
			return $content;
		}
	}
	else if ($lpa_post_wordcut == "Paragraphcut") {
		if ( (is_home() && $lpa_post_home == "on") || (is_category() && $lpa_post_category == "on") || (is_archive() && $lpa_post_archive == "on") || (is_search() && $lpa_post_search == "on") ) {
			$paragraphcut = explode('</p>', $content);
			global $post;
			$ismoretag = explode('<!--',$post->post_content);
			$ismoretag2 = explode('-->', $ismoretag[1]);
			if ($ismoretag2[0] != "more") {
				echo $paragraphcut[0];
				echo $lpa_post_ending;
				if ($lpa_post_linktext != ""){
					//mark080717 echo " <a href='" .get_permalink(). "' rel=\"nofollow\">".utf8_encode($lpa_post_linktext)."</a>";
					echo "<a href='" .get_permalink(). "' rel=\"nofollow\">".$lpa_post_linktext."</a>";
				}
				echo "</p>";
			}
			else {
				return $content;
			}
		}
		else {
			return $content;
		}
	}
	else {
		return $content;
	}
}
add_filter('the_content','lpa_replace_content');

function lpa_admin(){
    if(isset($_POST['submitted'])){
		// Get data from input fields
        $wordcut = $_POST['lpa_post_wordcut'];
		
		$letters = $_POST['lpa_post_letters'];
		$linktext = $_POST['lpa_post_linktext'];
		$ending = $_POST['lpa_post_ending'];
		
		$home = $_POST['lpa_post_home'];
		$category = $_POST['lpa_post_category'];
		$archive = $_POST['lpa_post_archive'];
		$search = $_POST['lpa_post_search'];
		$striptags = $_POST['lpa_striptags'];
        
		// Upload / update data to database
		update_option("lpa_post_wordcut", $wordcut);
		
		update_option("lpa_post_letters", $letters);
		update_option("lpa_post_linktext", $linktext);
		update_option("lpa_post_ending", $ending);
		
		update_option("lpa_post_home", $home);
		update_option("lpa_post_category", $category);
		update_option("lpa_post_archive", $archive);
		update_option("lpa_post_search", $search);
		
		update_option("lpa_striptags", $striptags);
		
        //Options updated message
        echo "<div id=\"message\" class=\"updated fade\"><p><strong>自动截断设置已保存。</strong></p></div>";
    }
	?>
    <div class="wrap">
    <h2>自动截断功能设置</h2>
	<?php
	$limitpostby = get_option("lpa_post_wordcut");
	$input_letters = get_option("lpa_post_letters");
	$input_linktext = get_option("lpa_post_linktext");
	$input_ending = get_option("lpa_post_ending");
	$lpa_home = get_option("lpa_post_home");
	$lpa_category = get_option("lpa_post_category");
	$lpa_archive = get_option("lpa_post_archive");
	$lpa_search = get_option("lpa_post_search");
	$lpa_striptags = get_option("lpa_striptags");
	?>
	
    <form method="post" name="options" target="_self">
	<h3 style="font-weight: normal;">文字截断方式:</h3>
	<table width="70%" border="0" cellspacing="2" cellpadding="2">
		<tr>
			<!-- <td width="25%"><input type="radio" name="lpa_post_wordcut" value="Lettercut" <?php if ($limitpostby == "Lettercut"){ echo 'checked="checked"'; } ?> onclick="javascript:document.getElementById('letternumber').style.display='';" /> Letter</td>	-->
			<td width="25%"><input type="radio" name="lpa_post_wordcut" value="Wordcut" <?php if ($limitpostby == "Wordcut"){ echo 'checked="checked"'; } ?> onclick="javascript:document.getElementById('letternumber').style.display='';" /> 按照字符数</td>
			<td width="25%"><input type="radio" name="lpa_post_wordcut" value="Paragraphcut" <?php if ($limitpostby == "Paragraphcut"){ echo 'checked="checked"'; } ?> onclick="javascript:document.getElementById('letternumber').style.display='none';" /> 只显示首段文字</td>
			<td width="25%"><input type="radio" name="lpa_post_wordcut" value="Nocut" <?php if ($limitpostby == "Nocut"){ echo 'checked="checked"'; } ?> onclick="javascript:document.getElementById('letternumber').style.display='none';" /> 不使用自动截断</td>  
		</tr>
	</table>
	<h3 style="font-weight: normal;">显示方式:</h3>
	<table>
		<tr id="letternumber" <?php if ($limitpostby=="Paragraphcut" || $limitpostby=="Nocut"){ echo 'style="display: none;"'; }?>>
			<td colspan="4"><strong>截取前面:</strong> <input name="lpa_post_letters" type="text" value="<?php echo $input_letters; ?>" /> 个字符(留空默认显示300个字)</td>
		</tr>
		<tr>
			<td colspan="4"><strong>结束符号:</strong> <input name="lpa_post_ending" type="text" value="<?php echo $input_ending; ?>" /> 表示后续文字</td>
		</tr>
		<tr>
			<td colspan="4"><strong>连接文字:</strong> <input name="lpa_post_linktext" type="text" value="<?php echo $input_linktext; ?>" /> 连接文字标记</td>
		</tr>
	</table>
	<h3 style="font-weight: normal;">在以下类别中使用自动截断功能:</h3>
	<table>
		<tr>
			<td><input type="checkbox" name="lpa_post_home" <?php if($lpa_home == "on"){ echo 'checked="checked"'; } ?>/> 首页 &nbsp;</td>
			<td><input type="checkbox" name="lpa_post_category" <?php if($lpa_category == "on"){ echo 'checked="checked"'; } ?>/> 分类 &nbsp;</td>
			<td><input type="checkbox" name="lpa_post_archive" <?php if($lpa_archive == "on"){ echo 'checked="checked"'; } ?>/> 归档 &nbsp;</td>
			<td><input type="checkbox" name="lpa_post_search" <?php if($lpa_search == "on"){ echo 'checked="checked"'; } ?>/> 搜索 &nbsp;</td>
		</tr>
	</table>
	<h3 style="font-weight: normal;">避免截断脚本功能:</h3>
	<table>
		<tr>
			<td><input type="checkbox" name="lpa_striptags" <?php if($lpa_striptags == "on"){ echo 'checked="checked"'; } ?>/> 移除所有脚本 (移除后在截断的文章中不能显示图片, 视频, 连接等)</td>
		</tr>
	</table>
	<br />
	<em>要启用或禁用WP的more标签, 请点击<a href="options-reading.php">设置/读取</a>进行设置。</em><br /><br />
	<h2>使用方法</h2>
	<ul>
		<li>推荐使用"字符数"的方式进行截取，由于本中文版插件采用UTF-8方式截取中文，因此不会出现最后一个中文文字乱码问题。如果采用"只显示首段"的截取方式，那么日志将以&lt;/p&gt;作为截断标记。</li>
		<li>"移除脚本" 删除文字中的Script脚本，只以纯文本的方式显示文章。这时图片，视频，连接将无法显示。这种方式可以避免破坏日志中的Script脚本，导致显示错乱的问题。</li>
	</ul>
<p class="submit">
<input name="submitted" type="hidden" value="yes" />
<input type="submit" name="Submit" value="保存设置" />
</p>
</form>

</div>

<?php } 
//*	//Mark Lin 按照UTF-8编码截断文字，能够避免中文最后出现乱码
function cut_word_utf8($str, $len, $etc='')
{
//    Parameters:{
//    str: The string you want to cut.
//    len: The display width of a string you want.(A alpha take one and a Chinese take two).
//    etc: Add a string '...' after the cuted string.
//    Get the display width of the string.
    $i =0;
    $j =0;
    $str_width = 0;
    do{
            if(ord($str[$i]) > 224){
                    $str_width += 2;
                    $i += 3;
                }
            else if(ord($str[$i] > 192)){
                    $str_width += 2;
                    $i += 2;
                }
            else{
                    $str_width++;
                    $i++;
                }
        }while($i<strlen($str));
    //IF the display width is shorter than you want ,return the string.
    if($str_width < $len)
        {
            return $str;
        }
    else{
            $i = 0;
            $j = 0;
            $newword = '';
            do{
                    //If the character is a Chinese
                    if(ord($str[$i]) > 224){
                            $newword .= $str[$i].$str[$i+1].$str[$i+2];
                            $i = $i +3;
                            $j =$j + 2;
                        }
                    //If the character is a symble
                    else if(ord($str[$i] > 192)){
                            $newword .= $str[$i].$str[$i+1];
                            $i = $i + 2;
                            $j = $j + 2;
                        }
                    //If the character is a alpha
                    else{
                            $newword .= $str[$i];
                            $i++;
                            $j++;
                        }
                }while($j<$len);
            return $newword . $etc;
        }
}
//Add the options page in the admin panel
function lpa_addpage() {
    add_submenu_page('options-general.php', '自动截断', '自动截断', 10, __FILE__, 'lpa_admin');
}
add_action('admin_menu', 'lpa_addpage');
?>