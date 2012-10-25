<?PHP
function dest_ftp() {
	global $WORKING,$STATIC;
	if (empty($STATIC['JOB']['ftphost']) or empty($STATIC['JOB']['ftpuser']) or empty($STATIC['JOB']['ftppass'])) {
		$WORKING['STEPSDONE'][]='DEST_FTP'; //set done	
		return;
	}
	$WORKING['STEPTODO']=2;
	trigger_error(sprintf(__('%d. try to sending backup file to a FTP Server...','backwpup'),$WORKING['DEST_FTP']['STEP_TRY']),E_USER_NOTICE);

	need_free_memory(filesize($STATIC['JOB']['backupdir'].$STATIC['backupfile'])*1.5);

	if ($STATIC['JOB']['ftpssl']) { //make SSL FTP connection
		if (function_exists('ftp_ssl_connect')) {
			$ftp_conn_id = ftp_ssl_connect($STATIC['JOB']['ftphost'],$STATIC['JOB']['ftphostport'],10);
			if ($ftp_conn_id) 
				trigger_error(sprintf(__('Connected by SSL-FTP to Server: %s','backwpup'),$STATIC['JOB']['ftphost'].':'.$STATIC['JOB']['ftphostport']),E_USER_NOTICE);
			else {
				trigger_error(sprintf(__('Can not connect by SSL-FTP to Server: %s','backwpup'),$STATIC['JOB']['ftphost'].':'.$STATIC['JOB']['ftphostport']),E_USER_ERROR);
				return false;
			}
		} else {
			trigger_error(__('PHP function to connect with SSL-FTP to server not exists!','backwpup'),E_USER_ERROR);
			return false;			
		}
	} else { //make normal FTP conection if SSL not work
		$ftp_conn_id = ftp_connect($STATIC['JOB']['ftphost'],$STATIC['JOB']['ftphostport'],10);
		if ($ftp_conn_id) 
			trigger_error(sprintf(__('Connected to FTP server: %s','backwpup'),$STATIC['JOB']['ftphost'].':'.$STATIC['JOB']['ftphostport']),E_USER_NOTICE);
		else {
			trigger_error(sprintf(__('Can not connect to FTP server: %s','backwpup'),$STATIC['JOB']['ftphost'].':'.$STATIC['JOB']['ftphostport']),E_USER_ERROR);
			return false;
		}
	}

	//FTP Login
	$loginok=false;
	trigger_error(sprintf(__('FTP Client command: %s','backwpup'),' USER '.$STATIC['JOB']['ftpuser']),E_USER_NOTICE);
	if ($loginok=ftp_login($ftp_conn_id, $STATIC['JOB']['ftpuser'], base64_decode($STATIC['JOB']['ftppass']))) {
		trigger_error(sprintf(__('FTP Server reply: %s','backwpup'),' User '.$STATIC['JOB']['ftpuser'].' logged in.'),E_USER_NOTICE);
	} else { //if PHP ftp login don't work use raw login
		$return=ftp_raw($ftp_conn_id,'USER '.$STATIC['JOB']['ftpuser']); 
		trigger_error(sprintf(__('FTP Server reply: %s','backwpup'),$return[0]),E_USER_NOTICE);
		if (substr(trim($return[0]),0,3)<=400) {
			trigger_error(sprintf(__('FTP Client command: %s','backwpup'),' PASS *******'),E_USER_NOTICE);
			$return=ftp_raw($ftp_conn_id,'PASS '.base64_decode($STATIC['JOB']['ftppass']));
			trigger_error(sprintf(__('FTP Server reply: %s','backwpup'),$return[0]),E_USER_NOTICE);
			if (substr(trim($return[0]),0,3)<=400) 
				$loginok=true;
		}
	}

	if (!$loginok)
		return false;

	//PASV
	trigger_error(sprintf(__('FTP Client command: %s','backwpup'),' PASV'),E_USER_NOTICE);
	if ($STATIC['JOB']['ftppasv']) {
		if (ftp_pasv($ftp_conn_id, true))
			trigger_error(sprintf(__('FTP Server reply: %s','backwpup'),__('Entering Passive Mode','backwpup')),E_USER_NOTICE);
		else
			trigger_error(sprintf(__('FTP Server reply: %s','backwpup'),__('Can not Entering Passive Mode','backwpup')),E_USER_WARNING);
	} else {
		if (ftp_pasv($ftp_conn_id, false))
			trigger_error(sprintf(__('FTP Server reply: %s','backwpup'),__('Entering Normal Mode','backwpup')),E_USER_NOTICE);
		else
			trigger_error(sprintf(__('FTP Server reply: %s','backwpup'),__('Can not Entering Normal Mode','backwpup')),E_USER_WARNING);		
	}
	//SYSTYPE
	trigger_error(sprintf(__('FTP Client command: %s','backwpup'),' SYST'),E_USER_NOTICE);
	$systype=ftp_systype($ftp_conn_id);
	if ($systype) 
		trigger_error(sprintf(__('FTP Server reply: %s','backwpup'),$systype),E_USER_NOTICE);
	else
		trigger_error(sprintf(__('FTP Server reply: %s','backwpup'),__('Error getting SYSTYPE','backwpup')),E_USER_ERROR);
	
	if ($WORKING['STEPDONE']==0) {
		//test ftp dir and create it f not exists
		$ftpdirs=explode("/", rtrim($STATIC['JOB']['ftpdir'],'/'));
		foreach ($ftpdirs as $ftpdir) {
			if (empty($ftpdir))
				continue;
			if (!@ftp_chdir($ftp_conn_id, $ftpdir)) {
				if (@ftp_mkdir($ftp_conn_id, $ftpdir)) {
					trigger_error(sprintf(__('FTP Folder "%s" created!','backwpup'),$ftpdir),E_USER_NOTICE);
					ftp_chdir($ftp_conn_id, $ftpdir);
				} else {
					trigger_error(sprintf(__('FTP Folder "%s" can not created!','backwpup'),$ftpdir),E_USER_ERROR);
					return false;
				}
			}
		}
		trigger_error(__('Upload to FTP now started ... ','backwpup'),E_USER_NOTICE);
		if (ftp_put($ftp_conn_id, $STATIC['JOB']['ftpdir'].$STATIC['backupfile'], $STATIC['JOB']['backupdir'].$STATIC['backupfile'], FTP_BINARY)) { //transfere file
			$WORKING['STEPTODO']=1+filesize($STATIC['JOB']['backupdir'].$STATIC['backupfile']);
			trigger_error(sprintf(__('Backup transferred to FTP server: %s','backwpup'),$STATIC['JOB']['ftpdir'].$STATIC['backupfile']),E_USER_NOTICE);
			$STATIC['JOB']['lastbackupdownloadurl']="ftp://".$STATIC['JOB']['ftpuser'].":".base64_decode($STATIC['JOB']['ftppass'])."@".$STATIC['JOB']['ftphost'].$STATIC['JOB']['ftpdir'].$STATIC['backupfile'];
			$WORKING['STEPSDONE'][]='DEST_FTP'; //set done
		} else
			trigger_error(__('Can not transfer backup to FTP server!','backwpup'),E_USER_ERROR);
	}
	
	if ($STATIC['JOB']['ftpmaxbackups']>0) { //Delete old backups
		$backupfilelist=array();
		if ($filelist=ftp_nlist($ftp_conn_id, $STATIC['JOB']['ftpdir'])) {
			foreach($filelist as $files) {
				if ($STATIC['JOB']['fileprefix'] == substr(basename($files),0,strlen($STATIC['JOB']['fileprefix'])) and $STATIC['JOB']['fileformart'] == substr(basename($files),-strlen($STATIC['JOB']['fileformart'])))
					$backupfilelist[]=basename($files);
			}
			if (sizeof($backupfilelist)>0) {
				rsort($backupfilelist);
				$numdeltefiles=0;
				for ($i=$STATIC['JOB']['ftpmaxbackups'];$i<sizeof($backupfilelist);$i++) {
					if (ftp_delete($ftp_conn_id, $STATIC['JOB']['ftpdir'].$backupfilelist[$i])) //delte files on ftp
					$numdeltefiles++;
					else
						trigger_error(sprintf(__('Can not delete "%s" on FTP server!','backwpup'),$STATIC['JOB']['ftpdir'].$backupfilelist[$i]),E_USER_ERROR);
				}
				if ($numdeltefiles>0)
					trigger_error(sprintf(_n('One file deleted on FTP Server','%d files deleted on FTP Server',$numdeltefiles,'backwpup'),$numdeltefiles),E_USER_NOTICE);
			}
		}
	}

	ftp_close($ftp_conn_id);
	$WORKING['STEPDONE']++;

}
?>