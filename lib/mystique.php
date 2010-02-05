<?php /* Mystique/digitalnature */

class Mystique {

	function init() {
	    global $wp_version;
        $mystique_theme_data = get_theme_data(TEMPLATEPATH.'/style.css');

        define('THEME_NAME', 'Mystique');
        define('THEME_AUTHOR', $mystique_theme_data['Author']);
        define('THEME_URI', $mystique_theme_data['URI']);
        define('THEME_VERSION', trim($mystique_theme_data['Version']));
        define('THEME_URL', get_bloginfo('template_url'));

        // end of line character
        if(!defined("PHP_EOL")) define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");

	    if (class_exists('xili_language')):
		  define('THEME_TEXTDOMAIN','mystique');
		  define('THEME_LANGS_FOLDER','/lang');
	    else:
	     load_theme_textdomain('mystique', get_template_directory() . '/lang');
	    endif;

        require_once(TEMPLATEPATH.'/lib/core.php');
        require_once(TEMPLATEPATH.'/lib/settings.php');
        require_once(TEMPLATEPATH.'/lib/shortcodes.php');

        // json functions for old php versions & wp < 2.9
        if(!function_exists('json_decode')) require_once(TEMPLATEPATH.'/lib/JSON.php');
        if ($wp_version >= 2.8) require_once(TEMPLATEPATH.'/lib/widgets.php');
        if(current_user_can('edit_themes')) require_once(TEMPLATEPATH.'/lib/admin.php');

        if(current_user_can('edit_posts')):
          require_once(TEMPLATEPATH.'/lib/editor.php');
          add_filter('mce_css', 'mystique_editor_styles');
          add_filter('mce_buttons_2', 'mcekit_editor_buttons');
          add_filter('tiny_mce_before_init', 'mcekit_editor_settings');
        endif;

        if(get_mystique_option('seo') && ($wp_version <= 2.8)) add_action('wp_head', 'canonical_for_comments');

        add_action('init', 'verify_options');
        if (get_mystique_option('read_more')) add_action('init', readmorelink);        
        add_action('template_redirect', 'meta_redirect');
        add_action('wp_head', 'setup_css',2);
        add_action('wp_head', 'setup_js',3);

        if($wp_version <= 2.8) add_filter('wp_trim_excerpt', 'excerpt_more'); else add_filter('excerpt_more', 'excerpt_more');


        // set up widget areas
        if (function_exists('register_sidebar')):
            register_sidebar(array(
                'name' => __('Default sidebar','mystique'),
                'id' => 'sidebar-1',
                'description' => __("This is the default sidebar, visible on 2 or 3 column layouts. If no widgets are active, the default theme widgets will be displayed instead.","mystique"),
        		'before_widget' => '<li class="block"><div class="block-%2$s clearfix" id="instance-%1$s">',
        		'after_widget' => '</div></li>',
        		'before_title' => '<h3 class="title"><span>',
        		'after_title' => '</span></h3><div class="block-div"></div><div class="block-div-arrow"></div>'
            ));

            register_sidebar(array(
                'name' => __('Secondary sidebar','mystique'),
                'id' => 'sidebar-2',
                'description' => __("This sidebar is active only on a 3 column setup. ","mystique"),
        		'before_widget' => '<li class="block"><div class="block-%2$s clearfix" id="instance-%1$s">',
        		'after_widget' => '</div></li>',
        		'before_title' => '<h3 class="title"><span>',
        		'after_title' => '</span></h3><div class="block-div"></div><div class="block-div-arrow"></div>'
            ));

            register_sidebar(array(
                'name' => __('Footer','mystique'),
                'id' => 'footer-1',
                'description' => __("You can add between 1 and 6 widgets here (3 or 4 are optimal). They will adjust their size based on the widget count. ","mystique"),
        		'before_widget' => '<li class="block block-%2$s" id="instance-%1$s"><div class="block-content clearfix">',
        		'after_widget' => '</div></li>',
        		'before_title' => '<h4 class="title">',
        		'after_title' => '</h4>'
            ));

            register_sidebar(array(
                'name' => __('Footer (slide 2)','mystique'),
                'id' => 'footer-2',
                'description' => __("Only visible if jQuery is enabled. ","mystique"),
        		'before_widget' => '<li class="block block-%2$s" id="instance-%1$s"><div class="block-content clearfix">',
        		'after_widget' => '</div></li>',
        		'before_title' => '<h4 class="title">',
        		'after_title' => '</h4>'
            ));

            register_sidebar(array(
                'name' => __('Footer (slide 3)','mystique'),
                'id' => 'footer-3',
                'description' => __("Only visible if jQuery is enabled. ","mystique"),
        		'before_widget' => '<li class="block block-%2$s" id="instance-%1$s"><div class="block-content clearfix">',
        		'after_widget' => '</div></li>',
        		'before_title' => '<h4 class="title">',
        		'after_title' => '</h4>'
            ));

            register_sidebar(array(
                'name' => __('Footer (slide 4)','mystique'),
                'id' => 'footer-4',
                'description' => __("Only visible if jQuery is enabled. ","mystique"),
        		'before_widget' => '<li class="block block-%2$s" id="instance-%1$s"><div class="block-content clearfix">',
        		'after_widget' => '</div></li>',
        		'before_title' => '<h4 class="title">',
        		'after_title' => '</h4>'
            ));
        endif;

        // set up post thumnails
        if (function_exists('add_theme_support')):
          add_theme_support('post-thumbnails');
          $size = explode('x',get_mystique_option('post_thumb'));
          set_post_thumbnail_size($size[0],$size[1],true);
        endif;

	}

}
?>