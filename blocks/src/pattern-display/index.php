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

	$asset_file = include 'block.asset.php';

	$block_js = 'block.js';
	wp_register_script(
		'vkpdc-pattern-display-block',
		plugins_url( $block_js, __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	$editor_css = 'editor.css';
	wp_register_style(
		'vkpdc-pattern-display-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		$asset_file['version']
	);

	$style_css = 'style.css';
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
 * Setup Pattern Display Block
 */
function vkpdc_setup_pattern_display_block() {
	wp_localize_script(
		'vkpdc-pattern-display-block',
		'vkpdcPatternDisplay',
		array(
			'homeUrl'      => home_url(),
		)
	);
}
add_action( 'enqueue_block_editor_assets', 'vkpdc_setup_pattern_display_block' );

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
	$content = '';
	

	// タイトルとコンテントを処理.
	if ( ! empty( $attributes['postUrl'] ) && false !== strpos( $attributes['postUrl'], home_url() ) ) {

		$post_id = url_to_postid( $attributes['postUrl'] );

		// iframe の幅をコントロールするボタン.
		$select_button = vkpdc_get_size_selector( 'single' );

		// Iframe を適用したコンテンツを取得.
		$iframe_content = vkpdc_get_iframe_content( $post_id, 'single' );

		// コピーボタン用の HTML.
		$copy_button = vkpdc_get_copy_button( $post_id, 'single' );

		
		// iframe 上部のコンテンツ
		$setect = apply_filters( 'vkpdc_single_select', $select_button );

		// 下部のボタン
		$buttons = apply_filters( 'vkpdc_single_buttons', $copy_button );

		// 外枠のクラスを設定.
		$class_name = 'vkpdc vkpdc_single';
		if ( ! empty( $attributes['className'] ) ) {
			$class_name .= ' ' . $attributes['className'];
		}

		// コンテンツを生成.
		$content .= '<div class="' . $class_name . '"><div class="vkpdc-outer-single">';
		if ( ! empty( $attributes['selectButton'] ) && true === $attributes['selectButton'] ) {
			$content .= $setect;
		}
		$content .= '<div class="vkpdc_iframe-outer vkpdc_iframe-outer--single">' . $iframe_content . '</div>';
		if ( ! empty( $attributes['copyButton'] ) && true === $attributes['copyButton'] ) {
			$content .= '<div class="vkpdc_buttons vkpdc_buttons--single">';
			$content .= $buttons;
			$content .= '</div>';
		}
		$content .= '</div>';
	}

	return $content;
}
