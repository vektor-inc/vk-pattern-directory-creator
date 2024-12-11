<?php
/**
 * Registers the `vk-pattern-directory-creator/pattern-list` block.
 *
 * @package vk-blocks
 */

/**
 * Register block pattern-list
 *
 * @return void
 */
function vkpdc_add_pattern_list_block() {

	$asset_file = include 'block.asset.php';

	$block_js = 'block.js';
	wp_register_script(
		'vkpdc-pattern-list-block',
		plugins_url( $block_js, __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	$editor_css = 'editor.css';
	wp_register_style(
		'vkpdc-pattern-list-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		$asset_file['version']
	);

	$style_css = 'style.css';
	wp_register_style(
		'vkpdc-pattern-list-style',
		plugins_url( $style_css, __FILE__ ),
		array(),
		$asset_file['version']
	);

	register_block_type(
		__DIR__,
		array(
			'editor_script'   => 'vkpdc-pattern-list-block',
			'editor_style'    => 'vkpdc-pattern-list-editor',
			'style'           => 'vkpdc-pattern-list-style',
			'attributes'      => array(
				'numberPosts' => array(
					'type'    => 'number',
					'default' => 6,
				),
				'order'       => array(
					'type'    => 'string',
					'default' => 'DESC',
				),
				'orderby'     => array(
					'type'    => 'string',
					'default' => 'date',
				),
				'colWidthMin' => array(
					'type'    => 'string',
					'default' => '300px',
				),
				'colWidthMinTablet' => array(
					'type'    => 'string',
					'default' => '300px',
				),
				'colWidthMinPC' => array(
					'type'    => 'string',
					'default' => '300px',
				),
				'gap' => array(
					'type'    => 'string',
					'default' => '1.5rem',
				),
				'gapRow' => array(
					'type'    => 'string',
					'default' => '1.5rem',
				),
				'display_image' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'display_author' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'display_date_publiched' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'display_date_modified' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'display_new' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'display_taxonomies' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'pattern_id' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'display_btn_view' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'display_btn_copy' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'new_date'   => array(
					'type'    => 'number',
					'default' => 7,
				),
				'new_text' => array(
					'type'    => 'string',
					'default' => 'NEW!!',
				),
				'className'   => array(
					'type'    => 'string',
					'default' => '',
				),
			),
			'render_callback' => 'vkpdc_render_pattern_list_callback',
		)
	);
}
add_action( 'init', 'vkpdc_add_pattern_list_block', 9999 );

/**
 * Post list render callback
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function vkpdc_render_pattern_list_callback( $attributes ) {
	$query_args = array(
		'post_type'      => 'vk-patterns',
		'paged'          => 1,
		'posts_per_page' => intval( $attributes['numberPosts'] ),
		'order'          => $attributes['order'],
		'orderby'        => $attributes['orderby'],
	);
	
	$query = new WP_Query( $query_args );
	
	$className = isset( $attributes['className'] ) ? $attributes['className'] : '';
    $html  = '<div class="vkpdc-pattern-list ' . esc_attr( $className ) . '">';
	$html .= vkpdc_get_archive_loop( $query, $attributes );
	$html .= '</div>';
	
	return $html;
	
}
