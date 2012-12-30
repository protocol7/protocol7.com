<?php
/**
 * @package WordPress
 * @subpackage Constructor
 * 
 * Don't work preview on admin page?
 * Read issue 11006 for more details
 * 
 * @see      http://core.trac.wordpress.org/ticket/11006
 * 
 * @author   Anton Shevchuk <AntonShevchuk@gmail.com>
 * @link     http://anton.shevchuk.name
 */
// need for defence
define('CONSTRUCTOR', true);

// debug only current theme
define('CONSTRUCTOR_DEBUG', false);

if ( function_exists('register_sidebar') ) {

    register_sidebar(array(
        'name'=>'header',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<span>',
        'after_title' => '</span>',
    )); 
    
    register_sidebar(array(
        'name'=>'content',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));
    
    // options for all follows sidebars
    $widget_options = array(
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    );
    
    register_sidebar(array_merge($widget_options, array('name'=>'sidebar')));
    register_sidebar(array_merge($widget_options, array('name'=>'sidebar categories')));
    register_sidebar(array_merge($widget_options, array('name'=>'sidebar posts')));
    register_sidebar(array_merge($widget_options, array('name'=>'sidebar pages')));
    
    register_sidebar(array_merge($widget_options, array('name'=>'extra')));
    register_sidebar(array_merge($widget_options, array('name'=>'extra categories')));
    register_sidebar(array_merge($widget_options, array('name'=>'extra posts')));
    register_sidebar(array_merge($widget_options, array('name'=>'extra pages')));
    
    register_sidebar(array_merge($widget_options, array('name'=>'footer')));
    register_sidebar(array_merge($widget_options, array('name'=>'footer categories')));
    register_sidebar(array_merge($widget_options, array('name'=>'footer posts')));
    register_sidebar(array_merge($widget_options, array('name'=>'footer pages')));    
}

define('CONSTRUCTOR_DIRECTORY',     get_template_directory());
define('CONSTRUCTOR_DIRECTORY_URI', get_template_directory_uri());

load_theme_textdomain('constructor', CONSTRUCTOR_DIRECTORY.'/lang');

if (function_exists('add_theme_support')) { // Added in 2.9
	// This theme uses post thumbnails
	add_theme_support('post-thumbnails' );
	set_post_thumbnail_size( 64, 64, true ); // Normal post thumbnail
	add_image_size('list-post-thumbnail', 128, 128, true );
	add_image_size('tile-post-thumbnail', 312, 292, true );
	add_image_size('slideshow-thumbnail');
	    
	// This theme uses wp_nav_menu()
	add_theme_support('nav-menus');

	// Add default posts and comments RSS feed links to head
	add_theme_support('automatic-feed-links');    	
}

if (!is_admin()) {    
    
    /**
     * Parse request
     *
     * @param unknown_type $wp
     */
    function constructor_parse_request($wp) {
        // only process requests with "my-plugin=ajax-handler"
        if (array_key_exists('theme-constructor', $wp->query_vars)){
            switch ($wp->query_vars['theme-constructor']) {
                case 'css':
                    require_once 'css.php';
                    break;
                case 'slideshow':
                    require_once 'slideshow.php';
                    break;
            }
            // die after return data
            die();
        } elseif (array_key_exists('preview', $wp->query_vars)) {
            global $postfix;
            
        }
    }
    add_action('wp', 'constructor_parse_request');
    
    /**
     * register query vars
     *
     * @param array $vars
     * @return array
     */
    function constructor_query_vars($vars) {
        $vars[] = 'theme-constructor';
        return $vars;
    }
    add_filter('query_vars', 'constructor_query_vars');
    
    /**
     * Preview filter
     *
     * @param string $content
     */
    function constructor_preview($content) {
        $link = add_query_arg(array('preview' => 1, 'template' => get_template()), '?theme-constructor=css');
        
        $content = str_replace('?theme-constructor=css', $link, $content);
        return $content;
    }
    
    add_filter('preview_theme_ob_filter', 'constructor_preview');
    
    require_once CONSTRUCTOR_DIRECTORY .'/libs/Constructor/Main.php';
    
    $main = new Constructor_Main();
    $main -> init();
    
    /* Alias section for fast theme development */    
    /**
     * get_constructor_slideshow
     *
     * @access  public
     * @param   boolean  $in In or Out of content container
     * @return  rettype  return
     */
    function get_constructor_slideshow($in = false)
    {
        global $main;
        $main->getSlideshow($in);
    }
    
    /**
     * get_constructor_layout
     *
     * return layout by admin options for $where
     * 
     * @param  string $where
     * @return string
     */
    function get_constructor_layout($where = 'index')
    {
        global $main;
        $main->getLayout($where);
    }
    
    /**
     * get top menu
     *
     * @param  string $before
     * @param  string $after
     * @return string
     */
    function get_constructor_menu($before = '', $after = '')
    {
        global $main;
        $main->getMenu($before, $after);
    }
        
    /**
     * get content widget
     * 
     * @param integer $i post counter
     * @return 
     */
    function get_constructor_content_widget($i)
    {
        global $main;
        $main->getContentWidget($i);
    }

    /**
     * get author name
     *
     * @param  string $before
     * @param  string $after
     * @return string
     */
    function get_constructor_author($before = '', $after = '')
    {
        global $main;
        echo $main->getAuthor($before, $after);
    }
    
    /**
     * get avatar size
     *
     * @return string
     */
    function get_constructor_avatar_size($size = 32)
    {
        global $main;
        return $main->getAvatarSize($size);
    }
    
    /**
     * get no image
     *
     * @return string
     */
    function get_constructor_noimage($width = 312, $height = 292, $align = 'none') 
    {
        return '<img class="thumb align'.$align.'" src="' .CONSTRUCTOR_DIRECTORY_URI. '/images/noimage.png" width="'.$width.'px" height="'.$height.'px" alt="' .__('No Image', 'constructor'). '"/>';
    }
    
    /**
     * get sidebar
     *
     * @access  public
     * @return  string
     */
    function get_constructor_sidebar()
    {
        global $main;
        $main->getSidebar();
    }
    
    /**
     * get navigation
     *
     * @access  public
     * @return  string
     */
    function get_constructor_navigation()
    {
        global $main;
        $main->getNavigation();
    }
    
    /**
     * get footer
     *
     * @access public
     * @return string
     */
    function get_constructor_footer()
    {
        global $main;
        $main->getFooter();
    }
    
    /**
     * Generate HTML code for images
     * 
     * @param integer $width [optional]
     * @param integer $height [optional]
     * @param string $key [optional]
     * @param string $align [optional]
     * @param bool $noimage [optional]
     * @return string
     */
    function get_constructor_post_image($width = 312, $height = 292, $key = 'thumb', $align = 'none', $noimage = true)
    {
        global $main;
        $main->getPostImage($width, $height, $key, $align, $noimage);
    }
    
    /**
     * get constructor category classname
     * 
     * @return string
     */
    function get_constructor_category_class()
    {
        global $main;
        return $main->getCategoryClass();
    }

    /**
     * get constructor category
     * 
     * @return string
     */
    function get_constructor_category()
    {
        global $main;
        return $main->getCategory();
    }
    
} else {
    // only for administrator
    if (CONSTRUCTOR_DEBUG || isset($_REQUEST['debug'])) {
        require_once CONSTRUCTOR_DIRECTORY .'/libs/debug.php';
    }
    
    // PHP4 compatibility
    if (version_compare(phpversion(), '5.0.0', '<')) {
        require_once CONSTRUCTOR_DIRECTORY .'/admin/compatibility.php';
    }
    
    // init modules for admin pages
    // you can disable any
    $constructor_modules = array(
        __('Themes',  'constructor') => 'themes',
        __('Layout',  'constructor') => 'layout',
        __('Sidebar', 'constructor') => 'sidebar',
        __('Header',  'constructor') => 'header',
        __('Content', 'constructor') => 'content',
        __('Comments','constructor') => 'comments',
        __('Footer',  'constructor') => 'footer',
        __('Fonts',   'constructor') => 'fonts',
        __('Colors',  'constructor') => 'colors',
        __('Design',  'constructor') => 'design',
        __('CSS',     'constructor') => 'css',
        __('Images',  'constructor') => 'images',
        __('Slideshow', 'constructor') => 'slideshow',
        __('Save',    'constructor') => 'save',
		__('Help',    'constructor') => 'help'
    );
    
    require_once CONSTRUCTOR_DIRECTORY .'/libs/Constructor/Admin.php';
    
    $admin = new Constructor_Admin();
    $admin -> init($constructor_modules);
}