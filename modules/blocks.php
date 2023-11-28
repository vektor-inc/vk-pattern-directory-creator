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

	if ( ! in_array( 'vk-patterns', $keys, true ) ) {
		$categories = array_merge(
			$categories,
			array(
				array(
					'slug'  => 'vk-patterns',
					'title' => __( 'VK Patterns', 'vk-patterns' ),
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

$vkpdc_blocks_array = array( 'pattern-description', 'pattern-display', 'pattern-list' );
foreach ( $vkpdc_blocks_array as $vkpdc_block ) {
	require_once VK_PATTERNS_PLUGIN_PATH . '/blocks/build/' . $vkpdc_block . '/index.php';
}

/**
 * Add Patterns Block
 */
function vkpdc_add_blocks() {
	$asset_file = include VK_PATTERNS_PLUGIN_PATH . '/blocks/build/all/block.asset.php';

	$block_js = '../blocks/build/all/block.js';
	wp_register_script(
		'vk-patterns-block',
		plugins_url( $block_js, __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	$editor_css = '../blocks/build/all/editor.css';
	wp_register_style(
		'vk-patterns-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		$asset_file['version']
	);

	$style_css = '../blocks/build/all/style.css';
	wp_register_style(
		'vk-patterns-style',
		plugins_url( $style_css, __FILE__ ),
		array(),
		$asset_file['version']
	);

	wp_localize_script(
		'vk-patterns-block',
		'VKPatterns',
		array(
			'homeUrl'      => home_url(),
			'hasFavorites' => ! empty( get_user_meta( get_current_user_id(), 'favorite-pattern-list', true ) ),
		)
	);
	

}
add_action( 'init', 'vkpdc_add_blocks', 11 );
