<?php /* Mystique/digitalnature */

 $seo = get_mystique_option('seo');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php //language_attributes('xhtml'); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php if ($seo && get_query_var('cpage')) printf(__('Page %s &laquo;','mystique'),get_query_var('cpage')); ?> <?php bloginfo('name'); ?></title>

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" />
<meta name="designer" content="digitalnature.ro" />
<?php if ($seo) meta_description();?>

<?php
wp_head();
$user_head = get_mystique_option('head_code');
if ($user_head):
 $user_head = trim($user_head);
 $user_head = preg_replace('/\<\?php/', '', $user_head, 1); // remove "<?php"
 eval($user_head);
endif; ?>
</head>
<body class="<?php mystique_body_class() ?>">
 <div id="page">

  <div class="shadow-left page-content header-wrapper">
   <div class="shadow-right">

    <div id="header" class="bubbleTrigger">
      <div id="site-title">

       <?php $tag = (is_home() || is_front_page()) ? 'h1' : 'div'; ?>
       <<?php echo $tag; ?> id="logo">
       <?php if(get_mystique_option('logo')): // logo image? ?>
         <a href="<?php bloginfo('url'); ?>/"><img src="<?php echo get_mystique_option('logo'); ?>" title="<?php bloginfo('name');  ?>" alt="<?php bloginfo('name');  ?>" /></a>
       <?php else: // text ?>
         <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a>
       <?php endif; ?>
       </<?php echo $tag; ?>>

       <?php if(get_bloginfo('description')): ?><p class="headline"><?php bloginfo('description'); ?></p><?php endif; ?>

      </div>

      <a href="<?php bloginfo('rss2_url'); ?>" class="nav-extra rss" title="<?php _e("RSS Feeds","mystique"); ?>"><span><?php _e("RSS Feeds","mystique"); ?></span></a>
      <?php
       //$twituser = get_mystique_option('twitter_id');
       // if(is_active_widget('TwitterWidget'))
       $twitinfo =  get_option('mystique-twitter');
       $twituser = $twitinfo['last_twitter_id'];

       if ($twituser): ?>
      <a href="http://www.twitter.com/<?php echo $twituser; ?>" class="nav-extra twitter" title="<?php _e("Follow me on Twitter!","mystique"); ?>"><span><?php _e("Follow me on Twitter!","mystique"); ?></span></a>
      <?php endif; ?>

      <ul id="navigation">

         <?php
          $navtype = get_mystique_option('navigation');
          if((get_option('show_on_front')<>'page') && get_mystique_option('exclude_home')<>'1'):
           if(is_home() && !is_paged()): ?>
            <li class="current_page_item" id="nav-home"><a class="home fadeThis" href="<?php echo get_settings('home'); ?>" title="<?php _e('You are Home','mystique'); ?>"><span class="title"><?php _e('Home','mystique'); ?></span><span class="pointer"></span></a></li>
           <?php else: ?>
            <li id="nav-home"><a class="home fadeThis" href="<?php echo get_option('home'); ?>" title="<?php _e('Click for Home','mystique'); ?>"><span class="title"><?php _e('Home','mystique'); ?></span><span class="pointer"></span></a></li>
          <?php
           endif;
          endif; ?>
         <?php
           if($navtype=='categories'):
            echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a>@i', '<li$1><a class="fadeThis"$2><span class="title">$3</span><span class="pointer"></span></a>', wp_list_categories('show_count=0&echo=0&title_li=&exclude='.get_mystique_option('exclude_categories')));
           elseif($navtype=='links'):

            $links = get_bookmarks(array(
            'orderby'        => 'name',
            'order'          => 'ASC',
            'limit'          => -1,
            'category'       => null,
            'category_name'  => get_mystique_option('navigation_links'),
            'hide_invisible' => true,
            'show_updated'   => 0,
            'include'        => null,
            'search'         => '.'));

            foreach ($links as $link):
             if($link->link_target) $target = ' target="'.wp_specialchars($link->link_target).'"'; else $target = '';
             if($link->link_rel) $rel = ' rel="'.wp_specialchars($link->link_rel).'"'; else $rel = '';
             if($link->link_description) $title = ' title="'.wp_specialchars($link->link_description).'"'; else $title = '';
             echo '<li><a class="fadeThis" href="'.$link->link_url.'"'.$target.$rel.$title.'><span class="title">'.$link->link_name.'</span></a><li>';
            endforeach;
           else:
             echo preg_replace('@\<li([^>]*)>\<a([^>]*)>(.*?)\<\/a>@i', '<li$1><a class="fadeThis"$2><span class="title">$3</span><span class="pointer"></span></a>', wp_list_pages('echo=0&orderby=name&title_li=&exclude='.get_mystique_option('exclude_pages')));
           endif;
          ?>

      </ul>
    </div>

   </div>
  </div>

  <!-- left+right bottom shadow -->
  <div class="shadow-left page-content main-wrapper">
   <div class="shadow-right">

     <?php
        $featured = get_mystique_option('featured_show_on');
        if(is_page_template('page-featured.php') || (is_home() && $featured=='home') || ((is_home() || is_page()) && $featured=='pages') || (is_single() && $featured=='posts') || $featured=='all') include(TEMPLATEPATH . '/featured-posts.php'); ?>
