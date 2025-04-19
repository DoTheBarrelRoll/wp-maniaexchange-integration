<?php
/*
Plugin Name: ManiaExchange Integration
Description: A plugin to integrate with the ManiaExchange API.
Version: 1.0
Author: Miikka Niemeläinen
Author URI:
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('MANIAEXCHANGE_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Autoload required files
require_once MANIAEXCHANGE_PLUGIN_DIR . 'includes/class-maniaexchange-plugin.php';
require_once MANIAEXCHANGE_PLUGIN_DIR . 'includes/class-maniaexchange-admin.php';
require_once MANIAEXCHANGE_PLUGIN_DIR . 'includes/class-maniaexchange-post-type.php';
require_once MANIAEXCHANGE_PLUGIN_DIR . 'includes/class-maniaexchange-api.php';

// Initialize the plugin
ManiaExchange_Plugin::get_instance();

// Load translations
function maniaexchange_load_textdomain()
{
    load_plugin_textdomain('maniaexchange-integration', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'maniaexchange_load_textdomain');
