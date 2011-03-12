<?php

/**
 * Maintenance page preprocessing
 */
function zeropoint_preprocess_maintenance_page(&$vars) {
  if (class_exists('Database', FALSE)) {
    zeropoint_preprocess_html($vars);  // set html vars (html.tpl.php is in maintenance-page.tpl.php)
    zeropoint_preprocess_page($vars);  // set page vars
  }
}


/**
 * HTML preprocessing
 */
function zeropoint_preprocess_html(&$vars) {
  global $theme_key, $user;

// Add to array of helpful body classes
  $vars['classes_array'][] = ($vars['is_admin']) ? 'admin' : 'not-admin';                                     // Page user is admin
  if (isset($vars['node'])) {
    $vars['classes_array'][] = ($vars['node']) ? 'full-node' : '';                                            // Full node
    $vars['classes_array'][] = (($vars['node']->type == 'forum') || (arg(0) == 'forum')) ? 'forum' : '';      // Forum page
  }
  else {
    $vars['classes_array'][] = (arg(0) == 'forum') ? 'forum' : '';                                            // Forum page
  }
  if (module_exists('panels') && function_exists('panels_get_current_page_display')) {                        // Panels page
    $vars['classes_array'][] = (panels_get_current_page_display()) ? 'panels' : '';
  }


// Add unique classes for each page and website section
  if (!$vars['is_front']) {
    $path = drupal_get_path_alias($_GET['q']);
    list($section, ) = explode('/', $path, 2);
    $vars['classes_array'][] = ('section-' . $section);
    $vars['classes_array'][] = ('page-' . $path);
  }


// Build array of additional body classes and retrieve custom theme settings
$layoutwidth = theme_get_setting('layout-width');
  if ($layoutwidth == '0'){ 
    $vars['classes_array'][] = 'layout-jello';
  }
  if ($layoutwidth == '1'){ 
    $vars['classes_array'][] = 'layout-fluid';
  }
  if ($layoutwidth == '2'){ 
    $vars['classes_array'][] = 'layout-fixed';
  }
$sidebarslayout = theme_get_setting('sidebarslayout');
  if ($sidebarslayout == '0'){ 
	  $vars['classes_array'][] = 'var';
  }
  if ($sidebarslayout == '1'){ 
	  $vars['classes_array'][] = 'fix';
  }
  if ($sidebarslayout == '2'){ 
	  $vars['classes_array'][] = 'var1';
  }
  if ($sidebarslayout == '3'){ 
	  $vars['classes_array'][] = 'fix1';
  }
  if ($sidebarslayout == '4'){ 
	  $vars['classes_array'][] = 'eq';
  }
$blockicons = theme_get_setting('blockicons');
  if ($blockicons == '1'){ 
    $vars['classes_array'][] = 'bicons32';
  }
  if ($blockicons == '2'){ 
    $vars['classes_array'][] = 'bicons48';
  }
$pageicons = theme_get_setting('pageicons');
  if ($pageicons == '1'){ 
    $vars['classes_array'][] = 'picons';
  }
$headerimg = theme_get_setting('headerimg');
  if ($headerimg == '1'){ 
    $vars['classes_array'][] = 'himg';
  }

  $vars['classes_array'] = array_filter($vars['classes_array']);                // Remove empty elements


// Add language and site ID classes
  $vars['classes_array'][] = ($vars['language']->language) ? 'lg-'. $vars['language']->language : '';        // Page has lang-x

$siteid = theme_get_setting('siteid');
  $vars['classes_array'][] = $siteid;


// Add a unique page id
  $vars['body_id'] = 'pid-' . strtolower(preg_replace('/[^a-zA-Z0-9-]+/', '-', drupal_get_path_alias($_GET['q'])));


// Set IE6 & IE7 stylesheets
  drupal_add_css(path_to_theme() . '/css/ie6.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 6', '!IE' => FALSE), 'preprocess' => FALSE));
  drupal_add_css(path_to_theme() . '/css/ie7.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'IE 7', '!IE' => FALSE), 'preprocess' => FALSE));


// Get css styles 
  function get_zeropoint_style() {
    $style = theme_get_setting('style');
    return $style;
  }

  drupal_add_css(drupal_get_path('theme','zeropoint').'/css/style-zero.css', array('group' => CSS_THEME, 'every_page' => TRUE));
  drupal_add_css(drupal_get_path('theme','zeropoint') . '/css/' . get_zeropoint_style() . '.css', array('group' => CSS_THEME, 'every_page' => TRUE));
  drupal_add_css(drupal_get_path('theme','zeropoint').'/_custom/custom-style.css', array('group' => CSS_THEME, 'every_page' => TRUE));

$roundcorners = theme_get_setting('roundcorners');
  if ($roundcorners == '1'){ 
  drupal_add_css(drupal_get_path('theme','zeropoint').'/css/round.css', array('group' => CSS_THEME, 'every_page' => TRUE));
}

  drupal_add_css(drupal_get_path('theme','zeropoint').'/css/print.css', array('group' => CSS_THEME, 'media' => 'print', 'every_page' => TRUE));
}


/**
 * Page preprocessing
 */
function zeropoint_preprocess_page(&$vars) {
  // Hide breadcrumb on all pages
  if (theme_get_setting('breadcrumb_display') == 0) {
    $vars['breadcrumb'] = '';
  }
}


/**
 * Block preprocessing
 */
function zeropoint_preprocess_block(&$vars) {
  global $theme_info, $user;
// Add regions with themed blocks (e.g., left, right) to $themed_regions array and retrieve custom theme settings
$themedblocks = theme_get_setting('themedblocks');
  if ($themedblocks == '0'){ 
    $themed_regions = array('sidebar_first','sidebar_second');
  }
  if ($themedblocks == '1'){ 
    $themed_regions = array('sidebar_first','sidebar_second','user1','user2','user3','user4','user5','user6','user7','user8');
  }
  if (is_array($themed_regions))
    $vars['themed_block'] = (in_array($vars['block']->region, $themed_regions)) ? TRUE : FALSE;
  else $vars['themed_block'] = FALSE;
}


/**
 * Node preprocessing
 */
function zeropoint_preprocess_node(&$vars) {
  
// Build array of handy node classes
  $vars['classes_array'][] = $vars['zebra'];                                     // Node is odd or even
  $vars['classes_array'][] = (!$vars['node']->status) ? 'node-unpublished' : ''; // Node is unpublished
  $vars['classes_array'][] = ($vars['sticky']) ? 'sticky' : '';                  // Node is sticky
  $vars['classes_array'][] = ($vars['teaser']) ? 'teaser' : 'full-node';         // Node is teaser or full-node
  $vars['classes_array'][] = 'node-type-'. $vars['node']->type;                  // Node is type-x, e.g., node-type-page
}


/**
 * Comment preprocessing
 */
function zeropoint_preprocess_comment(&$vars) {
  static $comment_odd = TRUE;                                                    // Comment is odd or even
  
// Build array of handy comment classes
  $vars['classes_array'][] = $comment_odd ? 'odd' : 'even';
  $comment_odd = !$comment_odd;
}


/**
 * Views preprocessing
 * Add view type class (e.g., node, teaser, list, table)
 */
function zeropoint_preprocess_views_view(&$vars) {
  $vars['css_name'] = $vars['css_name'] .' view-style-'. drupal_clean_css_identifier(strtolower($vars['view']->plugin_name));
}


/**
 * Search result preprocessing
 */
function zeropoint_preprocess_search_result(&$vars) {
  static $search_zebra = 'even';

  $search_zebra = ($search_zebra == 'even') ? 'odd' : 'even';
  $vars['search_zebra'] = $search_zebra;
  $result = $vars['result'];
  $vars['url'] = check_url($result['link']);
  $vars['title'] = check_plain($result['title']);

// Check for snippet existence. User search does not include snippets.
  $vars['snippet'] = '';
  if (isset($result['snippet']) && theme_get_setting('search_snippet')) {
    $vars['snippet'] = $result['snippet'];
  }

  $info = array();
  if (!empty($result['type']) && theme_get_setting('search_info_type')) {
    $info['type'] = check_plain($result['type']);
  }
  if (!empty($result['user']) && theme_get_setting('search_info_user')) {
    $info['user'] = $result['user'];
  }
  if (!empty($result['date']) && theme_get_setting('search_info_date')) {
    $info['date'] = format_date($result['date'], 'small');
  }
  if (isset($result['extra']) && is_array($result['extra'])) {
    // $info = array_merge($info, $result['extra']);  Drupal bug?  [extra] array not keyed with 'comment' & 'upload'
    if (!empty($result['extra'][0]) && theme_get_setting('search_info_comment')) {
      $info['comment'] = $result['extra'][0];
    }
    if (!empty($result['extra'][1]) && theme_get_setting('search_info_upload')) {
      $info['upload'] = $result['extra'][1];
    }
  }

// Provide separated and grouped meta information.
  $vars['info_split'] = $info;
  $vars['info'] = implode(' - ', $info);

// Provide alternate search result template.
//  $vars['template_files'][] = 'search-result-'. $vars['type'];
}


/**
 * Set default form file input size 
 */
function zeropoint_file($element) {
//  $element['#size'] = ($element['#size'] > 40) ? 40 : $element['#size'];
  return theme_file($element);
}


$preload = theme_get_setting('cssPreload'); // print the js file if css image preload enabled
  if ($preload == '1'){
    drupal_add_js(drupal_get_path('theme','zeropoint').'/js/preloadCssImages.jQuery_v5.js');
    drupal_add_js('jQuery(document).ready(function(){
    jQuery.preloadCssImages();
  });
  ','inline');
}

function menupos() {
  $navpos = theme_get_setting('navpos'); // Primary & secondary links position 
    if ($navpos == '0'){ 
      return 'navleft';
  }
    if ($navpos == '1'){ 
      return 'navcenter';
  }
    if ($navpos == '2'){ 
      return 'navright';
  }
}

function zeropoint_login(){
  global $user;
  $loginlinks = theme_get_setting('loginlinks');
  if ($loginlinks == '1'){ 
    if ($user->uid != 0) { 
      print '<ul class="links inline"><li class="first"><a href="' .url('user/'.$user->uid). '">' .$user->name. '</a></li><li><a href="' .url('user/logout'). '">' .t('Logout'). '</a></li></ul>'; 
    } 
    else { 
      print '<ul class="links inline"><li class="first"><a href="' .url('user'). '">' .t('Login'). '</a></li><li><a href="' .url('user/register'). '">' .t('Register'). '</a></li></ul>'; 
    }
  }
}



/**
 * CUSTOM
 */

/**
 * Use toplinks function to return links or whatever
 */

//function toplinks() {
//	return '
//<ul class="links">
//<li class="first"> LINK1 </li>
//<li> LINK2 </li>
//</ul>
//'; }
