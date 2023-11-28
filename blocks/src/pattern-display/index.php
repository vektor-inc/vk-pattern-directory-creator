<?php
/**
 * Register vk-pattern-directory-creator/patterns Block
 *
 * @package VK Patterns
 */

/**
 * Add Patterns Block
 */
function vkpdc_add_pattern_display_block() {
	$asset_file = 'block.asset.php';

	$block_js = './block.js';
	wp_register_script(
		'vkpdc-pattern-display-block',
		plugins_url( $block_js, __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	$editor_css = './editor.css';
	wp_register_style(
		'vkpdc-pattern-display-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		$asset_file['version']
	);

	$style_css = './style.css';
	wp_register_style(
		'vkpdc-pattern-display-style',
		plugins_url( $style_css, __FILE__ ),
		array(),
		$asset_file['version']
	);

	register_block_type(
		__DIR__,
		array(
			'editor_script'   => 'vkpdc-pattern-display-block',
			'editor_style'    => 'vkpdc-pattern-display-editor',
			'style'           => 'vkpdc-pattern-display-style',
			'attributes'      => array(
				'postUrl'      => array(
					'type' => 'string',
				),
				'selectButton' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'copyButton'   => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'className'    => array(
					'type'    => 'string',
					'default' => '',
				),
			),
			'render_callback' => 'vkpdc_render_pattern_display_callback',
		)
	);
}
add_action( 'init', 'vkpdc_add_pattern_display_block', 9999 );

/**
 * Render Patterns Block
 *
 * @param array $attributes attributes.
 * @param html  $content content.
 */
function vkpdc_render_pattern_display_callback( $attributes, $content ) {
	$attributes = wp_parse_args(
		$attributes,
		array(
			'selectButton' => true,
			'copyButton'   => true,
		)
	);

	// ここで使うコンテント.
	$return_content = '';

	// タイトルとコンテントを処理.
	if ( ! empty( $attributes['postUrl'] ) && false !== strpos( $attributes['postUrl'], home_url() ) ) {
		$post_id = url_to_postid( $attributes['postUrl'] );

		// iframe の幅をコントロールするボタン.
		$select_button = vkpdc_get_size_selector( 'single' );

		// Iframe を適用したコンテンツを取得.
		$iframe_content = vkpdc_get_iframe_content( $post_id, 'single' );

		// コピーボタン用の HTML.
		$copy_button = vkpdc_get_copy_button( $post_id, 'single' );

		// 外枠のクラスを設定.
		$class_name = 'vk-patterns';
		if ( ! empty( $attributes['className'] ) ) {
			$class_name .= ' ' . $attributes['className'];
		}

		// コンテンツを生成.
		$return_content .= '<div class="' . $class_name . '"><div class="vk-patterns-outer-single">';
		if ( ! empty( $attributes['selectButton'] ) && true === $attributes['selectButton'] ) {
			$return_content .= $select_button;
		}
		$return_content .= $iframe_content;
		if ( ! empty( $attributes['copyButton'] ) && true === $attributes['copyButton'] ) {
			$return_content .= $copy_button;
		}
		$return_content .= '</div></div>';
	}

	return $return_content;
}
