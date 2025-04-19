<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ManiaExchange_API
{
    public static function fetch_and_create_map()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (empty($_POST['track_id'])) {
            self::display_admin_error(__('Track ID is required.', 'maniaexchange-integration'));
            return;
        }

        $track_id = sanitize_text_field($_POST['track_id']);
        $api_url = "https://trackmania.exchange/api/maps/get_map_info/id/{$track_id}";

        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            self::display_admin_error(__('Failed to fetch data from the API: ', 'maniaexchange-integration') . $response->get_error_message());
            return;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (empty($data) || isset($data['error'])) {
            self::display_admin_error(__('Invalid response from the API.', 'maniaexchange-integration'));
            return;
        }

        // Create a new "Maps" post
        $post_id = wp_insert_post(array(
            'post_title' => $data['Name'] . ' by ' . $data['Username'],
            'post_type' => 'maps',
            'post_status' => 'publish',
        ));

        if (is_wp_error($post_id)) {
            self::display_admin_error(__('Failed to create the map post.', 'maniaexchange-integration'));
            return;
        }

        // Save tags
        if (!empty($data['Tags'])) {
            $tag_ids = explode(',', $data['Tags']);
            $tag_names = array();

            foreach ($tag_ids as $tag_id) {
                $term = get_term_by('slug', $tag_id, 'map_tags');
                if ($term) {
                    $tag_names[] = $term->name;
                }
            }

            if (!empty($tag_names)) {
                wp_set_object_terms($post_id, $tag_names, 'map_tags');
            }
        }

        // Save additional meta fields
        if (isset($data['AuthorTime'])) {
            update_post_meta($post_id, 'tm_AuthorTime', $data['AuthorTime']);
        }

        if (isset($data['Username'])) {
            update_post_meta($post_id, 'tm_Username', $data['Username']);
        }

        // Download and set the map image as the featured image
        self::set_map_featured_image($post_id, $track_id);

        echo '<p style="color: green;">' . __('Map created successfully!', 'maniaexchange-integration') . '</p>';
    }

    public static function fetch_and_save_tags()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $api_url = "https://trackmania.exchange/api/meta/tags";
        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            self::display_admin_error(__('Failed to fetch tags from the API: ', 'maniaexchange-integration') . $response->get_error_message());
            return;
        }

        $tags = json_decode(wp_remote_retrieve_body($response), true);

        if (empty($tags) || !is_array($tags)) {
            self::display_admin_error(__('Invalid response from the API.', 'maniaexchange-integration'));
            return;
        }

        foreach ($tags as $tag) {
            if (isset($tag['ID'], $tag['Name'])) {
                wp_insert_term(
                    $tag['Name'],
                    'map_tags',
                    array('slug' => $tag['ID'])
                );
            }
        }

        echo '<p style="color: green;">' . __('Tags fetched and saved successfully!', 'maniaexchange-integration') . '</p>';
    }

    private static function display_admin_error($message)
    {
        echo '<div class="notice notice-error"><p>' . esc_html($message) . '</p></div>';
    }

    private static function set_map_featured_image($post_id, $track_id)
    {
        $image_url = "https://trackmania.exchange/mapimage/{$track_id}";
        $upload_dir = wp_upload_dir();

        // Download the image
        $image_data = wp_remote_get($image_url);

        if (is_wp_error($image_data)) {
            return; // Exit if the image download fails
        }

        $image_data = wp_remote_retrieve_body($image_data);
        $filename = "map_{$track_id}.jpg";

        // Save the image to the uploads directory
        $file_path = $upload_dir['path'] . '/' . $filename;
        file_put_contents($file_path, $image_data);

        // Add the image to the WordPress media library
        $attachment_id = wp_insert_attachment(array(
            'guid' => $upload_dir['url'] . '/' . $filename,
            'post_mime_type' => 'image/jpeg',
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit',
        ), $file_path, $post_id);

        // Generate attachment metadata and update the attachment
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attachment_metadata = wp_generate_attachment_metadata($attachment_id, $file_path);
        wp_update_attachment_metadata($attachment_id, $attachment_metadata);

        // Set the image as the featured image for the post
        set_post_thumbnail($post_id, $attachment_id);
    }
}
