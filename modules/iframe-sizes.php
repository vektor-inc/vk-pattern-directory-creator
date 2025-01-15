<?php
/**
 * パターンの iframe の幅のリスト
 *
 * @package VK Patterns
 */

/**
 * パターンの iframe の幅のリスト
 */
function vkpdc_iframe_sizes() {
	$size_array = array(
		array(
			'label' => __( 'Full HD ( 1920px )', 'vk-pattern-directory-creator' ),
			'value' => '1920px',
		),
		array(
			'label' => __( 'Extra Extra Large ( 1400px )', 'vk-pattern-directory-creator' ),
			'value' => '1400px',
		),
		array(
			'label' => __( 'Extra Large ( 1200px )', 'vk-pattern-directory-creator' ),
			'value' => '1200px',
		),
		array(
			'label' => __( 'Large ( 992px )', 'vk-pattern-directory-creator' ),
			'value' => '992px',
		),
		array(
			'label' => __( 'Medium ( 768px )', 'vk-pattern-directory-creator' ),
			'value' => '768px',
		),
		array(
			'label' => __( 'Small ( 576px )', 'vk-pattern-directory-creator' ),
			'value' => '576px',
		),
		array(
			'label' => __( 'Extra Small ( 360px )', 'vk-pattern-directory-creator' ),
			'value' => '360px',
		),
	);
	return apply_filters( 'vkpdc_iframe_sizes', $size_array );
}
