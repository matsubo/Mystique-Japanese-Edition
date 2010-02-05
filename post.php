<?php /* Mystique/digitalnature */

 $post_preview = get_mystique_option('post_preview');
 $category = get_the_category();
 $category_name = $category[0]->cat_name;
 if(!empty($category_name)) $category_link = '<a href="'.get_category_link($category[0]->cat_ID).'">'.strip_string(34,$category_name).'</a>';
 else $category_link = "[...]";
 if(function_exists('the_post_thumbnail'))
  if (has_post_thumbnail()) $post_thumb = true; else $post_thumb = false;
?>
<!-- post -->
<div id="post-<?php the_ID(); ?>" <?php post_class('clearfix preview-'.$post_preview); ?>>

 <?php if($post_thumb): ?>
   <a class="post-thumb alignleft" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
 <?php endif; ?>

 <?php $title_url = get_post_meta($post->ID, 'title_url', true); ?>
 <h2 class="title"><a href="<?php if($title_url) echo $title_url; else the_permalink(); ?>" rel="bookmark" title="<?php _e('Permanent Link:','mystique'); echo ' '; the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

 <div class="post-date">
   <p class="day"><?php the_time(__('M jS','mystique')); ?></p>
 </div>

 <div class="post-info clearfix <?php if ($post_thumb) echo 'with-thumbs' ?>">
  <p class="author alignleft"><?php printf(__('Posted by %1$s in %2$s','mystique'),'<a href="'. get_author_posts_url(get_the_author_ID()) .'" title="'. sprintf(__("Posts by %s","mystique"), attribute_escape(get_the_author())).' ">'. get_the_author() .'</a>',$category_link);
    ?>
  <?php if(function_exists('the_views')): ?><span class="postviews">| <?php the_views(); ?></span><?php endif; ?>
  <?php edit_post_link(__('Edit','mystique'),' | '); ?>
  </p>
  <?php
  global $id, $comment;
  $number = get_comments_number($id);
  if (comments_open() || $comments): ?>
  <p class="comments alignright"><a href="<?php the_permalink() ?>#comments" class="<?php if ($number<1) echo "no"; ?> comments"><?php comments_number(__('No comments', 'mystique'), __('1 comment', 'mystique'), __('% comments', 'mystique')); ?></a></p>
  <?php endif; ?>
 </div>

 <?php if($post_preview<>'title'): ?>
   <div class="post-content clearfix">
    <?php
    if(is_search()):
     $content = get_the_excerpt();
     $keys= explode(" ",$searchquery);
     $content = preg_replace('/('.implode('|', escape_string_for_regex($keys)) .')/iu','<span class="altText highlight">\0</span>',$content);
     echo $content;
    else:
     if($post_preview=='excerpt'): the_excerpt(); else: the_content(__('More &gt;', 'mystique')); endif;
    endif; ?>
   </div>
   <?php if(function_exists('the_ratings')) the_ratings(); ?>

   <?php
    $post_tags = get_the_tags();
    if ($post_tags): ?>
    <div class="post-tags">
    <?php
      $tags = array();
      $i = 0;
      foreach($post_tags as $tag):
       $tags[$i] .=  '<a href="'.get_tag_link($tag->term_id).'" rel="tag" title="'.sprintf(__('%1$s (%2$s topics)'),$tag->name,$tag->count).'">'.$tag->name.'</a>';
       $i++;
      endforeach;
      echo implode(', ',$tags); ?>
    </div>
    <?php endif; ?>

 <?php endif; ?>
</div>
<!-- /post -->