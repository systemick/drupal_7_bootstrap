<?php

/**
 * @file template.php
 *
 * Template settings.
 */

/**
 * Inserts custom CSS files based on theme settings.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function mo6_preprocess_page(&$vars, $hook) {
  // Add a style sheet for compact comments
  if (theme_get_setting('mo6_compactcomments')) {
    $css = path_to_theme() . '/compactcomments.css';
    drupal_add_css($css, 'theme', 'all', 1);
  }

  // Rebuild Drupal's css array:
  $css = drupal_add_css();

  // Apply that array to the $styles string to be printed in the <head> section of page.tpl.php
  $vars['styles'] = drupal_get_css($css);

  // Format Primary menu into variable
  if (isset($vars['main_menu'])) {
    $vars['primary_nav'] = theme('links__system_main_menu', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'primary-links'),
      ),
      'heading' => array(
        'text' => t('Main menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }
  else {
    $vars['primary_nav'] = FALSE;
  }
}

/**
 * Render a taxonomy term page HTML output.
 *
 * @param $tids
 *   An array of term ids.
 * @param $result
 *   A pager_query() result, such as that performed by taxonomy_select_nodes().
 *
 * @ingroup themeable
 */
function mo6_taxonomy_term_page($tids, $result) {
  $output = '';

  if (module_exists('taxonomy')) {
    drupal_add_css(drupal_get_path('module', 'taxonomy') .'/taxonomy.css');

    // Only display the description if we have a single term, to avoid clutter and confusion.
    if (count($tids) == 1) {
      $term = taxonomy_get_term($tids[0]);
      $description = $term->description;

      // Check that a description is set.
      if (!empty($description)) {
        $output .= '<div class="taxonomy-term-description">';
        $output .= filter_xss_admin($description);
        $output .= '</div>';
      }
    }

    $renderednodes = taxonomy_render_nodes($result);

    // Display pager on top of the pager, if available and enabled
    $settings = theme_get_settings('mo6');
    if (!is_null($settings)) {
      if (isset($settings['mo6_pager_top']) && $settings['mo6_pager_top']) {
        // Check for the html signature of a pager
        if (strpos($renderednodes,'<ul class="pager">') !== FALSE) {
          $output .= theme('pager', NULL, variable_get('default_nodes_main', 10), 0);
        }
      }
    }

    $output .= $renderednodes;
  }

  return $output;
}

