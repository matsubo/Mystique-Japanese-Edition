<?php
 /* Mystique/digitalnature */
 get_header();
?>

  <!-- main content: primary + sidebar(s) -->
  <div id="main">
   <div id="main-inside" class="clearfix">
    <!-- primary content -->
    <div id="primary-content">
      <?php
       if (have_posts()):
        while (have_posts()):
         the_post();
         include(TEMPLATEPATH . '/post.php');
        endwhile; ?> 
       <div class="page-navigation clearfix">
        <?php if(function_exists('wp_pagenavi')): wp_pagenavi(); else: ?>
        <div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries','mystique')) ?></div>
        <div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;','mystique')) ?></div>
        <?php endif; ?>
       </div>
      <?php else: ?>
       <h1 class="title error"><?php _e("No posts found","mystique"); ?></h1>
       <p><?php _e("Sorry, but you are looking for something that isn't here.","mystique"); ?></p>

      <?php endif; ?>

    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
  <!-- /main content -->

<?php get_footer(); ?>