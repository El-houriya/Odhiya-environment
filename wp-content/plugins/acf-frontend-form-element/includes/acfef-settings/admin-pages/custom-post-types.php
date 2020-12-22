<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Post Type: Payments.
 */
$labels = [
    "name" => __( "Payments", "acf-frontend-form-element" ),
    "singular_name" => __( "Payment", "acf-frontend-form-element" ),
    "menu_name" => __( "My Payments", "acf-frontend-form-element" ),
    "all_items" => __( "Payments", "acf-frontend-form-element" ),
    "add_new" => __( "Add new", "acf-frontend-form-element" ),
    "add_new_item" => __( "Add new Payment", "acf-frontend-form-element" ),
    "edit_item" => __( "Edit Payment", "acf-frontend-form-element" ),
    "new_item" => __( "New Payment", "acf-frontend-form-element" ),
    "view_item" => __( "View Payment", "acf-frontend-form-element" ),
    "view_items" => __( "View Payments", "acf-frontend-form-element" ),
    "search_items" => __( "Search Payments", "acf-frontend-form-element" ),
    "not_found" => __( "No Payments found", "acf-frontend-form-element" ),
    "not_found_in_trash" => __( "No Payments found in trash", "acf-frontend-form-element" ),
    "parent" => __( "Parent Payment:", "acf-frontend-form-element" ),
    "featured_image" => __( "Featured image for this Payment", "acf-frontend-form-element" ),
    "set_featured_image" => __( "Set featured image for this Payment", "acf-frontend-form-element" ),
    "remove_featured_image" => __( "Remove featured image for this Payment", "acf-frontend-form-element" ),
    "use_featured_image" => __( "Use as featured image for this Payment", "acf-frontend-form-element" ),
    "archives" => __( "Payment archives", "acf-frontend-form-element" ),
    "insert_into_item" => __( "Insert into Payment", "acf-frontend-form-element" ),
    "uploaded_to_this_item" => __( "Upload to this Payment", "acf-frontend-form-element" ),
    "filter_items_list" => __( "Filter Payments list", "acf-frontend-form-element" ),
    "items_list_navigation" => __( "Payments list navigation", "acf-frontend-form-element" ),
    "items_list" => __( "Payments list", "acf-frontend-form-element" ),
    "attributes" => __( "Payments attributes", "acf-frontend-form-element" ),
    "name_admin_bar" => __( "Payment", "acf-frontend-form-element" ),
    "item_published" => __( "Payment published", "acf-frontend-form-element" ),
    "item_published_privately" => __( "Payment published privately.", "acf-frontend-form-element" ),
    "item_reverted_to_draft" => __( "Payment reverted to draft.", "acf-frontend-form-element" ),
    "item_scheduled" => __( "Payment scheduled", "acf-frontend-form-element" ),
    "item_updated" => __( "Payment updated.", "acf-frontend-form-element" ),
    "parent_item_colon" => __( "Parent Payment:", "acf-frontend-form-element" ),
];

$args = [
    "label" => __( "Payments", "acf-frontend-form-element" ),
    "labels" => $labels,
    "description" => "",
    "public" => false,
    "publicly_queryable" => false,
    "show_ui" => true,
    "show_in_rest" => false,
    "rest_base" => "",
    "rest_controller_class" => "WP_REST_Posts_Controller",
    "has_archive" => false,
    "show_in_menu" => "acfef-settings",
    "show_in_nav_menus" => false,
    "delete_with_user" => false,
    "exclude_from_search" => true,
    "capability_type" => "post",
    "map_meta_cap" => true,
    "hierarchical" => false,
    "rewrite" => [ "slug" => "acfef_payment", "with_front" => false ],
    "query_var" => true,
    "supports" => [ "none" ],
];

register_post_type( "acfef_payment", $args );