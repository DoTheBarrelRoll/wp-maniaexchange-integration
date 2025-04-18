<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ManiaExchange_Admin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'settings_init']);
    }

    public function add_admin_menu()
    {
        add_options_page(
            'ManiaExchange Integration',
            'ManiaExchange',
            'manage_options',
            'maniaexchange-integration',
            [$this, 'options_page']
        );
    }

    public function settings_init()
    {
        register_setting('maniaexchange_options', 'maniaexchange_settings');

        add_settings_section(
            'maniaexchange_section',
            __('API Configuration', 'maniaexchange'),
            [$this, 'settings_section_callback'],
            'maniaexchange_options'
        );

        add_settings_field(
            'maniaexchange_api_key',
            __('API Key', 'maniaexchange'),
            [$this, 'api_key_render'],
            'maniaexchange_options',
            'maniaexchange_section'
        );

        add_settings_field(
            'maniaexchange_api_url',
            __('API URL', 'maniaexchange'),
            [$this, 'api_url_render'],
            'maniaexchange_options',
            'maniaexchange_section'
        );
    }

    public function api_key_render()
    {
        $options = get_option('maniaexchange_settings');
        ?>
        <input type="text" name="maniaexchange_settings[maniaexchange_api_key]" value="<?php echo isset($options['maniaexchange_api_key']) ? esc_attr($options['maniaexchange_api_key']) : ''; ?>">
        <?php
    }

    public function api_url_render()
    {
        $options = get_option('maniaexchange_settings');
        ?>
        <input type="text" name="maniaexchange_settings[maniaexchange_api_url]" value="<?php echo isset($options['maniaexchange_api_url']) ? esc_attr($options['maniaexchange_api_url']) : ''; ?>">
        <?php
    }

    public function settings_section_callback()
    {
        echo __('Enter your ManiaExchange API details below.', 'maniaexchange');
    }

    public function options_page()
    {
        ?>
        <form action="options.php" method="post">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <?php
            settings_fields('maniaexchange_options');
            do_settings_sections('maniaexchange_options');
            submit_button();
            ?>
        </form>
        <hr>
        <h2><?php _e('Fetch Track Data', 'maniaexchange'); ?></h2>
        <form method="post">
            <label for="track_id"><?php _e('Track ID:', 'maniaexchange'); ?></label>
            <input type="text" id="track_id" name="track_id" required>
            <button type="submit" name="fetch_track_data"><?php _e('Fetch and Create Map', 'maniaexchange'); ?></button>
        </form>
        <hr>
        <h2><?php _e('Fetch Tags', 'maniaexchange'); ?></h2>
        <form method="post">
            <button type="submit" name="fetch_tags"><?php _e('Fetch and Save Tags', 'maniaexchange'); ?></button>
        </form>
        <?php
        if (isset($_POST['fetch_track_data'])) {
            ManiaExchange_API::fetch_and_create_map();
        }

        if (isset($_POST['fetch_tags'])) {
            ManiaExchange_API::fetch_and_save_tags();
        }
    }
}
