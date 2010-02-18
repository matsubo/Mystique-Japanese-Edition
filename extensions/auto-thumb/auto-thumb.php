<?php /* Mystique/digitalnature */

function mystique_autothumb_admin(){ ?>
   <tr>
    <th scope="row"><p><?php _e("Auto generate thumbnails","mystique"); ?><span><?php _e("Create thumbnail from the 1st image found in a post, if no thumbnail is set","mystique"); ?></span></p></th>
     <td><input name="post_thumb_auto" type="checkbox" class="checkbox" value="1" <?php checked( '1', get_mystique_option('post_thumb_auto')) ?> /></td>
   </tr>
<?php
}

function mystique_autothumb_settings($defaults){
  $defaults['post_thumb_auto'] = 1;
  return $defaults;
}

function mystique_post_autothumb($size){
  global $post, $id;
  if(get_mystique_option('post_thumb_auto')): // if not post thumbnail is set and auto generate is enabled, try to get the 1st image
    $image = get_first_image($post);
    $image = get_userfile_serverpath($image);  // if we're running wpmu - get the real paths
    if ($image):
      if($size == 'featured-thumbnail') $thumbnailsize = array(300, 240); else $thumbnailsize = explode('x',get_mystique_option('post_thumb_size'));
      echo '<a class="'.$size.' alignleft" href="'.get_permalink($id).'"><img src="'.THEME_URL.'/extensions/auto-thumb/timthumb.php?src='.$image.'&amp;w='.$thumbnailsize[0].'&amp;h='.$thumbnailsize[1].'&amp;zc=1&amp;q=100" alt="'.get_the_title($id).'" title="'.get_the_title($id).'" /></a>';
    endif;
  endif;
}


add_filter('mystique_default_settings','mystique_autothumb_settings');

add_action('mystique_admin_content','mystique_autothumb_admin');
add_action('mystique_post_thumbnail','mystique_post_autothumb', 10, 1);

?>