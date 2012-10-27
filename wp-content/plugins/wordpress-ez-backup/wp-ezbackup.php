<?php
/*
Plugin Name: WordPress EZ Backup
Plugin URI: http://lastnightsdesigns.com/?page_id=121
Description: Fast Creation of Full Site Backups & Database Backups. Simply adjust your settings & Create your Backup. Features E-mail Alert & E-Mailing Backups, Viewing Live Log files of the backup procedure, Backup Browser & Automated Backups with Scheduling. If using Automatic Upgrading please Manually DEACTIVATE & REACTIVATE your plugin when your upgrade is complete.
Version: 4.9
Author: SangrelX
Author URI: http://lastnightsdesigns.com
*/

function ez_stylesheet() {
    $style = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
    echo '<link rel="stylesheet" type="text/css" href="' . $style . 'functions/ezstyle.css" />';
}

add_action('admin_head', 'ez_stylesheet');
add_option("ezbu_db_version", "4.0Beta");

function ezbu_installer() {
    $chmods = dirname(__FILE__);
    $sh1 = $chmods.''."/functions/backup.sh";
    $sh2 = $chmods.''."/functions/functions.sh";
    chmod("$sh1", 0700);
    chmod("$sh2", 0700);



   global $wpdb;
   global $ezbu_db_version;

   $table_name = $wpdb->prefix . "ezbu_settings";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

	$sql = "CREATE TABLE " . $table_name . " ( id mediumint(9) NOT NULL AUTO_INCREMENT, db_server text NOT NULL, db_username text NOT NULL, db_password varchar(64) NOT NULL, db_name text NOT NULL, backup_what longtext NOT NULL, save_where longtext NOT NULL, name_what longtext NOT NULL, send_email varchar(64) NOT NULL, conf_dir longtext NOT NULL, attach_mail varchar(64) NOT NULL, UNIQUE KEY id (id))";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	$server = "";

	$username = "";

	$password = "";

	$database = "";

	$backupwhat = "";

	$savewhere = "";

        $name_what = ""; 

        $send_email = "";

        $conf_dir = "";

        $attach_mail = "";

	$insert = "INSERT INTO " . $table_name . " (db_server, db_username, db_password, db_name, backup_what, save_where, name_what, send_email, conf_dir, attach_mail) VALUES ('" . $wpdb->escape($server) . "','" . $wpdb->escape($username) . "','" . $wpdb->escape($password) . "','" . $wpdb->escape($database) . "','" . $wpdb->escape($backupwhat) . "','" . $wpdb->escape($savewhere) . "','" . $wpdb->escape($name_what) . "','" . $wpdb->escape($send_email) . "','" . $wpdb->escape($conf_dir) . "','" . $wpdb->escape($attach_mail) . "')";

	$results = $wpdb->query( $insert );

        add_option("ezbu_db_version", $ezbu_db_version);

	}
}

function ezbu_uninstall() {
   global $wpdb;
   $table_name = $wpdb->prefix . "ezbu_settings";
   $sqldrop = "DROP TABLE IF EXISTS $table_name";
   $results = $wpdb->query( $sqldrop );
}


register_activation_hook( __FILE__, 'ezbu_installer' );
register_deactivation_hook( __FILE__, 'ezbu_uninstall' );
add_action('admin_menu', 'ezbu_plugin_menu');

function ezbu_plugin_menu() {

  add_menu_page('EZ Backup Settings', 'EZ Backup', 8, __FILE__, ezbu_main_menu);
  add_submenu_page(__FILE__, 'Creating A Backup', 'Run Backup', 8, 'ezbackup-sub-page', ezbu_sub_menu); 
  add_submenu_page(__FILE__, 'Browse Backups', 'Backup Browser', 8, 'ezbackup-sub-page1', ezbu_sub_menu1);
  add_submenu_page(__FILE__, 'Scheduling', 'Scheduling', 8, 'ezbackup-sub-page2', ezbu_sub_menu2);
  add_submenu_page(__FILE__, 'Help & Information', 'Help', 8, 'ezbackup-sub-page3', ezbu_sub_menu3);
}

function ezbu_sub_menu3() {
echo '<h2>Help & Additional Information</h2>';
echo 'I am glad to provide assistance with this plugin & its use to anyone who needs it simply <a href="mailto:J.Garber@lastnightsdesigns.com?subject=EZ%20Backup%20Help">E-mail</a> me with your questions, comments, or issues. If you find that your current Web Host Cannot or Does not allow you to perform some of the Functions that this plugin requires for your site. please feel free to checkout our webhost who has provided excellent services & Support<br /><br /><a href="http://northportws.com/" target="_blank">NorthPort Web Services<br /><img src="http://www.northportws.com/images/logo.jpg" width="289" height="90"></a>';
echo '<br /><br /><h2>Why did you decide to release the previous premium addons for Free</h2>well like everyone else I have bills to pay & need all the funds I can get. However Since WordPress has always been released Freely & Plugins are generally done & released Freely. I decided to do the same & will be Gracious for any and all donations that I may receive. for now Everyone please enjoy this tool/plugin I find it useful hopefully you guys will too.';
}


function ezbu_sub_menu2() {
        $ppath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
?>
        <SCRIPT SRC="<?php echo $ppath; ?>functions/boxover.js"></SCRIPT>

<?php
        echo '<h2>Automatic Backups</h2>';
        echo '<p>Automatic Backups will take control of the most widely used feature of most webhosting panels today. The Cron Daemon. Cron is a tool that allows A user to schedule automatic tasks to run at a specific time & date. Using the form below you can Generate a proper cron command for your backups & then enter that command to your current cron schedule. Simply Generate it & save it!.</p>';
        $scriptpath = dirname(__FILE__);
        $cronfile = $scriptpath.''."/functions/schedule.jbz";
        $backupfile = $scriptpath.''."/functions/backup.sh";

        // dump current cron jobs from crontab
        if (file_exists($cronfile)) {
        }else{
        $crondump = "crontab -l > $cronfile";
        shell_exec("$crondump");
        $sh3 = $scriptpath.''."/functions/schedule.jbz";
        chmod("$sh3", 0700);
          }

        // open current jobs file
        $currentjobs = "$cronfile";
        $joblist = fopen($currentjobs, 'r') or die();
        $theData = fread($joblist, filesize($currentjobs));
        fclose($joblist);

if (isset($_POST['submit'])) {
$day = $_POST['days'];
$time = $_POST['time'];
$command = $_POST['command'];
$logfile = $scriptpath.''."/logs/log.txt";
$errorlog = $scriptpath.''."/logs/errorlog.txt";
$newline = "0 $time * * $day $backupfile > $logfile 2> $errorlog";
}//end of submit function


if (isset($_POST['submit_sched'])) {
$schedulepost = $_POST['newfile'];
$trim= StripSlashes($schedulepost);
$newschedule = trim($trim);

$writeit = "$cronfile";
$fh1 = fopen($writeit, 'w') or die("can't open file");
$stringData = "$newschedule\n";
fwrite($fh1, $stringData);
fclose($fh1);
echo '<p class="ezupdatedstatic">Refreshing your Schedule to Show Saved Changes... Please Hold</p>';
shell_exec("crontab $cronfile");
echo "<meta http-equiv='refresh' content='0'>";
}

if (isset($_POST['crontest'])) {
        if (file_exists($cronfile)) {
          echo '<p class="ezupdatedstatic">Congratulations! Your Webspace supports running Cron Jobs. You may continue to schedule your Automatic Backups</p>';
        }else{
          echo '<p class="ezupdatedstatic">SORRY - Your Webhosting Provider does not allow you to run Scheduled Tasks</p>';
           }

}


?>
<h2>Does my Webhost use Cron?</h2><p>Click the Button below to do a Quick test for your Cron Daemon. If Cron is not found please feel free to Contact me for further assistance. OR simply skip using Automated Backups!</p><form method="post"><input type="submit" name="crontest" value="Check for Cron Daemon"></form>
<h2>Create The Schedule</h2><form method="post">
<table border="0" width="100%">
  <tr>
     <td width="319">What Day of the Week do you wish to run Backups</td>
     <td><select name="days" align="left">
  <option value="1">Monday</option>
  <option value="2">Tuesday</option>
  <option value="3">Wednesday</option>
  <option value="4">Thursday</option>
  <option value="5">Friday</option>
  <option value="6">Saturday</option>
  <option value="7">Sunday</option>
</select></td>
  </tr>
  <tr>
<td width="319">What Time of Day do you wish to Run Backups</td>
<td><select name="time" align="left">
  <option value="0">Midnight</option>
  <option value="1">1 AM</option>
  <option value="2">2 AM</option>
  <option value="3">3 AM</option>
  <option value="4">4 AM</option>
  <option value="5">5 AM</option>
  <option value="6">6 AM</option>
  <option value="7">7 AM</option>
  <option value="8">8 AM</option>
  <option value="9">9 AM</option>
  <option value="10">10 AM</option>
  <option value="11">11 AM</option>
  <option value="12">12 NOON</option>  
  <option value="13">1 PM</option>
  <option value="14">2 PM</option>
  <option value="15">3 PM</option>
  <option value="16">4 PM</option>
  <option value="17">5 PM</option>
  <option value="18">6 PM</option>
  <option value="19">7 PM</option>
  <option value="20">8 PM</option>
  <option value="21">9 PM</option>
  <option value="22">10 PM</option>
  <option value="23">11 PM</option>                               
</select>
  </td>
  </tr>
  <tr><td width="319"><div title="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[Create Schedule Command] body=[Simply copy this command & paste it to your Current Schedule Below. if your changing the time your backups run. Then Replace the current backup command with this new one.]">Copy & Paste<img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""><input type="submit" name="submit" value="Generate Command"></div></td>
  <td><input type="text" size="32" name"command" value="<?php echo $newline ?>"></td>
   </tr>
<tr>
<td></td>
<td></td>
</tr>
</table></form><br />
<?php
echo '<h2>Your Current Schedule File</h2>How to Read Cron Jobs: MAILTO Command is the E-Mail Address the Cron Daemon Sends reports to. This setting will be ignored by this plugin so you can leave it Blank (MAILTO"") or set it to your Email Address (MAILTO"YourEmail@email.com").<br /><br />The First 5 Digits in Each Command are "Minute" - "Hour(24hr Format)" - "Day of Month" - "Month" - "Day of Week" Then it will show what Command it will be running at that scheduled time. Pretty easy once you understand it all.<br />';
echo '
<form method="post">
<textarea name="newfile" rows="20" cols="70">';
echo $theData;
echo '</textarea><br />';
?>

<div title="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[Save New Schedule] body=[Now that you have pasted the new command into your current schedule. Save it and your done. You have just scheduled your backup to run at the specified time and The Cron Daemon will do the rest.]"><img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""><input type="submit" name="submit_sched" value="Save Schedule"></div>
</form>

<?php

}  //END of Submenu for Scheduler



function ezbu_sub_menu1() {
        $ppath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
?>
        <SCRIPT SRC="<?php echo $ppath; ?>functions/boxover.js"></SCRIPT>
<?php

	global $wpdb;
        $table_name = $wpdb->prefix . "ezbu_settings";
        $current_options = $wpdb->get_results("SELECT save_where , backup_what FROM $table_name WHERE id = 1 ");
        foreach ($current_options as $option) { };
        $path = $option->save_where;
        $save_file = $option->backup_what;
        $send_file = $_POST['file'];
        $file = $path .'/'. $send_file;
        $newfile = $save_file .'/BackupDownloads/'. $send_file;

        //strips down to get base url of the site
        $stripwp = spliti ("/", $ppath, 4);
        $stripwp1 = $stripwp['2'];


        //strips down to get main backup dir setting
        $stripfile = spliti ("/", $save_file, 8);
        $stripfile1 = $stripfile['4'];

      if (empty($stripfile1)) {
        $stripfile1 = $stripfile['4'].'/';
      }else{
        $stripfile1 = '/'.$stripfile['4'].'/';
       };


        $baselink = $stripwp1 . $stripfile1 .'BackupDownloads/'. $send_file;





        $mkdir = $save_file .'/'."BackupDownloads";
        echo '<h2>Backup Browser</h2>';
        echo '<p>The Browser allows you to Prepare Backups for Downloading based on the Path you have been backing up. Once you prepare A download it will be located in a fully accessable public location on your webspace. You can access & download it via the Internet, or simply Download it using this page.<br /><br />This 2 for 1 feature allows you access to your backups without having to login to the WordPress Administration Panel. To browse any other directory you have backups in please <a href="./admin.php?page=wordpress-ez-backup/wp-ezbackup.php" target="_self">adjust your settings</a><br /><br /></p>';
        echo 'Saving Public Downloads to:';
        echo $save_file;
        echo '<br />';
        echo 'List of Current Backups';
        echo $path;
        echo '<form method="post">';
        $dhandle = opendir($path);
        // define an array to hold the files
        $files = array();
        if ($dhandle) {
   // loop through all of the files
   while (false !== ($fname = readdir($dhandle))) {
      // if the file is not this file, and does not start with a '.' or '..',
      // then store it for later display
      if (($fname != '.') && ($fname != '..') && ($fname != '.htaccess') &&
          ($fname != basename($_SERVER['PHP_SELF']))) {
          // store the filename
          $files[] = (is_dir( "./$fname" )) ? "(Dir) {$fname}" : $fname;
      }
   }
   // close the directory
   closedir($dhandle);
}

echo "<select name=\"file\">";
// Now loop through the files, echoing out a new select option for each one
foreach( $files as $fname )
{
   echo "<option>{$fname}</option><br />";
}
   echo '</select>';
?>
<br /><table width="80" border="1" height="163">
<tr>
<td>
<div title="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[Prepare Download] body=[This option will create A copy of your Selected Backup &amp; place it in a Publicy Accessable Folder in your webspace 'BackupDownloads']"><input type="submit" name="download" value="Prepare for Download" onclick="return confirm('Are you sure you want to add this Backup to your Public Directory? ')"></div> --------------------
<div title="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[Remove Public Copy] body=[Will remove the copy of the Selected Archive from the Public Folder 'BackupDownloads']"><input type="submit" name="clean" value="Remove from Public Directory" onclick="return confirm('Are you sure you want to delete this backup from your Public Directory? ')"></div>--------------------
<div title="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[Remove All Public Backups] body=[This option will completely remove the entire 'BackupDownloads' Directory from Current Backup Choice. The 'BackupDownloads' Folder is always created inside the Directory you are creating A backup of.]"><input type="submit" name="purge_all" value="Remove all Public Archives" onclick="return confirm('Are you sure you want to Permanently remove all Public Backups')"></div>--------------------
<div title="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[Permanently Delete Backup] body=[This will Permanently remove the Selected Backup from your Backups Folder &amp; Public Downloads Folder 'BackupDownloads']"><input type="submit" name="trash" value="Permanently Delete Backup" onclick="return confirm('Are you sure you want to Permanently Delete this backup? ')"></div>
</form>
</td></tr></table>
<br />
<?php
if (isset($_POST['trash'])) {
if (file_exists($file)) {
unlink($file);
echo '<p class="ezupdatedstatic1">Archive Deleted from<br />';
echo $file;
echo '<br />';
}
if (file_exists ($newfile)) {
unlink($newfile);
echo 'Checking for Copy of Backup in Public Downloads<br />';
echo 'Deleted Copy of Archive found in Public Downloads at<br />';
echo $newfile;
echo '<br /><br />';
echo 'Refreshing File Browser List in 5 Seconds</p>';
echo '</p>';
echo "<meta http-equiv='refresh' content='5'>"; 
}else{
echo 'Checking for Copy of Backup in Public Downloads<br />';
echo 'No Copy Found in Public Downloads Directory<br /><br />';
echo 'Refreshing File Browser List in 5 Seconds</p>';
echo "<meta http-equiv='refresh' content='5'>"; 
}
}
function delete_directory($mkdir) {
if (is_dir($mkdir))
$dir_handle = opendir($mkdir);
if (!$dir_handle)
return false;
while($file = readdir($dir_handle)) {
if ($file != "." && $file != "..") {
if (!is_dir($mkdir."/".$file))
unlink($mkdir."/".$file);
else
delete_directory($mkdir.'/'.$file); 
}
}
closedir($dir_handle);
rmdir($mkdir);
return true;

} 
if (isset($_POST['purge_all'])) {
delete_directory($mkdir);
echo '<p class="ezupdatedstatic1">Entire Public Directory located at ';
echo $mkdir;
echo ' has been removed.';
}
if (isset($_POST['download'])) {
if (is_dir($mkdir)) {
}else{
mkdir($mkdir, 0755);
}
if (file_exists($newfile)) {
echo '<p class=ezupdatedstatic1>Backup has previously been copied to the Public Downloads Directory<br />';
    echo "<a href=\"http://$baselink\">Download Now!</a></p>";
}else{
if (!copy($file, $newfile)) {
    echo "failed to copy $file...\n";
   }
echo '<p class=ezupdatedstatic1>Download is Ready<br />';
    echo "<a href=\"http://$baselink\">Download Now!</a></p>";
}
}
if (isset($_POST['clean'])) {
if (file_exists($newfile)) {
unlink($newfile);
echo '<p class=ezupdatedstatic1>File has been removed from ';
echo $newfile;
echo '</p>';
}else{
echo '<p class=ezupdatedstatic1>File does not exist. you must have cleared it previously.</p>';
}
}
} //END OF SUB MENU OVERALL
function ezbu_sub_menu() {
        $scriptpath = dirname(__FILE__);
        $javapath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
?>
        <SCRIPT SRC="<?php echo $javapath; ?>functions/boxover.js"></SCRIPT>
<?php

        $chmods = dirname(__FILE__);
        $sh1 = $chmods.''."/functions/backup.sh";
        $sh2 = $chmods.''."/functions/functions.sh";

        chmod("$sh1", 0700);
        chmod("$sh2", 0700);
        $marker = $scriptpath.''."/functions/marker.mk";
        if (file_exists($marker)) {
        unlink($marker);
        }
	global $wpdb;
        $table_name = $wpdb->prefix . "ezbu_settings";
        $current_options = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1 ");
        foreach ($current_options as $option) { };
        $db_server = $option->db_server;
        $db_username = $option->db_username;
        $db_password = $option->db_password;
        $db_name = $option->db_name;
        $backup_what = $option->backup_what;
        $save_where = $option->save_where;
        $name_what = $option->name_what;
        $send_email = $option->send_email;
        $attach_mail = $option->attach_mail;
        echo '<h2>Create your Site Backup</h2>';
        echo '<p> Please Verify the Below Settings are Correct before continuing!<br />';
        echo "<b>Backup Directory:</b> $backup_what<br />";
        echo "<b>Backup Database:</b> $db_name<br />";
        echo "<b>Backup/Archive Name:</b> $name_what<br />";
        echo "<b>Save to:</b> $save_where<br />";
        if ($send_email == ""){
        echo "<b>Send E-mail to:</b>DISABLED<br />";
          }else{
        echo "<b>Send E-mail to:</b> $send_email<br />";
         };
                if ($attach_mail == ""){
        echo "<b>Attach Copy to E-Mail:</b>DISABLED<br />";
          }else{
        echo "<b>Attach Copy to E-Mail:</b>YES<br />";
         };
        echo "<b>SQL Server:</b> $db_server<br />";
        echo "<b>SQL Username:</b> $db_username<br />";
        echo "<b>SQL Password:</b> **********<br />";
        echo 'if the settings above appear correct - Click Create Backup to Continue.<br />or<a href="./admin.php?page=wordpress-ez-backup/wp-ezbackup.php" target="_self">Adjust Settings</a></p>';

?>
           <script language="Javascript" type="text/javascript">
           function createTarget(t){
           window.open("<?php echo $javapath; ?>functions/backupstats.php", t, "scrollbars=yes,width=700,height=700");
           return true;
           }
           function createTarget1(a1){
           window.open("<?php echo $javapath; ?>functions/viewlog.php", a1, "scrollbars=yes,width=700,height=700");
           return true;
           }
           function createTarget2(b2){
           window.open("<?php echo $javapath; ?>functions/errorlog.php", b2, "scrollbars=yes,width=700,height=700");
           return true;
           }

         </script>
         <form method="post">
         <input type="hidden" value="1" name="run_update">
         <input type="submit" value="Create Backup" name="submit" onClick="createTarget('t');">
         </form>
         <br />
         <br />
         <form method="post">
               View Log File from Previous Backup:<input type="submit" value="View Log File" name="submit" onClick="createTarget1('a1');">
         </form>
         <br />
         <form method="post">
               View Error Log File from Previous Backup:<input type="submit" value="View Error Log" name="submit" onClick="createTarget2('b2');">
               </form>
                  

<?php        


          $run_update = $_POST['run_update'];


          if ($run_update == 1){
        $scriptpath = dirname(__FILE__);
        $marker = $scriptpath.''."/functions/marker.mk";
        $make_it = fopen($marker, "w");
        fclose($make_it);
        $backupscript = $scriptpath.''."/functions/backup.sh";
        $logfile = $scriptpath.''."/logs/log.txt";
        $errorlog = $scriptpath.''."/logs/errorlog.txt";
        $command = "$backupscript > $logfile 2> $errorlog";
        sleep(2);
        shell_exec($command);
        echo '<p class=ezupdatedstatic>Backup Complete</p>';
        $marker = $scriptpath.''."/functions/marker.mk";
        if (file_exists($marker)) {
        unlink($marker);
        }

      }

}       



function ezbu_main_menu() {
$ppath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
?>
        <SCRIPT SRC="<?php echo $ppath; ?>functions/boxover.js"></SCRIPT>
<h2>WordPress EZ Backup by SangrelX </h2><br />
<p> Please take your time now to adjust the settings below. if you have trouble understanding what A certain setting is for, move your mouse over the little help icon to read more information on it. You will find the information you need for most of the settings right beside them in their example. If you find you need more help please feel free to E-mail me<br />
<table border="0" width="269" height="192" align="left">
<tr>
<td width="169"><div title="cssbody=[dvbdy2] cssheader=[dvhdr2] header=[About The Author &amp; Plugin] body=[WordPress EZ Backup was created for private use on my own development sites &amp; client projects. I figured I would release it to the general public in case someone else might find it as useful as I do. Keep an eye on this plugins progress I am thinking of releasing another version with full scheduling ability for automated backups, &amp; other robust features.]" align="center"><img border="0" alt="" src="<?php echo $ppath; ?>/images/rocktar.jpg" width="125" height="125"><br>About The Author &amp; Plugin</div></td>
<td width="31">E-Mail: <a href="mailto:J.Garber@lastnightsdesigns.com?subject=EZ%20Backup%20Help">SangrelX</a><br />Donations are also Appreciated<br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post"  target="_blank">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="7061873">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></td>

<td width="35"></td>
</tr>
</table></p>







<form method="post">

<?php
                global $wpdb;
        	$update_yes = $_POST['form_update'];
        	$table_name = $wpdb->prefix . "ezbu_settings";
        	$new_server = $_POST['db_server'];
        	$new_username = $_POST['db_username'];
	$new_password = $_POST['db_password'];
	$new_dbname = $_POST['db_name'];
	$new_backup = $_POST['backup_what'];
	$new_save = $_POST['save_where'];
        $new_name = $_POST['name_what'];
        $new_email = $_POST['send_email'];

        $scriptpath = dirname(__FILE__);
        $backupfile = $scriptpath.''."/functions/functions.sh";
        $scriptfile = $scriptpath.''."/functions/backup.sh";

if(isset($_POST['attach_mail'])){
$attach_mail = "checked=\"yes\"";
}else{
$attach_mail ="";
}

        if ($update_yes == 1) {
	global $wpdb;
         $wpdb->get_results("UPDATE $table_name SET db_server = '$new_server' , db_username= '$new_username' , db_password= '$new_password' , db_name= '$new_dbname' , backup_what= '$new_backup' , save_where= '$new_save' , name_what= '$new_name' , send_email= '$new_email' , conf_dir= '$backupfile' , attach_mail= '$attach_mail' WHERE id = '1' "); 

$confdirtxt = '$confdir';

$fh = fopen($scriptfile, 'w+') or die("cannot open file");
$stringData1 = "#!/bin/bash\n";
$stringData2 = "#\n";
$stringData3 = "# fullsitebackup.sh V1.0\n";
$stringData4 = "#\n";
$stringData5 = "dbhost=$new_server\n";
$stringData6 = "dbuser=$new_username\n";
$stringData7 = "dbpass='$new_password'\n";
$stringData8 = "dbname=$new_dbname\n";
$stringData9 = "webrootdir=$new_backup\n";
$stringData10 = "tempdir=$new_save\n";
$stringData11 = "tarnamebase=$new_name\n";
$stringData12 = "email=$new_email\n";
$stringData13 = "confdir=$backupfile\n";


if(isset($_POST['attach_mail'])){
$stringData14 = "attach=yes\n";
}else{
$stringData14 = "attach=\n";
}



$stringData15 = ". $confdirtxt";
fwrite($fh, $stringData1);
fwrite($fh, $stringData2);
fwrite($fh, $stringData3);
fwrite($fh, $stringData4);
fwrite($fh, $stringData5);
fwrite($fh, $stringData6);
fwrite($fh, $stringData7);
fwrite($fh, $stringData8);
fwrite($fh, $stringData9);
fwrite($fh, $stringData10);
fwrite($fh, $stringData11);
fwrite($fh, $stringData12);
fwrite($fh, $stringData13);
fwrite($fh, $stringData14);
fwrite($fh, $stringData15);

fclose($fh);
echo ' <p class="ezupdated">All Settings have been updated<br />';
        echo $newattach_mail;

if ($new_email == '') {
echo ' E-mail Alerts Disabled - If you want them enabled please enter an E-mail Address in your Settings.<br />You will also not receive Attachments until you Enable E-Mail Alerts</p>';
   }else{
     echo '</p>';
   } 
}





        $saved_options = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1 ");
        foreach ($saved_options as $savedoption) {
          }

        $db_server = $savedoption->db_server;
        $db_username = $savedoption->db_username;
        $db_password = $savedoption->db_password;
        $db_name = $savedoption->db_name;
        $backup_what = $savedoption->backup_what;
        $save_where = $savedoption->save_where;
        $name_what = $savedoption->name_what;
        $send_email = $savedoption->send_email;
        $attach_mail = $savedoption->attach_mail;

        $userspath = $_SERVER['DOCUMENT_ROOT'];

        $replacepath = spliti ("/", $userspath, 12);
        $databasesave = $replacepath['1'];
        $databasepath = $replacepath['2']; 
?>

<div id="wrapper">
<table border="0"  width="100%">
  <tr>
     <td>What Directory to Backup:</td>
     <td><input type="text" name="backup_what" value="<?php echo $backup_what ?>"></td>
     <td><DIV TITLE="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[Directory to Backup] body=[You must assign Full Paths - The Example shows the current path to your main website files.]">Ex. <?php echo $_SERVER['DOCUMENT_ROOT']; ?>/mysite <img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""></DIV></td>
  </tr>
  <tr>
     <td>What Database to Backup:</td>
     <td><input type="text" name="db_name" value="<?php echo $db_name ?>"></td>
     <td><DIV TITLE="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[What Database] body=[Enter The Database name of the Database you wish to backup - The Example shows the most likely username prefix for your databases prefix_DBUser1]">Ex. <?php echo $databasepath; ?>_wrdp1 <img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""></DIV></td>	 
  </tr>
  <tr>
     <td>What Name to give your Backup:</td>
     <td><input type="text" name="name_what" value="<?php echo $name_what ?>"></td>
     <td><DIV TITLE="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[What Name] body=[Enter A file name to assign to your Backup. This Plugin will automatically attach a Date Stamp format YYYY-MM-DD to the End of the File Name you enter here.]">Ex. MySiteBackup2009-01-31 <img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""></DIV></td>	 
  </tr>
    <tr>
     <td>Save Backup Archive where:</td>
     <td><input type="text" name="save_where" value="<?php echo $save_where ?>"></td>
     <td><DIV TITLE="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[Save The Backup Where?] body=[Assign A Full path to where you wish to save your backup Archive NOTE: if you plan to backup your Full Site. Please Create the Backup Directory OUTSIDE of location your going to backup see Example.]">Ex. /<?php echo $databasesave; echo '/'; echo $databasepath; ?>/backups <img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""></DIV></td>	 
  </tr>
    <tr>
     <td>Send Email Alert to Who:</td>
     <td><input type="text" name="send_email" value="<?php echo $send_email ?>"></td>
     <td><DIV TITLE="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[E-Mail Alert to Who?] body=[You can receive an alert when A full backup is complete by setting your E-mail address here.]">Ex. MyName@myserver.com (BLANK TO DISABLE) <img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""></DIV></td>	 
  </tr>
          <tr>
     <td>Send Backup as E-Mail Attachment:</td>
     <td><input type="checkbox" name="attach_mail" <?php echo $attach_mail ?>></td>
     <td><DIV TITLE="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[Attach Backup in E-mail] body=[You can Attach the Backup Archive to your E-mail Alerts. Of course you MUST have E-mail Alerts Enabled so make sure you have Entered your E-mail Address]">Check Box to Send Backup with E-Mail Alerts<img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""></DIV></td> 
  </tr>
      <tr>
     <td>SQL Server Address:</td>
     <td><input type="text" name="db_server" value="<?php echo $db_server ?>"></td>
     <td><DIV TITLE="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[SQL Server] body=[Generally your SQL Server can be connected to using localhost - unless you know otherwise leave this set to localhost]">Ex. localhost <img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""></DIV></td>	 
  </tr>
        <tr>
     <td>SQL Server Username:</td>
     <td><input type="text" name="db_username" value="<?php echo $db_username ?>"></td>
     <td><DIV TITLE="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[SQL Username] body=[Enter your SQL Username that will be used to access and backup the Database - you must include the prefix like in the example]">Ex. <?php echo $databasepath; ?>_DBUser1 <img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""></DIV></td> 
  </tr>
          <tr>
     <td>SQL Server Password:</td>
     <td><input type="password" name="db_password" value="<?php echo $db_password ?>"></td>
     <td><DIV TITLE="cssbody=[dvbdy1] cssheader=[dvhdr1] header=[SQL Password] body=[Enter the Password you have assigned to the username that is being used to create the Database Backups]">Enter Password for your SQL username<img src="<?php echo $ppath; ?>/images/question.gif" width="16" height="16" alt="" border="0" align=""></DIV></td> 
  </tr>
</table>
<input type="hidden" value="1" name="form_update">
<input type="submit" value="Save Settings" name="submit"> : <a href="./admin.php?page=ezbackup-sub-page" target="_self">Create Backup</a>
</div>

<?php
}
?>