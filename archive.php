<?php
 /* Mystique/digitalnature */
 get_header();
?>

  <!-- main content: primary + sidebar(s) -->
  <div id="main">
   <div id="main-inside" class="clearfix">

    <!-- primary content -->
    <div id="primary-content">

      <?php if (have_posts()) : ?>

       <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
       <?php /* If this is a category archive */ if (is_category()) { ?>
       <h1 class="title archive-category"><?php echo single_cat_title(); ?></h1>
       <?php /* If this is a tag archive */ } elseif(is_tag()) { ?>
       <h1 class="title archive-tag"><?php printf( __('Posts tagged %s', 'mystique'), '<span class="altText">'.single_cat_title('', false).'</span>'); ?></h1>
       <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
       <h1 class="title archive-day"><?php  printf(__('Archive for %s', 'mystique'), '<span class="altText">'.get_the_time(get_option('date_format')).'</span>');  ?></h1>
       <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
       <h1 class="title archive-month"><?php  printf(__('Archive for %s', 'mystique'), '<span class="altText">'.get_the_time(__('F, Y','mystique')).'</span>');  ?></h1>
       <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
       <h1 class="title archive-year"><?php  printf(__('Archive for year %s', 'mystique'), '<span class="altText">'.get_the_time(__('Y','mystique')).'</span>');  ?></h1>
       <?php /* If this is an author archive (should never show because the author template exists... */ } elseif (is_author()) { ?>
       <h1 class="title archive-author"><?php _e('Author archive','mystique'); ?></h1>
       <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
       <h1 class="title"><?php _e('Blog Archives','mystique'); ?></h1>
       <?php } ?>
       <div class="divider"></div>

       <?php
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

       <?php else:

       if ( is_category() ) { // If this is a category archive
       ?> <h2> <?php printf(__("Sorry, but there aren't any posts in the %s category yet.", "mystique"),single_cat_title('',false)); ?> </h2> <?php
       } else if ( is_date() ) { // If this is a date archive
       ?> <h2> <?php _e("Sorry, but there aren't any posts within this date."); ?> </h2> <?php
       } else if ( is_author() ) { // If this is a category archive
       $userdata = get_userdatabylogin(get_query_var('author_name'));
       ?> <h2> <?php printf(__("Sorry, but there aren't any posts by %s yet.", "mystique"),$userdata->display_name); ?> </h2> <?php
       } else {
       ?> <h2> <?php _e('No posts found.'); ?> </h2> <?php
       }
       get_search_form();
       endif;
       ?>

    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
  <!-- /main content -->

<?php get_footer(); ?>