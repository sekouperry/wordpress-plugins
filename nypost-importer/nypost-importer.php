<?php
/*
Plugin Name: NY Post Importer
Plugin URI: http://example.com
Description: A plugin that imports articles from the NY Post news feed and creates posts with their images, authors, publish dates, headlines, and full stories (if available).
Version: 1.0
Author: Your Name
Author URI: http://example.com
*/

function nypost_fetch_feed() {
    $feed_url = 'https://nypost.com/news/feed';
    $response = wp_remote_get( $feed_url );

    if ( is_wp_error( $response ) ) {
        return 'Error: Could not retrieve NY Post news feed.';
    }

    $body = wp_remote_retrieve_body( $response );
    $xml = simplexml_load_string( $body );

    return $xml;
}

function nypost_import_posts() {
    $xml = nypost_fetch_feed();

    if ( ! $xml ) {
        return 'Error: Could not retrieve NY Post news feed.';
    }

    foreach ( $xml->channel->item as $item ) {
        $title = (string) $item->title;
        $link = (string) $item->link;
        $publish_date = (string) $item->pubDate;
        $author = (string) $item->author;
        $description = (string) $item->description;
        $content = (string) $item->children( 'content', true )->encoded;

        // Check if the post already exists
        $existing_post = get_page_by_title( $title, OBJECT, 'post' );
        if ( $existing_post ) {
            continue;
        }

        // Get the first image from the post content
        $first_image = '';
        if ( preg_match( '/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image ) ) {
            $first_image = $image['src'];
        }

        // Create the post
        $post_data = array(
            'post_title' => $title,
            'post_content'
