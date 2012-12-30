<?php 

class fauna {
	function init() {
		load_theme_textdomain('fauna');
		
		include(TEMPLATEPATH . '/options/options.php');
		include(TEMPLATEPATH . '/options/archive.php');
		
		$last_modified = filemtime(dirname(__FILE__));
		$last_modified_check = get_option('fauna_lastmodified');
		
		if($last_modified_check == false || $last_modified_check < $last_modified) {
			fauna::install($last_modified);
		}
		
		do_action('fauna_init');
	}
	function install($last_modified) {
		global $faunaVersion;
		
		if (get_option('fauna_version') == false) {
			add_option('fauna_version', $faunaVersion, '');
		} else {
			update_option('fauna_version', $faunaVersion);
		}
		
		if (get_option('fauna_lastmodified') == false) {
			add_option('fauna_lastmodified', $last_modified, '');
		} else {
			update_option('fauna_lastmodified', $last_modified);
		}
		
		do_action('fauna_install');
	}
	function uninstall() {
		do_action('fauna_uninstall');

		delete_option('fauna_version');
		delete_option('fauna_lastmodified');

		wp_cache_flush();
		
		update_option('template', 'default');
		update_option('stylesheet', 'default');
		do_action('switch_theme', 'Default');
		
		header('location: themes.php');
		exit;
	}
	function files_scan($path, $ext = false, $depth = 1, $relative = true) {
		$files = array();
		fauna::_files_scan($path, '', $ext, $depth, $relative, $files);
		return $files;
	}
	function _files_scan($base_path, $path, $ext, $depth, $relative, &$files) {
		if(($dir = @dir($base_path . $path)) !== false) {
			while(($file = $dir->read()) !== false) {
				$file_path = $path . $file;
				$file_full_path = $base_path . $file_path;

				if(is_dir($file_full_path) and $depth > 1 and !($file == '.' or $file == '..')) {
					fauna::_files_scan($base_path, $file_path . '/', $ext, $depth - 1, $relative, $files);
				} elseif(is_file($file_full_path) and (!$ext or preg_match('/\.' . $ext . '$/i', $file))) {
					$files[] = $relative ? $file_path : $file_full_path;
				}
			}
			$dir->close();
		}
	}
}

?>