=== WordPress EZ Backup ===
Contributors: SangrelX
Donate link: http://lastnightsdesigns.com/?page_id=121
Tags: easy, backup, files, database, mysql, site
Requires at least: 2.8.2
Tested up to: 2.9.2
Stable tag: 4.9

Backup & Archive Folders or Files & Any MySQL Database all from the Easy to use WordPress Administration Panel. Many Robust Features Included.

== Description ==

WordPress EZ Backup is A Administrators Plugin to allow the easiest most feature rich method for creating Backup Archives of your entire Site (not just WP Installations but Any part of your site or webspace) & allows backup archives of any MySQL Database you choose & More. Please take your time to Check out this neat plugin. WordPress EZ Backup started as a Private plugin I used on my own & now im bringing it to everyone.

== Installation ==

WAIVER OF RESPONSIBILITY
Neither Myself nor anyone Distributing this Plugin are to be held responsible for any damages, lost data, corrupted files or the alike that may occur be it due to misconfiguration or error. Your using this plugin at your own risk. This plugin has been tested thoroughly for functionality & safety.

This Plugin was Created on the Following Configuration
PHP Version 5.2.9
MySQL Version 5.0.81-community
Apache Version 2.2.11 (UNIX)

The WordPress EZ Backup is designed to function ONLY on Linux/*NIX based operating systems & will use certain unix Commands in order to perform its functions. If you find this plugin does NOT work on your Webhosting. Please Contact me for Support, Bug Reporting, or General Comments. It may be possible your Webhosting has blocked the ability to run the functions required to create the backups. A future version will be released that does not require Unix Commands. However this future version will NOT be able to create backups of large sites due to PHP Limitations.

1. Download Plugin
2. Upload the Plugin Folder to your wp-content/plugins folder
STEP 2 ALTERNATE METHOD: In your WP Admin Panels select Plugins - Add New - Upload & then simply upload the Plugin from your local pc ex. wp-ezbackup.zip

3. Activate the Plugin
4. Go to your newly installed plugin menu labeled EZ Backup & Configure your plugin


NOTICE WHEN USING AUTOMATIC UPGRADING
When using Automatic Upgrading please make sure to also Manually Deactivate & Reactivate your plugin. To ensure all changes are in effect


== Plugin Configuration ==

Please note in the Plugin Settings beside each field is a Example Section. The Sample section for each field will display very helpful information per setting. in cases where your required to specify file paths you will find the Example section beside that area is showing your current user path for you already. these paths are most likely the correct paths for your server. however if you know different please enter the paths you know to use.
Please Click the new label EZ Backup on the Bottom of your Admin Menu. See the list below for explanation of each Setting & to further understand how to set them.

1. What Directory to Backup - You will need to enter the full path to the Directory you wish to backup (ALL Files & Folders within the Directory will be backed up) Please enter the full path WITHOUT the Trailing Slash See Example Below & also take note of the Example Section on the Plugins Settings Page
Example: /home/user_112/public_html (This would make a backup of Everything within the public_html folder for user_112)
Your public_html folder is the usual default folder that ALL your site is stored under. in general your Public Site like the name says

2. What Database to Backup - You must use the Prefix for your Database name setting see the example on the settings page as it should have filled in your database prefix for you. for example if your user directory is named nighttime your Database prefix would be nighttime_databasename (database name being the name of the actual database)

3. Name your Backup - any name you want to assign to the backup archive. any name you choose will have the date automatically added to the end of it. example MyBackup2009-01-24

4. Save Archive Where - Save your backup to what Directory? Please use FULL Paths just like with the First Setting. However you MUST Note that you should save your Backups outside of the public folder (commonly public_html) especially if your saving the entire public_html directory. this avoids errors with creating your backup.

5. Send E-Mail alert to who? - Enter your E-mail address here to receive alerts upon backup completion (Leave this Blank to disable E-mailing Alerts)

6. Send A copy of the backup to your E-mail? - Pretty easy to understand this one lol

7. SQL Server Address - Commonly this is localhost. but if you know different then enter the address to your sql server here.

8. SQL Server Username - your user name will be set the same way you set the database table section. Must use the Prefix example: nighttime_backups

9. SQL Password - Simply enter the password to the SQL Server username you set.

== Features ==
1. Backup selected Directory and all files within.
2. Backup selected Database.
3. View Live log file of the Backup Procedure.
4. Choose to enable E-mail alerts upon backup completion.
5. Viewable Error Logs direct from the Plugin.
6. Viewable Procedure Logs direct from the Plugin.
7. Simple to use interface with interactive Help & Auto Settings pulled from your current webserver configuration.
5. Choose to have a copy of the Backup e-mailed to you.
6. Browse currently created Backups
7. Automatically schedule backup creation with the Scheduler.

== Screenshots ==
1. WordPress EZ Backup Settings & Information
2. WordPress EZ Backup Creation
3. WordPress EZ Backup Browser
4. WordPress EZ Backup Schedule Creation

== Uninstallation ==
1. Deactivating the Plugin will cause it to remove its Database tables so if you ever wish to stop using it. you will not have trash left over from it

== Frequently Asked Questions ==

= What does Prefix mean in terms of configuring this plugin =

Prefix means that a setting requires the users path prefix on their webserver. This prefix is called for you automatically and displayed in the Example section on the Configuration page to help you in configuring your settings. Follow the Example unless you know different

= Why do I get a "file changed as we read it" error in my Error Log =

This is A result of placing the Backup Archive in A Directory that your including in the Backup. If your trying to backup say public_html and your storing the backups in public_html/backups. The backup procedure will fail with this error because you cannot make a backup into a Directory that its trying to save into A archive. its a bit confusing i know but This is A fatal error. The archive that was created will not be complete. in order to Avoid this - Save your backups outside of any directory that will be included in the actual backup archive.

= Why do I have a blank index.html file in each folder of this plugin =

this is simply to prevent anyone from direct linking aka indexing your plugins directories & folders. if you wish & know how to do so. You could simply use your webhosting controls to adjust indexing on your site.

= How does the Backup Browser Function =

With the Backup Browser Addon. You can have more control over the Backups created without actually having to Login to your webhosting controls.

= How does E-Mailing the Backup as an Attachment Work =

With the Attachments Addon you can E-Mail A copy of your Created Backup as an Attachment with the Alerts E-Mails.

= How does the Automatic Scheduling work =

This Addon can be purchased & used to setup Automatic Creation of your Backups. It will take advantage of a widely used tool called Cron Daemon. This tool is used on the vast majority of Webhosting services & with this option you have an easy to use system for adjusting your scheduled tasks for your website & not just the tasks for EZ Backup but all tasks for your website can be adjusted with this addon.

= I upgraded to 4.0 but some options dont work =

DEACTIVATE then REACTIVATE the plugin. if something still doesnt work feel free to E-mail me.

== Changelog ==

= 4.9 =
Fixed Cosmetic issues - touched up and cleaned up some code - dropped all previous versions

= 4.8 =
Fixed A Fatal Error - Darn unix to dos conversions .... Please use this version NOT 4.7

= 4.7 =
Added more error checking code to the script to make it easier to figure out whats going wrong if the plugin is not working & Dropped support for all previous versions!

= 4.6 =
Fixed a few more issues Added directory check to ensure target for backup exists before continuing! & fixed an issue with passwords that use special characters exanple: !@#$%^&*() etc..

= 4.5 =
Fixed A Security issue - Highly recommend upgrading as soon as possible to patch this potential issue

= 4.4 =
Fixed Cosmetic Error that could Confuse some people about what Database is being Backed up.. Error Found by Curtis from http://www.htmlcenter.com/blog/upgrading-your-wordpress-installation

= 4.3 =
Fixed A small error with a scripting file. NON Fatal Error - DROPPED Support for ALL PREVIOUS VERSIONS

= 4.2 =
Fixed small error in Scheduling page and some CSS cross browser issues!

= 4.1 =
Fixed minor Bug with Texts This bug does not effect Function of the plugin submitted by navjotjsingh

= 4.0 =
Released all Addons for Free. Have fun guys!

= 3.3 =
Added additional page to plugin menu Help & Information

= 3.2 =
Added more premium options to plugin

= 3.1 =
Cleaned up some code remnants. nothing that will effect the functionality of the Plugin.

= 3.0 =
Made Many major updates to the plugin added the ability to purchase addons for your WordPress EZ-Backup Plugin

= 2.0 =
Made changes to certain texts, spelling, punctuation. just fixing up a few minor things.

= 1.0 =
First Release

= Deprecated Versions =
Betas 1 through 8 are officially dropped. there were issues with a few minor details & some coding that have been resolved in 1.0 up