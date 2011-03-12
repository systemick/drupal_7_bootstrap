<?php // $Id$

function kanji_preprocess_page(&$variables) {
  if (isset($variables['main_menu'])) {
    $pid = variable_get('menu_main_links_source', 'main-menu');
    $tree = menu_tree($pid);
    $variables['primary_nav'] = str_replace('menu', 'sf-menu menu', drupal_render($tree));
  } else {
    $variables['primary_nav'] = FALSE;
  }
}

function kanji_breadcrumb(&$variables) {
  $breadcrumb = $variables['breadcrumb'];
  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' | ', $breadcrumb) . '</div>';
  } else {
    $output = '<div class="breadcrumb">' . t('Home') . '</div>';
  }
  return $output;
}

function kanji_feed_icon(&$variables) {
  $text = t('Subscribe to @feed-title', array('@feed-title' => $variables['title']));
  if ($image = theme('image', array('path' => path_to_theme() . '/images/rss.png', 'alt' => $text))) {
    return l($image, $variables['url'], array('html' => TRUE, 'attributes' => array('class' => array('feed-icon'), 'title' => $text)));
  }
}
