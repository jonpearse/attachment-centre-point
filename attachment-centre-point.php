<?php
/*
    Plugin name:    Attachment centre-point selector
    Plugin URI:     https://github.com/jonpearse/attachment-centre-point
    Description:    Allows the user to select a centre- or focal-point of an attached image.
    Version:        0.0.0
    Author:         Jon Pearse
    Author URI:     https://jonpearse.net/
    License:        MIT
*/

namespace jdp\WP\AttachmentCentrePoint;

if (!defined('WPINC'))
{
    return;
}

// include autoloader
require 'autoload.php';

// activation/deactivation hook
register_activation_hook  (__FILE__, array('jdp\WP\AttachmentCentrePoint\Plugin', 'activate'));
register_deactivation_hook(__FILE__, array('jdp\WP\AttachmentCentrePoint\Plugin', 'deactivate'));

// init
add_action('init', array('jdp\WP\AttachmentCentrePoint\Plugin', 'init'));
