<?php
 /* Mystique/digitalnature */
 get_header();
?>

<!-- main content: primary + sidebar(s) -->
<div id="shadow-left" class="page-content">
 <div id="shadow-right">
  <div id="main">
   <div id="main-inside" class="clearfix">
    <!-- primary content -->
    <div id="primary-content">
      <?php
       $searchquery = wp_specialchars(get_search_query(),1);
       if(($searchquery) && ($searchquery!=__('Search',"mystique"))): ?>

       <?php if (have_posts()) : ?>
   	<h1 class="title"><?php printf(__("Search Results for %s","mystique"),'<span class="altText">'.$searchquery.'</span>'); ?></h1>

        <div class="page-navigation clearfix">
         <?php if(function_exists('wp_pagenavi')): wp_pagenavi(); else: ?>
         <div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries','mystique')) ?></div>
         <div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;','mystique')) ?></div>
         <?php endif; ?>
        </div>

        <?php
         while (have_posts()):
          the_post();
          include(TEMPLATEPATH . '/post.php');
         endwhile;
         ?>

        <div class="page-navigation clearfix">
         <?php if(function_exists('wp_pagenavi')): wp_pagenavi(); else: ?>
         <div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries','mystique')) ?></div>
         <div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;','mystique')) ?></div>
         <?php endif; ?>
        </div>
       <?php else : ?>

  	    <h1 class="title"><span class="error"><?php _e('Nothing found.','mystique'); ?></span> <?php _e('Try a different search?','mystique'); ?></h1>
        <?php search_form(); ?>
       <?php endif; ?>
     <?php else: ?>
  	    <h1 class="title"><?php _e('What do you wish to search for?','mystique'); ?></h1>
        <?php search_form(); ?>
     <?php endif; ?>

    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
 </div>
</div>
<!-- /main content -->

<?php get_footer(); ?>