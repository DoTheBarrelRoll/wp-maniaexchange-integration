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
    }

    public function options_page()
    {
?>
        <form action="options.php" method="post">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        </form>
        <hr>
        <h2><?php _e('Fetch Track Data', 'maniaexchange-integration'); ?></h2>
        <form method="post">
            <label for="track_id"><?php _e('Track ID:', 'maniaexchange-integration'); ?></label>
            <input type="text" id="track_id" name="track_id" required>
            <button type="submit" name="fetch_track_data"><?php _e('Fetch and Create Map', 'maniaexchange-integration'); ?></button>
        </form>
        <hr>
        <h2><?php _e('Fetch Tags', 'maniaexchange-integration'); ?></h2>
        <form method="post">
            <button type="submit" name="fetch_tags"><?php _e('Fetch and Save Tags', 'maniaexchange-integration'); ?></button>
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
