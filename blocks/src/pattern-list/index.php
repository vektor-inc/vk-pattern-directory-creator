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

	$asset_file = 'block.asset.php';

	$block_js = './block.js';
	wp_register_script(
		'vkpdc-pattern-list-block',
		plugins_url( $block_js, __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	$editor_css = './editor.css';
	wp_register_style(
		'vkpdc-pattern-list-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		$asset_file['version']
	);

	$style_css = './style.css';
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
	global $post;
	$query_args = array(
		'post_type'      => 'vk-patterns',
		'paged'          => 1,
		'posts_per_page' => intval( $attributes['numberPosts'] ),
		'order'          => $attributes['order'],
		'orderby'        => $attributes['orderby'],
	);
	$wp_query   = new WP_Query( $query_args );

	$loop_html = '';
	if ( $wp_query->have_posts() ) {
		$loop_html .= '<div class="vk_posts ' . esc_attr( $attributes['className'] ) . '">';

		// for infeed Ads Customize.
		global $vkpdc_loop_item_count;
		$vkpdc_loop_item_count = 0;

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$post_id = get_the_id();
			$options = vkpdc_loop_item_setting( $post_id );
			// phpcs:ignore
			$loop_html .= VK_Component_Posts::get_view( $post, $options );

			$vkpdc_loop_item_count++;
			do_action( 'vkpdc_loop_item_after' );
		}

		$loop_html .= '</div>';
	}
	wp_reset_postdata();
	return $loop_html;
}
