<?php
/*
Plugin Name: SexyBookmarks (by Shareaholic)
Plugin URI: http://www.shareaholic.com/tools/wordpress/
Description: Shareaholic adds a (X)HTML compliant list of social bookmarking icons to each of your posts. See <a href="admin.php?page=sexy-bookmarks.php">configuration panel</a> for more settings.
Version: 4.0.6.2
Author: Shareaholic
Author URI: http://www.shareaholic.com
Credits & Thanks: http://www.shareaholic.com/tools/wordpress/credits
*/

define('SHRSB_vNum','4.0.6.2');

/*
*   @note Make sure to include files first as there may be dependencies
*/

require_once 'includes/bookmarks-data.php';     // contains all bookmark templates
require_once 'includes/html-helpers.php';       // helper functions for html output
require_once 'includes/helper-functions.php';   // helper functions for backend
//require_once 'includes/widget.php';   // widget


/*
*  @ For Debugging Purpose
*/
if((isset($_GET['sb_debug']) || isset($_POST['sb_debug']) ) ){
	
    //Global Debugging Variable
    $shrsb_debug = array();
    $method =  isset($_GET)? "get" : "post"; //true for get, false for post
    $shrsb_debug['dump_type'] = shrsb_get_value($method, "sb_dump");
    $shrsb_debug['dump_type'] = shrsb_get_value($method, "dump_type");
    $shrsb_debug['sb_script'] = shrsb_get_value($method, "sb_script", false);
    $shrsb_debug['sb_die'] = shrsb_get_value($method, "sb_die", false);
    $shrsb_debug['sb_log'] = shrsb_get_value($method, "sb_log", false);
    
    shrsb_dump_settings();
}

/*
*   @desc Create Text Domain For Translations
*/

load_plugin_textdomain('shrsb', false, basename(dirname(__FILE__)) . '/languages/');

/*
 * Newer versions of WordPress include this class already
 * However, we've kept this here for people who are using older versions
 * This will mimick JSON support for PHP4 and below
*/

if ( !class_exists('SERVICES_JSON') ) {
	if ( !function_exists('json_decode') ){
		function json_decode($content, $assoc=false){
			require_once 'includes/JSON.php';
			if ( $assoc ){
				$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
			} else {
				$json = new Services_JSON;
			}
				return $json->decode($content);
		}
	}
	if ( !function_exists('json_encode') ){
		function json_encode($content){
			require_once 'includes/JSON.php';
			$json = new Services_JSON;
			return $json->encode($content);
		}
	}
}

/*
*   @desc Setting path for Shareaholic WP Plugin directory
*/

// Because we load assets via javascript, we can't rely on the Wordpress HTTPS plugin to do our http => https URL conversions.
// Consequently, we check here if this is an https (ssl) page load. If it is, we use the https prefix instead of http.
if ( !defined('WP_CONTENT_URL') ) {  
  define('SHRSB_PLUGPATH', shrsb_correct_protocol(get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/'));
	define('SHRSB_PLUGDIR', ABSPATH.'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');
} 
else {
  define('SHRSB_PLUGPATH', shrsb_correct_protocol(WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/'));
  define('SHRSB_PLUGDIR',WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
}

/*
*   @desc Setting path for Shareaholic Upload directory (local WP)
*/

if ( !function_exists('wp_upload_dir') ) {
        define('SHRSB_UPLOADPATH_DEFAULT', shrb_addTrailingChar(get_option('siteurl'),"/").'wp-content/uploads/shareaholic/');
        define('SHRSB_UPLOADDIR_DEFAULT', shrb_addTrailingChar(ABSPATH,"/").'wp-content/uploads/shareaholic/');
    } else {
        $upload_path  = wp_upload_dir();
        if(!$upload_path["error"]){
            define('SHRSB_UPLOADPATH_DEFAULT',shrb_addTrailingChar($upload_path['baseurl'],"/").'shareaholic/');
            define('SHRSB_UPLOADDIR_DEFAULT',shrb_addTrailingChar($upload_path['basedir'],"/").'shareaholic/');
        }else {
            define('SHRSB_UPLOADPATH_DEFAULT',shrb_addTrailingChar(SHRSB_PLUGPATH,"/"));
            define('SHRSB_UPLOADDIR_DEFAULT',shrb_addTrailingChar(SHRSB_PLUGDIR,"/"));
      }
}

/*
*   @desc Most Popular Services List
*   @note To change the most popular list also change the "Most Popular" link click handler in shareaholic-admin.js
*/

$shrsb_most_popular = array (
    'shr-facebook',
    'shr-twitter',
    'shr-linkedin',
    'shr-googlebookmarks',
    'shr-delicious',
    'shr-stumbleupon',
    'shr-reddit',
    'shr-gmail',
    'shr-mail',
    'shr-printfriendly'
);

$defaultLikeButtonOrder = array(
    'shr-fb-like',
    'shr-fb-send',
    'shr-plus-one'
);

/*
*   @desc Set default options
*/

$shrsb_plugopts = array(
  'position' => 'below', // below, above, or manual
  'reloption' => 'nofollow', // 'nofollow', or ''
  'targetopt' => '_blank', // 'blank' or 'self'
  'perfoption' => '1', // performance script (GA)
  'showShareCount' => '1', // fb/twit share count

  'likeButtonSetTop' => '0', // Include like button below the Post Title
  'fbLikeButtonTop' => '0', // Include fb like button
  'fbSendButtonTop' => '0', // Include fb like button
  'googlePlusOneButtonTop' => '0', // Include Google Plus One button
  'likeButtonSetSizeTop' => "1", // Size of like buttons
  'likeButtonSetCountTop' => "true", // Show count with +1 button
  'likeButtonOrderTop' => $defaultLikeButtonOrder,
  'likeButtonSetAlignmentTop' => '0', // Alignment 0 => left, 1 => right

  'likeButtonSetBottom' => '1', // Include like button below the Post
  'fbLikeButtonBottom' => '0', // Include fb like button
  'fbSendButtonBottom' => '0', // Include fb like button
  'googlePlusOneButtonBottom' => '0', // Include Google Plus One button
  'likeButtonSetSizeBottom' => "1", // Size of like buttons
  'likeButtonSetCountBottom' => "true", // Show count with +1 button
  'likeButtonOrderBottom' => $defaultLikeButtonOrder,
  'likeButtonSetAlignmentBottom' => '0', // Alignment 0 => left, 1 => right
    
  'fbNameSpace' => '1',  // Add fb name space to the html
  'preventminify' => '1',  // prevent wp_minify from minifying the js
  'shrlink' => '1', // show promo link
  'bgimg-yes' => 'yes', // 'yes' or blank
  'mobile-hide' => '', // 'yes' or blank
  'bgimg' => 'caring', // default bg image
  'shorty' => 'google', // default shortener
  'pageorpost' => 'postpageindexcategory',
  'bookmark' => $shrsb_most_popular,//array_keys($shrsb_bookmarks_data), // pulled from bookmarks-data.php
  'feed' => '0', // 1 or 0
  'expand' => '1',
  'autocenter' => '1',
  'tweetconfig' => '${title} - ${short_link} via @Shareaholic', // Custom configuration of tweet
  'warn-choice' => '',
  'doNotIncludeJQuery' => '',
  'custom-mods' => '',
  'scriptInFooter' => '',
  'shareaholic-javascript' => '1',
  'shrbase' => 'http://www.shareaholic.com',
  'apikey' => '8afa39428933be41f8afdb8ea21a495c',
  'designer_toolTips' => '1',
  'tip_bg_color' => '#000000',  // tooltip background color
  'tip_text_color' => '#ffffff', // tooltip text color
  // comma delimited list of service ids for publisher javascript
  'service' => '',
  'spritegen_path' => SHRSB_UPLOADDIR_DEFAULT,
  'ogtags' => '1'
);

//add to database
$shrsb_plugopts['tweetconfig'] = urlencode($shrsb_plugopts['tweetconfig']);
add_option('SexyBookmarks', $shrsb_plugopts);
add_option('SHRSB_apikey', $shrsb_plugopts['apikey']);
add_option('SHRSB_CustomSprite', '');
add_option('SHRSB_DefaultSprite',true);

//reload from database
$shrsb_plugopts = get_option('SexyBookmarks');


//Remove the propeller Service
if(isset ($shrsb_plugopts) && isset($shrsb_plugopts['service'])){
   $services = explode(',', $shrsb_plugopts['service']);

   if(!empty($services)){
       foreach ($services as $k => $v){
           if($v == '77'){
               unset ($services[$k]);
           }
       }
       $shrsb_plugopts['service'] = implode(',', $services );
   }
}

/*
*   @note Make sure spritegen_path is defined
*/

if(!isset($shrsb_plugopts['spritegen_path'])) {
    $shrsb_plugopts['spritegen_path'] = SHRSB_UPLOADDIR_DEFAULT;
}else{
    if($shrsb_plugopts['spritegen_path'] == 'SHRSB_UPLOADDIR_DEFAULT')
       $shrsb_plugopts['spritegen_path'] = SHRSB_UPLOADDIR_DEFAULT;
}

//Check for POST
if(isset($_POST['save_changes']) ){
    //Define the default path for Spritegen Directory
    if(isset($_POST['spritegen_path']) && $_POST['spritegen_path'] !=  SHRSB_UPLOADDIR_DEFAULT){
        //Create the Directory
        $p = shrb_addTrailingChar(stripslashes($_POST['spritegen_path']),"/");

        define('SHRSB_UPLOADDIR', $p);
        define('SHRSB_UPLOADPATH', shr_dir_to_path($p));
    }else{
        define('SHRSB_UPLOADDIR', SHRSB_UPLOADDIR_DEFAULT);
        define('SHRSB_UPLOADPATH', SHRSB_UPLOADPATH_DEFAULT);
    }
}else{
    if( isset($_POST['reset_all_options'])|| (isset($shrsb_plugopts['spritegen_path']) && $shrsb_plugopts['spritegen_path'] == SHRSB_UPLOADDIR_DEFAULT) ){
        // For Reseting the data Or First Time Install
        define('SHRSB_UPLOADDIR', SHRSB_UPLOADDIR_DEFAULT);
        define('SHRSB_UPLOADPATH', SHRSB_UPLOADPATH_DEFAULT);
    }else{
        $p = shrb_addTrailingChar(stripslashes($shrsb_plugopts['spritegen_path']),"/");
        define('SHRSB_UPLOADDIR', $p);
        define('SHRSB_UPLOADPATH', shr_dir_to_path($p));
    }
}

// Some new default options may not be present in the database.
// Add them if they aren't.
if(!isset($shrsb_plugopts['designer_toolTips'])) {
    $shrsb_plugopts['designer_toolTips'] = '1';
    $shrsb_plugopts['tip_bg_color'] = '#000000';  // tooltip background color
    $shrsb_plugopts['tip_text_color'] = '#ffffff'; // tooltip text color
}

if(!isset($shrsb_plugopts['likeButtonSetTop'])) {
      $shrsb_plugopts['likeButtonSetTop'] = '0'; // Include fb like button
      $shrsb_plugopts['fbLikeButtonTop'] = '0'; // if fb like button is included. Include in bottom right by default
      $shrsb_plugopts['fbSendButtonTop' ] =  '0'; // Include fb like button
      $shrsb_plugopts['googlePlusOneButtonTop' ] =  '0'; // Include Google Plus One button
      $shrsb_plugopts['likeButtonSetSizeTop' ] =  "1"; // Size of like buttons
      $shrsb_plugopts['likeButtonSetCountTop'] =  "true"; // Show count with +1 button
      $shrsb_plugopts['likeButtonOrderTop' ] =  $defaultLikeButtonOrder;
      $shrsb_plugopts['likeButtonSetAlignmentTop' ] =  '0'; // Alignment 0 => left, 1 => right
      $shrsb_plugopts['likeButtonSetBottom'] = '1'; // Include fb like button
      $shrsb_plugopts['fbLikeButtonBottom'] = '0'; // if fb like button is included. Include in bottom right by default
      $shrsb_plugopts['fbSendButtonBottom' ] =  '0'; // Include fb like button
      $shrsb_plugopts['googlePlusOneButtonBottom' ] =  '0'; // Include Google Plus One button
      $shrsb_plugopts['likeButtonSetSizeBottom' ] =  "1"; // Size of like buttons
      $shrsb_plugopts['likeButtonSetCountBottom'] =  "true"; // Show count with +1 button
      $shrsb_plugopts['likeButtonOrderBottom' ] =  $defaultLikeButtonOrder;
      $shrsb_plugopts['likeButtonSetAlignmentBottom' ] =  '0'; // Alignment 0 => left, 1 => right
}

if(!isset($shrsb_plugopts['ogtags'])) {
    $shrsb_plugopts['ogtags'] = "1";
}

if(!isset($shrsb_plugopts['fbNameSpace'])) {
    $shrsb_plugopts['fbNameSpace'] = "1";
}

if(!isset($shrsb_plugopts['preventminify'])) {
    $shrsb_plugopts['preventminify'] = "1";
}

if($shrsb_plugopts['fbNameSpace'] == '1') {
    add_filter('language_attributes', 'shrsb_addFBNameSpace');
}

$shrsb_plugopts['tweetconfig'] = urldecode($shrsb_plugopts['tweetconfig']);

$shrsb_plugopts['apikey'] = get_option('SHRSB_apikey');
$shrsb_custom_sprite = get_option('SHRSB_CustomSprite');
$shrsb_version = get_option('SHRSBvNum');

// code to remove redundant data fields from the database
if(isset($shrsb_plugopts['twittcat'])) {
    $shrsb_plugopts['ybuzzcat'] = '';
    $shrsb_plugopts['ybuzzmed'] = '';
    $shrsb_plugopts['twittcat'] = '';
    $shrsb_plugopts['defaulttags'] = '';
}

// Some databases got corrupted. This will set things in place.
if($shrsb_plugopts['shrbase'] != 'http://www.shareaholic.com'){
    $shrsb_plugopts['shrbase'] = 'http://www.shareaholic.com';
}

// Reset depreciated url shorteners
if($shrsb_plugopts['shorty'] == 'slly' || $shrsb_plugopts['shorty'] == 'cligs' || $shrsb_plugopts['shorty'] == 'snip' || $shrsb_plugopts['shorty'] == 'tinyarrow' || $shrsb_plugopts['shorty'] == 'b2l' || $shrsb_plugopts['shorty'] == 'trim' || $shrsb_plugopts['shorty'] == 'e7t')  {

    $shrsb_plugopts['shortyapi']['snip']['user'] = '';
    $shrsb_plugopts['shortyapi']['snip']['key'] = '';
    $shrsb_plugopts['shortyapi']['trim']['chk'] = '';
    $shrsb_plugopts['shortyapi']['trim']['user'] = '';
    $shrsb_plugopts['shortyapi']['trim']['pass'] = '';
    $shrsb_plugopts['shortyapi']['tinyarrow']['chk'] = '';
    $shrsb_plugopts['shortyapi']['tinyarrow']['user'] = '';
    $shrsb_plugopts['shortyapi']['cligs']['chk'] = '';
    $shrsb_plugopts['shortyapi']['cligs']['key'] = '';
}

/*
*   @desc Re-name values
*/

if($shrsb_plugopts['shorty'] == 'tiny') {
    $shrsb_plugopts['shorty'] = 'tinyurl';
}

if($shrsb_plugopts['shorty'] == 'googl') {
    $shrsb_plugopts['shorty'] = 'google';
}

/*
*   @desc Fix short URLs corrupt value
*/

if(isset($shrsb_plugopts['shortyapi'])){

    if(strpos($shrsb_plugopts['shortyapi']['bitly']['user'],"sexybookmarks/sexy-bookmarks.php")) {
        $shrsb_plugopts['shortyapi']['bitly']['user'] = "";
    }
    if(strpos($shrsb_plugopts['shortyapi']['bitly']['key'],"sexybookmarks/sexy-bookmarks.php")) {
        $shrsb_plugopts['shortyapi']['bitly']['key'] = "";
    }
    if(strpos($shrsb_plugopts['shortyapi']['jmp']['user'],"sexybookmarks/sexy-bookmarks.php")) {
        $shrsb_plugopts['shortyapi']['jmp']['user'] = "";
    }
    if(strpos($shrsb_plugopts['shortyapi']['jmp']['key'],"sexybookmarks/sexy-bookmarks.php") ) {
        $shrsb_plugopts['shortyapi']['jmp']['key'] = "";
    }
    if(strpos($shrsb_plugopts['shortyapi']['supr']['chk'],"sexybookmarks/sexy-bookmarks.php") ) {
        $shrsb_plugopts['shortyapi']['supr']['chk'] = "";
    }
    if(strpos($shrsb_plugopts['shortyapi']['supr']['user'],"sexybookmarks/sexy-bookmarks.php") ) {
        $shrsb_plugopts['shortyapi']['supr']['user'] = "";
    }
    if(strpos($shrsb_plugopts['shortyapi']['supr']['key'],"sexybookmarks/sexy-bookmarks.php") ) {
        $shrsb_plugopts['shortyapi']['supr']['key'] = "";
    }

}/* Short URLs End */

// if the version number is set and is not the latest, then call the upgrade function
if(false !== $shrsb_version &&  $shrsb_version !== SHRSB_vNum ) {
   update_option('SHRSB_DefaultSprite',true);
   add_action('admin_notices', 'shrsb_Upgrade', 12);
}

$default_spritegen = get_option('SHRSB_DefaultSprite');

if(!is_writable(SHRSB_UPLOADDIR)) {
    //add_action('admin_notices', 'shrsb_SpritegenNotice', 12);
}

//if(false !== $shrsb_version &&  $shrsb_version !== SHRSB_vNum &&  SHRSB_vNum === '3.3.11' ) {
//    // This is specific till 3.3.11 to over ride existing customers to beta mode
//   if($shrsb_plugopts['shareaholic-javascript']  !== '1') {
//           $shrsb_plugopts['shareaholic-javascript']  = '1';
//           update_option('SexyBookmarks', $shrsb_plugopts);
//   }
//}


function shrsb_Upgrade() {
    global $shrsb_plugopts;

    if (shrsb_get_current_user_role()=="Administrator"){
         // check if sprite files are not present, ask the user to re-save setting.
         if($shrsb_plugopts['shareaholic-javascript'] == '1' ||
                 (!file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png') || !file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css'))) {
             echo '
              <div id="update_sb" style="border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;background:#feb1b1;border:1px solid #fe9090;color:#820101;font-size:10px;font-weight:bold;height:auto;margin:35px 15px 0 0;overflow:hidden;padding:4px 10px 6px;">
                <div style="background:url('.SHRSB_UPLOADPATH.'images/custom-fugue-sprite.png) no-repeat 0 -525px;margin:2px 10px 0 0;float:left;line-height:18px;padding-left:22px;">
                  '.sprintf(__('NOTICE: Shareaholic was just updated... Please visit the %sPlugin Options Page%s and re-save your preferences.', 'shrsb'), '<a href="admin.php?page=sexy-bookmarks.php" style="color:#ca0c01">', '</a>').'
                </div>
              </div>';
         }
    }
}

function shrsb_SpritegenNotice() {
    if (shrsb_get_current_user_role()=="Administrator"){
             echo '
              <div id="update_sb" style="border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;background:#feb1b1;border:1px solid #fe9090;color:#820101;font-size:10px;font-weight:bold;height:auto;margin:35px 15px 0 0;overflow:hidden;padding:4px 10px 6px;">
                <div style="background:url('.SHRSB_UPLOADPATH.'custom-fugue-sprite.png) no-repeat 0 -525px;margin:2px 10px 0 0;float:left;line-height:18px;padding-left:22px;">
                  '.sprintf(__('NOTICE: Your spritegen directory isn\'t writable... Please %sCHMOD%s your spritegen directory to ensure that Shareaholic remains working like a charm...', 'shrsb'), '<a href="http://www.shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target = "_blank" style="color:#ca0c01">', '</a>').'
                </div>
              </div>';
    }
}

//add activation hook to remove all old and non-existent options from database if necessary
function shrsb_Activate() {
  if(false === get_option('SHRSBvNum') || get_option('SHRSBvNum') == '') {
    delete_option('SexyBookmarks');
    delete_option('SexyCustomSprite');
    delete_option('SEXY_SPONSORS');
    delete_option('SHRSB_CustomSprite');
  }
  if(!file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png') || !file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css')) {
    delete_option('SHRSB_CustomSprite');
  }
}
register_activation_hook( __FILE__, 'shrsb_Activate' );

//add deactivation hook to update the version number option so that options won't be deleted again upon upgrading
function shrsb_deActivate() {
  if(false !== get_option('SHRSBvNum') || get_option('SHRSBvNum') != '') {
    update_option('SHRSBvNum', SHRSB_vNum);
  }
}
register_deactivation_hook( __FILE__, 'shrsb_deActivate' );

//add update notice to the main dashboard area so it's visible throughout
function showUpdateNotice() {
  //If the option doesn't exist yet, it means the old naming scheme was found and scrubbed... Let's alert the user to update their settings
  if(!get_option('SHRSBvNum') || get_option('SHRSBvNum') == '') {
    echo '
      <div id="update_sb" style="border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;background:#feb1b1;border:1px solid #fe9090;color:#820101;font-size:10px;font-weight:bold;height:auto;margin:35px 15px 0 0;overflow:hidden;padding:4px 10px 6px;">
        <div style="background:url('.SHRSB_UPLOADPATH.'images/custom-fugue-sprite.png) no-repeat 0 -525px;margin:2px 10px 0 0;float:left;line-height:18px;padding-left:22px;">
          '.sprintf(__('NOTICE: Shareaholic needs to be configured... Please visit the %sPlugin Options Page%s and set your preferences.', 'shrsb'), '<a href="admin.php?page=sexy-bookmarks.php" style="color:#ca0c01">', '</a>').'
        </div>
      </div>';
  }
}
/*
*   @desc Adds "SexyBookmarks" options on each post
*/
function _add_meta_box_options() {
	
    if( shrsb_get_current_user_role() ==  "Administrator" || shrsb_get_current_user_role() ==  "Editor") {
        //"Hide options on each post
        add_meta_box( 'hide_options_meta', __( 'Shareaholic', 'shrsb' ), '_hide_options_meta_box_content', 'page', 'advanced', 'high' );
        add_meta_box( 'hide_options_meta', __( 'Shareaholic', 'shrsb' ), '_hide_options_meta_box_content', 'post', 'advanced', 'high' );
    }
}
add_action( 'admin_init', '_add_meta_box_options' );

function _hide_options_meta_box_content() {
    global $post;
    $hide_sexy = get_post_meta( $post->ID, 'Hide SexyBookmarks',true);
    $hide_ogtags = get_post_meta( $post->ID, 'Hide OgTags',true);
	if ( isset( $hide_sexy ) && $hide_sexy == 1 )
		$hide_sexy = ' checked="checked"';
	else
		$hide_sexy = '';
    if ( isset( $hide_ogtags ) && $hide_ogtags == 1 )
		$hide_ogtags = ' checked="checked"';
	else
		$hide_ogtags = '';
    
    //"Hide SexyBookmarks" option on each post
	echo '<p><label for="hide_sexy"><input name="hide_sexy" id="hide_sexy" value="1"' . $hide_sexy . ' type="checkbox"> ' . __( 'Disable SexyBookmarks Buttons on this page.', 'shrsb' ) . '</label></p>';
    
    //For Hiding the OG tags for specific posts
    echo '<p><label for="hide_ogtags"><input name="hide_ogtags" id="hide_ogtags" value="1"' . $hide_ogtags . ' type="checkbox"> ' . __( 'Disable Open Graph Tags on this page.', 'shrsb' ) . '(<a href="http://www.shareaholic.com/tools/wordpress/faq/#open_graph_image" target="_blank">?</a>)</label></p>';
}

function _hide_options_meta_box_save( $post_id ) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return $post_id;

	// Record sharing disable
	if (isset($_POST['post_type']) && !isset($_POST['_inline_edit']) && ('post' == $_POST['post_type'] || 'page' == $_POST['post_type'] )) {
		if ( current_user_can( 'edit_post', $post_id ) ) {
            if ( isset( $_POST['hide_sexy'] ) ) {
                update_post_meta( $post_id, 'Hide SexyBookmarks', 1 );
            }
            else {
                //delete_post_meta( $post_id, 'Hide SexyBookmarks' );
                update_post_meta( $post_id, 'Hide SexyBookmarks', 0 );
            }
            
            if ( isset( $_POST['hide_ogtags'] ) ) {
                update_post_meta( $post_id, 'Hide OgTags', 1 );
            }
            else {
                //delete_post_meta( $post_id, 'Hide OgTags' );
                update_post_meta( $post_id, 'Hide OgTags', 0 );
            }
		}
	}

  return $post_id;
}

add_action( 'save_post', '_hide_options_meta_box_save' );
add_action('admin_notices', 'showUpdateNotice', 12);

/*
*   @desc Checks to see if wp-minify is installed, if so, whitelist our files
*/
function exclude_from_minify_list() {
    $minify_opts = get_option("wp_minify");

    if(is_array($minify_opts) && is_array($minify_opts["js_exclude"])) {
        $bfound = false;
        foreach($minify_opts["js_exclude"] as $url) {
            if($url == 'jquery.shareaholic-publishers-sb.min.js') {
                $bfound = true;
            }
        }
        if(!$bfound) {
            array_push($minify_opts["js_exclude"],'jquery.shareaholic-publishers-sb.min.js');
        }
        update_option("wp_minify", $minify_opts);
    }
}

function shrsb_likeButtonSetHTML($pos = 'Bottom') {   // $pos = Bottom/Top
    global $shrsb_plugopts;
    ?>

    <table><tbody style ="display:none" class="likeButtonsAvailable<?php echo $pos;?>">
            <tr class="tabForTr">
                <td><span class="shrsb_option"><?php _e('Include Facebook Like Button', 'shrsb'); ?> <span style="color:red;">*</span></span>
                </td>
                <td style="width:125px"><label><input <?php echo (($shrsb_plugopts["fbLikeButton$pos"] == "1")? 'checked="checked"' : ""); ?> name="fbLikeButton<?php echo $pos;?>" id="fbLikeButton<?php echo $pos;?>-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                </td><td><label><input <?php echo (($shrsb_plugopts["fbLikeButton$pos"] == "0")? 'checked="checked"' : ""); ?> name="fbLikeButton<?php echo $pos;?>" id="fbLikeButton<?php echo $pos;?>-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                </td>
            </tr>
            <tr class="tabForTr">
                <td><span class="shrsb_option"><?php _e('Include Facebook Send Button', 'shrsb'); ?> <span style="color:red;">*</span></span>
                </td>
                <td style="width:125px"><label><input <?php echo (($shrsb_plugopts["fbSendButton$pos"] == "1")? 'checked="checked"' : ""); ?> name="fbSendButton<?php echo $pos;?>" id="fbSendButton<?php echo $pos;?>-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                </td><td><label><input <?php echo (($shrsb_plugopts["fbSendButton$pos"] == "0")? 'checked="checked"' : ""); ?> name="fbSendButton<?php echo $pos;?>" id="fbSendButton<?php echo $pos;?>-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                </td>
            </tr>
            <tr class="tabForTr">
                <td><span class="shrsb_option"><?php _e('Include Google +1 Button', 'shrsb'); ?> <span style="color:red;">*</span></span>
                </td>
                <td style="width:125px"><label><input <?php echo (($shrsb_plugopts["googlePlusOneButton$pos"] == "1")? 'checked="checked"' : ""); ?> name="googlePlusOneButton<?php echo $pos;?>" id="googlePlusOneButton<?php echo $pos;?>-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                </td><td><label><input <?php echo (($shrsb_plugopts["googlePlusOneButton$pos"] == "0")? 'checked="checked"' : ""); ?> name="googlePlusOneButton<?php echo $pos;?>" id="googlePlusOneButton<?php echo $pos;?>-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                </td>
            </tr>

            <tr class="tabForTr likeButtonSetOptions<?php echo $pos;?>" id="likeButtonSetAlignment<?php echo $pos;?>" style="display:none">
                <td>
                    <span class="tab shrsb_option" style="display:block"><?php _e('Button Alignment (w.r.t post)', 'shrsb'); ?></span>
                </td>
                <td colspan="2">
                    <select name="likeButtonSetAlignment<?php echo $pos;?>">
                        <?php
                            print shrsb_select_option_group(
                                'likeButtonSetAlignment'.$pos, array(
                                    '0'=>__('Left Aligned', 'shrsb'),
                                    '1'=>__('Right Aligned', 'shrsb')
                                )
                            );
                        ?>
                    </select>
                </td>
            </tr>
            <tr class ="tabForTr likeButtonSetOptions<?php echo $pos;?>" style="display:none">
                <td>
                    <span class="tab shrsb_option" style="display:block"><?php _e('Button Style', 'shrsb'); ?></span>
                </td>
                <td style="width:125px">
                    <select name="likeButtonSetSize<?php echo $pos;?>">
                        <?php
                            print shrsb_select_option_group(
                                "likeButtonSetSize$pos", array(
                                    '0'=>__('Standard', 'shrsb'),
                                    '1'=>__('Buttons', 'shrsb'),
                                    '2'=>__('Box', 'shrsb'),
                                )
                            );
                        ?>
                    </select>
                </td>

            </tr>

            <tr class ="tabForTr likeButtonSetOptions<?php echo $pos;?>" style="display:none">
                <td>
                    <span class="tab shrsb_option" style="display:block"><?php _e('Show counter for +1 Button:', 'shrsb'); ?></span>
                </td>
                <td style="width:125px">
                    <select name="likeButtonSetCount<?php echo $pos;?>">
                        <?php
                            print shrsb_select_option_group(
                                "likeButtonSetCount$pos", array(
                                    'true'=>__('Yes', 'shrsb'),
                                    'false'=>__('No', 'shrsb'),
                                )
                            );
                        ?>
                    </select>
                </td>

            </tr>

            <tr class ="tabForTr likeButtonSetOptions<?php echo $pos;?>" style="display:none">
                <td rowspan="4" colspan="3" >
                    <small><?php _e('Drag to reorder.', 'shrsb'); ?></small>

                    <div style="clear: both; min-height: 1px; height: 5px; width: 100%;"></div>
                    <div id="buttonPreviews<?php echo $pos;?>" style="clear: both; max-height: 100px !important; max-width: 600px !important;"><ul>
                        <?php
                            $fbLikeHTML = '<li ><div style="display:none; cursor:move;" class="likebuttonpreview'.$pos.'">
                                        <input name="likeButtonOrder'.$pos.'[]" type="hidden" value="shr-fb-like"/>
                                    </div></li>';
                            $plusOneHTML = '<li><div style=" display:none; cursor:move;" class="plusonepreview'.$pos.'">
                                            <input name="likeButtonOrder'.$pos.'[]" type="hidden" value="shr-plus-one"/>
                                    </div></li>';

                            $fbSendHTML = '<li><div style = "display:none; cursor:move;" class="sendbuttonpreview'.$pos.' shr-fb-send">
                                        <input name="likeButtonOrder'.$pos.'[]" type="hidden" value="shr-fb-send"/>
                                    </div></li>';

                            foreach($shrsb_plugopts['likeButtonOrder'.$pos] as $likeOption) {
                                switch($likeOption) {
                                    case "shr-fb-like":
                                        echo $fbLikeHTML;
                                        break;
                                    case "shr-plus-one":
                                        echo $plusOneHTML;
                                        break;
                                    case "shr-fb-send":
                                        echo $fbSendHTML;
                                        break;
                                }
                            }
                        ?>
                    </ul></div>
                </td>
            </tr>
            <tr height="60px">
                <script>
                (function ($) {
                    var renderPlusOnes = function () {
                            var size = $('select[name$="likeButtonSetSize<?php echo $pos;?>"]').val();
                            switch(size) {
                                case '1':
                                    size = "button";
                                    break;
                                case '2':
                                    size = "box";
                                    break;
                                default:
                                    size = "standard";
                                    break;
                            }
                            var count = $('select[name$="likeButtonSetCount<?php echo $pos;?>"]').val();
                            switch(count) {
                                case 'false':
                                    count = '';
                                    break;
                                default:
                                    count = '-count';
                                    break;
                            }
                            var classN = 'shr-plus-one-' + size + count;
                            classN = "plusonepreview<?php echo $pos;?> "  + classN;
                            $('.plusonepreview<?php echo $pos;?>').removeClass().addClass(classN);

                    };
                    $('select[name$="likeButtonSetCount<?php echo $pos;?>"],select[name$="likeButtonSetSize<?php echo $pos;?>"]').change(function () {
                        renderPlusOnes();
                    });

                    renderPlusOnes();


                    var renderLikeButtonPreview = function () {
                        var layout = $('select[name$="likeButtonSetSize<?php echo $pos;?>"]').val();
                        switch(layout) {
                            case '1':
                                layout = "button";
                                break;
                            case '2':
                                layout = "box";
                                break;
                            default:
                                layout = "standard";
                                break;
                        }
                        var classN = 'shr-fb-like-' + layout;
                        classN = "likebuttonpreview<?php echo $pos;?> "  + classN;
                        $('.likebuttonpreview<?php echo $pos;?>').removeClass().addClass(classN);
                    };

                    $('select[name$="likeButtonSetSize<?php echo $pos;?>"]').change(function () {
                        renderLikeButtonPreview();
                    });
                    renderLikeButtonPreview();
                })(jQuery);
            </script>
            </tr>
            <tr></tr>
            <tr></tr>


<?php
}

function _make_params($params) {
  $pairs = array();
  foreach ($params as $k => $v) {
    $pairs[] = implode('=', array(urlencode($k), urlencode($v)));
  }
  return implode('&', $pairs);
}

/**
 * Make a local copy of all shareaholic resources
 */
function shrsb_refresh_cache() {
  global $shrsb_plugopts, $shrsb_bgimg_map, $default_spritegen;

  $script = _shrsb_fetch_content('/media/js/jquery.shareaholic-publishers-sb.min.js', '/jquery.shareaholic-publishers-sb.min.js', true);

  // Sort services to make request more cacheable.
  $services = explode(',', $shrsb_plugopts['service']);
  sort($services, SORT_NUMERIC);
  $services = implode(',', $services);

  $sprite_opts = array(
    'v' => 2,
    'apikey' => $shrsb_plugopts['apikey'],
    'service' => $services,
    'bgimg' => $shrsb_bgimg_map[$shrsb_plugopts['bgimg']]['url'],
    'bgimg_padding' => $shrsb_bgimg_map[$shrsb_plugopts['bgimg']]['padding']
  );
  // save as css so mime types work on normal servers
  $css = _shrsb_fetch_content('/api/sprite/?'._make_params($sprite_opts), '/sprite.css', true);
  $sprite_opts['apitype'] = 'png';
  $png = _shrsb_fetch_content('/api/sprite/?'._make_params($sprite_opts), '/sprite.png', true);

  if(!$script || !$css || !$png) {
    update_option('SHRSB_DefaultSprite',true);
    $default_spritegen = true;
  } else {
    update_option('SHRSB_DefaultSprite',false);
    $default_spritegen = false;
  }
}

//write settings page
function shrsb_settings_page() {
	global $shrsb_plugopts, $shrsb_bookmarks_data, $wpdb, $shrsb_custom_sprite,$shrsb_most_popular,$defaultLikeButtonOrder;
    // Add all the global varaible declarations for the $shrsb_plugopts default options e.g. $shrsb_most_popular,$defaultLikeButtonOrder

	echo '<div class="wrap""><div class="icon32" id="icon-options-general"><br></div><h2>Shareaholic Settings</h2></div>';

    //Defaults - set if not present
    if (!isset($_POST['reset_all_options'])){$_POST['reset_all_options'] = '1';}
    if (!isset($_POST['shrsbresetallwarn-choice'])){$_POST['shrsbresetallwarn-choice'] = 'no';}
    if (!isset($_POST['custom-mods'])  || $shrsb_plugopts['custom-mods'] == ""){$_POST['custom-mods'] = 'no';}

	if($_POST['reset_all_options'] == '0') {
		echo '
		<div id="shrsbresetallwarn" class="dialog-box-warning" style="float:none;width:97%;">
			<div class="dialog-left fugue f-warn">
				'.__("WARNING: You are about to reset all settings to their default state! Do you wish to continue?", "shrsb").'
			</div>
			<div class="dialog-right">
				<form action="" method="post" id="resetalloptionsaccept">
					<label><input name="shrsbresetallwarn-choice" id="shrsbresetallwarn-yes" type="radio" value="yes" />'.__('Yes', 'shrsb').'</label> &nbsp; <label><input name="shrsbresetallwarn-choice" id="shrsbresetallwarn-cancel" type="radio" value="cancel" />'.__('Cancel', 'shrsb').'</label>
				</form>
			</div>
		</div>';
	}

	//Reset all options to default settings if user clicks the reset button
	if($_POST['shrsbresetallwarn-choice'] == "yes") { //check for reset button click
		delete_option('SexyBookmarks');
		$shrsb_plugopts = array(
			'position' => 'below', // below, above, or manual
			'reloption' => 'nofollow', // 'nofollow', or ''
			'targetopt' => '_blank', // 'blank' or 'self'
			'perfoption' => '1', // performance script (GA)
			'showShareCount' => '1', // fb/twit share count

            'likeButtonSetTop' => '0', // Include like button below the Post Title
            'fbLikeButtonTop' => '0', // Include fb like button
            'fbSendButtonTop' => '0', // Include fb like button
            'googlePlusOneButtonTop' => '0', // Include Google Plus One button
            'likeButtonSetSizeTop' => "1", // Size of like buttons
            'likeButtonSetCountTop' => "true", // Show count with +1 button
            'likeButtonOrderTop' => $defaultLikeButtonOrder,
            'likeButtonSetAlignmentTop' => '0', // Alignment 0 => left, 1 => right

            'likeButtonSetBottom' => '1', // Include like button below the Post
            'fbLikeButtonBottom' => '0', // Include fb like button
            'fbSendButtonBottom' => '0', // Include fb like button
            'googlePlusOneButtonBottom' => '0', // Include Google Plus One button
            'likeButtonSetSizeBottom' => "1", // Size of like buttons
            'likeButtonSetCountBottom' => "true", // Show count with +1 button
            'likeButtonOrderBottom' => $defaultLikeButtonOrder,
            'likeButtonSetAlignmentBottom' => '0', // Alignment 0 => left, 1 => right

            'fbNameSpace' => '1',  // Add fb name space to the html
            'preventminify' => '1',  // prevent wp_minify from minifying the js
            'shrlink' => '1', // show promo link
			'bgimg-yes' => 'yes', // 'yes' or blank
			'mobile-hide' => '', // 'yes' or blank
			'bgimg' => 'caring', // default bg image
			'shorty' => 'google', // default shortener
			'pageorpost' => 'postpageindexcategory',
			'bookmark' => $shrsb_most_popular ,//array_keys($shrsb_bookmarks_data),
			'feed' => '0', // 1 or 0
			'expand' => '1',
			'autocenter' => '1',
			'tweetconfig' => '${title} - ${short_link} via @Shareaholic', // Custom configuration of tweet
		    'warn-choice' => '',
			'doNotIncludeJQuery' => '',
			'custom-mods' => '',
			'scriptInFooter' => '',
            'shareaholic-javascript' => '1',
            'shrbase' => 'http://www.shareaholic.com',
            'apikey' => get_option('SHRSB_apikey'),
            'service' => '',
            'designer_toolTips' => '1',
            'tip_bg_color' => '#000000',  // tooltip background color
            'tip_text_color' => '#ffffff', // tooltip text color
            'spritegen_path' => SHRSB_UPLOADDIR_DEFAULT,
            'ogtags' => '1'  //OgTags
        );

        $shrsb_plugopts['tweetconfig'] = urlencode($shrsb_plugopts['tweetconfig']);
        
        if($shrsb_plugopts['preventminify'] == '1') {
            exclude_from_minify_list();
        }

        /* Short URLs */
        $shrsb_plugopts['shortyapi']['bitly']['user'] = "";
        $shrsb_plugopts['shortyapi']['bitly']['key'] = "";
        $shrsb_plugopts['shortyapi']['jmp']['user'] = "";
        $shrsb_plugopts['shortyapi']['jmp']['key'] = "";
        $shrsb_plugopts['shortyapi']['supr']['chk'] = "0";
        $shrsb_plugopts['shortyapi']['supr']['user'] = "";
        $shrsb_plugopts['shortyapi']['supr']['key'] = "";
        /* Short URLs End */
		
		update_option('SexyBookmarks', $shrsb_plugopts);
        $shrsb_plugopts['tweetconfig'] = urldecode($shrsb_plugopts['tweetconfig']);
		delete_option('SHRSB_CustomSprite');
		
		echo '
		<div id="statmessage" class="shrsb-success">
			<div class="dialog-left fugue f-success">
				'.__('All settings have been reset to their default values.', 'shrsb').'
			</div>
			<div class="dialog-right">
				<img src="'.SHRSB_PLUGPATH.'images/success-delete.jpg" class="del-x" alt=""/>
			</div>
		</div>';
	}

	// create folders for custom mods
	// then copy original files into new folders
	if($_POST['custom-mods'] == 'yes' || $shrsb_plugopts['custom-mods'] == 'yes') {
		if(is_admin() === true && !is_dir(WP_CONTENT_DIR.'/sexy-mods')) {
			$shrsb_oldloc = SHRSB_PLUGDIR;
			$shrsb_newloc = WP_CONTENT_DIR.'/sexy-mods/';

			wp_mkdir_p(WP_CONTENT_DIR.'/sexy-mods');
			wp_mkdir_p(WP_CONTENT_DIR.'/sexy-mods/css');
			wp_mkdir_p(WP_CONTENT_DIR.'/sexy-mods/images');
			wp_mkdir_p(WP_CONTENT_DIR.'/sexy-mods/js');

			copy($shrsb_oldloc.'css/style-dev.css', $shrsb_newloc.'css/style.css');
			copy($shrsb_oldloc.'js/sexy-bookmarks-public.js', $shrsb_newloc.'js/sexy-bookmarks-public.js');
			copy($shrsb_oldloc.'images/shr-sprite.png', $shrsb_newloc.'images/shr-sprite.png');
			copy($shrsb_oldloc.'images/share-enjoy.png', $shrsb_newloc.'images/share-enjoy.png');
			copy($shrsb_oldloc.'images/share-german.png', $shrsb_newloc.'images/share-german.png');
			copy($shrsb_oldloc.'images/share-love-hearts.png', $shrsb_newloc.'images/share-love-hearts.png');
			copy($shrsb_oldloc.'images/share-wealth.png', $shrsb_newloc.'images/share-wealth.png');
			copy($shrsb_oldloc.'images/sharing-caring-hearts.png', $shrsb_newloc.'images/sharing-caring-hearts.png');
			copy($shrsb_oldloc.'images/sharing-caring.png', $shrsb_newloc.'images/sharing-caring.png');
			copy($shrsb_oldloc.'images/sharing-shr.png', $shrsb_newloc.'images/sharing-shr.png');
		}
	}

	// processing form submission
	$status_message = "";
	$error_message = "";
	if(isset($_POST['save_changes'])) {

    if(isset($_POST['bookmark']['shr-fleck'])) {
      unset($_POST['bookmark']['shr-fleck']);
    }
        $_POST['pageorpost'] = shrsb_set_content_type();
		// Set success message
		$status_message = __('Your changes have been saved successfully!', 'shrsb');

		$errmsgmap = array(
			'position'=>__('Please choose where you would like the menu to be displayed.', 'shrsb'),
			'bookmark'=>__("You can't display the menu if you don't choose a few sites to add to it!", 'shrsb'),
			'pageorpost'=>__('Please choose where you want the menu displayed.', 'shrsb'),
		);
		foreach ($errmsgmap as $field=>$msg) {
			if ($_POST[$field] == '') {
				$error_message = $msg;
				break;
			}
		}
		// Twitter friendly Links & YOURLs Plugins: check to see if they have the plugin activated
		if ($_POST['shorty'] == 'tflp' && !function_exists('permalink_to_twitter_link')) {
			$error_message = sprintf(__('You must first download and activate the %sTwitter Friendly Links Plugin%s before hosting your own short URLs...', 'shrsb'), '<a href="http://wordpress.org/extend/plugins/twitter-friendly-links/">', '</a>');
		} elseif ($_POST['shorty'] == 'yourls' && !function_exists('wp_ozh_yourls_raw_url')) {
			$error_message = sprintf(__('You must first download and activate the %sYOURLS Plugin%s before hosting your own short URLs...', 'shrsb'), '<a href="http://wordpress.org/extend/plugins/yourls-wordpress-to-twitter/">', '</a>');
		}
          
          if ( isset($_POST['bookmark']) && is_array($_POST['bookmark']) && sizeof($_POST['bookmark']) > 0 && $shrsb_plugopts['shareaholic-javascript'] == '1') {
            $service_ids = array();
            foreach ( $_POST['bookmark'] as $bm ) {
              if ($this_id = $shrsb_bookmarks_data[$bm]['id']) {
                $service_ids[] = $this_id;
              }
            }
            $shrsb_plugopts['service'] = implode(',', $service_ids);
            shrsb_refresh_cache();
            _shrsb_copy_file(SHRSB_UPLOADDIR.'index.html', SHRSB_PLUGDIR.'spritegen_default/index.html');
            _shrsb_copy_file(SHRSB_UPLOADDIR.'spritegen/index.html', SHRSB_PLUGDIR.'spritegen_default/index.html');

          }

		if (!$error_message) {
			//generate a new sprite, to reduce the size of the image
			if(shrsb_preFlight_Checks()) {
				if ( isset($_POST['bookmark']) && is_array($_POST['bookmark']) and sizeof($_POST['bookmark']) > 0 ) {
					$spritegen_opts = '&service=';
					foreach ( $_POST['bookmark'] as $bm ) {
						$spritegen_opts .= substr($bm, 4) . ',';
					}
					$spritegen_opts = substr($spritegen_opts,0,-1);
					$spritegen_opts .= '&bgimg=' . $_POST['bgimg'] . '&expand=' . $_POST['expand'];
                    $save_return[0] = get_sprite_file($spritegen_opts, 'png');
                    $save_return[1] = get_sprite_file($spritegen_opts, 'css');
				}
                if($save_return[0] == 2 || $save_return[1] == 2) {
					echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.__('WARNING: The request for a custom sprite has timed out. Reverting to default sprite files.', 'shrsb').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
					$shrsb_custom_sprite = '';
					$status_message = __('Changes saved successfully. However, you should try to generate a custom sprite again later.', 'shrsb');
				}
				elseif($save_return[0] == 1 || $save_return[1] == 1) {
					if (!is_writable(SHRSB_UPLOADDIR.'spritegen')) {
						echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: Your %sspritegen folder%s is not writeable by the server! %sNeed Help?%s', 'shrsb'), '<a href="'.SHRSB_UPLOADPATH.'spritegen" target="_blank">','</a>','<a href="http://www.shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
						$shrsb_custom_sprite = '';
						$status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
					}
					elseif(file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png') && is_writable(SHRSB_UPLOADDIR.'spritegen') && !is_writable(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png')) {
						echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: You need to delete the current custom sprite %s before the plugin can write to the folder. %sNeed Help?%s', 'shrsb'), '(<a href="'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png" target="_blank">'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png</a>)','<a href="http://www.shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
						$shrsb_custom_sprite = '';
						$status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
					}
					elseif(file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css') && is_writable(SHRSB_UPLOADDIR.'spritegen') && !is_writable(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css')) {
						echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: You need to delete the current custom stylesheet %s before the plugin can write to the folder. %sNeed Help?%s', 'shrsb'), '(<a href="'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css" target="_blank">'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css</a>)','<a href="http://www.shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
						$shrsb_custom_sprite = '';
						$status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
					}
				}
				else {
					$shrsb_custom_sprite = SHRSB_UPLOADPATH.'spritegen/shr-custom-sprite.css';
				}
			}
			else{
                if (!is_writable(SHRSB_UPLOADDIR.'spritegen')) {
                    echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: Your %sspritegen folder%s is not writeable by the server! %sNeed Help?%s', 'shrsb'), '<a href="'.SHRSB_UPLOADPATH.'spritegen" target="_blank">','</a>','<a href="http://www.shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
                    $status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
                }
                elseif(file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png') && is_writable(SHRSB_UPLOADDIR.'spritegen') && !is_writable(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png')) {
                    echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: You need to delete the current custom sprite %s before the plugin can write to the folder. %sNeed Help?%s', 'shrsb'), '(<a href="'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png" target="_blank">'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png</a>)','<a href="http://www.shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
                    $status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
                }
                elseif(file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css') && is_writable(SHRSB_UPLOADDIR.'spritegen') && !is_writable(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css')) {
                    echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: You need to delete the current custom stylesheet %s before the plugin can write to the folder. %sNeed Help?%s', 'shrsb'), '(<a href="'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css" target="_blank">'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css</a>)','<a href="http://www.shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
                    $status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
                }
            }

        foreach (array(
                'position', 'reloption', 'targetopt', 'bookmark',
                'shorty', 'pageorpost', 'tweetconfig', 'bgimg-yes', 'mobile-hide', 'bgimg',
                'feed', 'expand', 'doNotIncludeJQuery', 'autocenter', 'custom-mods',
                'scriptInFooter', 'shareaholic-javascript', 'shrbase', 'showShareCount',
                'likeButtonSetTop','fbLikeButtonTop','fbSendButtonTop','googlePlusOneButtonTop','likeButtonSetSizeTop','likeButtonSetCountTop',
                'likeButtonOrderTop','likeButtonSetAlignmentTop',
                'likeButtonSetBottom','fbLikeButtonBottom','fbSendButtonBottom','googlePlusOneButtonBottom','likeButtonSetSizeBottom','likeButtonSetCountBottom',
                'likeButtonOrderBottom','likeButtonSetAlignmentBottom',

                'fbNameSpace','designer_toolTips' , 'tip_bg_color',
                'tip_text_color' , 'preventminify', 'shrlink', 'perfoption','spritegen_path', 'apikey','ogtags'
            )as $field) {
                if(isset($_POST[$field])) { // this is to prevent warning if $_POST[$field] is not defined
                    $shrsb_plugopts[$field] = $_POST[$field];
                } else {
                    $shrsb_plugopts[$field] = NULL;
                }
          }
          
          /*
          *   @note WordPress autoescapes (= adds slashes) to all post data. This is a workaround for that.
          */

          $shrsb_plugopts['tweetconfig'] = stripslashes($shrsb_plugopts['tweetconfig']);
          $shrsb_plugopts['spritegen_path'] = shrb_addTrailingChar(stripslashes($shrsb_plugopts['spritegen_path']),'/');

          /* Short URLs */
          //trim also at the same time as at times while copying, some whitespace also gets copied
          //check fields dont need trim function

          $shrsb_plugopts['shortyapi']['bitly']['user'] = trim(htmlspecialchars($_POST['shortyapiuser-bitly'], ENT_QUOTES));
          $shrsb_plugopts['shortyapi']['bitly']['key'] = trim(htmlspecialchars($_POST['shortyapikey-bitly'], ENT_QUOTES));
          $shrsb_plugopts['shortyapi']['jmp']['user'] = trim(htmlspecialchars($_POST['shortyapiuser-jmp'], ENT_QUOTES));
          $shrsb_plugopts['shortyapi']['jmp']['key'] = trim(htmlspecialchars($_POST['shortyapikey-jmp'], ENT_QUOTES));
          $shrsb_plugopts['shortyapi']['supr']['chk'] = htmlspecialchars($_POST['shortyapichk-supr'][0], ENT_QUOTES);
          $shrsb_plugopts['shortyapi']['supr']['user'] = trim(htmlspecialchars($_POST['shortyapiuser-supr'], ENT_QUOTES));
          $shrsb_plugopts['shortyapi']['supr']['key'] = trim(htmlspecialchars($_POST['shortyapikey-supr'], ENT_QUOTES));

          /* Short URLs End */

          $shrsb_plugopts['tweetconfig'] = urlencode($shrsb_plugopts['tweetconfig']);
          if($shrsb_plugopts['preventminify'] == '1') {
                exclude_from_minify_list();
          }

          update_option('SexyBookmarks', $shrsb_plugopts);
          $shrsb_plugopts['tweetconfig'] = urldecode($shrsb_plugopts['tweetconfig']);

          update_option('SHRSB_CustomSprite', $shrsb_custom_sprite);
          update_option('SHRSBvNum', SHRSB_vNum);
      }
  }//Closed Save

	//if there was an error, construct error messages
	if ($error_message != '') {
		echo '
		<div id="errmessage" class="shrsb-error">
			<div class="dialog-left fugue f-error">
				'.$error_message.'
			</div>
			<div class="dialog-right">
				<img src="'.SHRSB_PLUGPATH.'images/error-delete.jpg" class="del-x" alt=""/>
			</div>
		</div>';
	} elseif ($status_message != '') {
		echo '<style type="text/css">#update_sb{display:none !important;}</style>
		<div id="statmessage" class="shrsb-success">
			<div class="dialog-left fugue f-success">
				'.$status_message.'
			</div>
			<div class="dialog-right">
				<img src="'.SHRSB_PLUGPATH.'images/success-delete.jpg" class="del-x" alt=""/>
			</div>
		</div>';
	}
?>

<form name="sexy-bookmarks" id="sexy-bookmarks" action="" method="post">
	<div id="shrsb-col-left">
		<ul id="shrsb-sortables">
            <li>
                <div class="box-mid-head">
					<h2 class="fugue f-status"><?php _e('Plugin Health Status', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle2">
					<div class="padding">
                    <?php
                        $resave_required = shrsb_requires_resave();
                        $chmod_required = shrsb_requires_chmod($shrsb_plugopts['shareaholic-javascript']);
                        $phpupdate_required = shrsb_requires_phpupdate();
                    ?>

                        <table>
                            <tbody>
                                <tr>
                                    <td style="width: 22px;"><img class="shrsb_health_icon" src=
                                        <?php
                                            $color = $chmod_required ? "red":"green";
                                            echo SHRSB_PLUGPATH."images/circle_$color.png";
                                        ?>
                                    ></td>
                                    <td style="min-width: 240px;"><span class=""><?php _e('Directory Permissions', 'shrsb'); ?></span></td>
                                    <td>
                                        <?php
                                            echo $chmod_required ? sprintf(__('To Fix: Please appropriately
                                                                        %sCHMOD%s your /spritegen directory.', 'shrsb'),
                                                                    '<a href="http://www.shareaholic.com/tools/wordpress/usage-installation#chmodinfo"
                                                                        target = "_blank" style="color:#ca0c01">', '</a>') : "";
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="" style="width: 22px;"><img class="shrsb_health_icon" src=
                                        <?php
                                            $color = $resave_required ? "yellow":"green";
                                            echo SHRSB_PLUGPATH."images/circle_$color.png";
                                        ?>
                                    ></td>
                                    <td><span class=""><?php _e('Load Time Optimized', 'shrsb'); ?></span></td>
                                    <td><?php
                                        echo $resave_required ? "To Fix: Simply re-save your SB settings." : "";
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="" style="width: 22px;"><img class="shrsb_health_icon" src=
                                        <?php
                                            $color = $phpupdate_required ? "red":"green";
                                            echo SHRSB_PLUGPATH."images/circle_$color.png";
                                        ?>
                                    ></td>
                                    <td><span class=""><?php _e('Running PHP5+', 'shrsb'); ?></span></td>
                                    <td>
                                        <?php
                                        echo $phpupdate_required ? 'To Fix: Upgrade to PHP 5 or higher.' : "" ;
                                        ?>
                                    </td>
                                </tr>

                            </tbody>
                        </table>



                    </div>
                </div>
            </li>

            <li>
                <div class="box-mid-head">
                    <h2 class="fugue f-status"><?php _e('Shareaholic Social Engagement Analytics', 'shrsb'); ?></h2>
                </div>
				<div class="box-mid-body">
                        <div style="padding:8px;background:#FDF6E5;"><img src="<?php echo SHRSB_PLUGPATH; ?>images/line-chart.png" align="right" alt="New!" />
                                <?php
                                    $parse = parse_url(get_bloginfo('url'));
                                    $share_url = "http://www.shareaholic.com/api/data/".$parse['host']."/sharecount/30";
                                    $top_users_url =  "http://www.shareaholic.com/api/data/".$parse['host']."/topusers/16/";

                                    echo sprintf(__('<b style="font-size:14px;line-height:22px;">Did you know that content from this website has been shared <span style="color:#CC1100;"><span id="bonusShareCount"></span> time(s)</span> in the past <span id="bonusShareTimeFrame"></span> day(s)?</b>', 'shrsb'));
                                ?>

                                <script type ="text/javascript">
                                    (function($){
                                        $(document).ready( function () {
                                            var url = <?php echo "'".$share_url."'";?>;
                                            var top_users_url  = <?php echo "'".$top_users_url."'";?>;
                                            $.getJSON(url+'?callback=?', function (obj) {
                                                $('#bonusShareCount').text(obj.sharecount);
                                                $('#bonusShareTimeFrame').text(obj.timeframe);
                                            });

                                            $.getJSON(top_users_url+'?callback=?', function (obj) {
                                                add_faces(obj);
                                            });
                                        });

                                        var add_faces = function(obj) {
                                            if(obj && obj.length) {
                                                var shuffle = function(v){
                                                    //+ Jonas Raoni Soares Silva
                                                    //@ http://jsfromhell.com/array/shuffle [rev. #1]
                                                    for(var j, x, i = v.length; i; j = parseInt(Math.random() * i), x = v[--i], v[i] = v[j], v[j] = x);
                                                    return v;
                                                };
                                                obj = shuffle(obj);

                                                $('#bonusShareTopUser').show();
                                                var face_ul = $('<ul id="bonusShareFacesUL"/>');
                                                for(var i=0; i<obj.length; ++i) {
                                                    var shr_profile_url = "http://www.shareaholic.com/" + obj[i].username;
                                                    face_ul.append(
                                                        $("<li class='bonusShareLi'>").append("<a target='_blank' href="+shr_profile_url+"><img class='bonusShareFaces' title=" + obj[i].username + " src=" + obj[i].picture_url + "></img></a>")
                                                    );
                                                }

                                                $('#bonusShareTopUser').append(face_ul);

                                            }
                                        };
                                    })(jQuery);
                                </script>
                                <br/><br/>
                                <div id="bonusShareTopUser" style="display:none"><b><?php _e('Meet the people who spread your content the most:', 'shrsb'); ?></b></div>

                                <br />
                                <div style="background: url(http://www.shareaholic.com/media/images/border_hr.png) repeat-x scroll left top; height: 2px;"></div>
                                <br />
                                  <?php  echo sprintf(__('What are you waiting for? <b>Access detailed %ssocial engagement analytics%s about your website for FREE right now!</b><br><br>You have been selected to preview the upcoming premium analytics add-on for SexyBookmarks for FREE for a limited time - so hurry before it is too late! These analytics are designed to help you grow your traffic and referrals.', 'shrsb'), '<a href="http://www.shareaholic.com/siteinfo/'.$parse['host'].'">', '</a>');
                                ?>

                        </div>
                </div>
            </li>

			<li>
				<div class="box-mid-head" id="iconator">
					<h2 class="fugue f-globe-plus"><?php _e('Enabled Networks', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body iconator" id="toggle1">
					<div class="padding">
						<p><?php _e('Select the Networks to display. Drag to reorder.', 'shrsb'); ?></p>
						<ul class="multi-selection">
							<li><?php _e('Select', 'shrsb'); ?>:&nbsp;</li>
							<li><a id="sel-all" href="javascript:void(0);"><?php _e('All', 'shrsb'); ?></a>&nbsp;|&nbsp;</li>
							<li><a id="sel-none" href="javascript:void(0);"><?php _e('None', 'shrsb'); ?></a>&nbsp;|&nbsp;</li>
							<li><a id="sel-pop" href="javascript:void(0);"><?php _e('Most Popular', 'shrsb'); ?></a>&nbsp;</li>
		        </ul>
						<div id="shrsb-networks"><ul>
							<?php
								foreach ($shrsb_plugopts['bookmark'] as $name){if(array_key_exists($name, $shrsb_bookmarks_data)) {print shrsb_network_input_select($name, $shrsb_bookmarks_data[$name]['check']);}}
								$unused_networks=array_diff(array_keys($shrsb_bookmarks_data), $shrsb_plugopts['bookmark']);
								foreach ($unused_networks as $name) print shrsb_network_input_select($name, $shrsb_bookmarks_data[$name]['check']);
							?>
						</ul></div>
					</div>
					<div style="padding:10px; float:right;color:#999999;"><?php _e('Made with Much Love, these Icons are © Shareaholic', 'shrsb'); ?></div>
				</div>
			</li>

            <li>
				<div class="box-mid-head">
					<h2 class="fugue f-globe-plus"><?php _e('Additional Buttons', 'shrsb'); ?></h2>
				</div>
                <div class="box-mid-body" id="toggle2">
					<div class="padding">
						<div id="genopts">

                                    <table><tbody>
                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Include the Open Graph Tags?', 'shrsb'); ?> <span style="color:red;">*</span></span>
                                        </td>
                                        <td style="width:125px"><label><input <?php echo (($shrsb_plugopts['ogtags'] == "1")? 'checked="checked"' : ""); ?> name="ogtags" id="ogtags-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['ogtags'] == "0")? 'checked="checked"' : ""); ?> name="ogtags" id="ogtags-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Include the like button-set just above the post?', 'shrsb'); ?> <span style="color:red;">*</span></span>
                                        </td>
                                        <td style="width:125px"><label><input <?php echo (($shrsb_plugopts['likeButtonSetTop'] == "1")? 'checked="checked"' : ""); ?> name="likeButtonSetTop" id="likeButtonSetTop-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['likeButtonSetTop'] == "0")? 'checked="checked"' : ""); ?> name="likeButtonSetTop" id="likeButtonSetTop-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>
                                    </tbody></table>
                                    <?php
                                        shrsb_likeButtonSetHTML('Top');
                                    ?>

                                    <table><tbody>

                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Include the like button-set below the post?', 'shrsb'); ?> <span style="color:red;">*</span></span>
                                        </td>
                                        <td style="width:125px"><label><input <?php echo (($shrsb_plugopts['likeButtonSetBottom'] == "1")? 'checked="checked"' : ""); ?> name="likeButtonSetBottom" id="likeButtonSetBottom-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['likeButtonSetBottom'] == "0")? 'checked="checked"' : ""); ?> name="likeButtonSetBottom" id="likeButtonSetBottom-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>
                                    <?php
                                        shrsb_likeButtonSetHTML('Bottom');
                                    ?>

                                    </tbody></table>





                                <br />

                                <span style="display:block;"><?php echo sprintf(__('Check out %sour blog%s for additional customization options.', 'shrsb'), '<a target="_blank" href="http://blog.shareaholic.com/?p=1917">', '</a>'); ?></span><br />
    							<span style="display:block;"><span style="color:red;">* <?php _e('switch on "new" mode below to enable these exclusive features', 'shrsb'); ?></span></span>

                        </div>
                    </div>
                </div>

            </li>

			<li>
				<div class="box-mid-head">
					<h2 class="fugue f-wrench"><?php _e('Functionality Settings', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle2">
					<div class="padding">
						<div id="genopts">
                            <table><tbody>
                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Show Share Counters', 'shrsb'); ?> <span style="color:red;">*</span></span>
                                            <span style="display:block;"><?php _e('For Facebook, Twitter, Google Buzz and Delicious', 'shrsb'); ?></span>
                                        </td>
                                            <td><label><input <?php echo (($shrsb_plugopts['showShareCount'] == "1")? 'checked="checked"' : ""); ?> name="showShareCount" id="showShareCount-yes" type="radio" value="1" /> <?php _e('Yes (recommended)', 'shrsb'); ?></label>
                                    </td><td><label><input <?php echo (($shrsb_plugopts['showShareCount'] == "0")? 'checked="checked"' : ""); ?> name="showShareCount" id="showShareCount-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Use Designer Tooltips', 'shrsb'); ?> <span style="color:red;">*</span></span></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['designer_toolTips'] == "1")? 'checked="checked"' : ""); ?> name="designer_toolTips" id="designer_toolTips-yes" type="radio" value="1" /> <?php _e('Yes (recommended)', 'shrsb'); ?></label></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['designer_toolTips'] == "0")? 'checked="checked"' : ""); ?> name="designer_toolTips" id="designer_toolTips-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label></td>
						            </tr>

                                    <tr class="designer_toolTip_prefs" style="display:none">
                                                <td><label class="tab" for="tip_bg_color" style="margin-top:7px;"><?php _e('Background Color for Tooltips:', 'shrsb'); ?></label></td>
                                                <td><input style="margin-top:7px;" type="text" id="tip_bg_color" name="tip_bg_color" value="<?php echo $shrsb_plugopts['tip_bg_color']; ?>" /></td>
                                                <td><div id="tip_bg_color_picker" class ="color_selector">
                                                    <div style="background-color:<?php echo $shrsb_plugopts['tip_bg_color']; ?>; "></div>
                                                </div>
                                                </td>
                                                <td><div id="tip_bg_color_picker_holder" style="display:none; margin-top: 5px; position: absolute;" ></div></td>
                                                <td> <div id="tip_bg_color_reset" style="margin-left: 5px;"><a href="javascript:void(0);"><?php _e('reset', 'shrsb'); ?></a></div></td>
                                    </tr>
                                    <tr class="designer_toolTip_prefs" style="display:none">
                                        <td><label class="tab" style="margin-top:7px;" for="tip_text_color"><?php _e('Text Color for Tooltips:', 'shrsb'); ?></label></td>
                                        <td><input style="margin-top:7px;" type="text" id="tip_text_color" name="tip_text_color" value="<?php echo $shrsb_plugopts['tip_text_color']; ?>" /></td>
                                        <td><div id="tip_text_color_picker" class ="color_selector">
                                            <div style="background-color: <?php echo $shrsb_plugopts['tip_text_color']; ?>; "></div>
                                        </div>
                                        </td>
                                        <td><div id="tip_text_color_picker_holder" style="display:none; margin-top: 5px; position: absolute;"></div></td>
                                        <td> <div id="tip_text_color_reset" style="margin-left: 5px;"><a href="javascript:void(0);"><?php _e('reset', 'shrsb'); ?></a></div></td>
                                    </tr>

                                    <tr>
                                            <td><span class="shrsb_option"><?php _e('Track Performance', 'shrsb'); ?></span></td>
                                            <td><label><input <?php echo (($shrsb_plugopts['perfoption'] == "1")? 'checked="checked"' : ""); ?> name="perfoption" id="perfoption-yes" type="radio" value="1" /> <?php _e('Yes (recommended)', 'shrsb'); ?></label>
                                            </td><td><label><input <?php echo (($shrsb_plugopts['perfoption'] == "0")? 'checked="checked"' : ""); ?> name="perfoption" id="perfoption-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                            </td>
                                    </tr>

                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Add Nofollow to Links', 'shrsb'); ?></span></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['reloption'] == "nofollow")? 'checked="checked"' : ""); ?> name="reloption" id="reloption-yes" type="radio" value="nofollow" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['reloption'] == "")? 'checked="checked"' : ""); ?> name="reloption" id="reloption-no" type="radio" value="" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Open Links in New Window', 'shrsb'); ?></span></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['targetopt'] == "_blank")? 'checked="checked"' : ""); ?> name="targetopt" id="targetopt-blank" type="radio" value="_blank" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['targetopt'] == "_self")? 'checked="checked"' : ""); ?> name="targetopt" id="targetopt-self" type="radio" value="_self" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <span class="shrsb_option"><?php _e('Show Shareaholic Link', 'shrsb'); ?></span>
                                        </td>
                                        <td><label><input <?php echo (($shrsb_plugopts['shrlink'] == "1" || $shrsb_plugopts['shrlink'] == '')? 'checked="checked"' : ""); ?> name="shrlink" id="shrlink-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['shrlink'] == "0")? 'checked="checked"' : ""); ?> name="shrlink" id="shrlink-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>

                            </tbody></table>
							<br />
							<span style="display:block;"><span style="color:red;">* <?php _e('switch on "new" mode below to enable these exclusive features', 'shrsb'); ?></span></span>

                        </div>
					</div>
				</div>
			</li>

			<li>
                <div class="box-mid-head">
                    <h2 class="fugue f-status"><?php _e('Shareaholic for Publishers [BETA]', 'shrsb'); ?></h2>
                </div>
				<div class="box-mid-body">
                      <div class="padding">
                            <p>
            <?php _e('Switch on "new mode" to enable exclusive advanced features:') ?>
							<span class="shrsb_option"><?php _e('Use new version?', 'shrsb'); ?></span>
							<label><input <?php echo (($shrsb_plugopts['shareaholic-javascript'] == "1")? 'checked="checked"' : ""); ?> name="shareaholic-javascript" id="shareaholic-javascript-1" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
							<label><input <?php echo (($shrsb_plugopts['shareaholic-javascript'] != "1")? 'checked="checked"' : ""); ?> name="shareaholic-javascript" id="shareaholic-javascript-0" type="radio" value="" /> <?php _e('No', 'shrsb'); ?></label>
							<br><em><?php _e('You can switch back at any time.', 'shrsb'); ?></em>
                            <input type="hidden" name="shrbase" value="<?php echo $shrsb_plugopts['shrbase'] ?>"/>
                            <input type="hidden" name="apikey" value="<?php echo $shrsb_plugopts['apikey']?$shrsb_plugopts['apikey']:'8afa39428933be41f8afdb8ea21a495c' ?>"/>
                            </p>
                </div>
            </li>

			<li id="twitter-defaults" <?php if(!in_array('shr-twitter', $shrsb_plugopts['bookmark'])) { ?> class="hide"<?php } ?>>
				<div class="box-mid-head" id="iconator">
					<h2><img src="<?php echo SHRSB_PLUGPATH; ?>images/twitter-16x16.png" alt="Twitter!" align="absmiddle"  style="margin-right: 8px;" /><?php _e('Twitter Options', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle6">
					<div class="padding">

					    <p id="tweetinstructions">
                            <strong><?php _e('Configuration Instructions:', 'shrsb'); ?></strong><br />
                            <?php echo sprintf(__('Using the strings %s and %s you can fully customize your tweet output.', 'shrsb'), '<strong>${title}</strong>', '<strong>${short_link}</strong>'); ?><br /><br />
                            <strong><?php _e('Example Configurations:', 'shrsb'); ?></strong><br />
                            <em>${title} - ${short_link} (via @Shareaholic)</em><br />
                            <?php _e('or', 'shrsb'); ?><br />
                            <em>RT @Shareaholic: ${title} - ${short_link}</em>
                          </p>
                          <div style="position:relative;width:80%;">
                            <label for="tweetconfig"><?php _e('Configure Custom Tweet Template:', 'shrsb'); ?></label><small id="tweetcounter"><?php _e('Characters:', 'shrsb'); ?> <span></span></small><br />
                            <textarea id="tweetconfig" name="tweetconfig"><?php if(!empty($shrsb_plugopts['tweetconfig'])) { echo $shrsb_plugopts['tweetconfig']; } else { echo '${title} - ${short_link} via @Shareaholic'; } ?></textarea>
                          </div>
                          <p id="tweetoutput"><strong><?php _e('Example Tweet Output:', 'shrsb'); ?></strong><br /><span></span></p>

				        <label for="shorty"><?php _e('Which URL Shortener?', 'shrsb'); ?></label><br />
						<select name="shorty" id="shorty">
							<?php
								// output shorty select options
								print shrsb_select_option_group('shorty', array(
									'none'      =>__("Don't use a shortener", 'shrsb'),
									'bitly'     =>  'bit.ly',
									'jmp'       =>  'j.mp',
									'google'    =>  'Google (goo.gl)',
									'supr'      =>  'StumbleUpon (su.pr)',
									'tinyurl'   =>  'tinyurl',
									'tflp'      =>  'Twitter Friendly Links WP Plugin',
									'yourls'    =>  'YOURLS WP Plugin'
								));
							?>

						</select>
						<div id="shortyapimdiv-bitly"<?php if($shrsb_plugopts['shorty'] != "bitly") { ?> class="hidden"<?php } ?>>
							<div id="shortyapidiv-bitly">
								<label for="shortyapiuser-bitly"><?php _e('User ID:', 'shrsb'); ?></label>
								<input type="text" id="shortyapiuser-bitly" name="shortyapiuser-bitly" value="<?php echo $shrsb_plugopts['shortyapi']['bitly']['user']; ?>" />
								<label for="shortyapikey-bitly"><?php _e('API Key:', 'shrsb'); ?></label>
								<input type="text" id="shortyapikey-bitly" name="shortyapikey-bitly" value="<?php echo $shrsb_plugopts['shortyapi']['bitly']['key']; ?>" />
							</div>
						</div>

                        <div id="shortyapimdiv-jmp"<?php if($shrsb_plugopts['shorty'] != "jmp") { ?> class="hidden"<?php } ?>>
							<div id="shortyapidiv-jmp">
								<label for="shortyapiuser-jmp"><?php _e('User ID:', 'shrsb'); ?></label>
								<input type="text" id="shortyapiuser-jmp" name="shortyapiuser-jmp" value="<?php echo $shrsb_plugopts['shortyapi']['jmp']['user']; ?>" />
								<label for="shortyapikey-jmp"><?php _e('API Key:', 'shrsb'); ?></label>
								<input type="text" id="shortyapikey-jmp" name="shortyapikey-jmp" value="<?php echo $shrsb_plugopts['shortyapi']['jmp']['key']; ?>" />
							</div>
						</div>

						<div id="shortyapimdiv-supr" <?php if($shrsb_plugopts['shorty'] != 'supr') { ?>class="hidden"<?php } ?>>
							<span class="shrsb_option" id="shortyapidivchk-supr">
								<input <?php echo (($shrsb_plugopts['shortyapi']['supr']['chk'] == "1")? 'checked="true"' : ""); ?> name="shortyapichk-supr[]" id="shortyapichk-supr" type="checkbox" value="1" /> <?php _e('Track Generated Links?', 'shrsb'); ?>
                                <input type="hidden" name="shortyapichk-supr[]" type="checkbox" value="0"/>
							</span>
							<div class="clearbig"></div>
							<div id="shortyapidiv-supr" <?php if(!isset($shrsb_plugopts['shortyapi']['supr']['chk'])) { ?>class="hidden"<?php } ?>>
								<label for="shortyapiuser-supr"><?php _e('User ID:', 'shrsb'); ?></label>
								<input type="text" id="shortyapiuser-supr" name="shortyapiuser-supr" value="<?php echo $shrsb_plugopts['shortyapi']['supr']['user']; ?>" />
								<label for="shortyapikey-supr"><?php _e('API Key:', 'shrsb'); ?></label>
								<input type="text" id="shortyapikey-supr" name="shortyapikey-supr" value="<?php echo $shrsb_plugopts['shortyapi']['supr']['key']; ?>" />
							</div>
						</div>
						<div class="clearbig"></div>

					</div>
				</div>
			</li>

			<li>
				<div class="box-mid-head">
					<h2 class="fugue f-pallette"><?php _e('Plugin Aesthetics', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle3">
					<div class="padding">
						<div id="custom-mods-notice">
							<h1><?php _e('Warning!', 'shrsb'); ?></h1>
              <p><?php echo sprintf(__('This option is intended %STRICTLY%s for users who understand how to edit CSS/JS and intend to change/edit the associated images themselves. Unfortunately, no support will be offered for this feature, as I cannot be held accountable for your coding and/or image editing mistakes.', 'shrsb'), '<strong>', '</strong>'); ?></p>
							<h3><?php _e('How it works...', 'shrsb'); ?></h3>
							<p><?php _e('Since you have chosen for the plugin to override the style settings with your own custom mods, it will now pull the files from the new folders it is going to create on your server as soon as you save your changes. The file/folder locations should be as follows:', 'shrsb'); ?></p>
							<ul>
								<li class="custom-mods-folder"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods'; ?></a></li>
								<li class="custom-mods-folder"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/css'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/css'; ?></a></li>
								<li class="custom-mods-folder"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/js'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/js'; ?></a></li>
								<li class="custom-mods-folder"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images'; ?></a></li>
								<li class="custom-mods-code"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/js/sexy-bookmarks-public.js'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/js/sexy-bookmarks-public.js'; ?></a></li>
								<li class="custom-mods-code"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/css/style.css'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/css/style.css'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/shr-sprite.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/shr-sprite.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/share-enjoy.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/share-enjoy.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/share-german.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/share-german.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/share-love-hearts.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/share-love-hearts.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/share-wealth.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/share-wealth.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-caring-hearts.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-caring-hearts.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-caring.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-caring.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-shr.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-shr.png'; ?></a></li>
							</ul>
							<p><?php _e('Once you have saved your changes, you will be able to edit the image sprite that holds all of the icons for Shareaholic as well as the CSS which accompanies it. Just be sure that you do in fact edit the CSS if you edit the images, as it is unlikely the heights, widths, and background positions of the images will stay the same after you are done.', 'shrsb'); ?></p>
							<p><?php _e('Just a quick note... When you edit the styles and images to include your own custom backgrounds, icons, and CSS styles, be aware that those changes will not be reflected on the plugin options page. In other words: when you select your networks to be displayed, or when you select the background image to use, it will still be displaying the images from the original plugin directory.', 'shrsb'); ?></p>
							<h3><?php _e('In Case of Emergency', 'shrsb'); ?></h3>
							<p><?php _e('If you happen to mess things up, you can follow these directions to reset the plugin back to normal and try again if you wish:', 'shrsb'); ?></p>
							<ol>
								<li><?php _e('Login to your server via FTP or SSH. (whichever you are more comfortable with)', 'shrsb'); ?></li>
								<li><?php _e('Navigate to your wp-content directory.', 'shrsb'); ?></li>
								<li><?php _e('Delete the directory named "sexy-mods".', 'shrsb'); ?></li>
								<li><?php _e('Login to your WordPress dashboard.', 'shrsb'); ?></li>
								<li><?php _e('Go to the SexyBookmarks plugin options page. (Settings->SexyBookmarks)', 'shrsb'); ?></li>
								<li><?php _e('Deselect the "Use custom mods" option.', 'shrsb'); ?></li>
								<li><?php _e('Save your changes.', 'shrsb'); ?></li>
							</ol>
							<span class="fugue f-delete custom-mods-notice-close"><?php _e('Close Message', 'shrsb'); ?></span>
						</div>
						<div class="custom-mod-check fugue f-plugin">
							<label for="custom-mods" class="shrsb_option" style="display:inline;">
								<?php _e('Override Styles With Custom Mods Instead?', 'shrsb'); ?>
							</label>
							<input <?php echo (($shrsb_plugopts['custom-mods'] == "yes")? 'checked' : ""); ?> name="custom-mods" id="custom-mods" type="checkbox" value="yes" />
						</div>

						<span class="shrsb_option"><?php _e('Animate-expand multi-lined bookmarks?', 'shrsb'); ?></span>
						<label><input <?php echo (($shrsb_plugopts['expand'] == "1")? 'checked="checked"' : ""); ?> name="expand" id="expand-yes" type="radio" value="1" /><?php _e('Yes', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['expand'] != "1")? 'checked="checked"' : ""); ?> name="expand" id="expand-no" type="radio" value="0" /><?php _e('No', 'shrsb'); ?></label>
						<span class="shrsb_option"><?php _e('Auto-space/center the bookmarks?', 'shrsb'); ?></span>
						<label><input <?php echo (($shrsb_plugopts['autocenter'] == "2")? 'checked="checked"' : ""); ?> name="autocenter" id="autospace-yes" type="radio" value="2" /><?php _e('Space', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['autocenter'] == "1")? 'checked="checked"' : ""); ?> name="autocenter" id="autocenter-yes" type="radio" value="1" /><?php _e('Center', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['autocenter'] == "0")? 'checked="checked"' : ""); ?> name="autocenter" id="autocenter-no" type="radio" value="0" /><?php _e('No', 'shrsb'); ?></label>

						<span class="shrsb_option">
							<?php _e('Use a background image?', 'shrsb'); ?> <input <?php echo (($shrsb_plugopts['bgimg-yes'] == "yes")? 'checked' : ""); ?> name="bgimg-yes" id="bgimg-yes" type="checkbox" value="yes" />
						</span>
						<div id="bgimgs" class="<?php if(!isset($shrsb_plugopts['bgimg-yes'])) { ?>hidden<?php } else { echo ''; }?>">
							<label class="share-sexy">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "shr")? 'checked="checked"' : ""); ?> id="bgimg-sexy" name="bgimg" type="radio" value="shr" />
							</label>
							<label class="share-care">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "caring")? 'checked="checked"' : ""); ?> id="bgimg-caring" name="bgimg" type="radio" value="caring" />
							</label>
							<label class="share-care-old">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "care-old")? 'checked="checked"' : ""); ?> id="bgimg-care-old" name="bgimg" type="radio" value="care-old" />
							</label>
							<label class="share-love">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "love")? 'checked="checked"' : ""); ?> id="bgimg-love" name="bgimg" type="radio" value="love" />
							</label>
							<label class="share-wealth">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "wealth")? 'checked="checked"' : ""); ?> id="bgimg-wealth" name="bgimg" type="radio" value="wealth" />
							</label>
							<label class="share-enjoy">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "enjoy")? 'checked="checked"' : ""); ?> id="bgimg-enjoy" name="bgimg" type="radio" value="enjoy" />
							</label>
							<label class="share-german">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "german")? 'checked="checked"' : ""); ?> id="bgimg-german" name="bgimg" type="radio" value="german" />
							</label>
							<label class="share-knowledge">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "knowledge")? 'checked="checked"' : ""); ?> id="bgimg-knowledge" name="bgimg" type="radio" value="knowledge" />
							</label>
						</div>
					</div>
				</div>
			</li>

            <li>
                <div class="box-mid-head">
					<h2 class="fugue f-wrench"><?php _e('Compatibility Settings', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle2">
					<div class="padding">

                        <?php if (class_exists('WPMinify')) { ?>
                        <span class="shrsb_option"><?php _e('WP-Minify Compatibility Mode', 'shrsb'); ?></span>
							<label><input <?php echo (($shrsb_plugopts['preventminify'] == "1")? 'checked="checked"' : ""); ?> name="preventminify" id="preventminify-yes" type="radio" value="1" /> <?php _e('Enabled (recommended)', 'shrsb'); ?></label>
							<label><input <?php echo (($shrsb_plugopts['preventminify'] == "0")? 'checked="checked"' : ""); ?> name="preventminify" id="preventminify-no" type="radio" value="0" /> <?php _e('Disabled', 'shrsb'); ?></label>
							<span style="display:block;"><?php _e('(SexyBookmarks may not work with this option turned off)', 'shrsb'); ?></span>
                         <?php } ?>
                            <span class="shrsb_option"><?php _e('jQuery Compatibility Fix', 'shrsb'); ?></span>
						<label for="doNotIncludeJQuery"><?php _e("Check this box ONLY if you notice jQuery being loaded twice in your source code!", "shrsb"); ?></label>
						<input type="checkbox" id="doNotIncludeJQuery" name="doNotIncludeJQuery" <?php echo (($shrsb_plugopts['doNotIncludeJQuery'] == "1")? 'checked' : ""); ?> value="1" />
						<span class="shrsb_option"><?php _e('Load scripts in Footer', 'shrsb'); ?> <input type="checkbox" id="scriptInFooter" name="scriptInFooter" <?php echo (($shrsb_plugopts['scriptInFooter'] == "1")? 'checked' : ""); ?> value="1" /></span>
						<label for="scriptInFooter"><?php _e("Check this box if you want the SexyBookmarks javascript to be loaded in your blog's footer.", 'shrsb'); ?> (<a href="http://developer.yahoo.com/performance/rules.html#js_bottom" target="_blank">?</a>)</label>

                        <span class="shrsb_option"><?php _e('Add Facebook required namespaces to your HTML tag? (recommended)', 'shrsb'); ?> <input type="checkbox" id="fbNameSpace" name="fbNameSpace" <?php echo (($shrsb_plugopts['fbNameSpace'] == "1")? 'checked' : ""); ?> value="1" /></span>
						<label for="fbNameSpace"><?php _e("Check this box if you include Facebook's Like/Send buttons. These buttons may not work with this option turned off.", 'shrsb'); ?></label>

                        <span class="shrsb_option"><?php _e('Custom Path to Shareaholic Resources', 'shrsb'); ?></span>
                        <label for="spritegen_path"><?php _e("Set Custom Path:", "shrsb"); ?>
                            <input style="margin-top:7px; width: 500px" type="text" id="spritegen_path" name="spritegen_path"  value="<?php echo shrb_addTrailingChar(stripslashes($shrsb_plugopts['spritegen_path']), '/'); ?>" /></label>
                        <p><?php _e("Default Path: ", "shrsb"); echo SHRSB_UPLOADDIR_DEFAULT; ?> </p>
                    </div>
                </div>
            </li>

			<li>
				<div class="box-mid-head">
					<h2 class="fugue f-footer"><?php _e('Menu Placement', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle5">
					<div class="padding">
						<div class="dialog-box-information" id="info-manual">
							<div class="dialog-left fugue f-info">
								<?php echo sprintf(__('Need help with this? Find it in the %sofficial install guide%s.', 'shrsb'), '<a href="http://www.shareaholic.com/tools/wordpress/usage-installation">', '</a>'); ?></a>
							</div>
							<div class="dialog-right">
								<img src="<?php echo SHRSB_PLUGPATH; ?>images/information-delete.jpg" class="del-x" alt=""/>
							</div>
						</div>
						<span class="shrsb_option"><?php _e('Menu Location (in relation to content):', 'shrsb'); ?></span>
						<label><input <?php echo (($shrsb_plugopts['position'] == "above")? 'checked="checked"' : ""); ?> name="position" id="position-above" type="radio" value="above" /> <?php _e('Above Content', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['position'] == "below")? 'checked="checked"' : ""); ?> name="position" id="position-below" type="radio" value="below" /> <?php _e('Below Content', 'shrsb'); ?></label>
            <label><input <?php echo (($shrsb_plugopts['position'] == "both")? 'checked="checked"' : ""); ?> name="position" id="position-both" type="radio" value="both" /> <?php _e('Above & Below Content', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['position'] == "manual")? 'checked="checked"' : ""); ?> name="position" id="position-manual" type="radio" value="manual" /> <?php _e('Manual Mode', 'shrsb'); ?></label>
						
                        <span class="shrsb_option"><?php _e('Posts, pages,categories or the whole shebang?', 'shrsb'); ?></span>
                        <input type="checkbox" id="type_post" name="content_type[]"  value="post" <?php echo (false!==strpos($shrsb_plugopts['pageorpost'],"post"))? 'checked' : ""; ?>/><label for="type_post" class="padding"><?php _e('posts', 'shrsb'); ?></label><br>
                        <input type="checkbox" id="type_page" name="content_type[]"  value="page" <?php echo (false!==strpos($shrsb_plugopts['pageorpost'],"page"))? 'checked' : ""; ?>/><label for="type_page" class="padding"><?php _e('pages', 'shrsb'); ?></label><br>
                        <input type="checkbox" id="type_index" name="content_type[]"  value="index" <?php echo (false!==strpos($shrsb_plugopts['pageorpost'],"index"))? 'checked' : ""; ?>/><label for="type_index"  class="padding"><?php _e('main index', 'shrsb'); ?></label><br>
                        <input type="checkbox" id="type_category" name="content_type[]"  value="category" <?php echo (false!==strpos($shrsb_plugopts['pageorpost'],"category"))? 'checked' : ""; ?>/><label for="type_category" class="padding"><?php _e('category index', 'shrsb'); ?></label><br>
                       
                        
                        <span class="shebang-info fugue f-question" title="<?php _e('Click here for help with this option', 'shrsb'); ?>"> </span>
						<span class="shrsb_option"><?php _e('Show in RSS feed?', 'shrsb'); ?></span>
						<label><input <?php echo (($shrsb_plugopts['feed'] == "1")? 'checked="checked"' : ""); ?> name="feed" id="feed-show" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['feed'] == "0" || empty($shrsb_plugopts['feed']))? 'checked="checked"' : ""); ?> name="feed" id="feed-hide" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
						<label class="shrsb_option" style="margin-top:12px;">
							<?php _e('Hide menu from mobile browsers?', 'shrsb'); ?> <input <?php echo (($shrsb_plugopts['mobile-hide'] == "yes")? 'checked' : ""); ?> name="mobile-hide" id="mobile-hide" type="checkbox" value="yes" />
						</label>
						<br />
					</div>
				</div>
			</li>
		</ul>
		<div style="clear:both;"></div>
		<input type="hidden" name="save_changes" value="1" />
        <div class="shrsbsubmit"><input type="submit" id="save_changes" value="<?php _e('Save Changes', 'shrsb'); ?>" /></div>
	</form>
	<form action="" method="post">
		<input type="hidden" name="reset_all_options" id="reset_all_options" value="0" />
		<div class="shrsbreset"><input type="submit" value="<?php _e('Reset Settings', 'shrsb'); ?>" /></div>
	</form>
</div>
<div id="shrsb-col-right">

    <h2 class="sh-logo"></h2>

	<div class="box-right">
		<div class="box-right-head">
			<h3 class="fugue f-info-frame"><?php _e('Helpful Plugin Links', 'shrsb'); ?></h3>
		</div>
		<div class="box-right-body">
			<div class="padding">
				<ul class="infolinks">
					<li><a href="http://www.shareaholic.com/tools/wordpress/usage-installation" target="_blank"><?php _e('Installation &amp; Usage Guide', 'shrsb'); ?></a></li>
					<li><a href="http://www.shareaholic.com/tools/wordpress/faq" target="_blank"><?php _e('Frequently Asked Questions', 'shrsb'); ?></a></li>
					<li><a href="http://sexybookmarks.shareaholic.com/contact-forms/bug-form" target="_blank"><?php _e('Bug Submission Form', 'shrsb'); ?></a></li>
					<li><a href="http://sexybookmarks.shareaholic.com/contact-forms/feature-request" target="_blank"><?php _e('Feature Request Form', 'shrsb'); ?></a></li>
					<li><a href="http://www.shareaholic.com/tools/wordpress/translations" target="_blank"><?php _e('Submit a Translation', 'shrsb'); ?></a></li>
					<li><a href="http://www.shareaholic.com/tools/browser/" target="_blank"><?php _e('Shareaholic Browsers Add-ons', 'shrsb'); ?></a></li>
					<li><a href="http://www.shareaholic.com/tools/wordpress/credits" target="_blank"><?php _e('Thanks &amp; Credits', 'shrsb'); ?></a></li>
				</ul>
			</div>
		</div>
	</div>

	<div style="padding:15px;"><iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FShareaholic&amp;layout=standard&amp;show_faces=true&amp;width=240&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:240px; height:80px;" allowTransparency="true"></iframe>
	</div>

</div>
<?php

echo get_snapengage();

}//closing brace for function "shrsb_settings_page"


if(strnatcmp(phpversion(),'5.0') < 0) {
    //add_action('admin_notices', 'php_version_uncompatible', 12);
    // Since we have the health status we dont need php upgrade notice
} else {
    // shareaholic professional features authentication class
    require_once 'includes/shr_pub_pro.php';
}

function php_version_uncompatible() {
    //Show message to only Admins
    if (shrsb_get_current_user_role()=="Administrator"){

    echo '<div id="update_sb" style="border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;background:#feb1b1;border:1px solid #fe9090;color:#820101;font-size:10px;font-weight:bold;height:auto;margin:35px 15px 0 0;overflow:hidden;padding:4px 10px 6px;">
        <div style="background:url('.SHRSB_PLUGPATH.'images/custom-fugue-sprite.png) no-repeat 0 -525px;margin:2px 10px 0 0;float:left;line-height:18px;padding-left:22px;">
          '.sprintf(__('NOTICE: We have noticed that you are using an old version of PHP. It is highly recommended that you upgrade to PHP 5 or higher to avail certain advanced Shareaholic features.  Also, if you do not upgrade to PHP 5 you will not be able to run WordPress 3.2+', 'shrsb'), '<a href="admin.php?page=sexy-bookmarks.php" style="color:#ca0c01">', '</a>').'
        </div>
      </div>';
  }
}

require_once 'includes/shrsb_authentication_page.php';
require_once 'includes/shrsb_analytics_page.php';


function shrsb_requires_resave() {
        global $shrsb_plugopts,$default_spritegen;
        $resave_required = false;
        if(($shrsb_plugopts['shareaholic-javascript'] == '1'  //new mode
                    && $default_spritegen)
                || ($shrsb_plugopts['shareaholic-javascript'] != '1'      //old mode
                    && !(file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png')
                            && file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css')
                        )
                    )
        ){
            $resave_required = true;
        }

        return $resave_required;
}
/*
*   @desc Check for chmod for new-custom and old-custom mode only
*/
function shrsb_requires_chmod($mode = NULL) {
    return !(is_writable(SHRSB_UPLOADDIR.'spritegen'));
}

function shrsb_requires_phpupdate() {
    return (strnatcmp(phpversion(),'5.0') < 0);
}

function shrsb_account_page() {
    global $shrsb_plugopts;
    $apikey = $_POST['apikey'] ? $_POST['apikey'] : $shrsb_plugopts['apikey'] ;
    $bAuth = shrsb_authenticate_user($apikey);
    shrsb_authentication_page($bAuth ? $apikey : null);
}

function shrsb_authenticate_user($api_key = null) {
    $shr_pub_class = SHR_PUB_PRO::getInstance();
    $auth = $shr_pub_class->set_api_key($api_key);
    return $auth;
}

//add sidebar link to settings page
add_action('admin_menu', 'shrsb_menu_link');

/*
*   @desc Show Shareaholic Menu on WP Admin Dashboard
*/
function shrsb_menu_link() {
	if (function_exists('add_menu_page')) {
		$shrsb_admin_page = add_menu_page( __( 'Shareaholic for Publishers', 'shrsb' ), __( 'Shareaholic', 'shrsb' ),
		    'administrator', basename(__FILE__), 'shrsb_settings_page', SHRSB_PLUGPATH.'images/shareaholic_16x16.png');

		add_submenu_page( basename(__FILE__), __( 'SexyBookmarks' ), __( 'SexyBookmarks', 'shrsb' ),
    		'administrator', basename(__FILE__), 'shrsb_settings_page' );

    	/*
    	$shrsb_analytics_page = add_submenu_page( basename(__FILE__), __( 'Social Analytics' ), __( 'Social Analytics', 'shrsb' ),
    		'administrator', 'shareaholic_analytics.php', 'shrsb_analytics_page' );

        $shrsb_account_page = add_submenu_page( basename(__FILE__), __( 'My Account' ), __( 'My Account', 'shrsb' ),
    		'administrator', 'shareaholic_account.php', 'shrsb_account_page' );
        */

		add_action( "admin_print_scripts-$shrsb_admin_page", 'shrsb_admin_scripts' );
		add_action( "admin_print_styles-$shrsb_admin_page", 'shrsb_admin_styles' );
        //add_action( "admin_print_styles-$shrsb_account_page", 'shrsb_admin_styles' );
        //add_action( "admin_print_styles-$shrsb_analytics_page", 'shrsb_admin_styles' );
    }
}

//styles and scripts for admin area
function shrsb_admin_scripts() {
	wp_enqueue_script('shareaholic-admin-js', SHRSB_PLUGPATH.'js/shareaholic-admin.min.js', array('jquery','jquery-ui-sortable'), SHRSB_vNum, true);
	echo get_googleanalytics();
}

/*
*   @desc Add promo bar for browser extensions
*/
function shrsb_show_promo(){
    if (is_admin()) {
        wp_enqueue_script('shareaholic-promo', SHRSB_PLUGPATH.'js/shareaholic-promo.min.js', array('jquery'), SHRSB_vNum, false);
        wp_enqueue_style('shareaholic-promo', SHRSB_PLUGPATH.'css/shareaholic-promo.css', false, SHRSB_vNum);
    }
}
add_action('set_current_user', 'shrsb_show_promo');

//Change the directory path to webpath
function shr_dir_to_path($dir){
    if(!$dir){
        return false;
    }
    $path = get_option("siteurl");
    if(substr($path, -1) != '/'){
        $path .= '/';
    }
    $path .= substr($dir , strlen(ABSPATH));
    return $path;
}

function shrsb_first_image() {
  global $post, $posts;
  $og_first_img = '';
  ob_start();
  ob_end_clean();
  if ($post == null)
    return false;
  else {
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    if(isset($matches) && isset($matches[1]) && isset($matches[1][0]) ){
        $og_first_img = $matches[1][0];
    }
    if(empty($og_first_img)){ // return false if nothing there, makes life easier
      return false;
    }
    return $og_first_img;
  }
}

/*
*   @desc For setting the content type which are enablled
*/
function shrsb_set_content_type() {
    $type  = "";
    $content = $_POST['content_type'];
    if(empty ($content)){
        $type  = "postpageindexcategory";
    }else{
        $n = count($content);
        for($i = 0; $i < $n; $i++){
            $type .= $content[$i];
        }
    }
    return $type;
}

/*
*   @desc For adding Open Graph tags to each post (http://ogp.me/)
*/
function shrsb_add_ogtags_head() {
	global $post,$shrsb_plugopts;
    
	// check to see if ogtags are enabled or not
	if (!isset($shrsb_plugopts['ogtags']) || empty($shrsb_plugopts['ogtags'])) {
        echo "\n\n".'<!-- Shareaholic Notice: OgTags disabled for all posts -->'."\n\n";
	}else{
        //Check whther OG Tags enabled for this post
        if(($ogtags_meta = get_post_meta($post->ID, 'Hide OgTags',true)) == 1)  {
            echo "\n\n".'<!-- Shareaholic Notice: OgTags disabled for this post -->'."\n\n";
           return;
        }
        
        echo "\n\n".'<!-- Start Shareaholic OgTags -->'."\n\n";
        
		// do url stuff
//		if (is_home() || is_front_page() ) {
//			echo "\t<meta property='og:url' content='".get_bloginfo('url')."' />\n";
//		}else{
//		  if ($post != null)
//  			echo "\t<meta property='og:url' content='".get_permalink($post->ID)."' />\n";
//		}
		
		// do title stuff
//		if (is_home() || is_front_page() ) {
//			echo "\t<meta property='og:title' content='".get_bloginfo('name')."' />\n";
//		}else{
//			echo "\t<meta property='og:title' content='".get_the_title()."' />\n";
//		}
		
		// do additional randoms
//		echo "\t<meta property='og:site_name' content='".get_bloginfo('name')."' />\n";
		
		// do descriptions
//		if (is_singular('post')) {
//			if (has_excerpt($post->ID)) {
//				echo "\t<meta property='og:description' content='".esc_attr(strip_tags(get_the_excerpt($post->ID)))."' />\n";
//			}else{
//				echo "\t<meta property='og:description' content='".get_bloginfo('description')."' />\n";
//			}
//		}else{
//			echo "\t<meta property='og:description' content='".get_bloginfo('description')."' />\n";
//		}
		
		// do ogp type
//		if (is_singular('post')) {
//			echo "\t<meta property='og:type' content='article' />\n";
//		}else{
//			echo "\t<meta property='og:type' content='website' />\n";
//		}
		
		// og Image Tag
		if (is_home()) {
			if (isset($options['wpfbogp_fallback_img']) && $options['wpfbogp_fallback_img'] != '') {
				echo "\t<meta property='og:image' content='".$options['wpfbogp_fallback_img']."' />\n";
			}else{
				echo "\t<!-- Shareaholic Notice: There is no featured image set -->\n"; 
			}
		} else {
			if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
				$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' );
				echo "\t<meta property='og:image' content='".esc_attr($thumbnail_src[0])."' />\n";
			}elseif (( shrsb_first_image() !== false ) && (is_singular())) {
				echo "\t<meta property='og:image' content='".shrsb_first_image()."' />\n";
			}else{
                echo "\t".'<!-- Shareaholic Notice: There is neither a featured nor gallery image set -->'."\n";
			}
		}
		echo "\n<!-- End Shareaholic OgTags -->\n\n";
    }

} // end function

add_action('wp_head','shrsb_add_ogtags_head',10);

function shrsb_admin_styles() {
	global $shrsb_plugopts;

	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') !== false)) {
		wp_enqueue_style('ie-old-sexy-bookmarks', SHRSB_PLUGPATH.'css/ie7-admin-style.css', false, SHRSB_vNum);
	}
	wp_enqueue_style('sexy-bookmarks', SHRSB_PLUGPATH.'css/admin-style.css', false, SHRSB_vNum);
}

// Add the 'Settings' link to the plugin page, taken from yourls plugin by ozh
function shrsb_admin_plugin_actions($links) {
	$links[] = '<a href="admin.php?page=sexy-bookmarks.php">'.__('Settings', 'shrsb').'</a>';
	return $links;
}
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'shrsb_admin_plugin_actions', -10);

require_once "includes/public.php";

?>