<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ManiaExchange_Post_Type
{
    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_meta_fields']);
    }

    public function register_post_type()
    {
        register_post_type('maps', array(
            'labels' => array(
                'name' => __('Maps', 'maniaexchange-integration'),
                'singular_name' => __('Map', 'maniaexchange-integration'),
                'add_new' => __('Add New Map', 'maniaexchange-integration'),
                'add_new_item' => __('Add New Map', 'maniaexchange-integration'),
                'edit_item' => __('Edit Map', 'maniaexchange-integration'),
                'new_item' => __('New Map', 'maniaexchange-integration'),
                'view_item' => __('View Map', 'maniaexchange-integration'),
                'search_items' => __('Search Maps', 'maniaexchange-integration'),
                'not_found' => __('No Maps found', 'maniaexchange-integration'),
                'not_found_in_trash' => __('No Maps found in Trash', 'maniaexchange-integration'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'maps'),
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'show_in_rest' => true,
            'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('
                <svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <style>.st0{fill:#000000;}</style>
                    <g>
                        <path class="st0" d="M311.508,354.916l5.72-11.034c1.816-3.546,3.506-7.029,5.096-10.582c3.117-7.076,5.666-14.261,7.271-21.36
                            c3.265-14.331,1.566-27.476-7.146-37.21c-8.712-9.928-23.097-16.591-37.966-21.235c-31.654-9.11-64.024-19.248-95.343-31.139
                            c-7.862-2.985-15.686-6.086-23.479-9.39c-7.676-3.226-15.998-6.997-23.612-12.102c-7.527-5.088-15.421-11.868-19.496-22.263
                            c-2.019-5.112-2.549-10.894-1.87-16.147c0.685-5.267,2.4-10.06,4.528-14.331c4.426-8.751,9.857-15.063,14.751-21.835l15.235-19.871
                            l30.789-39.322l52.421-65.949L198.445,0c0,0-91.026,83.709-131.657,136.535c-35.324,45.914-16.03,86.483,47.87,113.772
                            c53.933,23.043,104.414,40.88,104.414,40.88c8.697,4.504,14.884,12.398,16.941,21.609c2.057,9.219-0.218,18.812-6.242,26.3
                            L90.719,512h138.733l59.006-112.868L311.508,354.916z"/>
                        <path class="st0" d="M444.973,261.023c-19.365-32.394-52.515-55.242-90.917-62.669l-136.052-28.684
                            c-5.673-1.091-10.442-4.691-12.85-9.694c-2.408-4.995-2.151-10.786,0.678-15.578l90.442-141.6l-44.052-1.262l-55.694,73.672
                            l-29.752,39.96l-14.627,20.082c-4.707,6.756-9.897,13.435-12.757,19.684c-3.046,6.46-3.81,12.617-1.612,17.728
                            c2.119,5.237,7.301,10.107,13.551,14.02c6.328,4.005,13.287,7.02,21.002,10.044c6.328,4.005,13.287,7.02,21.002,10.044
                            c7.59,3,15.289,5.845,23.043,8.572c31.155,10.948,62.536,19.956,95,28.459c16.645,4.956,34.264,11.595,48.299,26.402
                            c6.842,7.41,11.946,17.143,13.886,27.219c1.995,10.084,1.341,20.012-0.405,29.191c-1.839,9.172-4.714,17.791-8.089,26.004
                            c-1.706,4.083-3.546,8.128-5.431,12.024l-5.486,11.245l-21.983,44.8L261.284,512h140.626l53.192-144.646
                            C468.031,332.178,464.337,293.416,444.973,261.023z"/>
                    </g>
                </svg>
            '),
        ));

        register_taxonomy('map_tags', 'maps', array(
            'labels' => array(
                'name' => __('Tags', 'maniaexchange-integration'),
                'singular_name' => __('Tag', 'maniaexchange-integration'),
                'search_items' => __('Search Tags', 'maniaexchange-integration'),
                'all_items' => __('All Tags', 'maniaexchange-integration'),
                'edit_item' => __('Edit Tag', 'maniaexchange-integration'),
                'update_item' => __('Update Tag', 'maniaexchange-integration'),
                'add_new_item' => __('Add New Tag', 'maniaexchange-integration'),
                'new_item_name' => __('New Tag Name', 'maniaexchange-integration'),
            ),
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'map-tags'),
        ));
    }

    public function register_meta_fields()
    {
        register_meta('post', 'tm_Username', array(
            'type' => 'string',
            'description' => __('The username of the map author', 'maniaexchange-integration'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_meta('post', 'tm_AuthorTime', array(
            'type' => 'integer',
            'description' => __('The author time of the map', 'maniaexchange-integration'),
            'single' => true,
            'show_in_rest' => true,
        ));
    }
}
