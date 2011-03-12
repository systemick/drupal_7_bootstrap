<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0" dir="<?php print $language->dir; ?>"
  <?php print $rdf_namespaces ?>>

<head profile="<?php print $grddl_profile ?>">
<?php print $head ?>
<title><?php print $head_title ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<?php print $styles ?>
<?php print $scripts ?>
</head>

<body id="<?php print $body_id; ?>" class="<?php print $classes; ?>" <?php print $attributes;?>>
<div id="skip-nav"><a href="#main"><?php print t('Jump to Navigation'); ?></a></div>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
<div class="byy"><a href="http://www.radut.net">by Dr. Radut</a>.</div>
</body>
</html>