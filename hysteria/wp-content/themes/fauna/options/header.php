<?php

define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/images/masthead-fauna.jpg');
define('HEADER_IMAGE_WIDTH', 780);
if (get_option('fauna_height') == "") {
	define('HEADER_IMAGE_HEIGHT', 200);
} else {
	define('HEADER_IMAGE_HEIGHT', get_option('fauna_height'));
}
define('NO_HEADER_TEXT', true );

	

function header_style() { ?>
<style type="text/css">
#header {
	background: url('<?php header_image() ?>') no-repeat center;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
}
</style>
<?php }

function admin_header_style() { ?>
<style type="text/css">
#headimg {
	background: url('<?php header_image() ?>') no-repeat center;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
#headimg h1, #headimg #desc {
	display: none;
}
</style>
<?php }
if ( function_exists('add_custom_image_header') ) {
	add_custom_image_header('header_style', 'admin_header_style');
}
?>