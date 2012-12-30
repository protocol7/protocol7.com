<?php

class fauna_archive {
	function create_archive() {
		global $wpdb, $wp_version;

		$archives_id = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'page-archives.php' LIMIT 1");

		$archives_page = array();
		$archives_page['ID'] = $archives_id;
		$archives_page['post_title'] = __('Archives', 'fauna');
		$archives_page['menu_order'] = -5;

		if ($wp_version < 2.1) {
			// WP 2.0
			$archives_page['post_status'] = 'static';
		} else {
			// WP 2.1+
			$archives_page['post_status'] = 'publish';
			$archives_page['post_type'] = 'page';
		}
		$archives_page['page_template'] = 'page-archives.php';

		wp_insert_post($archives_page);
	}

	function delete_archive() {
		global $wpdb;

		$archives_id = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'page-archives.php' LIMIT 1");

		if (!empty($archives_id)) {
			wp_delete_post($archives_id);
		}
	}
}
?>