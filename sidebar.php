<?php /* Mystique/digitalnature */

$layout = get_mystique_option('layout');
if((!isLayoutTemplate() && ($layout!='col-1')) || (isLayoutTemplate() && (!is_page_template('page-1col.php')))):
  if ((is_page_template('page-3col.php') || is_page_template('page-3col-left.php') || is_page_template('page-3col-right.php')) || (!isLayoutTemplate()  && ($layout=='col-3' || $layout=='col-3-left' || $layout=='col-3-right'))): include(TEMPLATEPATH . '/sidebar2.php'); endif;
?>

<div id="sidebar">
 <ul class="sidebar-blocks">
    <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar()): ?>

    <?php if(!is_search()): ?>
    <li class="block">
     <?php search_form(); ?>
    </li>
    <?php endif; ?>

    <?php if(function_exists('the_widget')):  // only in wp 2.8+

    the_widget('SidebarTabsWidget', array('orderby' => 'name', 'postcount' => true, 'showcategories' => true, 'showtags' => true, 'showarchives' => true, 'showpopular' => true, 'showrecentcomm' => true), array('widget_id'=>'instance-sidebartabswidget','before_widget' => '<li class="block"><div class="block-sidebar_tabs">','after_widget' => '</div></li>','before_title' => '<h3 class="title"><span>','after_title' => '</span></h3><div class="block-div"></div><div class="block-div-arrow"></div>'));

    the_widget('TwitterWidget', array('title'=>__('My latest tweets','mystique'), 'twituser'=>'wordpress', 'twitcount'=>'4'), array('widget_id'=>'instance-twitterwidget','before_widget' => '<li class="block"><div class="block-twitter">','after_widget' => '</div></li>','before_title' => '<h3 class="title"><span>','after_title' => '</span></h3><div class="block-div"></div><div class="block-div-arrow"></div>'));

    the_widget('LoginWidget', array(), array('widget_id'=>'instance-loginwidget','before_widget' => '<li class="block"><div class="block-login">','after_widget' => '</div></li>','before_title' => '<h3 class="title"><span>','after_title' => '</span></h3><div class="block-div"></div><div class="block-div-arrow"></div>'));

    endif; ?>

    <li class="block">
     <div class="block-bookmarks">
      <h3 class="title"><span><?php _e("Blogroll","mystique"); ?></span></h3>
      <div class="block-div"></div><div class="block-div-arrow"></div>
      <ul>
       <?php
        $links = get_bookmarks();
        foreach ($links as $link):
          if($link->link_target) $target = ' target="'.wp_specialchars($link->link_target).'"'; else $target = '';
          if($link->link_rel) $rel = ' rel="'.wp_specialchars($link->link_rel).'"'; else $rel = '';
          if($link->link_description) $title = ' title="'.wp_specialchars($link->link_description).'"'; else $title = '';
          echo '<li><a href="'.$link->link_url.'"'.$target.$rel.$title.'>'.$link->link_name.'</a></li>';
        endforeach;
       ?>
      </ul>
     </div>
    </li>

    <?php endif; ?>

 </ul>
</div>
<?php endif; ?>