<?php

// When using dropbox backup method, make sure all files get backed up into a sub-folder
// Based on site name/title
add_filter('updraftplus_dropbox_modpath', 'prefix_filenames_with_site_name');
function prefix_filenames_with_site_name($file) {
  return sanitize_title(get_bloginfo('name')) . '/' . $file;
}