<?php
/**
 * VK Patterns Enqueue Scripts
 *
 * @package VK Patterns
 */

/**
 * Enqueue Scripts
 */
function vkpdc_enqueue_scripts() {
	$copy_button_data     = vkpdc_get_copy_button_data();

	wp_enqueue_style( 'vk-patterns', VKPDC_PLUGIN_ROOT_URL . 'assets/build/css/style.css', array(), VKPDC_PLUGIN_VERSION );
	wp_enqueue_script( 'vk-patterns-size-select', VKPDC_PLUGIN_ROOT_URL . 'assets/build/js/size-select.js', array(), VKPDC_PLUGIN_VERSION, true );
	wp_localize_script(
		'vk-patterns-size-select',
		'vkPatternsSizeSelect',
		array(
			'sizeList' => vkpdc_iframe_sizes(),
		)
	);
	wp_enqueue_script( 'vk-patterns-copy-button', VKPDC_PLUGIN_ROOT_URL . 'assets/build/js/copy-button.js', array(), VKPDC_PLUGIN_VERSION, true );
	wp_localize_script(
		'vk-patterns-copy-button',
		'vkPatternsCopyButton',
		array(
			'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
			'beforeTextSingle'  => $copy_button_data['single']['copy_text'],
			'beforeTextArchive' => $copy_button_data['archive']['copy_text'],
			'afterText'         => __( 'Copied', 'vk-pattern-directory-creator' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'vkpdc_enqueue_scripts' );
