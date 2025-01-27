<?php
/**
 * VK Patterns Blocks
 *
 * @package VK Patterns
 */

/**
 * Add Block Categories
 *
 * @param array  $categories Block Categories.
 * @param Object $post       Post Object.
 */
function vkpdc_block_categories( $categories, $post ) {
	foreach ( $categories as $key => $value ) {
		$keys[] = $value['slug'];
	}

	if ( ! in_array( 'vk-pattern-directory-creator', $keys, true ) ) {
		$categories = array_merge(
			$categories,
			array(
				array(
					'slug'  => 'vk-pattern-directory-creator',
					'title' => __( 'VK Pattern Directory Creator', 'vk-pattern-directory-creator' ),
					'icon'  => '',
				),
			)
		);
	}

	return $categories;
}

// ver5.8.0 block_categories_all.
if ( function_exists( 'get_default_block_categories' ) && function_exists( 'get_block_editor_settings' ) ) {
	add_filter( 'block_categories_all', 'vkpdc_block_categories', 10, 2 );
} else {
	add_filter( 'block_categories', 'vkpdc_block_categories', 10, 2 );
}

/**
 * Add Patterns Block
 */
function vkpdc_add_blocks() {
	$vkpdc_blocks_array = array( 'pattern-description', 'pattern-display', 'pattern-list' );
	foreach ( $vkpdc_blocks_array as $vkpdc_block ) {
		require_once VKPDC_PLUGIN_ROOT_PATH . '/blocks/build/' . $vkpdc_block . '/index.php';
	}
}
add_action( 'init', 'vkpdc_add_blocks', 11 );
