<?php

/**
 * @file page.tpl.php
 *
 * Displays a single Drupal page.
 * Added support for changing header and footer images by theme settings.
 * More compact html structure.
 */

?>
<?php print render($page['header']); ?>
<?php if (isset($page_top)) { print $page_top; } ?>
<div id="page_wrapper"><h1><?php

if (strlen(trim($title)) > 0) {
  print $title;
}
elseif (($is_front) && ($site_slogan)) {
  print $site_slogan;
}
else {
  // print $head_title;
}

?></h1><?php

  if ($breadcrumb) {
    print $breadcrumb;
  }

if ($page['toppage']) {
  print render($page['toppage']);
}

if ($tabs) {
  ?><div class="tabs"><?php print render($tabs) ?></div><?php
}

print $messages; 
print render($page['help']);

if ($page['topcontent']) {
  print $page['topcontent'];
}

/*
print "<pre>";
print_r($page['content']);
print "</pre>";
*/
print render($page['content']);

?><div id="side"><?php

if ($page['left']) {
  print render($page['left']);
}

if ($page['right']) {
  print render($page['right']);
}

?></div>
<?php 

if (isset($site_name)) {
  // Don't display the site name on the front page if there isn't a slogan
  // because in this case the site name is already displayed in the
  // location of the slogan.
  if (!(($is_front) && (!$site_slogan))) {
    print '<div id="sitename">'. l($site_name,'') .'</div>';
  }
}
if (isset($primary_nav)) {
  print $primary_nav;
}

?>
<div id="footer">
  <?php print render($page['footer']); ?>
  <div class="content">Powered by <a href="http://drupal.org/" rel="nofollow">Drupal</a> CMS and <a href="http://drupal.org/project/mo6" rel="nofollow">MO6 theme</a></div>
</div></div>
<?php if (isset($page_bottom)) { print $page_bottom; } ?>
