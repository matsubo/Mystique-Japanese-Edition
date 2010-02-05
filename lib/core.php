<?php /* Mystique/digitalnature */

function getTinyUrl($url) {
    $response = wp_remote_retrieve_body(wp_remote_get('http://tinyurl.com/api-create.php?url='.$url));     // replaces curl (thanks Joseph!)
    return $response;
}

function excerpt_more($excerpt) {
  global $wp_version;
  $link = ' <a href="'.get_permalink().'" class="more-link">'.__('More &gt;', 'mystique').'</a>';
  return ($wp_version <= 2.8) ? str_replace('[...]', $link, $excerpt) : $link;
}

function meta_redirect(){
  if(is_single() || is_page()):
   global $post;
   $field = get_post_meta($post->ID, 'redirect', true);
   if($field) wp_redirect(clean_url($field), 301);
  endif;
}


function readmorelink(){
 if($_POST['redirect-more-link'] == 1):
   query_posts("p=" . $_POST['postid']);
   if(have_posts()):
     the_post();
     $spanId = "more-" . $_POST['postid'];
     $content = get_the_content();

     // have to apply any filters; 'get_the_content' does not do this (copied from 'the_content')
     $content = apply_filters('the_content', $content);
     $content = str_replace(']]>', ']]&gt;', $content);

     // grab only the stuff after 'more'
     $debris = explode("<span id=\"".$spanId."\"></span>", $content);
     echo $debris[1];
     exit;
   endif;

 endif;
}



function search_form(){ ?>
<!-- search form -->
<div class="search-form">
  <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/" class="clearfix">
    <fieldset>
      <div id="searchfield">
       <input type="text" name="s" id="searchbox" class="text clearField" value="<?php _e("Search","mystique"); ?>" />
      </div>
      <input type="submit" value="" class="submit" />
     </fieldset>
 </form>
</div>
<!-- /search form -->
<?php
}            

function timeSince($older_date, $newer_date = false){
  $chunks = array(
   'year'	=> 60 * 60 * 24 * 365,	// 31,536,000 seconds
   'month'	=> 60 * 60 * 24 * 30,	// 2,592,000 seconds
   'week'	=> 60 * 60 * 24 * 7,	// 604,800 seconds
   'day'	=> 60 * 60 * 24,	    // 86,400 seconds
   'hour'	=> 60 * 60,		        // 3600 seconds
   'minute'	=> 60,				    // 60 seconds
   'second'	=> 1				    // 1 second
  );

 $newer_date = ($newer_date == false) ? (time()+(60*60*get_settings("gmt_offset"))) : $newer_date;
 $since = $newer_date - $older_date;

 foreach ($chunks as $key => $seconds)
  if (($count = floor($since / $seconds)) != 0) break;

 $messages = array(
   'year'	=> _n('about %s year ago', 'about %s years ago', $count, 'mystique'),
   'month'	=> _n('about %s month ago', 'about %s months ago', $count, 'mystique'),
   'week'	=> _n('about %s week ago', 'about %s weeks ago', $count, 'mystique'),
   'day'	=> _n('about %s day ago', 'about %s days ago', $count, 'mystique'),
   'hour'	=> _n('about %s hour ago', 'about %s hours ago', $count, 'mystique'),
   'minute'	=> _n('about %s minute ago', 'about %s minutes ago', $count, 'mystique'),
   'second'	=> _n('about %s second ago', 'about %s seconds ago', $count, 'mystique'),
  );
  return sprintf($messages[$key],$count);
}


function detectWPMU(){
  return function_exists('is_site_admin');
}

function detectWPMUadmin(){
  if(detectWPMU()) return is_site_admin();
}


function isLayoutTemplate(){
  if(is_page_template('page-1col.php') || is_page_template('page-1col.php') || is_page_template('page-2col-right.php') || is_page_template('page-2col-left.php') || is_page_template('page-3col.php') ||is_page_template('page-3col.php') || is_page_template('page-3col-left.php') || is_page_template('page-3col-right.php')) return true;
  return false;
}

// from sandbox theme
// Generates semantic classes for BODY element
function mystique_body_class($print = true) {
  global $wp_query, $current_user, $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

  // Generic semantic classes for what type of content is displayed
  is_front_page()  ? $c[] = 'home'       : null; // For the front page, if set
  is_home()        ? $c[] = 'blog'       : null; // For the blog posts page, if set
  is_archive()     ? $c[] = 'archive'    : null;
  is_date()        ? $c[] = 'date'       : null;
  is_search()      ? $c[] = 'search'     : null;
  is_attachment()  ? $c[] = 'attachment' : null;
  is_404()         ? $c[] = 'not-found'  : null; // CSS does not allow a digit as first character

  // Special classes for BODY element when a single post
  if (is_single()):
    $postID = $wp_query->post->ID;
    the_post();

    // Adds 'single' class and class with the post ID
    $c[] = 'single-post postid-' . $postID;

    // Adds category classes for each category on single posts
    if ($cats = get_the_category()) foreach ($cats as $cat) $c[] = 'category-'.$cat->slug;

    // Adds tag classes for each tags on single posts
    if ($tags = get_the_tags()) foreach ($tags as $tag) $c[] = 'tag-'.$tag->slug;

    // Adds author class for the post author
    $c[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author_login()));
    rewind_posts();

  elseif (is_author()):	// Author name classes for BODY on author archives
    $author = $wp_query->get_queried_object();
    $c[] = 'author';
    $c[] = 'author-' . $author->user_nicename;

  elseif (is_category()):	// Category name classes for BODY on category archvies
    $cat = $wp_query->get_queried_object();
    $c[] = 'category';
    $c[] = 'category-' . $cat->slug;

  elseif (is_tag()):	// Tag name classes for BODY on tag archives
    $tags = $wp_query->get_queried_object();
    $c[] = 'tag';
    $c[] = 'tag-' . $tags->slug;

  elseif (is_page()): 	// Page author for BODY on 'pages'
    $pageID = $wp_query->post->ID;
    $page_children = wp_list_pages("child_of=$pageID&echo=0");
    the_post();
    $c[] = 'single-page pageid-' . $pageID;
    $c[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));
    // Checks to see if the page has children and/or is a child page; props to Adam
    if ($page_children) $c[] = 'page-parent';
    if ($wp_query->post->post_parent) $c[] = 'page-child';
    rewind_posts();

  elseif (is_search()): 	// Search classes for results or no results
    the_post();
    if (have_posts()) $c[] = 'search-results'; else $c[] = 'search-no-results';
    rewind_posts();
  endif;

  $layout = get_mystique_option('layout');

  // layout
  if (is_page_template('page-1col.php') || (!isLayoutTemplate() && $layout=="col-1")) $c[] = 'col-1';
  if (is_page_template('page-2col-left.php') || (!isLayoutTemplate() && $layout=='col-2-left'))  $c[] = 'col-2-left';
  if (is_page_template('page-2col-right.php') || (!isLayoutTemplate() && $layout=='col-2-right'))  $c[] = 'col-2-right';
  if (is_page_template('page-3col.php') || (!isLayoutTemplate() && $layout=='col-3'))  $c[] = 'col-3';
  if (is_page_template('page-3col-left.php') || (!isLayoutTemplate() && $layout=='col-3-left'))  $c[] = 'col-3-left';
  if (is_page_template('page-3col-right.php') || (!isLayoutTemplate() && $layout=='col-3-right'))  $c[] = 'col-3-right';

  // For when a visitor is logged in while browsing
  if ($current_user->ID) $c[] = 'loggedin';

    // detect browser
  if($is_lynx) $browser = 'lynx';
  elseif($is_gecko) $browser = 'gecko';
  elseif($is_opera) $browser = 'opera';
  elseif($is_NS4) $browser = 'ns4';
  elseif($is_safari) $browser = 'safari';
  elseif($is_chrome) $browser = 'chrome';
  elseif($is_IE) $browser = 'ie';
  else $browser = 'unknown';
  if($is_iphone) $browser .= '-iphone';
  $c[] = 'browser-'.$browser;

  // Separates classes with a single space, collates classes for BODY
  $c = join(' ', apply_filters('body_class', $c)); // Available filter: body_class

  // And tada!
  return $print ? print($c) : $c;
}


function strip_string($intLength = 0, $strText = "") {
 $strText = strip_tags($strText);
 if(strlen($strText) > $intLength):
   $strText = mb_substr($strText,0,$intLength);
   $strText = mb_substr($strText,0,strrpos($strText,' '));
    return $strText.'...';
 else:
   return $strText;
 endif;
}


function escape_string_for_regex($str){
 //All regex special chars (according to arkani at iol dot pt below):
 // \ ^ . $ | ( ) [ ]
 // * + ? { } ,
 $patterns = array('/\//', '/\^/', '/\./', '/\$/', '/\|/',
                   '/\(/', '/\)/', '/\[/', '/\]/', '/\*/', '/\+/',
                   '/\?/', '/\{/', '/\}/', '/\,/');
 $replace = array('\/', '\^', '\.', '\$', '\|', '\(', '\)',
                  '\[', '\]', '\*', '\+', '\?', '\{', '\}', '\,');
 return preg_replace($patterns,$replace, $str);
}


function jsspecialchars( $string = '') {
 $string = preg_replace("/\r*\n/","\\n",$string);
 $string = preg_replace("/\//","\\\/",$string);
 $string = preg_replace("/\"/","&quot;",$string); // &quot; instead of \\\\" (xhtml)
 $string = preg_replace("/'/","\\\'",$string);
 return $string;
}


function get_first_image() {
 global $post, $posts;
 $first_img = '';
 $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
 $first_img = $matches [1][0];
 return $first_img;
}


function canonical_for_comments() {
 global $cpage, $post;
 if ($cpage > 1) echo '<link rel="canonical" href="'.get_permalink($post->ID).'" />'.PHP_EOL;
}


// check if sidebar has widgets
function is_sidebar_active($index = 1) {
  global $wp_registered_sidebars;

  if (is_int($index)): $index = "sidebar-$index";
  else :
  	$index = sanitize_title($index);
  	foreach ((array) $wp_registered_sidebars as $key => $value):
    	if ( sanitize_title($value['name']) == $index):
		 $index = $key;
	     break;
		endif;
	endforeach;
  endif;
  $sidebars_widgets = wp_get_sidebars_widgets();
  if (empty($wp_registered_sidebars[$index]) || !array_key_exists($index, $sidebars_widgets) || !is_array($sidebars_widgets[$index]) || empty($sidebars_widgets[$index]))
    return false;
  else
  	return true;
}


function shareThis(){
   $content = apply_filters('the_content',get_the_excerpt());
   ?>
   <!-- socialize -->
   <div class="shareThis clearfix">
    <a href="#" class="share"><?php _e("Share this post!","mystique"); ?></a>
    <ul class="bubble">
     <li><a href="http://twitter.com/home?status=<?php the_title(); ?>+-+<?php echo getTinyUrl(get_permalink()); ?>" class="twitter" title="Tweet This!"><span>Twitter</span></a></li>
     <li><a href="http://digg.com/submit?phase=2&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" class="digg" title="Digg this!"><span>Digg</span></a></li>
     <li><a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&amp;t=<?php the_title(); ?>" class="facebook" title="Share this on Facebook"><span>Facebook</span></a></li>
     <li><a href="http://del.icio.us/post?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" class="delicious" title="Share this on del.icio.us"><span>Delicious</span></a></li>
     <li><a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" class="stumbleupon" title="Stumbled upon something good? Share it on StumbleUpon"><span>StumbleUpon</span></a></li>
     <li><a href="http://www.google.com/bookmarks/mark?op=add&amp;bkmk=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" class="google" title="Add this to Google Bookmarks"><span>Google Bookmarks</span></a></li>
     <li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>&amp;summary=<?php echo strip_tags($content); ?>&amp;source=<?php bloginfo('name'); ?>" class="linkedin" title="Share this on Linkedin"><span>LinkedIn</span></a></li>
     <li><a href="http://buzz.yahoo.com/buzz?targetUrl=<?php the_permalink(); ?>&amp;headline=<?php the_title(); ?>&amp;summary=<?php echo strip_tags($content); ?>" class="yahoo" title="Buzz up!"><span>Yahoo Bookmarks</span></a></li>
     <li><a href="http://technorati.com/faves?add=<?php the_permalink(); ?>" class="technorati" title="Share this on Technorati"><span>Technorati Favorites</span></a></li>
    </ul>
   </div>
   <!-- /socialize -->
  <?php
}


function curPageURL() {
  $pageURL = 'http';
  if ($_SERVER["HTTPS"] == "on") $pageURL .= "s";
  $pageURL .= "://";
  if ($_SERVER["SERVER_PORT"] != "80") $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; else $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
  return $pageURL;
}

function meta_description() {
  global $wp_query;
  if (is_home()): $description = get_bloginfo('description');
  elseif (is_singular()):
    if(function_exists('get_metadata')): $description = get_metadata('post', $wp_query->post->ID, 'Description', true); endif;
    if (empty( $description) && is_front_page()):
      $description = get_bloginfo('description');
    elseif (empty($description)):
      $description = get_post_field('post_excerpt', $wp_query->post->ID);
    endif;
  elseif (is_archive()):
   if (is_author()):
     $description = get_the_author_meta('description', get_query_var('author'));
   elseif (is_category() || is_tag() || is_tax()):
     $description = term_description('', get_query_var('taxonomy'));
   endif;
  endif;

  if (!empty($description)) echo '<meta name="description" content="'.str_replace(array("\r", "\n", "\t"),'',esc_attr(strip_tags($description))).'" />'."\n";
}


function get_flag($ip) {
  require_once(TEMPLATEPATH.'/lib/ip2c/ip2c.php');
  if (isset($GLOBALS['ip2c'])):
   global $ip2c;
  else:
   $ip2c = new ip2country(TEMPLATEPATH.'/lib/ip2c/ip-to-country.bin');
   $GLOBALS['ip2c'] = $ip2c;
  endif;

  $country = $ip2c->get_country($ip);
  if ($country):
   $code = strtolower($country['id2']);
   $name = ucwords(strtolower($country['name']));
   return array('code' => $code, 'name' => $name);
  else:
   return false;
  endif;
}


function mystique_comment_class() {
  global $post, $comment, $flag;
  $classes = get_comment_class();
  if (get_option('show_avatars')) $classes[] = 'withAvatars';

  if($flag['code']) $classes[]= 'country-'.$flag['code'];

  if ($comment->user_id > 0):
    $user = new WP_User($comment->user_id);
    if (is_array($user->roles))
     foreach ($user->roles as $role) $classes[] = "role-{$role}";
    $classes[] = 'user-'.sanitize_html_class($user->user_nicename, $user->user_id);
  else:
    $classes[] = 'reader name-'.get_comment_author();
  endif;

  $class = join(' ', $classes);
  echo apply_filters("comment_class", $class);
}


// list pings
function list_pings($comment, $args, $depth) {
 $GLOBALS['comment'] = $comment;
 ?>
 <li class="ping" id="comment-<?php comment_ID(); ?>"><a class="websnapr" href="<?php comment_author_url();?>" rel="nofollow"><?php comment_author(); ?></a>
<?php
} // </li> is added by WP

// list comments
function list_comments($comment, $args, $depth) {
 $GLOBALS['comment'] = $comment;
 global $commentcount;

 if(get_mystique_option('comment_author_country')):
  $flag = get_flag(get_comment_author_IP());
  $GLOBALS['flag'] = $flag;
 endif;

 if(!$commentcount) $commentcount = 0; ?>

  <!-- comment entry -->
  <li class="<?php mystique_comment_class(); ?>" id="comment-<?php comment_ID() ?>">
    <div class="comment-head <?php mystique_comment_class(); ?>">

      <?php if (function_exists('get_avatar') && get_option('show_avatars')): ?><div class="avatar-box"><?php print get_avatar($comment, 48); ?></div><?php endif; ?>
      <div class="author">
       <?php
        if (get_comment_author_url()) $authorlink='<a class="comment-author" id="comment-author-'.get_comment_ID().'" href="'.get_comment_author_url().'" rel="nofollow">'.get_comment_author().'</a>';
        else $authorlink='<b class="comment-author" id="comment-author-'.get_comment_ID().'">'.get_comment_author().'</b>';

        if ($flag['code']) $authorlink .= '<abbr class="flag '.$flag['code'].'" title="'.$flag['name'].'">&nbsp;</abbr>';
        ?><span class="by"><?php printf(__('%1$s written by %2$s', 'mystique'), '<a class="comment-id" href="#comment-'.get_comment_ID().'">#'.++$commentcount.'</a>', $authorlink); ?> </span>
        <br />
        <?php echo timeSince(abs(strtotime($comment->comment_date . " GMT"))); ?>
      </div>

      <div class="controls bubble">
        <?php if (get_mystique_option('jquery') && (comments_open())): ?>
           <?php if(get_option('thread_comments')):?>
           <a class="reply" id="reply-to-<?php echo get_comment_ID(); ?>" href="<?php echo esc_url(add_query_arg('replytocom', $comment->comment_ID)); ?>#respond"><?php _e("Reply","mystique"); ?></a>
           <a class="quote" title="<?php _e('Quote','mystique'); ?>" href="#respond"><?php _e('Quote','mystique'); ?></a>
           <?php endif; ?>
        <?php endif; ?>
        <?php edit_comment_link('Edit','',''); ?>
      </div>
    </div>
    <div class="comment-body clearfix" id="comment-body-<?php comment_ID() ?>">
      <?php if ($comment->comment_approved == '0'): ?><p class="error"><?php _e('Your comment is awaiting moderation.','mystique'); ?></p><?php endif; ?>
      <div class="comment-text"><?php comment_text(); ?></div>
      <a id="comment-reply-<?php comment_ID() ?>"></a>
    </div>
<?php
}  // </li> is added by WP
?>
