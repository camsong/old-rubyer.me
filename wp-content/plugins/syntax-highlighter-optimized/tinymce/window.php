<?php

// look up for the path
require_once( dirname(__FILE__) .'/auto-syntax-highlighter-config.php');

global $wpdb;

// check for rights
if ( !is_user_logged_in() || !current_user_can('edit_posts') ) 
	wp_die(__("You are not allowed to be here"));
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Syntax Highlighter Optimized</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript">
	function init() {
		tinyMCEPopup.resizeToInnerSize();
	}
	
	function insertShcLink() {
		
		var langu;
		var codetext;
		
		var ash = document.getElementById('ash_panel');
		
		// who is active ?
		if (ash.className.indexOf('current') != -1) {
			var ashla = document.getElementById('ash_lang').value;			
			var ashid = document.getElementById('ash_code').value.replace(/</g,'&lt;').replace(/\r/g,'<br>');
				
			if (ashid != '' )
				codetext = "<pre class=\"brush:" + ashla + "\">" + ashid + "</pre>";
			else
				tinyMCEPopup.close();
		}
	
		
		if(window.tinyMCE) {
			window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, codetext);
			//Peforms a clean up of the current editor HTML. 
			//tinyMCEPopup.editor.execCommand('mceCleanup');
			//Repaints the editor. Sometimes the browser has graphic glitches. 
			tinyMCEPopup.editor.execCommand('mceRepaint');
			tinyMCEPopup.close();
		}
		
		return;
	}
	</script>
	<base target="_self" />
</head>
<body id="advimage" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';document.getElementById('ash_code').focus();" style="display: none">
<!-- <form onsubmit="insertLink();return false;" action="#"> -->
	<form name="ash_form" action="#">
	<div class="tabs">
		<ul>
			<li id="ash_tab" class="current"><span><a href="javascript:mcTabs.displayTab('ash_tab','ash_panel');" onmousedown="return false;" style="font-size: 12px;">Syntax Highlighter Optimized</a></span></li>
		</ul>
	</div>
	
	<div class="panel_wrapper" style="font-size: 12px;line-height: 22px; padding: 2px 5px; margin: 0; display: block;">
		<!-- ash panel -->
		<div id="ash_panel" class="panel current" style="display: block; padding: 2px;">
            <label for="ash_lang" style="font-size: 12px;">Select language:
            <select name="ash_lang" id="ash_lang" style="font-size: 12px;">
              <option value="as3">ActionScript3</option>
              <option value="shell">Bash/shell</option>
              <option value="csharp">C#</option>
              <option value="cpp">C++</option>
              <option value="css">CSS</option>
              <option value="delphi">Delphi</option>
              <option value="diff">Diff</option>
              <option value="groovy">Groovy</option>
              <option value="js">JavaScript</option>
              <option value="java">Java</option>
              <option value="perl">Perl</option>
              <option value="php">PHP</option>
              <option value="plain">Plain Text</option>
              <option value="py">Python</option>
              <option value="ruby">Ruby</option>
              <option value="scala">Scala</option>
              <option value="sql">SQL</option>
              <option value="vb">Visual Basic</option>
              <option value="xml">XML</option>              
            </select></label><br />
            <label for="ash_code" style="font-size: 12px;">Input code:
            <textarea id="ash_code" name="ash_code" style="width: 98%; margin:0 auto; height:250px;font-size: 12px;" /></textarea>
          	</label>
		</div>
		<!-- end ash panel -->
		<div style="clear:both;height: 1px;"></div>
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="submit" id="insert" name="insert" value="Insert" onclick="insertShcLink();" />
		</div>

		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
		</div>
	</div>
</form>
</body>
</html>
<?php

?>