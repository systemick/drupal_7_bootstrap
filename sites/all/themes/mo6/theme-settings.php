<?php

/**
 * @file theme-settings.php
 *
 * Theme specific configuration options.
 */

/**
 * Implementation of THEMEHOOK_settings() function.
 * Support for compact comments forms and uploading a
 * custom images used in header and footer.
 *
 * @param $saved_settings
 *   array An array of saved settings for this theme.
 * @return
 *   array A form array.
 */
// function mo6_settings($saved_settings) {
function mo6_form_system_theme_settings_alter(&$form, $form_state) {

  // Miscelleaneous MO6 settings
  $form['mo6_misc'] = array(
    '#type' => 'fieldset',
    '#title' => t('Miscelleaneous MO6 settings'),
  );

  // Hide breadcrumb
  $form['mo6_misc']['mo6_breadcrumb'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide breadcrumbs'),
    '#default_value' => theme_get_setting('mo6_breadcrumb'),
  );

  // Always display breadcrumb, link to homepage if empty breadcrumb
  $form['mo6_misc']['mo6_always_breadcrumb'] = array(
    '#type' => 'checkbox',
    '#title' => t('Always display a breadcrumb'),
    '#description' => t('Display a link to the homepage if the breadcrumb is empty, except on the homepage. Use this option if there\'s no primary menu with a link to the homepage.'),
    '#default_value' => theme_get_setting('mo6_always_breadcrumb'),
  );

  // Display page navigation on top of taxonomy pages, if available
  $form['mo6_misc']['mo6_pager_top'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display taxonomy pagers on top'),
    '#description' => t('Display a page navigation on top of taxonomy pages, if available'),
    '#default_value' => theme_get_setting('mo6_pager_top'),
  );

  // Compact comment forms
  $form['mo6_misc']['mo6_compactcomments'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use a compact comment form for anonymous users'),
    '#default_value' => theme_get_setting('mo6_compactcomments'),
  );

  // Custom header and footer images, provides upload
  $form['header'] = array(
    '#type' => 'fieldset',
    '#title' => t('Custom header image'),
    '#description' => t('Image is displayed on top and bottom of every page'),
  );
  $form['header']['mo6_use_header'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use a custom header image'),
    '#default_value' => theme_get_setting('mo6_use_header'),
  );
  $form['header']['mo6_header_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Path to custom header image'),
    '#default_value' => theme_get_setting('mo6_header_path'),
  );

  $form['header']['mo6_header_upload'] = array(
    '#type' => 'file',
    '#title' => t('Upload header image'),
    '#description' => t('For best results use an image with a width of 770 pixels and a height of 150 pixels'),
  );
  $form['#submit'][] = 'mo6_settings_submit';
  $form['header']['mo6_header_upload']['#element_validate'][] = 'mo6_settings_submit';

  // Custom colors
  $form['mo6_colors'] = array(
    '#type' => 'fieldset',
    '#title' => t('Custom colors'),
  );
  $form['mo6_colors']['mo6_use_colors'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use custom colors'),
    '#default_value' => theme_get_setting('mo6_use_colors'),
    '#description' => '<div class="outercolorpicker"><div id="colorpicker"></div></div>',
  );

  $form['mo6_color']['#attached']['library'][] = array(
    'system',
    'farbtastic',
  );

  // Add our own js for intializing and linking farbtastic
  drupal_add_js(drupal_get_path('theme', 'mo6') .'/mo6-color.js');

  $names = array(
    'mo6_color_base' => array( t('Content background color'), '#f8f8f8' ),
    'mo6_color_link' => array( t('Link color'), '#0066cc' ),
    'mo6_color_text' => array( t('Text color'), '#444444' ),
    'mo6_color_header' => array( t('Header color'), '#444444' ),
    'mo6_color_linked_node' => array( t('Linked node header color'), '#888888' ),
  );
  foreach ($names as $name => $value) {
    $form['mo6_colors'][$name] = array(
      '#type' => 'textfield',
      '#title' => $value[0],
      '#default_value' => (theme_get_setting($name) ? theme_get_setting($name) : $value[1]),
      '#size' => 7,
      '#maxlength' => 7,
      '#attributes' => array( 'class' => array('color_textfield') ),
      '#description' => t('Default: ') .$value[1],
    );
  }

  // Custom sizes
  $form['mo6_sizes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Custom sizes'),
  );
  $form['mo6_sizes']['mo6_use_sizes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use custom sizes'),
    '#default_value' => theme_get_setting('mo6_use_sizes'),
  );
  $form['mo6_sizes']['mo6_min_height'] = array(
    '#type' => 'textfield',
    '#size' => 6,
    '#maxlength' => 6,
    '#title' => t('Minimum page height'),
    '#default_value' => theme_get_setting('mo6_min_height'),
    '#description' => t('Minimum page height in pixels. Default: 650.'),
  );
  $form['mo6_sizes']['mo6_content_width'] = array(
    '#type' => 'textfield',
    '#size' => 6,
    '#maxlength' => 6,
    '#title' => t('Width of content column'),
    '#default_value' => theme_get_setting('mo6_content_width'),
    '#description' => t('Width of content column in pixels, including left and right padding, e.g. the width of the header and footer image. Default: 770, to fit in a browser window 1024 pixels wide.'),
  );


  return $form;
}

/**
 * Capture theme settings submissions and update uploaded image
 *
 * @param @form
 * @param &$form_state
 */
function mo6_settings_submit($form, &$form_state) {
  // Check for a new uploaded file, and use that if available.
  if ($file = file_save_upload('mo6_header_upload')) {
    $parts = pathinfo($file->filename);
    $filename = 'public://mo6_header.'. $parts['extension'];

    // The image was saved using file_save_upload() and was added to the
    // files table as a temporary file. We'll make a copy and let the garbage
    // collector delete the original upload.
    if ($filepath = file_unmanaged_copy($file->uri, $filename)) {
      $_POST['mo6_use_header'] = $form_state['values']['mo6_use_header'] = TRUE;
      $_POST['mo6_header_path'] = $form_state['values']['mo6_header_path'] = $filepath;
    }
  }
}

