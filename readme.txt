

Project page:
http://digitalnature.ro/projects/mystique

Licensed under GPL
http://www.opensource.org/licenses/gpl-license.php


CREDITS:
- digitalnature - http://digitalnature.ro (design and coding)
- Dkret3 theme by Joern Kretzschmar - http://diekretzschmars.de
- Sandbox theme - http://www.plaintxt.org/themes/sandbox
- Tarski theme - http://tarskitheme.com
- Hybrid theme - http://themehybrid.com
- jQuery - http://jquery.com
- jQuery Flickr plug-in by Daniel MacDonald - www.projectatomic.com
- loopedSlider by Nathan Searles - http://nathansearles.com/loopedslider
- clearfield by Stijn Van Minnebruggen - http://www.donotfold.be
- Fancybox by Janis Skarnelis - http://fancybox.net
- Recent comments by George Notaras - http://www.g-loaded.eu/2006/01/15/simple-recent-comments-wordpress-plugin/
- Smashing Magazine - http://smashingmagazine.com
- Wordpress - http://wordpress.org
- French translation by Sebastien Revollon
- German translation by Pascal Herbert
- Chinese translation by Awu - http://www.awuit.cn
- Polish translation by 96th http://96th.co.uk
- Swedish translation by Magnus Jonasson - http://www.magnusjonasson.com
- jQuery Color picker plugin by eyecon.ro
- Spanish translation by Facundo Jordan - http://nogardtech.com.ar
- Italian translation by Alessandro Fiorotto, updated by Piersandro Guerrera - http://www.theparrot.it
- Turkish translation by Ömer Taylan Tugut http://www.tuguts.com ; old translation by Erdinç Gür - http://www.turkonline.org 
- Arabic translation by http://www.anas-b.com
- member only content shortcodes - http://justintadlock.com/archives/2009/05/09/using-shortcodes-to-show-members-only-content
- google pie chart shortcode - http://blue-anvil.com/archives/8-fun-useful-shortcode-functions-for-wordpress
- Russian translation by CyberAP - http://anna-sophia-robb.com
- Brazilian Portuguese translation by Atilio Baroni Filho
- CodeMirror javascript library - http://marijn.haverbeke.nl/codemirror
- Czech translation by Lukáš Stredula - http://blog.thatrocked.com
- "Read more" ajax based on "Read More Right Here" plugin by William King - http://www.wooliet.com
- Danish translation by Søren Eskilsen - http://soeren.benzon.org
- IP2Country by Omry Yadan - http://firestats.cc/wiki/ip2c
- IP2C database by Webhosting.info
- Finnish translation by Tomi R. - http://www.rantom.fi
- Dutch translation translation by FrankB
- Hungarian translation by GabeszMeister - http://blog.gabesz-meister.hu

REQUIREMENTS:
- PHP 5+
- Wordpress or Wordpress MU, 2.8+ required

TO DO: (a list of things to remember I need to do in the future)
- add hide delay to shareThis
- add Facebook connect, twitter and OpenID login to comment box
- featured content: check if images from posts are links and show the link source image in the lightbox

CHANGE LOG:
17,02,2009   1.99 - too many changes to mention (rewritten most of the back-end)

18,01,2009   1.72 - timeSince fix (really :)

18,01,2009   1.71 - minor bug-fix to theme settings
                  - translation updates

16,01,2009   1.7  - added ajax requests option in comments (for comment page change)
                  - removed websnapr from comment author links, useless
                  - removed slide effect on reply
                  - removed youtube shortcode, useless
                  - fixed twitter bug on php 4.x (thanks Mike)
                  - added "redirect" custom field (eg. add external links in the menu)
                  - seo improvements
                  - added country flag option to comments
                  - fixed (I think) a wp bug that caused the 1st comment in a page to show incorrect time
                  - added editor styles
                  - added [register_form] shortcode
                  - added back ajax on read more

             1.62 - bug fixes in theme settings and search page
             1.61 - removed small glitch in comments

28,12,2009: v1.6  - added more restrictions for wpmu blogs: non-admin users are not allowed to post html in footer, add advertisments or add html trough shortcodes
                  - changed comment date/time with timeSince
                  - changes to black nav style (to see them remove the old css & add the new one)
                  - changes to read more links
                  - search page form improvements (don't show more than one form on the page, highlight search terms)
                  - added the [widget] shortcode. more info @ http://wordpress.digitalnature.ro/mystique/shortcodes/arbitrary-widgets-inside-posts
                  - optimized [query] shortcode
                  - improvements and minor js bug-fixes in theme settings
                  - changes to featured content options
                  - replaced wp's reply js with mine, jquery based
                  - changed default thumbnail size and added a option for this
                  - replaced "short post" view with the default one
                  - support for wp-print
                  - made twitter widget show cached tweets if twitter request ends in a error (not more than a 6 hour cache)

19,12,2009: v1.53 - user/default background image improvements
                  - added file upload security checks in theme settings (very important for public wpmu blogs so the users don't upload malicious scripts)
                  - added active menu styles for category navigation type

18,12,2009: v1.5  - twitter widget now loads all data trough ajax to avoid slow page loading (jquery must be enabled)
                  - fancybox plugin compatibility
                  - made theme preview iframe load with ajax so it doesn't affect the theme settings loading time
                  - fixed caption center alignment issue
                  - made twitter widget compatible with php<=4.x
                  - updated post thumbnail functions for the latest beta changes
                  - updated translations and fixed a small theme settings bug
                  - support for 2.9 post thumbnails
                  - added checkboxes for pages/categories in exclude nav. option
                  - added CodeMirror for theme settings code related textareas
                  - restricted <head> php code setting for wpmu users
                  - fixed a XSS vulnerability in search


...


3, 10.2009: First release (1.0)
