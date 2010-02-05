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
          the_post(); ?>
          <div id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
            <?php
             if(function_exists('the_post_thumbnail'))
              if (has_post_thumbnail()) $post_thumb = true; else $post_thumb = false;
             ?>
            <?php if (!get_post_meta($post->ID, 'hide_title', true) && !is_page_template('featured-content.php')): ?><h1 class="title"><?php the_title(); ?></h1><?php endif; ?>
            <div class="post-content clearfix">
             <?php if($post_thumb): ?>
             <div class="post-thumb alignleft"><?php the_post_thumbnail(); ?></div>
             <?php endif; ?>

             <?php the_content(__('More &gt;', 'mystique')); ?>
             <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
             <?php if(function_exists('wp_print')): ?><div class="alignright"><?php print_link(); ?></div><?php endif; ?>
             <?php edit_post_link(__('Edit this entry', 'mystique')); ?>
            </div>
          </div>
       <?php
         endwhile;
        endif;
       ?>

       <?php comments_template(); ?>

    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
  <!-- /main content -->

<?php get_footer(); ?>