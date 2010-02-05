<?php /* Mystique/digitalnature */

// php snippets
$predefined_head_scripts = array(
 __("Insert Ad slot #1 in every post and page","mystique") => <<<PHP
function insert_post_ads(\$content){
  \$content .= do_shortcode("[ad code=1 align=center]");
  return \$content;
}
if(is_single() || is_page()){
  add_filter('the_content', 'insert_post_ads',4);
  add_filter('get_the_excerpt', create_function('','remove_filter("the_content", "insert_post_ads",6); return;'),5);
}
PHP
,
 __("Insert Ad slot #2 at every 5 comments","mystique") => <<<PHP
function insert_comment_ads(\$content){
  global \$commentcount;
  if(!(\$commentcount % 5)) \$content .= do_shortcode("[ad code=2]");
  return \$content;
}
add_filter('comment_text', 'insert_comment_ads');
PHP
,
 __("Replace blog description text with Ad #4","mystique") => <<<PHP
function insert_header_ads(\$output,\$show){
  if(\$show=='description')
    echo do_shortcode("[ad code=4]");
  else return \$output;
}
add_filter('bloginfo', 'insert_header_ads',1,2);
PHP
);

$themeurl = THEME_URL;

// user css code, some examples...
$predefined_css = array(
 __("Black navigation style","mystique") => <<<CSS
ul#navigation{background-position:left -464px;background-color:#000;}
ul#navigation li{background-position:right bottom;}
ul#navigation li a{text-shadow:none;color:#b3b3b3;}

ul#navigation li.active a,
ul#navigation li.current_page_item a,ul#navigation li.current_page_parent a,ul#navigation li.current_page_ancestor a,
ul#navigation li.current-cat a,ul#navigation li.current-cat-parent a,ul#navigation li.current-cat-ancestor a{background-position:left -164px;color:#fff;}

ul#navigation li a:hover,ul#navigation li:hover a,ul#navigation li a.fadeThis span.hover{background-color:rgba(255,255,255,0.1);}
ul#navigation li:hover li a{background-color:transparent;}
ul#navigation li li a:hover,ul#navigation li li a.fadeThis span.hover{background-color:#ed1e24 !important;}

ul#navigation li.active a span.pointer,
ul#navigation li.current_page_item a span.pointer,ul#navigation li.current_page_parent a span.pointer,ul#navigation li.current_page_ancestor a span.pointer,
ul#navigation li.current-cat a span.pointer,ul#navigation li.current-cat-parent a span.pointer,ul#navigation li.current-cat-ancestor a span.pointer
{background-position:center bottom;}

ul#navigation ul{background-color:rgba(0,0,0,0.66);border-color:#000;}

ul#navigation li.active ul,
ul#navigation li.current_page_item ul,ul#navigation li.current_page_parent ul,ul#navigation li.current_page_ancestor ul,
ul#navigation li.current-cat ul,ul#navigation li.current-cat-parent ul,ul#navigation li.current-cat-ancestor ul
{background-color:#656565;border-color:#656565;}

ul#navigation ul ul{border-top:1px solid rgba(255,255,255,0.5);}
CSS
,
 __("Alternate header image","mystique") => <<<CSS
#page{background-image:url({$themeurl}/images/_alt/header2.jpg);}
CSS
,
 __("Hide RSS button","mystique") => <<<CSS
#header a.rss {display:none;}
CSS
,
 __("Hide Twitter button","mystique") => <<<CSS
#header a.twitter {display:none;}
CSS
,
 __("Set a fluid page width","mystique") => <<<CSS
.page-content {max-width:95%;}
CSS
,
 __("Decrease site title text size","mystique") => <<<CSS
#site-title #logo {font-size:300%;}
CSS
,
 __("Hide post information bar","mystique") => <<<CSS
.post-date, .post-info {display:none;}
CSS
,
 __("Change footer background color to light gray","mystique") => <<<CSS
#footer{background:#ddd;}
#footer-blocks .leftFade,#footer-blocks .rightFade{background-image:none;}
CSS
,
 __("Dark background footer","mystique") => <<<CSS
#footer{background:#666;color:#ddd;}
#footer a{color:#fff;}
#footer-blocks .leftFade,#footer-blocks .rightFade{background-image:none;}
CSS
,
 __("Join navigation with main content","mystique") => <<<CSS
.header-wrapper .shadow-right{padding-bottom:0;}
CSS
,
 __("Hide navigation","mystique") => <<<CSS
.shadow-left.header-wrapper,.header-wrapper .shadow-right{padding-bottom:0;background:none;}
#navigation, #header a.rss{display:none;}
#header{height:150px;}
CSS
,
 __("Remove styling from tables","mystique") => <<<CSS
table td,table th,table tr.even td,table tr:hover td{border:0;background-color:transparent;}
CSS
,
 __("Text-Justify post and page content","mystique") => <<<CSS
.post-content{text-align:justify;}
CSS
,
 __("Fixed custom background image","mystique") => <<<CSS
body{background-attachment:fixed;}
CSS
);

?>