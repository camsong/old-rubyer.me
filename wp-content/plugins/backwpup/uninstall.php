<?PHP
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
	die();
}

global $wp_version;
$blugurl=get_option('siteurl');
if (defined('WP_SITEURL'))
	$blugurl=WP_SITEURL;
wp_remote_post( 'https://api.backwpup.com', array( 'sslverify' => false, 'body'=>array('URL'=>$blugurl,'ACTION'=>'delete'), 'user-agent'=>'BackWPup'));
delete_option('backwpup');
delete_option('backwpup_jobs');
?>
