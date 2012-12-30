<?php

// If you have your 'wp-content' directory in a place other than the default location, please specify your blog directory here. This is not your blog url. It is the address in your server. For example: '/public_html/myblog'
$blogdir = ""; 

if(isset($_REQUEST['refresh'])) {
	if (!$blogdir) {
		$blogdir = preg_replace('|/wp-content.*$|','', __FILE__);
	}
	if($blogdir == __FILE__) {
		$blogdir = preg_replace('|\wp-content.*$|','', __FILE__);
		include_once($blogdir.'\wp-config.php');
		include_once($blogdir.'\wp-includes\wp-db.php');
	}
	else {
		include_once($blogdir.'/wp-config.php');
		include_once($blogdir.'/wp-includes/wp-db.php');
	}
	include_once(str_replace("-ajax", "", __FILE__));
	$show_author = isset($_REQUEST['show_author'])?$_REQUEST['show_author']:1;
	$show_source = isset($_REQUEST['show_source'])?$_REQUEST['show_source']:1;
	if($display = quotescollection_display_randomquote($show_author, $show_source, 2)) {
		@header("Content-type: text/javascript; charset=utf-8");
		die( "document.getElementById('quotescollection_randomquote-".$_REQUEST['refresh']."').innerHTML = '".$display."'" ); 
	}
	else
		die( "alert('$error')" );
}

?>
