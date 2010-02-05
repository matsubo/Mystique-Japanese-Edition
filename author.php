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
       // global $wp_query;
       // $curauth = $wp_query->get_queried_object();

       if(isset($_GET['author_name'])): $curauth = get_userdatabylogin($author_name); else : $curauth = get_userdata(intval($author)); endif;
       ?>

       <h1 class="title"><?php echo $curauth->display_name; ?></h1>

       <div class="clearfix">
       <div class="alignleft"><?php echo get_avatar($curauth->user_email, '128', $avatar); ?></div>
       <div>
        <p>
        <?php
         if($curauth->user_description<>''): echo $curauth->user_description;
         else: _e("This user hasn't shared any biographical information","mystique");
         endif;
        ?>
        </p>
         <?php
          if(($curauth->user_url<>'http://') && ($curauth->user_url<>'')) echo '<p class="im www">'.__('Homepage:','mystique').' <a href="'.$curauth->user_url.'">'.$curauth->user_url.'</a></p>';
          if($curauth->yim<>'') echo '<p class="im yahoo">'.__('Yahoo Messenger:','mystique').' <a href="ymsgr:sendIM?'.$curauth->yim.'">'.$curauth->yim.'</a></p>';
          if($curauth->jabber<>'') echo '<p class="im gtalk">'.__('Jabber/GTalk:','mystique').' <a href="gtalk:chat?jid='.$curauth->jabber.'">'.$curauth->jabber.'</a></p>';
          if($curauth->aim<>'') echo '<p class="im aim">'.__('AIM:','mystique').' <a href="aim:goIM?screenname='.$curauth->aim.'">'.$curauth->aim.'</a></p>';
         ?>
       </div>
       </div>
       <div class="divider"></div>
       <br />
       <?php if (have_posts()): ?>
        <h3 class="title"><?php printf(__('Posts by %s', 'mystique'), $curauth->display_name); ?></h3>
        <div class="divider"></div>  
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
        <p class="error"><?php _e('No posts found by this author.','mystique'); ?></p>
       <?php endif; ?>


    </div>
    <!-- /primary content -->

    <?php get_sidebar(); ?>

   </div>
  </div>
  <!-- /main content -->

<?php get_footer(); ?>