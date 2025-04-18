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
            echo '<p style="color: red;">' . __('Track ID is required.', 'maniaexchange') . '</p>';
            return;
        }

        $track_id = sanitize_text_field($_POST['track_id']);
        $api_url = "https://trackmania.exchange/api/maps/get_map_info/id/{$track_id}";

        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            echo '<p style="color: red;">' . __('Failed to fetch data from the API.', 'maniaexchange') . '</p>';
            return;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (empty($data) || isset($data['error'])) {
            echo '<p style="color: red;">' . __('Invalid response from the API.', 'maniaexchange') . '</p>';
            return;
        }

        $post_id = wp_insert_post(array(
            'post_title' => $data['Name'] . ' by ' . $data['Username'],
            'post_type' => 'maps',
            'post_status' => 'publish',
        ));

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

        echo '<p style="color: green;">' . __('Map created successfully!', 'maniaexchange') . '</p>';
    }

    public static function fetch_and_save_tags()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $api_url = "https://trackmania.exchange/api/meta/tags";
        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            echo '<p style="color: red;">' . __('Failed to fetch tags from the API.', 'maniaexchange') . '</p>';
            return;
        }

        $tags = json_decode(wp_remote_retrieve_body($response), true);

        if (empty($tags) || !is_array($tags)) {
            echo '<p style="color: red;">' . __('Invalid response from the API.', 'maniaexchange') . '</p>';
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

        echo '<p style="color: green;">' . __('Tags fetched and saved successfully!', 'maniaexchange') . '</p>';
    }
}
