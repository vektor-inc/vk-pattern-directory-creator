<?php
/**
 * VK Patterns Enqueue Scripts
 *
 * @package VK Patterns
 */

/**
 * Enqueue Scripts
 */
function vk_patterns_enqueue_scripts() {
	$copy_button_data = vk_patterns_get_copy_button_data();
	$favorite_button_data = vk_patterns_get_favorite_button_data();

	wp_enqueue_style( 'vk-patterns', VK_PATTERNS_PLUGIN_URL . 'assets/build/css/style.css', array(), VK_PATTERNS_PLUGIN_VERSION );
	wp_enqueue_script( 'vk-patterns-size-select', VK_PATTERNS_PLUGIN_URL . 'assets/build/js/size-select.js', array(), VK_PATTERNS_PLUGIN_VERSION, true );
	wp_localize_script(
		'vk-patterns-size-select',
		'vkPatternsSizeSelect',
		array(
			'sizeList' => vk_patterns_iframe_sizes(),
		)
	);
	wp_enqueue_script( 'vk-patterns-copy-button', VK_PATTERNS_PLUGIN_URL . 'assets/build/js/copy-button.js', array(), VK_PATTERNS_PLUGIN_VERSION, true );
	wp_localize_script(
		'vk-patterns-copy-button',
		'vkPatternsCopyButton',
		array(
			'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
			'beforeTextSingle'  => $copy_button_data['single']['copy_text'],
			'beforeTextArchive' => $copy_button_data['archive']['copy_text'],
			'afterText'         => __( 'Copied', 'vk-patterns' ),
		)
	);
	wp_enqueue_script( 'vk-patterns-favorite-button', VK_PATTERNS_PLUGIN_URL . 'assets/build/js/favorite-button.js', array(), VK_PATTERNS_PLUGIN_VERSION, true );

	wp_localize_script(
		'vk-patterns-favorite-button',
		'vkPatternsFavoriteButton',
		array(
			'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
			'addClass'    => $favorite_button_data['add']['class'],
			'addText'     => $favorite_button_data['add']['text'],
			'removeClass' => $favorite_button_data['remove']['class'],
			'removeText'  => $favorite_button_data['remove']['text'],
		)
	);

}
add_action( 'wp_enqueue_scripts', 'vk_patterns_enqueue_scripts' );
