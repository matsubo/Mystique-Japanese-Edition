<!-- 2nd sidebar -->
<div id="sidebar2">
 <ul class="sidebar-blocks">
  <?php
   if (function_exists('dynamic_sidebar') && dynamic_sidebar('sidebar-2')) : else : ?>
    <li class="block">
     <div class="block-info">
      <p><?php _e("2nd sidebar is active. Add widgets here from the Dashboard","mystique"); ?></p>
     </div>
    </li>
  <?php endif; ?>
 </ul>
</div>
<!-- /2nd sidebar -->