<?php
/*
Plugin Name: Instagram Feed
Plugin URI: http://example.com
Description: A plugin that displays your Instagram posts on your website.
Version: 1.0
Author: Your Name
Author URI: http://example.com
*/

function instagram_feed_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'num_posts' => 10,
            'username' => '',
        ),
        $atts,
        'instagram_feed'
    );

    // Get the Instagram posts using the Instagram API
    $api_url = 'https://www.instagram.com/' . $atts['username'] . '/';
    $response = wp_remote_get( $api_url );

    if ( is_wp_error( $response ) ) {
        return 'Error: Could not retrieve Instagram posts.';
    }

    $body = wp_remote_retrieve_body( $response );
    $json = json_decode( $body );

    if ( empty( $json->entry_data->ProfilePage ) ) {
        return 'Error: Invalid Instagram username.';
    }

    $posts = $json->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges;

    if ( empty( $posts ) ) {
        return 'Error: No posts found for this Instagram username.';
    }

    // Limit the number of posts to the specified number
    $posts = array_slice( $posts, 0, $atts['num_posts'] );

    // Build the HTML output for the Instagram posts
    $output = '<div class="instagram-feed">';
    foreach ( $posts as $post ) {
        $output .= '<div class="instagram-post">';
        $output .= '<a href="' . $post->node->display_url . '" target="_blank"><img src="' . $post->node->thumbnail_src . '"></a>';
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode( 'instagram_feed', 'instagram_feed_shortcode' );

function instagram_feed_enqueue_scripts() {
    wp_enqueue_style( 'instagram-feed-style', plugins_url( 'instagram-feed.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'instagram_feed_enqueue_scripts' );
