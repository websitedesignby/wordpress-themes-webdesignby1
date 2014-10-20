<?php
/* Launch the Theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'theme-setup/setup.php' );

// Miscellaneous Utilities
require_once( trailingslashit( get_template_directory() ) . '/theme-setup/utilities.php');

$webdesignby = new Webdesignby_Theme();