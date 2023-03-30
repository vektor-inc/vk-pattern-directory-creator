<?php
/**
 * Register Post Type and Taxonomy
 *
 * @package VK Pattern Directory Creator
 */

/**
 * Register Post Type and Taxonomy
 */
function vkpdc_register_post_type() {
	register_post_type(
		'vk-patterns',
		array(
			'labels'          => array(
				'name'                     => _x( 'Block Pattern', 'post type general name', 'vk-pattern-directory-creator' ),
				'singular_name'            => _x( 'Block Pattern', 'post type singular name', 'vk-pattern-directory-creator' ),
				'add_new'                  => _x( 'Add New', 'block pattern', 'vk-pattern-directory-creator' ),
				'add_new_item'             => __( 'Add New Pattern', 'vk-pattern-directory-creator' ),
				'edit_item'                => __( 'Edit Pattern', 'vk-pattern-directory-creator' ),
				'new_item'                 => __( 'New Pattern', 'vk-pattern-directory-creator' ),
				'view_item'                => __( 'View Pattern', 'vk-pattern-directory-creator' ),
				'view_items'               => __( 'View Patterns', 'vk-pattern-directory-creator' ),
				'search_items'             => __( 'Search Patterns', 'vk-pattern-directory-creator' ),
				'not_found'                => __( 'No patterns found.', 'vk-pattern-directory-creator' ),
				'not_found_in_trash'       => __( 'No patterns found in Trash.', 'vk-pattern-directory-creator' ),
				'all_items'                => __( 'All Block Patterns', 'vk-pattern-directory-creator' ),
				'archives'                 => __( 'Pattern Archives', 'vk-pattern-directory-creator' ),
				'attributes'               => __( 'Pattern Attributes', 'vk-pattern-directory-creator' ),
				'insert_into_item'         => __( 'Insert into block pattern', 'vk-pattern-directory-creator' ),
				'uploaded_to_this_item'    => __( 'Uploaded to this block pattern', 'vk-pattern-directory-creator' ),
				'filter_items_list'        => __( 'Filter patterns list', 'vk-pattern-directory-creator' ),
				'items_list_navigation'    => __( 'Block patterns list navigation', 'vk-pattern-directory-creator' ),
				'items_list'               => __( 'Block patterns list', 'vk-pattern-directory-creator' ),
				'item_published'           => __( 'Block pattern published.', 'vk-pattern-directory-creator' ),
				'item_published_privately' => __( 'Block pattern published privately.', 'vk-pattern-directory-creator' ),
				'item_reverted_to_draft'   => __( 'Block pattern reverted to draft.', 'vk-pattern-directory-creator' ),
				'item_scheduled'           => __( 'Block pattern scheduled.', 'vk-pattern-directory-creator' ),
				'item_updated'             => __( 'Block pattern updated.', 'vk-pattern-directory-creator' ),
			),
			'description'     => 'Stores publicly shared Block Patterns (predefined block layouts, ready to insert and tweak).',
			'public'          => true,
			'show_in_rest'    => true,
			'has_archive'     => true,
			'menu_icon'       => 'dashicons-screenoptions',
			'rewrite'         => array( 'slug' => 'pattern' ),
			'supports'        => array( 'title', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'editor' ),
		)
	);
}
add_action( 'init', 'vkpdc_register_post_type', 0 );
