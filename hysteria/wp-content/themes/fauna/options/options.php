<?php

class fauna_options {
	function init() {
		add_action('admin_menu', array('fauna_options', 'add_menu'));

		if($_GET['page'] == 'fauna-options' and isset($_POST['uninstall'])) {
			fauna::uninstall();
		}
	}
	function add_menu() {
		add_theme_page(__('Fauna Options', 'fauna'), __('Fauna Options', 'fauna'), 5, 'fauna-options', array('fauna_options', 'admin'));
	}
	function admin() {
		include(TEMPLATEPATH . '/options/display.php');
	}
	function update() {
		if (!empty($_POST)) {
			if (isset($_POST['fauna'])) {
			
				if (isset($_POST['fauna']['about'])) {
					update_option('fauna_about', $_POST['fauna']['about'], '','');
				}
				if (isset($_POST['fauna']['note'])) {
					update_option('fauna_note', $_POST['fauna']['note'], '','');
				}
				if (isset($_POST['fauna']['comment'])) {
					update_option('fauna_comment', '1');
				} else {
					update_option('fauna_comment', '0');
				}
				if (isset($_POST['fauna']['trackback'])) {
					update_option('fauna_trackback', '1');
				} else {
					update_option('fauna_trackback', '0');
				}
				// Author Info
				if (isset($_POST['fauna']['author'])) {
					update_option('fauna_author', '1');
				} else {
					update_option('fauna_author', '0');
				}
				// Archives Page
				if(isset($_POST['fauna']['archives'])) {
					update_option('fauna_archives', '1');
					fauna_archive::create_archive();
				} else {
					update_option('fauna_archives', '0');
					fauna_archive::delete_archive();
				}
				// Rounded Corners
				if (isset($_POST['fauna']['rounded'])) {
					update_option('fauna_rounded', '1');
				} else {
					update_option('fauna_rounded', '0');
				}
				
				foreach($_POST['fauna'] as $option => $value) {
					update_option('fauna_' . $option, $value);
				}
			}
		}
	}
	function install() {
		add_option('fauna_about', '', '');
		add_option('fauna_note', '', '');
		add_option('fauna_tab', 'Blog', '');
		add_option('fauna_sidebar', 'right', '');
		add_option('fauna_scalemode', 'fixed', '');
		add_option('fauna_comment', '', '');
		add_option('fauna_trackback', '', '');
		add_option('fauna_author', '', '');
		add_option('fauna_noteworthy', '%26hearts;', '');
		add_option('fauna_style', 'default/default.css', '');
		add_option('fauna_archives', '', '');
		add_option('fauna_header', 'fauna', '');
		add_option('fauna_height', 200, '');
		add_option('fauna_background', '', '');
		add_option('fauna_rounded', 1, '');
	}
	function uninstall(){
		delete_option('fauna_about');
		delete_option('fauna_note');
		delete_option('fauna_tab');
		delete_option('fauna_sidebar');
		delete_option('fauna_scalemode');
		delete_option('fauna_comment');
		delete_option('fauna_trackback');
		delete_option('fauna_author');
		delete_option('fauna_noteworthy');
		delete_option('fauna_style');
		delete_option('fauna_archives');
		delete_option('fauna_header');
		delete_option('fauna_headerurl');
		delete_option('fauna_height');
		delete_option('fauna_background');
		delete_option('fauna_rounded');
	}
}

add_action('fauna_init', array('fauna_options', 'init'), 1);
add_action('fauna_install', array('fauna_options', 'install'));
add_action('fauna_uninstall', array('fauna_options', 'uninstall'));

?>
