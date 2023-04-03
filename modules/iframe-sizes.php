<?php
/**
 * パターンの iframe の幅のリスト
 *
 * @package VK Patterns
 */

/**
 * パターンの iframe の幅のリスト
 */
function vk_patterns_iframe_sizes() {
	$size_array = array(
		array(
			'label' => __( 'Extra Extra Large ( 1400px )', 'vk-patterns' ),
			'value' => '1400px',
		),
		array(
			'label' => __( 'Extra Large ( 1200px )', 'vk-patterns' ),
			'value' => '1200px',
		),
		array(
			'label' => __( 'Large ( 992px )', 'vk-patterns' ),
			'value' => '992px',
		),
		array(
			'label' => __( 'Medium ( 768px )', 'vk-patterns' ),
			'value' => '768px',
		),
		array(
			'label' => __( 'Small ( 576px )', 'vk-patterns' ),
			'value' => '576px',
		),
		array(
			'label' => __( 'Extra Small ( 360px )', 'vk-patterns' ),
			'value' => '360px',
		),
	);
	return apply_filters( 'vk_patterns_iframe_sizes', $size_array );
}
