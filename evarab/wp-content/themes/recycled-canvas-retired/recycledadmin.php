<?php

if (!function_exists('recycledadmin')) {
    function RecycledAdmin($theme='',$array='',$file='') {
        global ${$theme};
        if ($theme == '' or $array == '' or $file == '') {
            die ('No theme name, theme option, or parent defined in Theme Toolkit');
        }
        ${$theme} = new RecycledAdmin($theme,$array,$file);
    }
}

if (!class_exists('RecycledAdmin')) {
    class RecycledAdmin{

        var $option, $infos;

        function RecycledAdmin($theme,$array,$file){
            
            global $wp_version; 
            
            $this->infos['path'] = '../themes/' . basename(dirname($file));
            /* Create some vars needed if an admin menu is to be printed */
            if ($array['debug']) {
                if ((basename($file)) == $_GET['page']) $this->infos['debug'] = 1;
                unset($array['debug']);
            }
            if ((basename($file)) == $_GET['page']){
                $this->infos['menu_options'] = $array;
                $this->infos['classname'] = $theme;
            }
            $this->option=array();



            /* Get infos about the theme and particularly its 'shortname'
             * which is used to name the entry in wp_options where data are stored */
            $this->do_init();

            /* Read data from options table */
            $this->read_options();

            /* Are we in the admin area ? Add a menu then ! */
            $this->file = $file;
            add_action('admin_menu', array(&$this, 'add_menu'));
        }


        /* Add an entry to the admin menu area */
        function add_menu() {
            global $wp_version;
            if ( $wp_version >= 2 ) {
                $level = 'edit_themes';
            } else {
                $level = 9;
            }
            //add_submenu_page('themes.php', 'Configure ' . $this->infos[theme_name], $this->infos[theme_name], 9, $this->infos['path'] . '/functions.php', array(&$this,'admin_menu'));
            add_theme_page('Configure ' . $this->infos['theme_name'], $this->infos['theme_name'] . ' Admin', 'edit_themes', basename($this->file), array(&$this,'admin_menu'));
            /* Thank you MCincubus for opening my eyes on the last parameter :) */
        }

        /* Get infos about this theme */
        function do_init() {
            $themes = get_themes();
            $shouldbe= basename($this->infos['path']);
            foreach ($themes as $theme) {
                $current= basename($theme['Template Dir']);
                if ($current == $shouldbe) {
                    if (get_settings('template') == $current) {
                        $this->infos['active'] = TRUE;
                    } else {
                        $this->infos['active'] = FALSE;
                    }
                $this->infos['theme_name'] = $theme['Name'];
                $this->infos['theme_shortname'] = $current;
                $this->infos['theme_site'] = $theme['Title'];
                $this->infos['theme_version'] = $theme['Version'];
                $this->infos['theme_author'] = preg_replace("#>\s*([^<]*)</a>#", ">\\1</a>", $theme['Author']);
                }
            }
        }

        /* Read theme options as defined by user and populate the array $this->option */
        function read_options() {
            $options = get_option('theme-'.$this->infos['theme_shortname'].'-options');
            $options['_________junk-entry________'] = 'ozh is my god';
            foreach ($options as $key=>$val) {
                $this->option["$key"] = stripslashes($val);
            }
            array_pop($this->option);
            return $this->option;

        }

        /* Write theme options as defined by user in database */
        function store_options($array) {
            update_option('theme-'.$this->infos['theme_shortname'].'-options','');
            if (update_option('theme-'.$this->infos['theme_shortname'].'-options',$array)) {
                return "Options successfully stored";
            } else {
                return "Could not save options !";
            }
        }

 
        /* Check if the theme has been loaded at least once (so that this file has been registered as a plugin) */
        function is_installed() {
            global $wpdb;
            $where = 'theme-'.$this->infos['theme_shortname'].'-options';
            $check = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->options WHERE option_name = '$where'");
            if ($check == 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        }

        /* Theme used for the first time (create blank entry in database) */
        function do_firstinit() {
            global $wpdb;
            $options = array();
            foreach(array_keys($this->option) as $key) {
                $options["$key"]='';
            }
            add_option('theme-'.$this->infos['theme_shortname'].'-options',$options, 'Options for theme '.$this->infos['theme_name']);
            return "Theme options added in database (1 entry in table '". $wpdb->options ."')";
        }

        /* The mother of them all : the Admin Menu printing func */
        function admin_menu () {
            global $cache_settings, $wpdb;
			
			
            /* Process things when things are to be processed */
            if (@$_POST['action'] == 'store_option') {
                unset($_POST['action']);
                $msg = $this->store_options($_POST);
            } elseif (!$this->is_installed()) {
                $msg = $this->do_firstinit();
            }

			
			
			
            if (@$msg) print "<div class='updated'><p><b>" . $msg . "</b></p></div>\n";

echo '<script src="http://www.creativesynthesis.net/recycling/version-check.php?version=' . $this->infos['theme_version'] . '" type="text/javascript"></script>';
            echo '<div class="wrap"><h2>Recycled Canvas</h2>';
           
 			echo '<p>Welcome to an exciting world of learning with <strong>Recycled Canvas</strong>, a theme for Wordpress. This theme was made by '.$this->infos['theme_author'].'. More information is available at <a href="http://www.creativesynthesis.net/blog/projects/recycled-research/recycled-canvas/">our website.</a> or in the <a href="' . get_bloginfo('template_directory') . '/readme.html">readme</a>.</p>';



            if (!$this->infos['active']) { /* theme is not active */
                echo '<p>(Please note that this theme is currently <strong>not activated</strong> on your site as the default theme.)</p>';
            }

            $cache_settings = '';
            $check = $this->read_options();
            
            echo '<form action="" style="border:1px solid #ccc; width:860px; padding:10px; margin:20px auto;" method="post">
            <input type="hidden" name="action" value="store_option">
            <table cellspacing="2" cellpadding="5" border="0" width=100% class="editform">';

            /* Print form, here comes the fun part :) */
            print "<tr valign='top'>";

            foreach ($this->infos['menu_options'] as $key=>$val) {
	             
	           
	           //if widget shelf is not being used, don't display flickr and delicious options

	
	            
                $items='';
                preg_match('/\s*([^{#]*)\s*({([^}]*)})*\s*([#]*\s*(.*))/', $val, $matches);
                if ($matches[3]) {
                    $items = split("\|", $matches[3]);
                }

                if (@$items) {
                    $type = array_shift($items);
                    switch ($type) {

                    case 'radio':
                        print "<br/><strong>".$matches[1]."</strong><br/><br/>";
                        while ($items) {
                            $v=array_shift($items);
                            $t=array_shift($items);
                            $checked='';
                            if ($v == $this->option[$key]) $checked='checked';
                            print "<label for='${key}${v}'><input type='radio' id='${key}${v}' name='$key' value='$v' $checked /> $t</label><br/>";
                        }
                        break;
	                    case 'boxstyle':
							print "</td>";	
	                        print "<td><strong>".$matches[1]."</strong><br/><br/>";
	                        while ($items) {
	                            $v=array_shift($items);
	                            $t=array_shift($items);
	                            $checked='';
	                            if ($v == $this->option[$key]) $checked='checked';
	                            print "<div style=\"float:left; margin-left:20px;\"><label for='${key}${v}'><input  type='radio' id='${key}${v}' name='$key' value='$v' $checked /> ${t}<br/><br/> <img src=\"" . get_bloginfo('template_directory') . "/images/admin/${v}.png\" alt=\"${v}\" style=\"vertical-align:top;\"/></input></label></div>";
	                        }
							print "</td>";
	                        break;
                    case 'textarea':
                        $rows=array_shift($items);
                        $cols=array_shift($items);
                    print "<td width=\"200\"><label for='$key'><strong>".$matches[1]."</strong></label><br/><br/>";
                        print "<textarea name='$key' id='$key' rows='$rows' cols='$cols'>" . $this->option[$key] . "</textarea><br/><br/>";
                        break;
                   	
					case 'headerimg':

						print "<input type=\"file\" id=\"el10\"/><br/><br/>";
						break;
                    }
			
                } else {
                    print "<label for='$key'>".$matches[1]."</label><td>";
                    print "<input type='text' name='$key' id='$key' value='" . $this->option[$key] . "' />";
                }


            }
            echo '</tr></table><div style=\"clear:both;\"></div>
            <p class="submit"><input type="submit" value="Store Options" /></p>
            </form>';

   		}
    }
}

?>
