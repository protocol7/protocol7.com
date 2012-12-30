<?php
/*
Plugin Name: WordIdentica
Plugin URI: http://blog.bluefur.com/wordidentica
Description: Generates Identica Updates when a new Post is Published.
Author: Gary Jones
Version: 1.0
Author URI: http://blog.bluefur.com/
*/

$identica_plugin_name = 'WordIdentica';
$identica_plugin_prefix = 'wordidentica_';

add_action('publish_post', 'identica_post_now_published');

function identica_update_status($username, $password, $new_status)
{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');
        curl_setopt($curl, CURLOPT_URL, 'http://identi.ca/main/login');
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'nickname='. $username .'&password='. $password .'');
        curl_exec($curl);
        curl_setopt($curl, CURLOPT_URL, 'http://identi.ca/notice/new');
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'qualifier=%3A&status_textarea='. $new_status .'&lang=en&no_comments=0');
        curl_exec($curl);
        curl_close($curl);

}

function identica_post_now_published($post_id)
{
	global $identica_plugin_prefix;

	$has_been_identicaed = get_post_meta($post_id, 'has_been_identicaed', true);
	if (!($has_been_identicaed == 'yes')) {
		query_posts('p=' . $post_id);

		if (have_posts()) {
			the_post();
			$post_url = file_get_contents('http://tinyurl.com/api-create.php?url=' . get_permalink());
			$title = get_the_title();
			if (strlen($title) > 110) {
				$title = substr_replace($title, '...', 107);
			}
			$i = '\'' . $title . '\' - ' . $post_url;
			
			$identica_username = get_option($identica_plugin_prefix . 'username', 0);
			$identica_password = get_option($identica_plugin_prefix . 'password', 0);
			
			identica_update_status($identica_username, $identica_password, $i);
	
			add_post_meta($post_id, 'has_been_identicaed', 'yes');
		}
	}
}

function wordidentica_plugin_url($str = '')
{
	$dir_name = '/wp-content/plugins/wordidentica';
	bloginfo('url');
	echo($dir_name . $str);
}

function wordidentica_options_subpanel()
{
	global $identica_plugin_name;
	global $identica_plugin_prefix;

  	if (isset($_POST['info_update'])) 
	{
		if (isset($_POST['username'])) {
			$username = $_POST['username'];
		} else {
			$username = '';
		}

		if (isset($_POST['password'])) {
			$password = $_POST['password'];
		} else {
			$password = '';
		}

		update_option($identica_plugin_prefix . 'username', $username);
		update_option($identica_plugin_prefix . 'password', $password);
	} else {
		$username = get_option($identica_plugin_prefix . 'username');
		$password = get_option($identica_plugin_prefix . 'password');
	}

	echo('<div class=wrap><form method="post">');
	echo('<h2>' . $plugin_name . ' Options</h2>');

	?>
	<p><h3>General Options</h3>
		You can find out more information about this plugin at <a href="http://blog.bluefur.com/wordidentica">the WordIdentica plugin page</a>.
		<p><br />
		Identica Username: <input type="text" name="username" value="<?php echo($username); ?>"><br />
		Identica Password: <input type="password" name="password" value="<?php echo($password); ?>"><br />
		<div class="submit"><input type="submit" name="info_update" value="Update Options" /></div></form>

	<?php

	echo('</div>');
}

function wordidentica_add_plugin_option()
{
	global $identica_plugin_name;
	if (function_exists('add_options_page')) 
	{
		add_options_page($identica_plugin_name, $identica_plugin_name, 0, basename(__FILE__), 'wordidentica_options_subpanel');
    	}	
}

add_action('admin_menu', 'wordidentica_add_plugin_option');

?>
