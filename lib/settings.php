<?php /* Mystique/digitalnature */

// default theme settings
function default_settings(){
 return array(
    'theme_version' => THEME_VERSION,
    'layout' => 'col-2-right',
    'main_width_2col' => '70',
    'sidebar1_width_3col' => '25',
    'sidebar2_width_3col' => '25',
    'color_scheme' => 'green',
    'font_style' => 0,
    'imageless' => 0,
    'footer_content' => '[credit] <br /> [rss] [xhtml] [top]',
    'navigation' => 'pages',
    'navigation_links' => 'Blogroll',
    'exclude_home' => '',
    'exclude_pages' => '',
    'exclude_categories' => '',
    'post_preview' => 'full',
    'post_thumb' => '64x64',
    'sharethis' => 1,
    'featured_content' => 0,
    'featured_show_on' => 'template',
    'featured_count' => '5',
    'read_more' => 1,
    'related_posts' => 1,
    'comment_author_country' => 0,
    'seo' => 1,
    'jquery' => 1,
    'ajax_commentnavi' => 1,
    'lightbox' => 1,
    'user_css' => '',
    'logo' => '',
    'background' => '',
    'background_color' => '#000000',
    'ad_code_1' => '',
    'ad_code_2' => '',
    'ad_code_3' => '',
    'ad_code_4' => '',
    'ad_code_5' => '',
    'ad_code_6' => '',
    'head_code' => '',
    'remove_settings' => 0);
}

function font_styles(){
 // default font styles
 return array(
  0 => array('code' => '"Segoe UI",Calibri,"Myriad Pro",Myriad,"Trebuchet MS",Helvetica,Arial,sans-serif',
             'desc' => 'Segoe UI (Windows Vista/7)'),

  1 => array('code' => '"Helvetica Neue",Helvetica,Arial,Geneva,"MS Sans Serif",sans-serif',
             'desc' => 'Helvetica/Arial'),

  2 => array('code' => 'Georgia,"Nimbus Roman No9 L",serif',
             'desc' => 'Georgia'),

  3 => array('code' => '"Lucida Grande","Lucida Sans","Lucida Sans Unicode","Helvetica Neue",Helvetica,Arial,Verdana,sans-serif',
             'desc' => 'Lucida Grande/Sans (Mac/Windows)')
  // you can add more font styles here based on the above entries (4, 5, 6 etc...)
 );
}

function theme_install_notification(){ ?>
  <div class='updated fade'><p><?php printf(__('You can configure Mystique from the <a%s>theme settings</a> page.','mystique'),' href="themes.php?page=theme-settings"'); ?></p></div>
<?php
}

function verify_options(){
  $default_settings = default_settings();
  $current_settings = get_option('mystique');
  if(!$current_settings):
   setup_options();
   add_action('admin_notices', 'theme_install_notification');
  else:
   // only go further if the theme version from the database differs from the one in the theme files
   if (version_compare($current_settings['theme_version'], THEME_VERSION, '!=')):
     // check for new options
     foreach($default_settings as $option=>$value):
      if(!array_key_exists($option, $current_settings)) $current_settings[$option] = $default_settings[$option];
     endforeach;

    // update theme version
    $current_settings['theme_version'] = THEME_VERSION;
    update_option('mystique' , $current_settings);
   endif;
  endif;
}

function setup_options() {
  $default_settings = default_settings();
  remove_options();
  update_option('mystique' , apply_filters('theme_default_settings', $default_settings));
}

function remove_options() {
  delete_option('mystique');
  delete_option('mystique-twitter');
}

function get_mystique_option($option) {
  $get_mystique_options = get_option('mystique');
  return $get_mystique_options[$option];
}

function print_mystique_option($option) {
  $get_mystique_options = get_option('mystique');
  echo $get_mystique_options[$option];
}

function setup_js(){
 if(get_mystique_option('jquery')):
  wp_enqueue_script('jquery');
  wp_enqueue_script('mystique',THEME_URL.'/js/jquery.mystique.js',array('jquery'),$ver=THEME_VERSION,$in_footer=true);
endif;
}

function setup_css(){
 echo '<style type="text/css">'.PHP_EOL;
 $mystique_options = get_option('mystique');
 $font_styles = font_styles();

 if($mystique_options['imageless']):
  echo '@import "'.THEME_URL.'/style-imageless.css";'.PHP_EOL;
 else:
  echo '@import "'.get_bloginfo('stylesheet_url').'";'.PHP_EOL;
  echo '@import "'.THEME_URL.'/color-'.$mystique_options['color_scheme'].'.css";'.PHP_EOL;
 endif;

 if($mystique_options['comment_author_country'] && (is_single() || is_page())):  echo '@import "'.THEME_URL.'/lib/ip2c/flags.css";'.PHP_EOL; endif;

 if($mystique_options['font_style']) echo '*{font-family:'.$font_styles[$mystique_options['font_style']]['code'].';}'.PHP_EOL;

 if($mystique_options['background']) echo '#page{background-image:none;}'.PHP_EOL.'body{background-image:url("'.$mystique_options['background'].'");background-repeat:no-repeat;background-position:center top;}'.PHP_EOL;
 if(($mystique_options['background_color']) && ($mystique_options['background_color']<>'#000000')):
  echo 'body{background-color:'.$mystique_options['background_color'].';}'.PHP_EOL;
  if (!$mystique_options['background']) echo 'body,#page{background-image:none;}'.PHP_EOL;
 endif;

 if(($mystique_options['layout']=='col-2-right') || (is_page_template('page-2col-right.php'))):
  if($mystique_options['main_width_2col']<>'70'):
   echo 'body.col-2-right #sidebar{width:'.(100-($mystique_options['main_width_2col'])).'%;}'.PHP_EOL;
   echo 'body.col-2-right #primary-content{width:'.$mystique_options['main_width_2col'].'%;}'.PHP_EOL;
  endif;
 elseif(($mystique_options['layout']=='col-3')  || (is_page_template('page-3col.php'))):
  if(($mystique_options['sidebar1_width_3col']<>'25') || ($mystique_options['sidebar2_width_3col']<>'25')):
   echo 'body.col-3 #sidebar{width:'.$mystique_options['sidebar1_width_3col'].'%;left:-'.((100-$mystique_options['sidebar1_width_3col']-$mystique_options['sidebar2_width_3col'])+$mystique_options['sidebar2_width_3col']).'%;}'.PHP_EOL;
   echo 'body.col-3 #sidebar2{width:'.$mystique_options['sidebar2_width_3col'].'%;left:'.$mystique_options['sidebar1_width_3col'].'%;}'.PHP_EOL;
   echo 'body.col-3 #primary-content{width:'.(100-$mystique_options['sidebar1_width_3col']-$mystique_options['sidebar2_width_3col']).'%;left:'.$mystique_options['sidebar1_width_3col'].'%;}'.PHP_EOL;
  endif;
 endif;

 echo '.post-info.with-thumbs{margin-left:'.($mystique_options['post_thumb']+30).'px;}'.PHP_EOL;

 if ($mystique_options['user_css']) echo $mystique_options['user_css'].PHP_EOL;

 // "css" custom field
 if (is_single() || is_page()):
   global $post;
   $css = get_post_meta($post->ID, 'css', true);
   if (!empty($css)) echo $css.PHP_EOL;
 endif;

 echo '</style>'.PHP_EOL; ?>
<!--[if lte IE 6]><style type="text/css" media="screen">@import "<?php bloginfo('template_url'); ?>/ie6.css";</style><![endif]-->
<!--[if IE 7]><style type="text/css" media="screen">@import "<?php bloginfo('template_url'); ?>/ie7.css";</style><![endif]-->
 <?php
}
