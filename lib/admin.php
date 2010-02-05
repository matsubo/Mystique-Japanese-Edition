<?php /* Mystique/digitalnature */

function setup_admin_js(){
  wp_enqueue_script('jquery-ui-core', 'None', array('jquery'));
  wp_enqueue_script('mystique-settings', THEME_URL.'/js/_admin/jquery.mystique-settings.js', array('jquery','jquery-ui-core'));
  wp_enqueue_script('codemirror', THEME_URL.'/lib/codemirror/js/codemirror.js');
  ?>
 <?php
}

function get_upload_dir($dir) {
  $uploadpath = wp_upload_dir();
  if ($uploadpath['baseurl']=='') $uploadpath['baseurl'] = get_bloginfo('siteurl').'/wp-content/uploads';
  return $uploadpath[$dir];
}

// load theme preview iframe with ajax (faster access to theme settings)
function getsitepreview() {
  check_ajax_referer("getsitepreview"); ?>
  <iframe id="themepreview" name="themepreview" src="<?php echo get_option('siteurl');  ?>?preview=true" style="width:96%;height:300px;border:8px solid #ddd"></iframe>
  <?php die();
}

function setup_admin_init_js(){
  $font_styles = font_styles();
  $nonce = wp_create_nonce('getsitepreview'); ?>
  <script type="text/javascript">
  /* <![CDATA[ */
  // init
  jQuery(document).ready(function () {

    // set up tabs
    jQuery("#theme-settings-tabs").minitabs({contentClass:'.sections',speed:333});

    // show link select box if navigation is set to show links
    jQuery('#opt_navigation').change(function() {
     jQuery('.opt_links, #page-list, #category-list').hide();
     if(jQuery(this).find('option:selected').attr('value')=='links') jQuery('.opt_links').show();
     if(jQuery(this).find('option:selected').attr('value')=='pages') jQuery('#page-list').show();
     if(jQuery(this).find('option:selected').attr('value')=='categories') jQuery('#category-list').show();
    });
    jQuery("#opt_navigation").change();

    jQuery('#opt_jquery').change(function() {
     jQuery('#opt_lightbox,#opt_ajax_comments,#opt_sharethis').attr("disabled", true);
     if (jQuery(this).is(":checked")){
       jQuery('#opt_lightbox,#opt_ajax_comments,#opt_sharethis').attr("disabled", false);
     }
    });
    jQuery("#opt_jquery").change();

    // not on IE
    if(jQuery.support.leadingWhitespace) jQuery('#layout-settings, #color-scheme').radio2select();

    jQuery.ajax({
		type: "post",url: "admin-ajax.php",data: { action: 'getsitepreview', _ajax_nonce: '<?php echo $nonce; ?>' },
		beforeSend: function() {jQuery("#themepreview-wrap .loading").show("slow");},
		complete: function() { jQuery("#themepreview-wrap .loading").hide("fast");},
		success: function(html){
			jQuery("#themepreview-wrap").html(html);
			jQuery("#themepreview-wrap").show("slow");

            // wait for load because of IE problems...
            jQuery('#themepreview').load(function(){

              // color scheme check
              jQuery('#color-scheme input[type=radio]').change(function() {
               $sel = jQuery(this).attr('value');
               jQuery('body',themepreview.document).append('<style type="text/css">@import "<?php echo THEME_URL; ?>/color-'+$sel+'.css"; </style>');
              });
              jQuery("#color-scheme input[type=radio]:checked").change();

              // font style check
              jQuery('#opt_font_style').change(function() {
               $sel = jQuery(this).find('option:selected').attr('value');
               switch($sel) {
                <?php foreach ($font_styles as $i => $value): ?>
                case '<?php echo $i; ?>':jQuery('*',themepreview.document).css('font-family','<?php echo $font_styles[$i]['code']; ?>');break;
                <?php endforeach; ?>
               }
              });
              jQuery("#opt_font_style").change();

              // bg color check
              jQuery('#colorpicker').farbtastic('#opt_background_color');
              jQuery('#colorpicker').hide();
              jQuery('#opt_background_color').focus(function() { jQuery('#colorpicker').show('fast'); });
              jQuery('#opt_background_color').blur(function() {
                jQuery('#colorpicker').hide('fast');
                bg = jQuery(this).attr('value');
                if(bg!='#000000') {
                  jQuery('<?php if(get_mystique_option('background')==''): ?>body,<?php endif; ?>#page',themepreview.document).css('background-image','none');
                  jQuery('body',themepreview.document).css('background-color',bg);
                } else {
                  jQuery('body',themepreview.document).css('background-color',bg);
                  <?php if(get_mystique_option('background')==''): ?>
                  jQuery('body',themepreview.document).css('background-image','url(<?php echo THEME_URL; ?>/images/bg.png)');
                  jQuery('#page',themepreview.document).css('background-image','url(<?php echo THEME_URL; ?>/images/header.jpg)');
                  <?php endif; ?>
                }
              });

              jQuery('#colorpicker').mousemove(function() {
                bg = jQuery('#opt_background_color').attr('value');
                if(bg!='#000000') {
                  jQuery('<?php if(get_mystique_option('background')==''): ?>body,<?php endif; ?>#page',themepreview.document).css('background-image','none');
                  jQuery('body',themepreview.document).css('background-color',bg);
                } else {
                  jQuery('body',themepreview.document).css('background-color',bg);
                  <?php if(get_mystique_option('background')==''): ?>
                  jQuery('body',themepreview.document).css('background-image','url(<?php echo THEME_URL; ?>/images/bg.png)');
                  jQuery('#page',themepreview.document).css('background-image','url(<?php echo THEME_URL; ?>/images/header.jpg)');
                  <?php endif; ?>
                }
              });

               // 2 col sidebar resize
              jQuery("#slider-range-2col").slider({
                range: 'min',
                animate: true,
                min: 0,
                max: 100,
                value: <?php esc_attr(print_mystique_option('main_width_2col')); ?>,
                slide: function(event, ui) {
                    s1 = 100 - ui.value;

                    jQuery("#opt_main_width_2col").val(ui.value);

                    jQuery('#sidebar',themepreview.document).css({'width': s1+'%'});
                    jQuery('#primary-content',themepreview.document).css({'width': ui.value+'%'});
                }
              });

              // 3 col sidebar resize
              jQuery("#slider-range-3col").slider({
                range: true,
                animate: true,
                min: 0,
                max: 100,
                values: [<?php esc_attr(print_mystique_option('sidebar1_width_3col')); ?>, <?php $s2 = esc_attr(get_mystique_option('sidebar2_width_3col')); print 100-$s2; ?>],
                slide: function(event, ui) {
                    s2 = jQuery('#slider-range-3col').slider('option', 'max') - jQuery("#slider-range-3col").slider("values", 1);
                    mc = 100 - ui.values[0] - s2;

                    jQuery("#opt_sidebar1_width_3col").val(ui.values[0]);
                    jQuery("#opt_sidebar2_width_3col").val(s2);

                    jQuery('#sidebar',themepreview.document).css({'width': ui.values[0]+'%' ,'left': - (mc + s2) +'%'});
                    jQuery('#sidebar2',themepreview.document).css({'width': s2+'%' ,'left': ui.values[0]+'%'});
                    jQuery('#primary-content',themepreview.document).css({'width': mc+'%' ,'left': ui.values[0]+'%'});
                }
              });

              // remove links to other pages from iframe doc.
              jQuery('#themepreview').contents().find("a").each(function() {
                 $href = jQuery(this).attr('href');
                 if ($href && $href.indexOf("#") != 0) {
                   jQuery(this).attr("href","#");
                 }
              });

            });

       	}
	});

  });

  // codemirror
  var editor_footer_content = CodeMirror.fromTextArea('opt_footer_content', {
   height: "200px",
   parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "parsehtmlmixed.js"],
   stylesheet: ["<?php echo THEME_URL; ?>/lib/codemirror/css/xmlcolors.css", "<?php echo THEME_URL; ?>/lib/codemirror/css/jscolors.css", "<?php echo THEME_URL; ?>/lib/codemirror/css/csscolors.css"],
   path: "<?php echo THEME_URL; ?>/lib/codemirror/js/",
   iframeClass: 'iframe_footer_content'
  });

  <?php if (!detectWPMU() || detectWPMUadmin()): ?>
  var editor_head_code = CodeMirror.fromTextArea('opt_head_code', {
   height: "300px",
   parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "parsehtmlmixed.js", "../contrib/php/js/tokenizephp.js", "../contrib/php/js/parsephp.js",
                   "../contrib/php/js/parsephphtmlmixed.js"],
   stylesheet: ["<?php echo THEME_URL; ?>/lib/codemirror/css/xmlcolors.css", "<?php echo THEME_URL; ?>/lib/codemirror/css/jscolors.css", "<?php echo THEME_URL; ?>/lib/codemirror/css/csscolors.css", "<?php echo THEME_URL; ?>/lib/codemirror/contrib/php/css/phpcolors.css"],
   path: "<?php echo THEME_URL; ?>/lib/codemirror/js/",
   iframeClass: 'iframe_head_code'
  });
  <?php endif; ?>

  var editor_user_css = CodeMirror.fromTextArea('opt_user_css', {
   height: "540px",
   parserfile: "parsecss.js",
   stylesheet: "<?php echo THEME_URL; ?>/lib/codemirror/css/csscolors.css",
   path: "<?php echo THEME_URL; ?>/lib/codemirror/js/",
   iframeClass: 'iframe_user_css'
  });

  /* ]]> */
  </script>
 <?php
}

function setup_admin_css($dir) {
  wp_register_style('MystiqueSettings', THEME_URL. '/lib/settings.css');
  wp_enqueue_style('MystiqueSettings');
}

function is_valid_image($image){
  // check mime type
  if(!eregi('image/', $_FILES[$image]['type'])):
   wp_redirect(admin_url('themes.php?page=theme-settings&error=1'));
   exit(0);
  endif;

  // check if valid image
  $imageinfo = getimagesize($_FILES[$image]['tmp_name']);
  if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png' && isset($imageinfo)):
   wp_redirect(admin_url('themes.php?page=theme-settings&error=2'));
   exit(0);
  endif;

  $directory = get_upload_dir('basedir').'/';
  if(!@move_uploaded_file($_FILES[$image]['tmp_name'],$directory.$_FILES[$image]["name"])):
   wp_redirect(admin_url('themes.php?page=theme-settings&error=3'));
   exit(0);
  else:
   return true;
  endif;
}

function update_options() {
  check_admin_referer('theme-settings');
  if (!current_user_can('edit_themes')) wp_die(__('You are not authorised to perform this operation.', 'mystique'));
  $options = get_option('mystique');
  if (isset($_POST['layout'])) $options['layout'] = $_POST['layout'];
  if (isset($_POST['sidebar1_width_3col'])) $options['sidebar1_width_3col'] = $_POST['sidebar1_width_3col'];
  if (isset($_POST['sidebar2_width_3col'])) $options['sidebar2_width_3col'] = $_POST['sidebar2_width_3col'];
  if (isset($_POST['main_width_2col'])) $options['main_width_2col'] = $_POST['main_width_2col'];
  if (isset($_POST['background_color'])) $options['background_color'] = $_POST['background_color'];
  if (isset($_POST['font_style'])) $options['font_style'] = $_POST['font_style'];
  if (isset($_POST['color_scheme'])) $options['color_scheme'] = $_POST['color_scheme'];
  if (isset($_POST['imageless'])) $options['imageless'] = 1; else $options['imageless'] = 0;

  if (!detectWPMU() || detectWPMUadmin()): // this options are only available to the site admin (if wpmu blog)
   if (isset($_POST['footer_content'])): $options['footer_content'] = stripslashes($_POST['footer_content']); endif;// html enabled in footer
   if (isset($_POST['head_code'])): $options['head_code'] = stripslashes($_POST['head_code']); endif;
   for ($i=1; $i<=6; $i++)
    if (isset($_POST['ad_code_'.$i])): $options['ad_code_'.$i] = stripslashes($_POST['ad_code_'.$i]); endif;
  else:
   if (isset($_POST['footer_content'])): $options['footer_content'] = wp_specialchars(stripslashes($_POST['footer_content'])); endif; // no html in the footer
  endif;

  if (isset($_POST['navigation'])) $options['navigation'] = $_POST['navigation'];
  if (isset($_POST['navigation_links'])) $options['navigation_links'] = $_POST['navigation_links'];
  if (isset($_POST['exclude_pages'])) $options['exclude_pages'] = implode(',', $_POST['exclude_pages']);
  if (isset($_POST['exclude_categories'])) $options['exclude_categories'] = implode(',', $_POST['exclude_categories']);
  if (isset($_POST['exclude_home'])) $options['exclude_home'] = 1; else $options['exclude_home'] = 0;
  if (isset($_POST['post_preview'])) $options['post_preview'] = $_POST['post_preview'];

  // wp 2.8+
  if(function_exists('the_post_thumbnail'))
   if (isset($_POST['post_thumb'])) $options['post_thumb'] = $_POST['post_thumb'];

  if (isset($_POST['sharethis'])) $options['sharethis'] = 1; else $options['sharethis'] = 0;
  if (isset($_POST['featured_content'])) $options['featured_content'] = $_POST['featured_content'];
  if (isset($_POST['featured_show_on'])) $options['featured_show_on'] = $_POST['featured_show_on'];
  if (isset($_POST['featured_count'])) $options['featured_count'] =  wp_specialchars(stripslashes($_POST['featured_count']));
  if (isset($_POST['read_more'])) $options['read_more'] = 1; else $options['read_more'] = 0;
  if (isset($_POST['related_posts'])) $options['related_posts'] = 1; else $options['related_posts'] = 0;
  if (isset($_POST['comment_author_country'])) $options['comment_author_country'] = 1; else $options['comment_author_country'] = 0;
  if (isset($_POST['seo'])) $options['seo'] = 1; else $options['seo'] = 0;
  if (isset($_POST['jquery'])) $options['jquery'] = 1; else $options['jquery'] = 0;
  if (isset($_POST['ajax_commentnavi'])) $options['ajax_commentnavi'] = 1; else $options['ajax_commentnavi'] = 0;
  if (isset($_POST['lightbox'])) $options['lightbox'] = 1; else $options['lightbox'] = 0;
  if (isset($_POST['user_css'])) $options['user_css'] = wp_specialchars(stripslashes($_POST['user_css']));
  if (isset($_POST['remove_settings'])) $options['remove_settings'] = 1; else $options['remove_settings'] = 0;

  if (isset($_POST['remove-logo'])):
    $options['logo'] = '';
  else:
    if ($_FILES["file-logo"]["type"]):
      if(is_valid_image('file-logo')): $options['logo'] = get_upload_dir('baseurl'). "/". $_FILES["file-logo"]["name"]; endif;
    endif;
  endif;

  if (isset($_POST['remove-background'])):
    $options['background'] = '';
  else:
    if ($_FILES["file-background"]["type"]):
      if(is_valid_image('file-background')): $options['background'] = get_upload_dir('baseurl')."/".$_FILES["file-background"]["name"]; endif;
    endif;
  endif;

  update_option('mystique', $options);

  // reset?
  if (isset($_POST['reset'])) setup_options();

  wp_redirect(admin_url('themes.php?page=theme-settings&updated=true'));
}



function theme_settings() {
  include(TEMPLATEPATH.'/lib/codehelpers.php');
  $font_styles = font_styles();
  if (current_user_can('edit_themes')): ?>

  <div id="theme-settings" class="wrap clearfix">
   <?php screen_icon(); ?>
   <h2><?php _e('Mystique settings','mystique'); ?></h2>

   <form action="<?php echo admin_url('admin-post.php?action=mystique_update'); ?>" method="post" enctype="multipart/form-data">
   <?php wp_nonce_field('theme-settings'); ?>
   <?php if (isset($_GET['updated'])): ?>
   <div class="updated fade below-h2">
    <p><?php printf(__('Settings saved. %s', 'mystique'),'<a href="' . user_trailingslashit(get_bloginfo('url')) . '">' . __('View site','mystique') . '</a>'); ?></p>
   </div>
   <?php elseif (isset($_GET['error'])):
     $errors  = array(
       1 => _("Please upload a valid image file!","mystique"),
       2 => _("The file you uploaded doesn't seem to be a valid JPEG, PNG or GIF image","mystique"),
       3 => _("The image could not be saved on your server","mystique")
     );

   ?>
   <div class="error fade below-h2">
    <p><?php printf(__('Error: %s', 'mystique'),$errors[$_GET['error']]); ?></p>
   </div>
   <?php endif; ?>

   <!-- tabbed content -->
   <div id="theme-settings-tabs">
    <ul class="tabs clearfix">
     <li class="general"><a href='#tab-1'><?php _e("General","mystique"); ?></a></li>
     <li class="navigation"><a href='#tab-2'><?php _e("Design/colors","mystique"); ?></a></li>
     <li class="bglogo"><a href='#tab-3'><?php _e("Navigation","mystique"); ?></a></li>
     <li class="posts"><a href='#tab-4'><?php _e("Posts","mystique"); ?></a></li>
     <li class="seo"><a href='#tab-5'><?php _e("SEO","mystique"); ?></a></li>
     <?php if (!detectWPMU() || detectWPMUadmin()): ?>
     <li class="ads"><a href='#tab-6'><?php _e("Ads","mystique"); ?></a></li>
     <?php endif; ?>
     <li class="adv"><a href='#tab-7'><?php _e("Advanced","mystique"); ?></a></li>
     <li class="usercss"><a href='#tab-8'><?php _e("User CSS","mystique"); ?></a></li>
    </ul>

    <!-- sections -->
    <div class="sections">

     <div class="section" id="tab-1">
      <table class="form-table" style="width: auto">

       <tr>
        <th scope="row"><p><?php _e("Layout style","mystique") ?><span><?php _e("Use page templates to apply these only to specific pages","mystique"); ?></span></p></th>
        <td id="layout-settings" class="clearfix">
         <div class="layout-box">
          <label for="layout-settings-col-1" class="layout col-1"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-1" value="col-1" <?php if(get_mystique_option('layout')=='col-1') echo 'checked="checked" '; ?>/>
         </div>

         <div class="layout-box">
          <label for="layout-settings-col-2-left" class="layout col-2-left"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-2-left" value="col-2-left" <?php if(get_mystique_option('layout')=='col-2-left') echo 'checked="checked" '; ?>/>
         </div>

         <div class="layout-box">
          <label for="layout-settings-col-2-right" class="layout col-2-right"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-2-right" value="col-2-right" <?php if(get_mystique_option('layout')=='col-2-right') echo 'checked="checked" '; ?>/>
         </div>

         <div class="layout-box">
          <label for="layout-settings-col-3" class="layout col-3"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-3" value="col-3" <?php if(get_mystique_option('layout')=='col-3') echo 'checked="checked" '; ?>/>
         </div>

         <div class="layout-box">
          <label for="layout-settings-col-3-left" class="layout col-3-left"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-3-left" value="col-3-left" <?php if(get_mystique_option('layout')=='col-3-left') echo 'checked="checked" '; ?>/>
         </div>

         <div class="layout-box">
          <label for="layout-settings-col-3-right" class="layout col-3-right"></label>
          <input class="radio" type="radio" name="layout" id="layout-settings-col-3-right" value="col-3-right" <?php if(get_mystique_option('layout')=='col-3-right') echo 'checked="checked" '; ?>/>
         </div>

        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Imageless layout","mystique") ?><span><?php _e("No background images; reduces page load downto just a few KB, with the cost of less graphic details","mystique"); ?></span></p></th>
        <td><input name="imageless" id="opt_imageless" type="checkbox" value="1" <?php checked('1', get_mystique_option('imageless')) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Footer content","mystique"); ?><span><?php   if (!detectWPMU() || detectWPMUadmin()) _e("You can post HTML code",'mystique'); else _e("HTML code is disallowed",'mystique'); ?></span></p></th>
        <td>
         <textarea id="opt_footer_content" rows="4" cols="50" name="footer_content" class="code"><?php echo wp_specialchars(get_mystique_option('footer_content')); ?></textarea><br />

         <?php printf(__("Use the following short codes for convenient adjustments: <br />%s",'mystique'),'<code>[rss]</code> <code>[copyright]</code> <code>[credit]</code> <code>[ad code=#]</code> <code>[wp-link]</code> <code>[theme-link]</code> <code>[login-link]</code> <code>[blog-title]</code> <code>[xhtml]</code> <code>[css]</code> <code>[top]</code>.'); ?>
        </td>
       </tr>

      </table>
     </div>

     <div class="section" id="tab-2">
      <div id="themepreview-wrap"><div class="clearfix"><div class="loading"><?php _e("Loading site preview...","mystique"); ?></div></div></div>
      <table class="form-table" style="width: auto">

       <?php
        $layout = get_mystique_option('layout');
        if ($layout=='col-3'):
       ?>
       <tr>
        <th><p><?php _e('Sidebar(s) vs Main Content ratio','mystique'); ?></p></th>
        <td style="min-width: 600px;">
         <input name="sidebar1_width_3col" id="opt_sidebar1_width_3col" type="hidden" value="<?php esc_attr(print_mystique_option('sidebar1_width_3col')); ?>" />
         <input name="sidebar2_width_3col" id="opt_sidebar2_width_3col" type="hidden" value="<?php esc_attr(print_mystique_option('sidebar2_width_3col')); ?>" />
         <div id="slider-range-3col"></div>

        </td>
       </tr>
       <?php
       elseif($layout=='col-2-right'):
       ?>
       <tr>
        <th><?php _e('Sidebar(s) vs Main Content ratio','mystique'); ?></th>
        <td style="min-width: 600px;">
         <input name="main_width_2col" id="opt_main_width_2col" type="hidden" value="<?php esc_attr(print_mystique_option('main_width_2col')); ?>" />
         <div id="slider-range-2col"></div>

        </td>
       </tr>
       <?php endif; ?>

       <tr>
        <th scope="row"><p><?php _e("Preferred font style","mystique"); ?><span><?php _e("Font priority and their availability","mystique"); ?></span></p></th>
        <td>
         <select name="font_style" id="opt_font_style">
          <?php foreach ($font_styles as $i => $value): ?>
          <option value="<?php echo $i; ?>" <?php if(get_mystique_option('font_style')==$i) echo 'selected="selected" '; ?>><?php echo $font_styles[$i]['desc']; ?></option>
          <?php endforeach; ?>
         </select>
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Color scheme","mystique"); ?></p></th>
        <td id="color-scheme" class="clearfix">

         <?php if(get_mystique_option('imageless')): ?>
         <p class="error"><?php _e("Not available with <em>imageless</em> setting","mystique"); ?></p>
         <?php else: ?>
         <div class="color-box">
          <label for="color-box-green" class="color_scheme green"></label>
          <input class="radio" type="radio" name="color_scheme" id="color-box-green" value="green" <?php if(get_mystique_option('color_scheme')=='green') echo 'checked="checked" '; ?>/>
         </div>

         <div class="color-box">
          <label for="color-box-blue" class="color_scheme blue"></label>
          <input class="radio" type="radio" name="color_scheme" id="color-box-blue" value="blue" <?php if(get_mystique_option('color_scheme')=='blue') echo 'checked="checked" '; ?>/>
         </div>

         <div class="color-box">
          <label for="color-box-red" class="color_scheme red"></label>
          <input class="radio" type="radio" name="color_scheme" id="color-box-red" value="red" <?php if(get_mystique_option('color_scheme')=='red') echo 'checked="checked" '; ?>/>
         </div>

         <div class="color-box">
          <label for="color-box-grey" class="color_scheme grey"></label>
          <input class="radio" type="radio" name="color_scheme" id="color-box-grey" value="grey" <?php if(get_mystique_option('color_scheme')=='grey') echo 'checked="checked" '; ?>/>
         </div>

         <?php endif; ?>
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Custom logo image","mystique"); ?><span><?php _e("Show a logo image instead of text; Upload the graphic file from your computer","mystique"); ?></span></p></th>
        <td>
         <?php if(is_writable(get_upload_dir('basedir'))): ?>
           <input type="file" name="file-logo" id="file-logo" />
           <?php if(get_mystique_option('logo')): ?>
           <div style="background: #000;margin-top:10px;overflow:hidden;"><img src="<?php echo get_mystique_option('logo'); ?>" style="padding:10px;" /></div>
           <button type="submit" class="button" name="remove-logo" value="0"><?php _e("Remove current image","mystique"); ?></button>
           <?php endif; ?>
         <?php else: ?>
         <p class="error" style="padding: 4px;"><?php printf(__("Directory %s doesn't have write permissions - can't upload!","mystique"),'<strong>'.get_upload_dir('basedir').'</strong>'); ?></p><p><?php _e("Check your upload path in Settings/Misc or CHMOD this directory to 755/777.<br />Contact your host if you don't know how","mystique"); ?></p>
         <?php endif; ?>
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Custom background image","mystique"); ?><span><?php _e("Upload a new background/header image to replace the default one","mystique"); ?></span></p></th>
        <td>
         <?php if(is_writable(get_upload_dir('basedir'))): ?>
           <input type="file" name="file-background" id="file-background" />
           <?php if(get_mystique_option('background')): ?>
           <div style="background: #000;margin-top:10px;overflow:hidden;"><img src="<?php echo get_mystique_option('background'); ?>" style="padding:10px;" /></div>
           <button type="submit" class="button" name="remove-background" value="0"><?php _e("Remove current image","mystique"); ?></button>
           <?php endif; ?>
         <?php else: ?>
         <p class="error" style="padding: 4px;"><?php printf(__("Directory %s doesn't have write permissions - can't upload!","mystique"),'<strong>'.get_upload_dir('basedir').'</strong>'); ?></p><p><?php _e("Check your upload path in Settings/Misc or CHMOD this directory to 755/777.<br />Contact your host if you don't know how","mystique"); ?></p>
         <?php endif; ?>
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Background color","mystique"); ?><span><?php _e("Leave Black (#000000) to keep the default background","mystique"); ?></span></p></th>
        <td>
         <input class="text" type="text" size="8" name="background_color" id="opt_background_color" value="<?php esc_attr(print_mystique_option('background_color')); ?>" />
         <div id="colorpicker"></div>
        </td>
       </tr>

      </table>
     </div>

     <div class="section" id="tab-3">
      <table class="form-table" style="width: auto;">
       <tr>
        <th scope="row"><p><?php _e("Top navigation shows","mystique"); ?></p></th>
        <td class="clearfix">
         <select name="navigation" id="opt_navigation" class="alignleft">
          <option value="pages" <?php if(get_mystique_option('navigation')=='pages') echo 'selected="selected" '; ?>><?php _e('Pages', 'mystique'); ?></option>
          <option value="categories" <?php if(get_mystique_option('navigation')=='categories') echo 'selected="selected" '; ?>><?php _e('Categories', 'mystique'); ?></option>
          <option value="links" <?php if(get_mystique_option('navigation')=='links') echo 'selected="selected" '; ?>><?php _e('Links', 'mystique'); ?></option>
         </select>
         <div class="hidden alignleft inline opt_links">
          <?php
           $taxonomy = 'link_category';
           $args ='';
           $terms = get_terms( $taxonomy, $args );
           if ($terms): ?>
             <?php _e("from category","mystique"); ?>
             <select name="navigation_links">
             <?php
             foreach($terms as $term) {
              if ($term->count > 0):
               if(get_mystique_option('navigation_links')==$term->name) $selected = 'selected="selected" '; else $selected='';
               echo '<option value="'.$term->name.'" '.$selected.'>'.$term->name.' ('.$term->count.' links)</option>';
              endif;
             }
             ?>
             </select>
             <?php
           else: ?>
             <p class="error"><?php _e("No links found","mystique"); ?></p>
           <?php
           endif;
          ?>
         </div>
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Exclude from navigation","mystique"); ?><span><?php _e("Check the items you wish to hide from the main menu","mystique"); ?></span></p></th>
        <td>

          <?php if(get_option('show_on_front')<>'page'): ?>
          <ol class="nav-exclude">
            <li><input name="exclude_home" id="opt_exclude_home" type="checkbox" value="1" <?php checked('1', get_mystique_option('exclude_home')) ?> /><label> <a href="<?php echo get_settings('home'); ?>"><?php _e('Home','mystique'); ?></a> </label></li>
          </ol>
          <?php endif; ?>

          <ol class="nav-exclude hidden opt_links">
           <li><?php _e("(Use the private property to hide other links)","mystique"); ?></li>
          </ol>
          <?php

          class WalkerCategorySelect extends Walker {
           var $tree_type = 'category';
           var $db_fields = array ('parent' => 'parent', 'id' => 'term_id'); //TODO: decouple this
           var $selected = array();

           function WalkerCategorySelect($selected) { $this->selected = $selected; }

           function start_lvl(&$output, $depth) {
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ol>\n";
           }

           function end_lvl(&$output, $depth) {
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ol>\n";
           }

           function start_el(&$output, $category, $depth) {
            if ($depth) $indent = str_repeat("\t", $depth); else $indent = '';
            if (in_array($category->term_id, $this->selected)) $checked = ' checked="checked"'; else $checked = '';

            $output .= $indent. '<li>';
            $output .= '<input id="opt-category-'. $category->term_id. '" name="exclude_categories[]" type="checkbox" value="'. $category->term_id. '"'. $checked. ' />';
            $output .= '<label for="opt-category-'. $category->term_id. '"> <a href="' . get_category_link($category->term_id ) . '">'.attribute_escape($category->name).'</a> <span>('.intval($category->count).')</span> </label>';
           }

           function end_el(&$output, $page, $depth) { $output .= "</li>\n"; }
          }

          $categories = &get_categories();
          $nav_categories = explode(',', get_mystique_option('exclude_categories'));
          $walker = new WalkerCategorySelect($nav_categories);
          if (!empty($categories)): ?>
           <ol class="hidden nav-exclude" id="category-list">
           <?php echo $walker->walk($categories, 0, 0, array()); ?>
           </ol>
          <?php endif; ?>


          <?php
          class WalkerPageSelect extends Walker {
           var $tree_type = 'page';
           var $db_fields = array('parent' => 'post_parent', 'id' => 'ID');
           var $selected = array();

           function WalkerPageSelect($selected) { $this->selected = $selected; }

           function start_lvl(&$output, $depth) {
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ol>\n";
           }

           function end_lvl(&$output, $depth) {
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ol>\n";
           }

           function start_el(&$output, $page, $depth) {
            if ($depth) $indent = str_repeat("\t", $depth);	else $indent = '';
            if (in_array($page->ID, $this->selected)) $checked = ' checked="checked"'; else $checked = '';

            $output .= $indent. '<li>';
            $output .= '<input id="opt-page-'. $page->ID. '" name="exclude_pages[]" type="checkbox" value="'.$page->ID.'"'. $checked. ' /> <label for="opt-page-'.$page->ID.'"><a title="'. __('View page','mystique'). '" href="'.get_page_link($page->ID).'">'. apply_filters('the_title', $page->post_title). '</a></label>';
           }
           function end_el(&$output, $page, $depth) { $output .= "</li>\n"; }
          }

          $pages = &get_pages('sort_column=post_parent,menu_order');
          $nav_pages = explode(',', get_mystique_option('exclude_pages'));
          $walker = new WalkerPageSelect($nav_pages);
          if (!empty($pages)): ?>
           <ol class="hidden nav-exclude" id="page-list">
           <?php echo $walker->walk($pages, 0, 0, array()); ?>
           </ol>
          <?php endif; ?>

        </td>
       </tr>
      </table>
     </div>

     <div class="section" id="tab-4">
      <table class="form-table" style="width: auto">

       <tr>
        <th scope="row"><p><?php _e("Post previews","mystique"); ?><span><?php _e("The way posts are displayed on blog homepage and archive pages","mystique"); ?></span></p></th>
        <td>
         <select name="post_preview">
          <option value="full" <?php if(get_mystique_option('post_preview')=='full') echo 'selected="selected" '; ?>><?php _e('Full', 'mystique'); ?></option>
          <option value="excerpt" <?php if(get_mystique_option('post_preview')=='excerpt') echo 'selected="selected" '; ?>><?php _e('Excerpts', 'mystique'); ?></option>
          <option value="title" <?php if(get_mystique_option('post_preview')=='title') echo 'selected="selected" '; ?>><?php _e('Titles only', 'mystique'); ?></option>
         </select>
        </td>
       </tr>

       <?php if(function_exists('the_post_thumbnail')): ?>
       <tr>
        <th scope="row"><p><?php _e("Post thumbnail size","mystique"); ?><span><?php printf(__("Note that this only works for images you upload from now on, older images will be browser-resized. You should use the %s plugin to create missing image sizes","mystique"),'<a href="http://wordpress.org/extend/plugins/regenerate-thumbnails/" target="_blank">Regenerate Thumbnails</a>'); ?></span></p></th>
        <td>
         <select name="post_thumb">
          <?php $wpsize=get_option('thumbnail_size_w').' x '.get_option('thumbnail_size_h'); ?>
          <option value="64x64" <?php if(get_mystique_option('post_thumb')=='64x64') echo 'selected="selected" '; ?>><?php _e('Small: 64 x 64', 'mystique'); ?></option>
          <option value="100x100" <?php if(get_mystique_option('post_thumb')=='100x100') echo 'selected="selected" '; ?>><?php _e('Medium: 100 x 100', 'mystique'); ?></option>
          <option value="<?php echo str_replace (" ","",$wpsize); ?>" <?php if(get_mystique_option('post_thumb')==str_replace (" ","",$wpsize)) echo 'selected="selected" '; ?>><?php printf(__("WP's Media setting: %s", "mystique"),$wpsize); ?></option>
         </select>
        </td>
       </tr>
       <?php endif; ?>

       <tr>
        <th scope="row"><p><?php _e('Enable "Share This Post" feature','mystique'); ?><span><?php _e("Uncheck this if you're using a social bookmaring plugin","mystique"); ?></span></p></th>
        <td><input id="opt_sharethis" name="sharethis" type="checkbox" value="1" <?php checked('1', get_mystique_option('sharethis')) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Featured content source","mystique"); ?><span><?php _e("The last 5 posts from this category will be shown in the featured content area (in random order)","mystique"); ?></span></p></th>
        <td>
         <?php wp_dropdown_categories(array(
          'name' => 'featured_content',
          'selected' => get_mystique_option('featured_content'),
          'show_option_all' => __('- All categories', 'mystique'),
          'hide_empty' => 0,
          'orderby' => 'name',
          'show_count' => 1,
          'hierarchical' => 1)) ?>
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Where to display the featured posts?","mystique"); ?></p></th>
        <td>
         <select name="featured_show_on" id="opt_featured_show_on" class="alignleft">
          <option value="template" <?php if(get_mystique_option('featured_show_on')=='template') echo 'selected="selected" '; ?>><?php _e('On pages that use the Featured Posts template', 'mystique'); ?></option>
          <option value="home" <?php if(get_mystique_option('featured_show_on')=='home') echo 'selected="selected" '; ?>><?php _e('On the home page', 'mystique'); ?></option>
          <option value="pages" <?php if(get_mystique_option('featured_show_on')=='pages') echo 'selected="selected" '; ?>><?php _e('On all pages, including home', 'mystique'); ?></option>
          <option value="posts" <?php if(get_mystique_option('featured_show_on')=='posts') echo 'selected="selected" '; ?>><?php _e('On all posts', 'mystique'); ?></option>
          <option value="all" <?php if(get_mystique_option('featured_show_on')=='all') echo 'selected="selected" '; ?>><?php _e('On all pages and posts', 'mystique'); ?></option>
         </select>
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Maximum number of featured posts","mystique"); ?></p></th>
        <td>
         <input class="text" type="text" size="4" name="featured_count" id="opt_featured_count" value="<?php esc_attr(print_mystique_option('featured_count')); ?>" />
        </td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Expand the &laquo;read more&raquo; button with more content","mystique"); ?><span><?php _e("Uncheck if want the button to link to the single post page","mystique"); ?></span></p></th>
         <td><input name="read_more" type="checkbox" value="1" <?php checked( '1', get_mystique_option('read_more')) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Show related posts next to comments","mystique"); ?></p></th>
        <td><input name="related_posts" type="checkbox" value="1" <?php checked( '1', get_mystique_option('related_posts')) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Show comment author's country flag","mystique"); ?><span><?php _e("May slow down page loading","mystique"); ?></span></p></th>
        <td><input name="comment_author_country" type="checkbox" value="1" <?php checked( '1', get_mystique_option('comment_author_country')) ?> /></td>
       </tr>

      </table>
     </div>

     <div class="section" id="tab-5">
      <table class="form-table" style="width: auto">
       <tr>
        <th scope="row"><p><?php _e("Additional site optimization for search engines","mystique"); ?><span><?php _e("Uncheck if you are using a SEO plugin!","mystique"); ?></span></p></th>
        <td><input name="seo" type="checkbox" id="opt_seo" value="1" <?php checked( '1', get_mystique_option('seo')) ?> /></td>
       </tr>

       <tr>
        <th scope="row"></th>
        <td>
         <h3><?php _e("What does this do?","mystique"); ?></h3>
         <ul style="list-style: disc">
          <li><em><?php printf(__('enables canonical URLs for comments (duplicate content fix)','mystique')); ?></em></li>
          <li><em><?php printf(__('generates unique titles for posts with multiple comment pages (prevents duplicate titles)','mystique')); ?></em></li>
          <li><em><?php printf(__('no keywords are generated because they are <a %s>useless</a>','mystique'),'href="http://googlewebmastercentral.blogspot.com/2009/09/google-does-not-use-keywords-meta-tag.html" target="_blank"'); ?> </em></li>
         </ul>
        </td>
       </tr>

      </table>
     </div>

     <?php if (!detectWPMU() || detectWPMUadmin()): ?>
     <div class="section" id="tab-6">
      <table class="form-table" style="width: auto">

       <tr>
        <th scope="row">
         <p><?php printf(__("Advertisment blocks","mystique"),$i); ?><span><?php printf(__('Use the %s short code to insert these ads into posts, text widgets or footer','mystique'),'<code>[ad]</code>'); ?></span></p><br />
         <p><span><?php _e("Example:","mystique"); ?></span></p>
         <p><code>[ad code=4 align=center]</code></p>
        </th>
        <td class="clearfix">
         <?php for ($i=1; $i<=6; $i++): ?>
         <div class="alignleft">
          <label for="opt_ad_code_<?php echo $i; ?>"><?php printf(__("Ad code #%s:","mystique"),$i); ?></label><br />
          <textarea rows="8" cols="40" name="ad_code_<?php echo $i; ?>" id="opt_ad_code_<?php echo $i; ?>" class="code"><?php echo wp_specialchars(get_mystique_option('ad_code_'.$i)); ?></textarea>
          <br /><br />
         </div>
         <?php endfor; ?>
        </td>
       </tr>


      </table>
     </div>
     <?php endif; ?>

     <div class="section" id="tab-7">
      <table class="form-table" style="width: auto">
       <tr>
        <th scope="row"><p><?php _e("Use jQuery","mystique"); ?><span><?php _e("For testing purposes only. Only uncheck if you know what you're doing!","mystique"); ?></span></p></th>
        <td><input id="opt_jquery" name="jquery" type="checkbox" value="1" <?php checked( '1', get_mystique_option('jquery') ) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Enable AJAX on comment pagination","mystique"); ?><span><?php _e("Navigate trough comment pages without refreshing (faster load, but may be incompatible with some plugins)","mystique"); ?></span></p></th>
        <td><input id="opt_ajax_commentnavi" name="ajax_commentnavi" type="checkbox" value="1" <?php checked( '1', get_mystique_option('ajax_commentnavi') ) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Enable theme built-in lightbox on all image links","mystique"); ?><span><?php _e("Uncheck if you prefer a lightbox plugin","mystique"); ?></span></p></th>
        <td><input id="opt_lightbox" name="lightbox" type="checkbox" value="1" <?php checked( '1', get_mystique_option('lightbox') ) ?> /></td>
       </tr>

       <tr>
        <th scope="row"><p><?php _e("Remove Mystique settings from the database after theme switch","mystique"); ?><span><?php _e("Uncheck this if you plan to keep Mystique as your default theme!","mystique"); ?></span></p></th>      <td>
         <input name="remove_settings" id="opt_remove_settings" type="checkbox" value="1" <?php checked( '1', get_mystique_option('remove_settings') ) ?> />
        </td>
       </tr>
      </table>

      <?php if (!detectWPMU() || detectWPMUadmin()): // disable this option for wpmu users, other than site admin (they shouldn't be allowed to execute php code) ?>
      <hr />
      <table class="form-table" style="width: auto">

       <tr>
        <th scope="row">
         <p><?php _e("Document head code","mystique"); ?><span><?php printf(__("PHP code to insert in the %s section of the XHTML document. Useful if you have plugins that require you to add stuff here, adding Analytics code etc.","mystique"),'<code>&lt;head&gt;</code>'); ?></span></p>
         <p><span class="warning"><?php _e("To output HTML only, close the PHP tag first.","mystique"); ?></span></p>
         <br />
         <p><span><?php _e("Examples and helpers:","mystique") ?></span></p>
         <br />
          <?php foreach($predefined_head_scripts as $id=>$code): ?>
          <button class="button" onclick="jQuery('#opt_head_code').appendVal('<?php echo '\n\n'.'<?php \/\/ '.$id.'?>\n'.jsspecialchars($code); ?>'); $current = editor_head_code.getCode(); editor_head_code.setCode($current+'<?php echo '\n\n'.'\<\?php \/\/ '.$id.'\n'.jsspecialchars($code).'\n\?\>'; ?>');" type="button"><?php echo $id; ?></button><br />
          <?php  endforeach; ?>
       </th>
        <td>
         <textarea rows="16" cols="60" name="head_code" id="opt_head_code" class="code"><?php echo wp_specialchars(get_mystique_option('head_code')); ?></textarea>
        </td>
       </tr>

      </table>
      <?php endif; ?>
     </div>

     <div class="section" id="tab-8">
      <table class="form-table" style="width: auto">

       <tr>
        <th scope="row"><p><?php _e("Modify anything related to design using CSS code","mystique"); ?><span><?php printf(__("Check %s to see existing theme classes and properties","mystique"),'<a href="'.get_bloginfo('stylesheet_url').'">style.css</a>'); ?></span><br /><span class="warning"><?php _e("Avoid modifying theme files directly; use this option instead to preserve your changes after update","mystique"); ?></span></p>
         <br />
         <p><span><?php _e("Examples and helpers:","mystique") ?></span></p>
         <br />
         <?php
          foreach($predefined_css as $id=>$code): ?>
          <button class="button" onclick="jQuery('#opt_user_css').appendVal('<?php echo '\n\n'.'/* '.$id.' */\n'.jsspecialchars($code); ?>'); $current = editor_user_css.getCode(); editor_user_css.setCode($current+'<?php echo '\n\n'.'/* '.$id.' */\n'.jsspecialchars($code); ?>');" type="button"><?php echo $id; ?></button><br />
          <?php endforeach; ?>
        </th>
        <td valign="top">
         <textarea rows="30" cols="80" name="user_css" id="opt_user_css" class="code"><?php echo wp_specialchars(get_mystique_option('user_css')); ?></textarea>
        </td>
       </tr>

      </table>
     </div>


    </div>
    <!-- /sections -->

   </div>
   <!-- /tabbed content -->
   <p><input type="submit" class="button-primary" name="submit" value="<?php _e("Save Changes","mystique"); ?>" /><input type="submit" class="button-primary" name="reset" value="<?php _e("Reset to Defaults","mystique"); ?>" /></p>
   </form>
   <hr />
   <div class="support">
    <form class="alignleft" action="https://www.paypal.com/cgi-bin/webscr" method="post"> <input name="cmd" type="hidden" value="_s-xclick" /> <input name="hosted_button_id" type="hidden" value="4605915" /> <input alt="Donate" name="submit" src="<?php echo THEME_URL; ?>/images/_admin/paypal.gif" type="image" /></form>
     <a href="http://digitalnature.ro/projects/mystique">Mystique</a> is a free theme developed by <a href="http://digitalnature.ro">digitalnature</a>.<br />You can support this project by donating.
   </div>


  </div>
  <?php endif;
}

function add_menu() {
  $page = add_theme_page(
    __('Mystique settings','mystique'),
    __('Mystique settings','mystique'),
       'edit_themes',
       'theme-settings',
       'theme_settings'
  );
  add_action("admin_print_scripts-$page", 'setup_admin_js');
  add_action("admin_footer-$page", 'setup_admin_init_js');
  add_action("admin_print_styles-$page", 'setup_admin_css');
}

add_action('wp_ajax_getsitepreview', 'getsitepreview');
add_action('admin_menu', 'add_menu');
add_action('admin_post_mystique_update', 'update_options');
if(get_mystique_option('remove_settings')) add_action('switch_theme', 'remove_options');

?>