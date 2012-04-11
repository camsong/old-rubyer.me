<?php
/*
Plugin Name: Syntax Highlighter Optimized
Plugin URI: http://blog.nxun.com/archives/8
Description: A WordPress code highlight plugin powered by <a href="http://alexgorbatchev.com/SyntaxHighlighter/">Alex Gorbatchev's SyntaxHighlighter</a>.
Version: 3.0.83
Author: Nxun
Author URI: http://blog.nxun.com/
*/

function highlighter_header() {
	$current_path = get_option('siteurl') .'/wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
	?>
	<link type="text/css" rel="stylesheet" href="<?php echo $current_path; ?>styles/shCoreDefault.css" />
	<script type="text/javascript" src="<?php echo $current_path; ?>scripts/shCore.js"></script>
	<script type="text/javascript" src="<?php echo $current_path; ?>scripts/shAutoloader.js"></script>
	<?php
}

function highlighter_footer() {
	$current_path = get_option('siteurl') .'/wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
	?>
<script type="text/javascript">
function path() {
	var args = arguments,
	result = [];
  for(var i = 0; i < args.length; i++)
		result.push(args[i].replace('@', '<?php echo $current_path; ?>scripts/'));
  return result
};
SyntaxHighlighter.autoloader.apply(null, path(
  'applescript            @shBrushAppleScript.js',
  'actionscript3 as3      @shBrushAS3.js',
  'bash shell             @shBrushBash.js',
  'coldfusion cf          @shBrushColdFusion.js',
  'cpp c                  @shBrushCpp.js',
  'c# c-sharp csharp      @shBrushCSharp.js',
  'css                    @shBrushCss.js',
  'delphi pascal          @shBrushDelphi.js',
  'diff patch pas         @shBrushDiff.js',
  'erl erlang             @shBrushErlang.js',
  'groovy                 @shBrushGroovy.js',
  'java                   @shBrushJava.js',
  'jfx javafx             @shBrushJavaFX.js',
  'js jscript javascript  @shBrushJScript.js',
  'perl pl                @shBrushPerl.js',
  'php                    @shBrushPhp.js',
  'text plain             @shBrushPlain.js',
  'py python              @shBrushPython.js',
  'ruby rails ror rb      @shBrushRuby.js',
  'sass scss              @shBrushSass.js',
  'scala                  @shBrushScala.js',
  'sql                    @shBrushSql.js',
  'vb vbnet               @shBrushVb.js',
  'xml xhtml xslt html    @shBrushXml.js'
));
SyntaxHighlighter.defaults['quick-code'] = false;
SyntaxHighlighter.all();
</script>
	<?php
}

/**
 * add a button on tinymce
 * @author htp
 *
 */

class wp_ash {
	function wp_ash() {
		global $wp_version;
		
		// The current version
		define('ash_VERSION', '1.0');
		
		// Check for WP2.6 installation
		if (!defined ('IS_WP26'))
			define('IS_WP26', version_compare($wp_version, '2.6', '>=') );
		
		//This works only in WP2.6 or higher
		if ( IS_WP26 == FALSE) {
			add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>Sorry,Syntax Highlighter Optimized TinyMCE plugin works only under WordPress 2.6 or higher</strong></p></div>\';'));
			return;
		} else {
      // define URL
      define('ash_ABSPATH', WP_PLUGIN_DIR.'/'.plugin_basename( dirname(__FILE__) ).'/' );
      define('ash_URLPATH', WP_PLUGIN_URL.'/'.plugin_basename( dirname(__FILE__) ).'/' );
      include_once (dirname (__FILE__)."/tinymce/tinymce.php");
		}
	}
}

wp_enqueue_script("jquery");
add_action('wp_head','highlighter_header');
add_action('wp_footer','highlighter_footer');
add_action('plugins_loaded', create_function( '', 'global $wp_ash; $wp_ash = new wp_ash();' ));

?>
