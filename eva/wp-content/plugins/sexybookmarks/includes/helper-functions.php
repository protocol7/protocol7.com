<?php

/**
 * Returns the translated role of the current user. If that user has
 * no role for the current blog, it returns false.
 *
 * @return string The name of the current role
 * @notes older versions of WP return "Administrator|User role" which we strip down to "Administrator"
 **/
function shrsb_get_current_user_role() {
	global $wp_roles;
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	$role = array_shift($roles);
	return isset($wp_roles->role_names[$role]) ? preg_replace("/\|User role$/","",$wp_roles->role_names[$role]) : false;
}

/**
 * Warning : Please go through the code first before reusing the function
 * Append the character at the end of the string.
 * For Windows Servers, replace backward slashes to forward
 * 
 * @param <type> $string
 * @param <type> $char
 * @return <type> string
 */
function shrb_addTrailingChar($string, $char){
    // For window based servers
    if($char == '/'){
        $string = shrb_convertBackToForwardSlash($string);
    }
    
    //Appending the charachter at end if it already deoes not exist.
    if(substr($string, -1) != $char){
        $string .= $char;
    }
    return $string;
}

function shrb_convertBackToForwardSlash($string){

    $exp = array('\\','\\/', '\\\\','///');
    $string = str_replace($exp, '/', $string);

    return $string;
}

/* Adds FB Namespace */
function shrsb_addFBNameSpace($attr) {
    $attr .= "\n xmlns:og=\"http://opengraphprotocol.org/schema/\"";
	$attr .= "\n xmlns:fb=\"http://www.facebook.com/2008/fbml\"";
    return $attr;
}

function shrsb_preFlight_Checks() {
	global $shrsb_plugopts;

    //Check for the directory exists or not
    if(!wp_mkdir_p(SHRSB_UPLOADDIR.'spritegen/')) {
        @error_log("Failed to create path ".dirname($path));
    }
    if (!is_writable(SHRSB_UPLOADDIR.'spritegen')) {
        // the spritegen folder isn't writable. Try changing it to writable
      @chmod(SHRSB_UPLOADDIR.'spritegen/', 0775);
      // may or may not work
  }
  
	if( ((function_exists('curl_init') && function_exists('curl_exec')) || function_exists('file_get_contents'))
            && (is_dir(SHRSB_UPLOADDIR) && is_writable(SHRSB_UPLOADDIR))
            && ((isset($_POST['bookmark']) && is_array($_POST['bookmark']) && sizeof($_POST['bookmark']) > 0 ) || (isset($shrsb_plugopts['bookmark']) && is_array($shrsb_plugopts['bookmark']) && sizeof($shrsb_plugopts['bookmark']) > 0 ))
            && (!isset($shrsb_plugopts['custom-mods']) ||  isset($shrsb_plugopts['custom-mods']) && $shrsb_plugopts['custom-mods'] !== 'yes') ) {

		return true;
	}
	else {
		return false;
	}
}

function get_sprite_file($opts, $type) {
  global $shrsb_plugopts;
  $shrbase = $shrsb_plugopts['shrbase']?$shrsb_plugopts['shrbase']:'http://www.shareaholic.com';
  $spritegen = $shrbase.'/api/sprite/?v=1&apikey=8afa39428933be41f8afdb8ea21a495c&imageset=60'.$opts.'&apitype='.$type;
  $filename = SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.'.$type;
  $content = FALSE;

  if (!is_writable(SHRSB_UPLOADDIR.'spritegen')) {
        // the spritegen folder isn't writable. Try changing it to writable
      @chmod(SHRSB_UPLOADDIR.'spritegen', 0775);
      // may or may not work
  }
  if ( $type == 'png' ) {
    $fp_opt = 'rb';
  }
  else {
    $fp_opt = 'r';
  }

  if(function_exists('wp_remote_retrieve_body') && function_exists('wp_remote_get') && function_exists('wp_remote_retrieve_response_code')) {
    $request = wp_remote_get(
      $spritegen,
      array(
        'user-agent' => "shr-wpspritebot-fopen/v" . SHRSB_vNum,
        'headers' => array(
          'Referer' => get_bloginfo('url')
        )
      )
    );
    $response = wp_remote_retrieve_response_code($request);
    if($response == 200 || $response == '200') {
      $content = wp_remote_retrieve_body($request);
    }
    else {
      $content = FALSE;
    }
  }

  if ( $content === FALSE && function_exists('curl_init') && function_exists('curl_exec') ) {
	  $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $spritegen);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_USERAGENT, "shr-wpspritebot-cURL/v" . SHRSB_vNum);
    curl_setopt($ch, CURLOPT_REFERER, get_bloginfo('url'));
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);

    $content = curl_exec($ch);

    if ( curl_errno($ch) != 0 ) {
      $content = FALSE;
    }
    curl_close($ch);
  }

  if ( $content !== FALSE ) {
    if ( $type == 'png' ) {
      $fp_opt = 'w+b';
    }
    else {
      $fp_opt = 'w+';
    }

    
    $fp = @fopen($filename, $fp_opt);

    if ( $fp !== FALSE ) {
      $ret = @fwrite($fp, $content);
      @fclose($fp);
    }
    else {
      $ret = @file_put_contents($filename, $content);
    }

    if ( $ret !== FALSE ) {
      @chmod($filename, 0666);
      return 0;
    }
    else {
      return 1;
    }
  }
  else {
    return 2;
  }
}

/**
 * Gets the contents of a url on www.shareaholic.com.  We use shrbase as the
 * URL base path.  The caller is responsible for keeping track of whether the
 * cache is up-to-date or not.  If the cache is stale (because some argument
 * has changed), then the caller should pass true as the second argument.
 *
 * @url        - the partial url without base.  ex. /publishers
 * @path       - path to cache result to, under spritegen.
 *               ex. /publishers.html
 *               pass null to use the path part of url
 * @clearcache - force call and overwrite cache.
 */
function _shrsb_fetch_content($url, $path, $clearcache=false) {
  global $shrsb_plugopts;

  $shrbase = $shrsb_plugopts['shrbase']?$shrsb_plugopts['shrbase']:'http://www.shareaholic.com';

  if (!preg_match('|^/|', $url)) {
    @error_log("url must start with '/' in _shrsb_fetch_content");
    return FALSE;
  }

  // default path
  if (null === $path) {
    $url_parts = explode('?', $url);
    $path = rtrim($url_parts[0], '/');
  }

  $base_path = path_join(SHRSB_UPLOADDIR, 'spritegen');
  $abs_path = $base_path.$path;

  if ($clearcache || !($retval = _shrsb_read_file($abs_path))) {
    $response = wp_remote_get($shrbase.$url);
    if (is_wp_error($response)) {
      @error_log("Failed to fetch ".$shrbase.$url);
      $retval = FALSE;
    } else {
      $retval = $response['body'];
    }

   $write_succeed = _shrsb_write_file($abs_path, $retval);
   if(!$write_succeed) {
       $retval = FALSE;
   }
  }

  return $retval;
}

function _shrsb_write_file($path, $content) {
  $dir = dirname($path);
  $return = false;
  if(!wp_mkdir_p(dirname($path))) {
    @error_log("Failed to create path ".dirname($path));
  }
  $fh = fopen($path, 'w+');
  if (!$fh) {
    @error_log("Failed to open ".$path);
  } 
  else {
    if (!fwrite($fh, $content)) {
      @error_log("Failed to write to ".$path);
    } else {
        $return = true;
    }
    @fclose($fh);
  }
  return $return;
}

function _shrsb_read_file($path) {
  $content = FALSE;

  $fh = @fopen($path, 'r');
  if (!$fh) {
    @error_log("Failed to open ".$path);
  } 
  else {
    if (!$content = fread($fh, filesize($path))) {
      @error_log("Failed to read from ".$path);
    }
    @fclose($fh);
  }

  return $content;
}

//Copy the file in to the requested folder
function _shrsb_copy_file($des , $src){
    if(!$des || !$src )
        return false;
    return _shrsb_write_file($des ,_shrsb_read_file($src));
}



/**
 * Return Google Analytics for Admin Pages
 *
 * @return string
 * @author Jay Meattle
 **/
 
function get_googleanalytics() {
	$google_analytics = <<<EOD
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-12964573-5']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
EOD;
	return $google_analytics;
}

/**
 * Return SnapEngage Help Tab
 *
 * @return string
 * @author Jay Meattle
 **/
 
function get_snapengage() {
	$snapengage = <<<EOD
<!-- SnapEngage -->
<script type="text/javascript">
document.write(unescape("%3Cscript src='" + ((document.location.protocol=="https:")?"https://snapabug.appspot.com":"http://www.snapengage.com") + "/snapabug.js' type='text/javascript'%3E%3C/script%3E"));</script><script type="text/javascript">
SnapABug.setDomain('shareaholic.com');
SnapABug.addButton("62fa2e8b-38a9-4304-ba5c-86503444d30c","1","85%");
</script>
<!-- SnapEngage End -->
EOD;
	return $snapengage;
}

/**
 * @desc dump the sexybookmark settings from the database
 **/ 
function shrsb_dump_settings(){
    
    global $shrsb_debug;
    //data to dump
    $data = array(
		"siteurl"               => 	get_option('siteurl'),
		"version_database"      => 	get_option('SHRSBvNum'),
        "version_plugin"        => 	SHRSB_vNum,
		"apikey"                => 	get_option('SHRSB_apikey'),
		"custom_sprite"         => 	get_option('SHRSB_CustomSprite'),
		"default_spritegen" 	=> 	get_option('SHRSB_DefaultSprite'),
		"plugopts"              =>	get_option('SexyBookmarks')
	);
    
    if($shrsb_debug['dump_type'])
        switch($shrsb_debug['dump_type']){
            case "json":
                echo json_encode($data);	
                break;
            case "tree":
                echo shrsb_displayTree($data);
                break;
            default :
                var_export($data);    
        }
	$shrsb_debug['sb_die'] && die();
}


/**
 * @desc check for the attributes in the get and post
 **/
function shrsb_get_value($method =NULL, $attr = NULL, $def=false){
    if(!$method && !$attr){
      return $def;
    }

    switch($method){
        case "get":
            if(isset($_GET) && isset($_GET[$attr]) ) 
                return $_GET[$attr];
            break;
        case "post":
            if(isset($_POST) && isset($_POST[$attr]) ) 
                return $_POST[$attr];
            break;
        default :
    }
    
    return $def;
}

/**
 * @desc log the message if logging is enabled
 **/
function shrsb_log($msg){
    global $shrsb_debug;
    if(isset($shrsb_debug) && isset($shrsb_debug['sb_log']) && $shrsb_debug['sb_log'] !== false){
            echo '<!-- log:start --><span style=color:red>'.$msg.'</span><br><!-- log:end -->';
    }
}