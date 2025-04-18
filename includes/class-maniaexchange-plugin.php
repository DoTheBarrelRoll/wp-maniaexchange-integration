<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ManiaExchange_Plugin
{
    private static $instance = null;

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->load_dependencies();
        $this->initialize_components();
    }

    private function load_dependencies()
    {
        require_once MANIAEXCHANGE_PLUGIN_DIR . 'includes/class-maniaexchange-admin.php';
        require_once MANIAEXCHANGE_PLUGIN_DIR . 'includes/class-maniaexchange-post-type.php';
        require_once MANIAEXCHANGE_PLUGIN_DIR . 'includes/class-maniaexchange-api.php';
    }

    private function initialize_components()
    {
        new ManiaExchange_Admin();
        new ManiaExchange_Post_Type();
    }
}
