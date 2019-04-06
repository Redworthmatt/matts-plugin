<?php

/*
* Trigger this file on Plugin uninstall
*
* @package Matts Plugin
*/

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// CLear Database stored data - dletes post 1 at a time
$books = get_posts( array( 'post_type' => 'book', 'numberposts' => -1 ) );

foreach( $books as $book ) {
	wp_delete_post( $book->ID, true );
}

// Access the database via SQL - more direct but risky
global $wpdb;

$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'book'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)");
$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)");