<?php
/*
Plugin Name: Backupify for WordPress
Plugin URI: http://www.backupify.com
Description: Backup of WordPress database and all files to your  Backupify account. Navigate to <a href="admin.php?page=Backupify"> Backupify</a> to get started.
Author: backupify.com
Author URI: http://backupify.com
Version: 1.0.5

Many thanks to Austin Matzko (http://www.ilfilosofo.com/) for his plugin WordPress Database Backup (http://www.ilfilosofo.com/blog/wp-db-backup) 

Copyright 2010 www.backupify.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA
*/

if (!function_exists('add_action'))
{
    require_once("../../../wp-config.php");
	if(get_option('backup_file_db'))define('backup_file_db', get_option('backup_file_db'));
	else define('backup_file_db', 0);
	if(get_option('backup_file_wp'))define('backup_file_wp', get_option('backup_file_wp'));
	else define('backup_file_wp', 0);
	if(get_option('backup_file_content'))define('backup_file_content', get_option('backup_file_content'));
	else define('backup_file_content', 0);
	if(get_option('id_last_backup_post'))define('id_last_backup_post', get_option('id_last_backup_post'));
	else define('id_last_backup_post', 0);
	if(get_option('oldbackupifyKey'))define('oldbackupifyKey', get_option('oldbackupifyKey'));
	if(get_option('backupifyKey'))define('backupifyKey', get_option('backupifyKey'));
}

$rand = substr(sha1(sha1( DB_PASSWORD )), -15);
global $backupify_content_dir, $backupify_content_url, $backupify_plugin_dir;
$backupify_content_dir = ( defined('WP_CONTENT_DIR') ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';
$backupify_content_url = ( defined('WP_CONTENT_URL') ) ? WP_CONTENT_URL : get_option('siteurl') . '/wp-content';
$backupify_plugin_dir = WP_CONTENT_DIR.'/plugins/wp-backupify/';

if ( ! defined('BACKUPIFY_DIR') ) {
	define('BACKUPIFY_DIR', $backupify_content_dir . '/backupify-'.$rand.'/');
}

if ( ! defined('BACKUPIFY_URL') ) {
	define('BACKUPIFY_URL', $backupify_content_url . '/backupify-'.$rand.'/');
}

if ( ! defined('ROWS_IN_SEGMENT') ) {
	define('ROWS_IN_SEGMENT', 100);
}

/** 
 * Set MOD_EVASIVE_OVERRIDE to true 
 * and increase EVASIVE_DELAY 
 * if the backup stops prematurely.
 */
// define('MOD_EVASIVE_OVERRIDE', false);
if ( ! defined('EVASIVE_DELAY') ) {
	define('EVASIVE_DELAY', '500');
}

if($_GET['token']&&$_GET['make']){
	if($_GET['make']=='first')$hash = base64_encode(sha1(backupifyKey.'backupify'));
	else $hash = base64_encode(sha1(oldbackupifyKey.'backupify'));
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo '<result>';
	echo '<version>1.87</version>';
	if(urldecode($_GET['token'])==$hash){
		switch($_GET['make']){
			case'first': echo "<status>Ok</status>";
						if(get_option('oldbackupifyKey'))update_option('oldbackupifyKey', backupifyKey);
						else add_option('oldbackupifyKey',backupifyKey);
						$BACKUPIFY = new Backupify();
						$newbackupifyKey = $BACKUPIFY-> makePassword(10);
						if(get_option('backupifyKey'))update_option('backupifyKey', $newbackupifyKey);
						else add_option('backupifyKey', $newbackupifyKey);					
			break;
			case'second':echo "<status>Ok</status>";
						if(get_option('oldbackupifyKey'))update_option('oldbackupifyKey', backupifyKey);
						else add_option('oldbackupifykey',backupifyKey);
						echo "<plugin_key>".backupifyKey."</plugin_key>";
						$BACKUPIFY = new Backupify();
						$newbackupifyKey = $BACKUPIFY-> makePassword(10);
						if(get_option('backupifyKey'))update_option('backupifyKey', $newbackupifyKey);
						else add_option('backupifyKey', $newbackupifyKey);			
			break;
			case'backup': global $wpdb;
				      $id_last_backup_post =$wpdb->get_var("SELECT ID FROM $wpdb->posts ORDER BY ID DESC LIMIT 1");
				      if(id_last_backup_post<$id_last_backup_post){
				      	//generate names for blog backup files
				      	$parse_url = parse_url(get_option('siteurl'));
						if(isset($parse_url['path']))$btitle= str_replace('/', '', $parse_url['path']);
						else $btitle= str_replace('www.', '', $parse_url['host']);
						$btitle= str_replace('.', '-', $btitle);	
						$backup_file_wp=date("dMY").'_'.$btitle.'-blog.zip';
						//end generate names for blog backup files
						
						//delete all old files from backupify folder
						if (is_dir(BACKUPIFY_DIR)){
							if ($openBackupify=@opendir(BACKUPIFY_DIR)){
		    					while (($backupifyFile = readdir($openBackupify)) !== false){
		    						if(!strstr("index.php", $backupifyFile))@unlink(BACKUPIFY_DIR.$backupifyFile);
		    					}
								closedir($openBackupify);
							}
						}  
						//end delete all old files from backupify folder
						
						//show necessary  data in xml
						echo"<status>Ok</status>";
						$v_remove = $dir= ABSPATH;
						if (substr($dir, 1,1) == ':') $v_remove = substr($dir, 2);
						$abspathSize=ceil(getDirSize($dir)/pow(1024,2));
						$filesArr=filesArr($dir);
						$contentSize=ceil((getDirSize(WP_CONTENT_DIR)-getDirSize(WP_CONTENT_DIR.'/plugins')-getDirSize(WP_CONTENT_DIR.'/themes'))/pow(1024,2));
						$memoryLimit=(int)strtr(ini_get('memory_limit'),'M','');
						echo"<memoryLimit>".$memoryLimit."</memoryLimit>";
						$maxExecutionTime=(int)ini_get('max_execution_time');
						echo"<maxExecutionTime>".$maxExecutionTime."</maxExecutionTime>";
						echo"<abspathSize>".$abspathSize."</abspathSize>";
						echo"<contentSize>".$contentSize."</contentSize>";
						$piece=120;
						if($abspathSize>120){
							$needML=48;
							$needMET=60;
							if($memoryLimit>=$needML||$maxExecutionTime>=$needMET)$piece=350;
							else{
								if($memoryLimit<$needML)ini_set('memory_limit','48M');
								if($maxExecutionTime<$needMET)ini_set('max_execution_time','60');
								$memoryLimit=(int)strtr(ini_get('memory_limit'),'M','');
								$maxExecutionTime=(int)ini_get('max_execution_time');
								if($memoryLimit>=$needML||$maxExecutionTime>=$needMET)$piece=350;
							}
						}
						if($abspathSize>$piece){
							$withoutContentSize=$abspathSize-$contentSize;
							echo"<withoutContentSize>".$withoutContentSize."</withoutContentSize>";
							$contentArr=array();
							$coreArr=array();
							foreach($filesArr as $file){
								if(strstr($file,WP_CONTENT_DIR)&&!strstr($file,WP_CONTENT_DIR.'/plugins')&&!strstr($file,WP_CONTENT_DIR.'/themes')&&!strstr($file,WP_CONTENT_DIR.'/index.php'))$contentArr[]=$file;
								else $coreArr[]=$file;
							}
							if((get_option('backupify_content_array')==0)||(get_option('backupify_content_array')==true)) update_option('backupify_content_array', $contentArr);
							else (add_option('backupify_content_array', $contentArr));
							if((get_option('backupify_piece')==0)||(get_option('backupify_piece')==true)) update_option('backupify_piece', $piece);
							else (add_option('backupify_piece', $piece));
							$contentPieces=ceil($contentSize/$piece);
							echo"<contentPieces>".$contentPieces."</contentPieces>";
						}
	
						//create backup all wordpress files
						if(!class_exists('BackupifyPclZip'))require_once($backupify_plugin_dir.'lib/backupifyPclZip/backupifyPclZip.lib.php');
						$archive = new BackupifyPclZip(BACKUPIFY_DIR.$backup_file_wp);
						if($coreArr)$v_list = $archive->add($coreArr, PCLZIP_OPT_REMOVE_PATH, $v_remove, PCLZIP_OPT_ADD_PATH,$backup_file_wp,PCLZIP_OPT_NO_COMPRESSION, PCLZIP_OPT_ADD_TEMP_FILE_ON);
						else $v_list = $archive->add($filesArr, PCLZIP_OPT_REMOVE_PATH, $v_remove, PCLZIP_OPT_ADD_PATH,$backup_file_wp,PCLZIP_OPT_NO_COMPRESSION, PCLZIP_OPT_ADD_TEMP_FILE_ON);
                        if ($v_list == 0) echo'<error_pcl>'.$archive->errorInfo(true).'</error_pcl>';						
						if((get_option('backup_file_wp')==0)||(get_option('backup_file_wp')==true)) update_option('backup_file_wp', $backup_file_wp);
						else (add_option('backup_file_wp', $backup_file_wp));
						//end create backup all wordpress files
						
						if (file_exists(BACKUPIFY_DIR.$backup_file_wp))echo "<wpurl>".BACKUPIFY_URL.$backup_file_wp."</wpurl>";
				      }else echo "<status>Ok</status><url>No new info</url>";
			break;
			case'backupcontent': 
					if(get_option('backupify_content_array')==true&&get_option('backupify_piece')==true){
						echo"<status>Ok</status>";
						//generate array for backup of partial content files
						$piece=get_option('backupify_piece');
						echo"<piece>".$piece."</piece>"; 
						if($piece==350){
							$needML=48;
							$needMET=60;
							$memoryLimit=ini_get('memory_limit');
							$maxExecutionTime=ini_get('max_execution_time');
							if($memoryLimit<$needML)ini_set('memory_limit','48M');
							if($maxExecutionTime<$needMET)ini_set('max_execution_time','60');
						}
						$contentArr= get_option('backupify_content_array');
						$contentPieceArr=array();
						$contentPieceSize=0;
						$contentArrSize=0;
						$piecesBit=(int)$piece*pow(1024,2);	
						while(count($contentArr)){
							$contentFile=array_shift($contentArr); 
							$contentPieceArr[] = $contentFile;
							$contentPieceSize += filesize($contentFile);						
							if($contentPieceSize>=$piecesBit){	
								if((get_option('backupify_content_array')==0)||(get_option('backupify_content_array')==true)) update_option('backupify_content_array', $contentArr);
								else (add_option('backupify_content_array', $contentArr));
								if(count($contentArr)){
									foreach($contentArr as $contentFile)$contentArrSize += filesize($contentFile);
								}
								break;
							}	
						}//end while
						if(!count($contentArr)){
							@delete_option('backupify_content_array');
							@delete_option('backupify_piece');
						}
						else{
							$contentPieces=ceil($contentArrSize/($piecesBit));
							echo"<contentPieces>".$contentPieces."</contentPieces>";
							if((get_option('backupify_piece')==0)||(get_option('backupify_piece')==true)) update_option('backupify_piece', $piece);
							else (add_option('backupify_piece', $piece));
						}
						//end generate array for backup of partial content files
						
						//generate names for backup of partial content files
				      	$parse_url = parse_url(get_option('siteurl'));
						if(isset($parse_url['path']))$btitle= str_replace('/', '', $parse_url['path']);
						else $btitle= str_replace('www.', '', $parse_url['host']);
						$btitle= str_replace('.', '-', $btitle);	
						$backup_file_content=date("dMY").'_'.$btitle.'-content-'.$_GET['numcontent'].'.zip';
						$backup_file_template=date("dMY").'_'.$btitle.'-content.zip';
						//end generate names for backup of partial content files
						
						//create backup of partial content files
						if(!class_exists('BackupifyPclZip'))require_once($backupify_plugin_dir.'lib/backupifyPclZip/backupifyPclZip.lib.php');
						if($contentPieceArr){
							$v_remove = WP_CONTENT_DIR;
							if (substr($v_remove, 1,1) == ':') $v_remove = substr($v_remove, 2);
							$contentArchive = new BackupifyPclZip(BACKUPIFY_DIR.$backup_file_content);
							$v_list = $contentArchive->add($contentPieceArr, PCLZIP_OPT_REMOVE_PATH, $v_remove, PCLZIP_OPT_ADD_PATH,$backup_file_content,PCLZIP_OPT_NO_COMPRESSION, PCLZIP_OPT_ADD_TEMP_FILE_ON);
							if ($v_list == 0) echo'<error_pcl>'.$archive->errorInfo(true).'</error_pcl>';
						}
						
						//end create backup of partial content files
						if (file_exists(BACKUPIFY_DIR.$backup_file_content)){
							echo "<contenturl>".BACKUPIFY_URL.$backup_file_content."</contenturl>";
							if((get_option('backup_file_content')==0)||(get_option('backup_file_content')==true)) update_option('backup_file_content', $backup_file_template);
							else (add_option('backup_file_content', $backup_file_template));
						}
					}else echo"<status>Ok</status><error_content>Content array not exist in database</error_content>";
			break;
			case'backupdb': global $wpdb;
						$id_last_backup_post =$wpdb->get_var("SELECT ID FROM $wpdb->posts ORDER BY ID DESC LIMIT 1");
				      	//generate names for db backup file
				      	$parse_url = parse_url(get_option('siteurl'));
						if(isset($parse_url['path']))$btitle= str_replace('/', '', $parse_url['path']);
						else $btitle= str_replace('www.', '', $parse_url['host']);
						$btitle= str_replace('.', '-', $btitle);	
						$backup_file_db=date("dMY").'_'.$btitle.'-dbase.sql.gz';
						//end generate names for db backup file
						
						//create backup database
						$BACKUPIFY = new Backupify();
						$backup_file_db = $BACKUPIFY-> fullBackup();
						if((get_option('backup_file_db')==0)||(get_option('backup_file_db')==true)) update_option('backup_file_db', $backup_file_db);
						else (add_option('backup_file_db', $backup_file_db));
						if(get_option('id_last_backup_post')) update_option('id_last_backup_post', $id_last_backup_post);
						else add_option('id_last_backup_post', $id_last_backup_post);
						//end create backup database
						$last_post = get_option('id_last_backup_post');
						echo"<status>Ok</status>";
						if (file_exists(BACKUPIFY_DIR.$backup_file_db))echo"<url>".BACKUPIFY_URL.$backup_file_db."</url>";
			break;
			case'delete':
				if ((backup_file_db!=0)&&(file_exists(BACKUPIFY_DIR.backup_file_db))){
					@unlink( BACKUPIFY_DIR.backup_file_db);
					update_option('backup_file_db', 0);
					echo "<status>Ok</status>";
				}
			break;
			case'wpdelete': 
				if ((backup_file_wp!=0)&&(file_exists(BACKUPIFY_DIR.backup_file_wp))){
					@unlink( BACKUPIFY_DIR.backup_file_wp);
					update_option('backup_file_wp', 0);
					echo "<status>Ok</status>";
				}
			break;
			case'contentdelete':
				if (backup_file_content!=0){
					$backup_file_content = str_replace('.zip','-'.$_GET['numcontent'].'.zip',backup_file_content);
					if(file_exists(BACKUPIFY_DIR.$backup_file_content)){
						@unlink( BACKUPIFY_DIR.$backup_file_content);
						echo "<status>Ok</status>";
					}
				}
			break;
		}
	}else echo "<status>Not valid token</status>";
	echo"</result>";
}
function getDirSize($path)
{
    $fileSize = 0;
    $dir = scandir($path);  
    foreach($dir as $file){
        if (($file!='.') && ($file!='..'))
            if(is_dir($path.'/'.$file)) $fileSize += getDirSize($path.'/'.$file);
            else $fileSize += filesize($path.'/'.$file);
    }
    return $fileSize;
}

function filesArr($path)
{
	if (substr($path, -1, 1)=='/')$path=substr($path, 0, -1);
	$filesArr = array();
	$dir = scandir($path);
	foreach($dir as $file){
		if (($file!='.') && ($file!='..'))
			if(is_dir($path.'/'.$file))$filesArr=array_merge($filesArr, filesArr($path.'/'.$file));
			else $filesArr[]=$path.'/'.$file;
	}
	return $filesArr;
}

class Backupify {
	var $backup_dir;
	var $backup_file_db;
	var $backup_file_wp;
	var $errors = array();
	var $basename;
	var $page_url;
	var $referer_check_key;
	var $user_id;
	
	function gziped() {
		return function_exists('gzopen');
	}

	function checkModules() {
		$mod_evasive = false;
		if ( true === MOD_EVASIVE_OVERRIDE ) return true;
		if ( false === MOD_EVASIVE_OVERRIDE ) return false;
		if ( function_exists('apache_get_modules') ) 
			foreach( (array) apache_get_modules() as $mod ) 
				if ( false !== strpos($mod,'mod_evasive') || false !== strpos($mod,'mod_dosevasive') )
					return true;
		return false;
	}

	function Backupify() {

	    global $table_prefix, $wpdb;
	    $this->user_id = (int)wp_validate_auth_cookie();
	    
	    if(!get_option('backupifyKey'))add_option('backupifyKey', $this->makePassword(10));
	    
	    if ( ! defined('backupifyKey')&& get_option('backupifyKey'))define('backupifyKey', get_option('backupifyKey'));
		if ( ! defined('oldbackupifyKey')&& get_option('oldbackupifyKey'))define('oldbackupifyKey', get_option('oldbackupifyKey'));

		if($_POST['backupifyOption']){
			if ($_POST['exclude_comments']==null and  get_option('exclude_comments'))delete_option('exclude_comments');
			if ($_POST['exclude_comments']==1){
				if(get_option('exclude_comments')){
					update_option('exclude_comments', '1');
				}else{ 
					add_option('exclude_comments', '1');
				}
			}
		}	

		if ( ! defined('exclude_comments')){
			define('exclude_comments', get_option('exclude_comments'));
		}

		$table_prefix = ( isset( $table_prefix ) ) ? $table_prefix : $wpdb->prefix;
		$parse_url = parse_url(get_option('siteurl'));
		if(isset($parse_url['path'])) $blogtitle= str_replace('/', '', $parse_url['path']);
		else $blogtitle= str_replace('www.', '', $parse_url['host']);
		$blogtitle= str_replace('.', '-', $blogtitle);
		$this->backup_file_db = date("dMY").'_'.$blogtitle.'-dbase.sql';
		if ($this->gziped()) $this->backup_file_db .= '.gz';	
		$this->backup_file_wp=date("dMY").'_'.$blogtitle.'-blog.zip';
			
		$this->backup_dir = trailingslashit(BACKUPIFY_DIR);
		$this->basename = 'Backupify';
	
		$this->referer_check_key = $this->basename . '-download_' . DB_NAME;
		$query_args = array( 'page' => $this->basename );
		if ( function_exists('wp_create_nonce') )
			$query_args = array_merge( $query_args, array('_wpnonce' => wp_create_nonce($this->referer_check_key)) );
		$base = ( function_exists('site_url') ) ? site_url('', 'admin') : get_option('siteurl');
		$this->page_url = add_query_arg( $query_args, $base . '/wp-admin/edit.php');
		if (isset($_POST['db_backupify'])) {
			$this->wpSecure('fatal');
			check_admin_referer($this->referer_check_key);
			$this->canUserBackup('main');
			if($_POST['db_backupify'] && $_POST['db_backupify']=='fragments')add_action('admin_menu', array(&$this, 'backupifyFragmentMenu'));
		} elseif (isset($_GET['fragmentBackup'] )) {
			$this->canUserBackup('frame');
			add_action('init', array(&$this, 'init'));
		} else {
			add_action('admin_menu', array(&$this, 'backupifyAdminMenu'));
		}
	}
	
	function init() {
		$this->canUserBackup();
		if (isset($_GET['fragmentBackup'] )) {
			list($table, $segment, $filename) = explode(':', $_GET['fragmentBackup']);
			$this->backupifyValidateFile($filename);
			if ($table == ''){
					//delete all old files from backupify folder
					if (is_dir(BACKUPIFY_DIR)){
    					if ($openBackupify=@opendir(BACKUPIFY_DIR)){
        					while (($backupifyFile = readdir($openBackupify)) !== false){
        						if(!strstr("index.php", $backupifyFile))@unlink(BACKUPIFY_DIR . $backupifyFile);
        					}
							closedir($openBackupify);
						}
					}  
					//end delete all old files from backupify folder							
			}elseif($table=='backup_all_files')$this->backupBlog();
			$this->backupFragmentDB($table, $segment, $filename);
		}
		die();
	}
	
	function makePassword($length) {
		$nowtime=time();
		$allow = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ".$nowtime;
		$i = 1;
		while ($i <= $length) {
			$max  = strlen($allow)-1;
			$num  = mt_rand(0, $max);
			$temp = substr($allow, $num, 1);
			$ret  = $ret . $temp;
			$i++;
		}
		return $ret;
	}
	
	function backupifyMenu() {
		global $table_prefix, $wpdb;
		$feedback = '';
		$whoops = false;

		if($_POST['download_backup_db']){
			echo '<iframe id="downloader_db" src="about:blank" style="visibility:hidden;border:none;height:1em;width:1px;"></iframe><script type="text/javascript"><!--//
			var fram = document.getElementById("downloader_db");
			window.onbeforeunload = null;
			fram.src = "'. BACKUPIFY_URL.$_POST["download_backup_db"] . '";
			//--></script>
			';
		}
		
		if($_POST['download_backup_wp']){
			echo '<iframe id="downloader_wp" src="about:blank" style="visibility:hidden;border:none;height:1em;width:1px;"></iframe><script type="text/javascript"><!--//
			var fram = document.getElementById("downloader_wp");
			window.onbeforeunload = null;
			fram.src = "'. BACKUPIFY_URL.$_POST["download_backup_wp"] . '";
			//--></script>
			';
		}
	
		// security check
		$this->wpSecure();  

		if (count($this->errors)) {
			$feedback .= '<div class="updated Backupify-updated error"><p><strong>The following errors were reported: </strong></p>';
			$feedback .= '<p>' . $this->backupifyErrorDisplay( 'main', false ) . '</p>';
			$feedback .= "</p></div>";
		}

		if ('' != $feedback)
			echo $feedback;

		if ( ! $this->wpSecure() ) 	
			return;

		// Give the new dirs the same perms as wp-content.
//		$stat = stat( ABSPATH . 'wp-content' );
//		$dir_perms = $stat['mode'] & 0000777; // Get the permission bits.
		$dir_perms = '0777';

		// the file doesn't exist and can't create it
		if ( ! file_exists($this->backup_dir) && ! @mkdir($this->backup_dir) ) {
			?><div class="updated Backupify-updated error"><p><?php echo'WARNING: Your backup directory does <strong>NOT</strong> exist, and we cannot create it.'; ?></p>
			<p><?php printf('Using your FTP client, try to create the backup directory yourself: <code>' . $this->backup_dir . '</code>'); ?></p></div><?php
			$whoops = true;
		// not writable due to write permissions
		} elseif ( !is_writable($this->backup_dir) && ! @chmod($this->backup_dir, $dir_perms) ) {
			?><div class="updated Backupify-updated error"><p><?php echo 'WARNING: Your backup directory is <strong>NOT</strong> writable! We cannot create the backup files.'; ?></p>
			<p><?php printf('Using your FTP client, try to set the backup directory’s write permission to %1$s or %2$s: %3$s', '<code>777</code>', '<code>a+w</code>', '<code>' . $this->backup_dir . '</code>'); ?>
			</p></div><?php 
			$whoops = true;
		} else {
			$this->fp = $this->fileOpen($this->backup_dir . 'test' );
			if( $this->fp ) { 
				$this->fileClose($this->fp);
				@unlink($this->backup_dir . 'test' );
			// the directory is not writable probably due to safe mode
			} else {
				?><div class="updated Backupify-updated error"><p><?php echo 'WARNING: Your backup directory is <strong>NOT</strong> writable! We cannot create the backup files.'; ?></p><?php 
				if( ini_get('safe_mode') ){
					?><p><?php echo 'This problem seems to be caused by your server’s <code>safe_mode</code> file ownership restrictions, which limit what files web applications like WordPress can create.'; ?></p><?php 
				}
				?><?php printf('You can try to correct this problem by using your FTP client to delete and then re-create the backup directory: %s', '<code>' . $this->backup_dir . '</code>');
				?></div><?php 
				$whoops = true;
			}
		}

		

		if ( !file_exists($this->backup_dir . 'index.php') )
			@ touch($this->backup_dir . 'index.php');
		?>
		<div class='wrap'>
		<h2>Backupify</h2>

		<form method="post" action="">
		<?php 
			if ( function_exists('wp_nonce_field') ) wp_nonce_field($this->referer_check_key); ?>
			<div  class ="backupify_title">
				Full backup now
				<span id="service_backupNow" style="cursor:pointer;" onclick="switch_settings('backupNow');">+</span>
			</div>
			<div id="settings_backupNow" style="display:none;">
				<ul>
					<li>
						<label for="do_save">
							<input type="hidden" id="do_save" name="backupify_download" value="none" style="border:none;" />
							Backup  locally
						</label>
					</li>				
				</ul>
				<?php if ( ! $whoops ) : ?>
				<input type="hidden" name="db_backupify" id="db_backupify" value="backup" /> 
				<p class="submit">
					<input type="submit" name="submit" onclick="document.getElementById('db_backupify').value='fragments';" value="Backup!" />
				</p>
				<?php else : ?>
					<div class="updated Backupify-updated error"><p><?php echo'WARNING: Your backup directory is <strong>NOT</strong> writable!'; ?></p></div>
				<?php endif; // ! whoops ?>
			</div>
		</form>
		
		<div  class ="backupify_title">
			Backup option
			<span id="service_after_posts" style="cursor:pointer;" onclick="switch_settings('after_posts');">+</span>
		</div>
				<div id="settings_after_posts" style="display:none;">
    				<form action="" method="post">
						<div style ="float:left;margin-right:3px;"><input type='checkbox' name='exclude_comments' value='1' <?php if(exclude_comments)echo 'checked';?>/></div>Exclude comments
						<p class="submit"><input type="submit" name="backupifyOption" value="Save" /></p>
    				</form>   
				</div>

			<div  class ="backupify_title">
				Backupify Login
				<span id="service_backupify" style="cursor:pointer;" onclick="switch_settings('backupify');">+</span>
			</div>
				<div id="settings_backupify" style="display:none;">
						<?php if(!get_option('oldbackupifyKey')){		
							$reg_url="https://secure.backupify.com/wordpress/create?plugin_key=".backupifyKey;?>
							<p><a href='<?php echo $reg_url; ?>' target='_blank'>Initialize Plugin</a></p>
    					<?php }else{
    						$reg_url="https://secure.backupify.com/wordpress/recreate?oldplugin_key=".oldbackupifyKey;?>
							<p><a href='<?php echo $reg_url; ?>' target='_blank'>Reinitialize Plugin</a></p>
						<?php } ?>
				</div>
		</div>
		</div><!-- .wrap -->
		<script type="text/javascript">
			function switch_settings(service){
				if($('settings_'+service).visible()){
					$('settings_'+service).hide();
					$('service_'+service).update('+')
				}else{
					$('settings_'+service).show();
					$('service_'+service).update('-');
				}
			}
		</script>
		
		<style>
			<!--
				.backupify_title{
					font-size:larger;
					font-weight:bold; 
					margin-bottom:0.5em;
					padding:1em 0;
				}

				#settings_backupNow, #settings_backupify, #settings_progress, #settings_tables, #settings_after_posts{
					border:1px dashed #7FC34C;
					padding:23px 23px 0 23px; 
					overflow:hidden;
					clear:both;
					margin-bottom:10px;
				}

				#settings_tables{
					 padding:0.5em 1.5em 1.5em;
				}

				#progress_message{
					padding:1em 0;
				}
				
				#errors{
					padding-bottom:1em;
					color:red;
				}

			-->
		</style>
	<?php	
	} // end backupifyMenu()

	function createBackupifyScript() {
		global $table_prefix, $wpdb;
	
		echo "<div class='wrap'>";
		echo 	'<div class="backupify_title"> Progress</div> 
		    <div id=settings_progress>
			<p><strong>
				DO NOT CLOSE THIS BROWSER AND RELOAD THIS PAGE AS IT WILL CAUSE YOUR BACKUP TO FAIL:
			</strong></p>
			<p><strong>Progress: </strong></p>
			<div id="meterbox"><div id="meter"> </div></div>
			<div id="progress_message"></div>
			<div id="errors"></div>
			</div>
			<iframe id="backuploader" src="about:blank" style="visibility:hidden;border:none;height:1em;width:1px;"></iframe>
			<script type="text/javascript">
			//<![CDATA[
			window.onbeforeunload = function() {
				return "Navigating away from this page will cause your backup to fail.";
			}
			function backupifySetMeter(pct) {
				var meter = document.getElementById("meter");
				meter.style.width = pct + "%";
				meter.innerHTML = Math.floor(pct) + "%";
			}
			function backupifySetProgress(str) {
				var progress = document.getElementById("progress_message");
				progress.innerHTML = str;
			}
			function backupifyAddError(str) {
				var errors = document.getElementById("errors");
				errors.innerHTML = errors.innerHTML + str + "<br />";
			}

			function backupifyPiece(table, segment) {
				var fram = document.getElementById("backuploader");
				fram.src = "' . $this->page_url . '&fragmentBackup=" + table + ":" + segment + ":' . $this->backup_file_db . ':";
			}
			
			var curStep = 0;
			
			function backupifyNextStep() {
				backupifyStep(curStep);
				curStep++;
			}
			
			function finishBackupify() {
				var fram = document.getElementById("backuploader");				
				backupifySetMeter(100);
		';
        $download_to_local= "<form method='post' action=''><input type='hidden' name='download_backup_db' id='download_backup_db' value='".$this->backup_file_db."' />
        	<input type='hidden' name='download_backup_wp' id='download_backup_wp' value='".$this->backup_file_wp."' />
			<p class='submit'>
				Backup complete. <input type='submit' name='submit'  value='Download' /> archives. </p></form>"; 
		if ($_POST['backupify_download']) {
			echo '
				backupifySetProgress('.$download_to_local.');
				window.onbeforeunload = null;';
		}
		
		echo '
			}
			
			function backupifyStep(step) {
				switch(step) {
				case 0: backupifyPiece("", 0); break;
		';
		
		$all_tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
		$all_tables = array_map(create_function('$a', 'return $a[0];'), $all_tables);
		$all_tables[] = 'prebackup_all_files';
		$all_tables[] = 'backup_all_files';
		foreach ($all_tables as $table) {
			if ( $table == $wpdb->comments ) {
				if(!exclude_comments) $tables[]=$table;
			} else {
				$tables[]=$table;
			}
		}
		$step_count = 1;
		foreach ($tables as $table) {
				$rec_count = $wpdb->get_var("SELECT count(*) FROM {$table}");
				$rec_segments = ceil($rec_count / ROWS_IN_SEGMENT);
				$table_count = 0;
				if ( $this->checkModules() ) {
					$delay = "setTimeout('";
					$delay_time = "', " . (int) EVASIVE_DELAY . ")";
				}
				else { $delay = $delay_time = ''; }
				do {
					if($table!='backup_all_files'&&$table!='prebackup_all_files'){
						echo "case {$step_count}: {$delay}backupifyPiece(\"{$table}\", {$table_count}){$delay_time}; break;\n";
						$step_count++;
						$table_count++;
					}
				} while($table_count < $rec_segments);
				echo "case {$step_count}: {$delay}backupifyPiece(\"{$table}\", -1){$delay_time}; break;\n";
				$step_count++;
		}
		echo "case {$step_count}: finishBackupify(); break;";
		
		echo '
				}
				if(step != 0) backupifySetMeter(100 * step / ' . $step_count . ');
			}

			backupifyNextStep();
			// ]]>
			</script>
	</div>
		';
		$this->backupifyMenu();
	?>
	<style><!--
			#meter{
				height:15px;
				background-image:url(../wp-content/plugins/wp-backupify/images/little_green_band.gif);
				width:0%;
				text-align:center;
			}

			#meterbox{
				background-image:url(../wp-content/plugins/wp-backupify/images/little_blue_band.gif);
				height:15px;
				width:80%;
				border-width: 0 1px; 
				border-style: solid;
				border-color:#659fff;
			}
			-->
	</style>
	<?php
	}
	
	function backupBlog(){
		global $wpdb;
		//get necessary  data	
		$v_remove = $dir= ABSPATH;
		if (substr($dir, 1,1) == ':') {
			$v_remove = substr($dir, 2);
		}
		$abspathSize=ceil($this->getDirSize($dir)/pow(1024,2));
		$memoryLimit=(int)strtr(ini_get('memory_limit'),'M','');
		if($memoryLimit==0) $memoryLimit=(int)strtr(get_cfg_var('memory_limit'),'M','');
		$maxExecutionTime=(int)ini_get('max_execution_time');
		if($maxExecutionTime==0)$maxExecutionTime=get_cfg_var('max_execution_time');
						
		//installing the necessary server settings(It works not for all hostings)
		if($abspathSize<=120){
			$needML=32;
		}elseif($abspathSize<=400){
			$needML=48;
			$needMET=60;
		}elseif($abspathSize<=720){
			$needML=64;
			$needMET=120;
		}elseif($abspathSize<=1024){				
			$needML=80;
			$needMET=180;
		}elseif($abspathSize<=1332){
			$needML=96;
			$needMET=240;
		}elseif($abspathSize<=2000){
			$needML=128;
			$needMET=300;
		}elseif($abspathSize<=2253){
			$needML=144;
			$needMET=360;
		}else{
			$needML=192;
			$needMET=600;
		}
		if($memoryLimit<$needML) @ini_set('memory_limit', $needML.'M');
		if($maxExecutionTime<$needMET) @ini_set('max_execution_time', $needMET);
		//end installing the necessary server settings
						
		//test new settings
		$newMemoryLimit=(int)strtr(ini_get('memory_limit'),'M','');
		if($newMemoryLimit==0)$newMemoryLimit=(int)strtr(get_cfg_var('memory_limit'),'M','');
		if($newMemoryLimit<$needML){
			$memError='Too low memory limit for backup all your files ('.$abspathSize.'Mb). Php_memory_limit must be '.$needML.'M for successful backup.';
			$this->backupifyError($memError);	
		}	
		$newMETime=(int)ini_get('max_execution_time');
		if($newMETime==0)$newMETime=(int)get_cfg_var('max_execution_time');
		if($newMETime<$needMET){
			$timeError='Too low time for backup all your files. Max_execution_time must be '.$needMET.' for successful backup.';
			$this->backupifyError($timeError);
		}								
		//end test new settings
		
		//create backup all wordpress files
		if(!$memError&&!$timeError){
			if(!class_exists('BackupifyPclZip'))require_once($backupify_plugin_dir.'lib/backupifyPclZip/backupifyPclZip.lib.php');
				$archive = new BackupifyPclZip(BACKUPIFY_DIR.$this->backup_file_wp);
	  			$v_list = $archive->create($dir,PCLZIP_OPT_REMOVE_PATH, $v_remove, PCLZIP_OPT_ADD_PATH,$this->backup_file_wp,PCLZIP_OPT_NO_COMPRESSION, PCLZIP_OPT_ADD_TEMP_FILE_ON);


			if((get_option('backup_file_wp')==0)||(get_option('backup_file_wp')==true)) update_option('backup_file_wp', $this->backup_file_wp);
			else (add_option('backup_file_wp', $this->backup_file_wp));
		}
		//end create backup all wordpress files					
	}
	
	function getDirSize($path){
		$fileSize = 0;
		$dir = scandir($path);  
		foreach($dir as $file)
		{
		    if (($file!='.') && ($file!='..'))
		        if(is_dir($path . '/' . $file))
		            $fileSize += getDirSize($path.'/'.$file);
		        else
		            $fileSize += filesize($path . '/' . $file);
		}
		return $fileSize;
	}
	
	function backupFragmentDB($table, $segment, $filename) {
		global $table_prefix, $wpdb;
			
		echo "$table:$segment:$filename";
		if($table == '') {
			$msg ='Creating database backup file...';
		}elseif($table=='prebackup_all_files'){
			$msg='Create backup of all Wordpress files...';	
		}else{
			if($segment == -1) {
				$msg = sprintf('Finished backing up table \\"%s\\".', $table);
			}else{
				$msg = sprintf('Backing up table \\"%s\\"...', $table);
			}
		}
		if (is_writable($this->backup_dir)) {
			$this->fp = $this->fileOpen($this->backup_dir . $filename, 'a');
			if(!$this->fp) {
				$this->backupifyError('Could not open the backup file for writing!');
				$this->backupifyError(array('loc' => 'frame', 'kind' => 'fatal', 'msg' => 'The backup file could not be saved.  Please check the permissions for writing to your backup directory and try again.'));
			}
			else {
				if($table == '') {
					//Begin new backup of MySql
					$this->backupStow("# WordPress MySQL database backup \n");
					$this->backupStow("#\n");
					$this->backupStow("# " . sprintf('Generated: %s',date("l j. F Y H:i T")) . "\n");
					$this->backupStow("# " . sprintf('Hostname: %s',DB_HOST) . "\n");
					$this->backupStow("# " . sprintf('Database: %s',$this->backupifyBackquote(DB_NAME)) . "\n");
					$this->backupStow("# --------------------------------------------------------\n");
				} else {
					if($segment == 0) {
						// Increase script execution time-limit to 15 min for every table.
						if ( !ini_get('safe_mode')) @set_time_limit(15*60);
						// Create the SQL statements
						$this->backupStow("# --------------------------------------------------------\n");
						$this->backupStow("# " . sprintf('Table: %s',$this->backupifyBackquote($table)) . "\n");
						$this->backupStow("# --------------------------------------------------------\n");
					}			
					if($table!='backup_all_files'&&$table!='prebackup_all_files')$this->backupTable($table, $segment);
				}
			}
		} else {
			$this->backupifyError(array('kind' => 'fatal', 'loc' => 'frame', 'msg' => 'The backup directory is not writeable!  Please check the permissions for writing to your backup directory and try again.'));
		}

		if($this->fp) $this->fileClose($this->fp);
		
		$this->backupifyErrorDisplay('frame');

		echo '<script type="text/javascript"><!--//
		var msg = "' . $msg . '";
		window.parent.backupifySetProgress(msg);
		window.parent.backupifyNextStep();
		//--></script>
		';
		
		die();
	}

	function backupifyAdminMenu() {
		$_page_hook = add_menu_page('Backupify', 'Backupify', 'import', $this->basename, array(&$this, 'backupifyMenu'));
		add_action('load-' . $_page_hook, array(&$this, 'backupifyAdminLoad'));
		if ( function_exists('add_contextual_help') ) {
			$text = $this->backupifyHelpMenu();
			add_contextual_help($_page_hook, $text);
		}
	}

	function backupifyFragmentMenu() {
		$page_hook = add_menu_page('Backupify','Backupify', 'import', $this->basename, array(&$this, 'createBackupifyScript'));
		add_action('load-' . $page_hook, array(&$this, 'backupifyAdminLoad'));
	}

	function backupifyAdminLoad() {
		wp_enqueue_script('prototype');
	}

	function fullBackup(){
		global $wpdb;
		$all_tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
		$all_tables = array_map(create_function('$a', 'return $a[0];'), $all_tables);
		foreach ($all_tables as $table) {
			if ( $table == $wpdb->comments ) {
				if(!exclude_comments) $tables[]=$table;
			} else {
				$tables[]=$table;
			}
		}
		$backup_file_db = $this->backupDB($tables);
		return $backup_file_db;
	}
		/** 
	 * Add Backupify-specific help options to the 2.7 =< WP contextual help menu
	 * return string The text of the help menu.
	 */
	function backupifyHelpMenu() {
		$text = "\n<a href=\"http://backupify.com/faq.php\" target=\"_blank\">FAQ</a>";

		return $text;
	}

	/**
	 * Better addslashes for SQL queries.
	 * Taken from phpMyAdmin.
	 */
	function sqlAddslashes($a_string = '', $is_like = false) {
		if ($is_like) $a_string = str_replace('\\', '\\\\\\\\', $a_string);
		else $a_string = str_replace('\\', '\\\\', $a_string);
		return str_replace('\'', '\\\'', $a_string);
	} 

	/**
	 * Add backquotes to tables and db-names in
	 * SQL queries. Taken from phpMyAdmin.
	 */
	function backupifyBackquote($a_name) {
		if (!empty($a_name) && $a_name != '*') {
			if (is_array($a_name)) {
				$result = array();
				reset($a_name);
				while(list($key, $val) = each($a_name)) 
					$result[$key] = '`' . $val . '`';
				return $result;
			} else {
				return '`' . $a_name . '`';
			}
		} else {
			return $a_name;
		}
	} 

	function fileOpen($filename = '', $mode = 'w') {
		if ('' == $filename) return false;
		if ($this->gziped()) 
			$fp = @gzopen($filename, $mode);
		else
			$fp = @fopen($filename, $mode);
		return $fp;
	}

	function fileClose($fp) {
		if ($this->gziped()) gzclose($fp);
		else fclose($fp);
	}

	/**
	 * Write to the backup file
	 * @param string $query_line the line to write
	 * @return null
	 */
	function backupStow($query_line) {
		if ($this->gziped()) {
			if(! @gzwrite($this->fp, $query_line))
				$this->backupifyError('There was an error writing a line to the backup script:  '. $query_line . '  ' . $php_errormsg);
		} else {
			if(false === @fwrite($this->fp, $query_line))
				$this->backupifyError('There was an error writing a line to the backup script:  '. $query_line . '  ' . $php_errormsg);
		}
	}
	
	/**
	 * Logs any error messages
	 * @param array $args
	 * @return bool
	 */
	function backupifyError($args = array()) {
		if ( is_string( $args ) ) 
			$args = array('msg' => $args);
		$args = array_merge( array('loc' => 'main', 'kind' => 'warn', 'msg' => ''), $args);
		$this->errors[$args['kind']][] = $args['msg'];
		if ( 'fatal' == $args['kind'] || 'frame' == $args['loc'])
			$this->backupifyErrorDisplay($args['loc']);
		return true;
	}

	/**
	 * Displays error messages 
	 * @param array $errs
	 * @param string $loc
	 * @return string
	 */
	function backupifyErrorDisplay($loc = 'main', $echo = true) {
		$errs = $this->errors;
		unset( $this->errors );
		if ( ! count($errs) ) return;
		$msg = '';
		$err_list = array_slice(array_merge( (array) $errs['fatal'], (array) $errs['warn']), 0, 10);
		if ( 10 == count( $err_list ) )
			$err_list[9] ='Subsequent errors have been omitted from this log.';
		$wrap = ( 'frame' == $loc ) ? "<script type=\"text/javascript\">\n var msgList = ''; \n %1\$s \n if ( msgList ) alert(msgList); \n </script>" : '%1$s';
		$line = ( 'frame' == $loc ) ? 
			"try{ window.parent.backupifyAddError('%1\$s'); } catch(e) { msgList += ' %1\$s';}\n" :
			"%1\$s<br />\n";
		foreach( (array) $err_list as $err )
			$msg .= sprintf($line,str_replace(array("\n","\r"), '', addslashes($err)));
		$msg = sprintf($wrap,$msg);
		if ( count($errs['fatal'] ) ) {
			if ( function_exists('wp_die') && 'frame' != $loc ) wp_die(stripslashes($msg));
			else die($msg);
		}
		else {
			if ( $echo ) echo $msg;
			else return $msg;
		}
	}

	/**
	 * Taken partially from phpMyAdmin and partially from
	 * Alain Wolf, Zurich - Switzerland
	 * Website: http://restkultur.ch/personal/wolf/scripts/db_backup/
	
	 * Modified by Scott Merrill (http://www.skippy.net/) 
	 * to use the WordPress $wpdb object
	 * @param string $table
	 * @param string $segment
	 * @return void
	 */
	function backupTable($table, $segment = 'none') {
		global $wpdb;

		$table_structure = $wpdb->get_results("DESCRIBE $table");
		if (! $table_structure) {
			$this->backupifyError('Error getting table details' . ": $table");
			return false;
		}
	
		if(($segment == 'none') || ($segment == 0)) {
			// Add SQL statement to drop existing table
			$this->backupStow("\n\n");
			$this->backupStow("#\n");
			$this->backupStow("# " . sprintf('Delete any existing table %s',$this->backupifyBackquote($table)) . "\n");
			$this->backupStow("#\n");
			$this->backupStow("\n");
			$this->backupStow("DROP TABLE IF EXISTS " . $this->backupifyBackquote($table) . ";\n");
			
			// Table structure
			// Comment in SQL-file
			$this->backupStow("\n\n");
			$this->backupStow("#\n");
			$this->backupStow("# " . sprintf('Table structure of table %s',$this->backupifyBackquote($table)) . "\n");
			$this->backupStow("#\n");
			$this->backupStow("\n");
			
			$create_table = $wpdb->get_results("SHOW CREATE TABLE $table", ARRAY_N);
			if (false === $create_table) {
				$err_msg = sprintf('Error with SHOW CREATE TABLE for %s.', $table);
				$this->backupifyError($err_msg);
				$this->backupStow("#\n# $err_msg\n#\n");
			}
			$this->backupStow($create_table[0][1] . ' ;');
			
			if (false === $table_structure) {
				$err_msg = sprintf('Error getting table structure of %s', $table);
				$this->backupifyError($err_msg);
				$this->backupStow("#\n# $err_msg\n#\n");
			}
		
			// Comment in SQL-file
			$this->backupStow("\n\n");
			$this->backupStow("#\n");
			$this->backupStow('# ' . sprintf('Data contents of table %s',$this->backupifyBackquote($table)) . "\n");
			$this->backupStow("#\n");
		}
		
		if(($segment == 'none') || ($segment >= 0)) {
			$defs = array();
			$ints = array();
			foreach ($table_structure as $struct) {
				if ( (0 === strpos($struct->Type, 'tinyint')) ||
					(0 === strpos(strtolower($struct->Type), 'smallint')) ||
					(0 === strpos(strtolower($struct->Type), 'mediumint')) ||
					(0 === strpos(strtolower($struct->Type), 'int')) ||
					(0 === strpos(strtolower($struct->Type), 'bigint')) ) {
						$defs[strtolower($struct->Field)] = ( null === $struct->Default ) ? 'NULL' : $struct->Default;
						$ints[strtolower($struct->Field)] = "1";
				}
			}
			// Batch by $row_inc
			
			if($segment == 'none') {
				$row_start = 0;
				$row_inc = ROWS_IN_SEGMENT;
			} else {
				$row_start = $segment * ROWS_IN_SEGMENT;
				$row_inc = ROWS_IN_SEGMENT;
			}
			
			do {	
				if ( !ini_get('safe_mode')) @set_time_limit(15*60);
				$table_data = $wpdb->get_results("SELECT * FROM $table LIMIT {$row_start}, {$row_inc}", ARRAY_A);

				$entries = 'INSERT INTO ' . $this->backupifyBackquote($table) . ' VALUES (';	
				//    \x08\\x09, not required
				$search = array("\x00", "\x0a", "\x0d", "\x1a");
				$replace = array('\0', '\n', '\r', '\Z');
				if($table_data) {
					foreach ($table_data as $row) {
						$values = array();
						foreach ($row as $key => $value) {
							if ($ints[strtolower($key)]) {
								// make sure there are no blank spots in the insert syntax,
								// yet try to avoid quotation marks around integers
								$value = ( null === $value || '' === $value) ? $defs[strtolower($key)] : $value;
								$values[] = ( '' === $value ) ? "''" : $value;
							} else {
								$values[] = "'" . str_replace($search, $replace, $this->sqlAddslashes($value)) . "'";
							}
						}
						$this->backupStow(" \n" . $entries . implode(', ', $values) . ');');
					}
					$row_start += $row_inc;
				}
			} while((count($table_data) > 0) and ($segment=='none'));
		}
		
		if(($segment == 'none') || ($segment < 0)) {
			// Create footer/closing comment in SQL-file
			$this->backupStow("\n");
			$this->backupStow("#\n");
			$this->backupStow("# " . sprintf('End of data contents of table %s', $this->backupifyBackquote($table)) . "\n");
			$this->backupStow("# --------------------------------------------------------\n");
			$this->backupStow("\n");
		}
	} // end backupTable()
	
	function backupDB($tables) {
		global $table_prefix, $wpdb;
		
		if (is_writable($this->backup_dir)) {
			$this->fp = $this->fileOpen($this->backup_dir . $this->backup_file_db);
			if(!$this->fp) {
				$this->backupifyError('Could not open the backup file for writing!');
				return false;
			}
		} else {
			$this->backupifyError('The backup directory is not writeable!');
			return false;
		}
		
		//Begin new backup of MySql
		$this->backupStow("# " . 'WordPress MySQL database backup'. "\n");
		$this->backupStow("#\n");
		$this->backupStow("# " . sprintf('Generated: %s',date("l j. F Y H:i T")) . "\n");
		$this->backupStow("# " . sprintf('Hostname: %s',DB_HOST) . "\n");
		$this->backupStow("# " . sprintf('Database: %s',$this->backupifyBackquote(DB_NAME)) . "\n");
		$this->backupStow("# --------------------------------------------------------\n");
		
		foreach ($tables as $table) {
			// Increase script execution time-limit to 15 min for every table.
			if ( !ini_get('safe_mode')) @set_time_limit(15*60);
			// Create the SQL statements
			$this->backupStow("# --------------------------------------------------------\n");
			$this->backupStow("# " . sprintf('Table: %s',$this->backupifyBackquote($table)) . "\n");
			$this->backupStow("# --------------------------------------------------------\n");
			$this->backupTable($table);
		}
				
		$this->fileClose($this->fp);
		
		if (count($this->errors)) {
			return false;
		} else {
			return $this->backup_file_db;
		}
		
	} //Backupify
		
	/**
	 * Checks that WordPress has sufficient security measures 
	 * @param string $kind
	 * @return bool
	 */
	function wpSecure($kind = 'warn', $loc = 'main') {
		global $wp_version;
		if ( function_exists('wp_verify_nonce') ) return true;
		else {
			$this->backupifyError(array('kind' => $kind, 'loc' => $loc, 'msg' => sprintf('Your WordPress version, %1s, lacks important security features without which it is unsafe to use the Backupify plugin.  Hence, this plugin is automatically disabled.  Please consider <a href="%2s">upgrading WordPress</a> to a more recent version.',$wp_version,'http://wordpress.org/download/')));
			return false;
		}
	}

	/**
	 * Checks that the user has sufficient permission to backup
	 * @param string $loc
	 * @return bool
	 */
	function canUserBackup($loc = 'main') {
		$can = false;
		// make sure WPMU users are site admins, not ordinary admins
		if ( function_exists('is_site_admin') && ! is_site_admin() )
			return false;
		if ( ( $this->wpSecure('fatal', $loc) ) && current_user_can('import') )
			$can = $this->backupifyVerifyNonce($_REQUEST['_wpnonce'], $this->referer_check_key, $loc);
		if ( false == $can ) 
			$this->backupifyError(array('loc' => $loc, 'kind' => 'fatal', 'msg' =>'You are not allowed to perform backups.'));
		return $can;
	}

	/**
	 * Verify that the nonce is legitimate
	 * @param string $rec 	the nonce received
	 * @param string $nonce	what the nonce should be
	 * @param string $loc 	the location of the check
	 * @return bool
	 */
	function backupifyVerifyNonce($rec = '', $nonce = 'X', $loc = 'main') {
		if ( wp_verify_nonce($rec, $nonce) )
			return true;
		else 
			$this->backupifyError(array('loc' => $loc, 'kind' => 'fatal', 'msg' => sprintf('There appears to be an unauthorized attempt from this site to access your database located at %1s.  The attempt has been halted.',get_option('home'))));
	}

	/**
	 * Check whether a file to be downloaded is  
	 * surreptitiously trying to download a non-backup file
	 * @param string $file
	 * @return null
	 */ 
	function backupifyValidateFile($file) {
		if ( (false !== strpos($file, '..')) || (false !== strpos($file, './')) || (':' == substr($file, 1, 1)) )
			$this->backupifyError(array('kind' => 'fatal', 'loc' => 'frame', 'msg' =>"Cheatin' uh ?"));
	}

}//end Backupify class

function wpBackupifyInit() {
	global $backupify;
	$backupify = new Backupify(); 
}

function wpBackupifyReactivate(){
	@delete_option('backup_file_db');
	@delete_option('backup_file_wp');
	@delete_option('backup_file_content');
	@delete_option('id_last_backup_post');
	@delete_option('oldbackupifyKey');
	@delete_option('backupifyKey');
	@delete_option('backupify_content_array');
	@delete_option('backupify_piece');
}

add_action('plugins_loaded', 'wpBackupifyInit');
register_activation_hook( __FILE__, 'wpBackupifyReactivate');
register_deactivation_hook( __FILE__, 'wpBackupifyReactivate');

?>
