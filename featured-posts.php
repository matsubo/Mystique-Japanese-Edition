<?php /* Mystique/digitalnature */

 $category = get_mystique_option('featured_content');
 $count =  get_mystique_option('featured_count');
?>
<div id="featured-content"<?php if($count>1): ?> class="withSlider"<?php endif; ?>>
 <!-- block container -->
 <div class="slide-container">
  <ul class="slides">

  <?php
   $args = 'posts_per_page='.$count.'&orderby=date&order=DESC';
   if($category) $args .= '&cat='.$category;

   $backup = $post;
   $query = new WP_Query($args);

   $count = 1;

   while ($query->have_posts()):
     $query->the_post();
     $do_not_duplicate[] = $post->ID;

     if(function_exists('the_post_thumbnail'))
      if (has_post_thumbnail()) $post_thumb = true; else $post_thumb = false;
     ?>
     <!-- slide (100%) -->
     <li class="slide slide-<?php echo $count; ?> featured-content">
      <div class="slide-content clearfix">
       <div class="details clearfix">
       <?php if($post_thumb): ?>
         <a class="post-thumb alignleft" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
        <?php else:
         $image = get_first_image();
         if ($image): ?>
         <a class="post-thumb alignleft" href="<?php the_permalink(); ?>"><img height="150" src="<?php echo $image; ?>" alt="<?php the_title(); ?>" /></a>
         <?php endif; endif; ?>

         <h3><?php echo strip_string(50,get_the_title()); ?></h3>
        <div class="summary"><?php echo strip_string(300,get_the_excerpt()); ?></div>
       </div>
       <a href="<?php the_permalink(); ?>" rel="bookmark" class="readmore"><?php _e("Read more","mystique"); ?></a>
      </div>
     </li>
     <!-- /slide -->
     <?php
     $count++;
   endwhile;

   $post = $backup;
   wp_reset_query();

   ?>
  </ul>
 </div>
 <!-- /block container -->
</div>
