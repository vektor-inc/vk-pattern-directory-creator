<?php
/**
 * Iframe の表示内容
 *
 * @package VK Pattern Directory Creator
 */

/**
 * Iframe で表示中か
 */
function vkpdc_is_iframe_view() {
	$return = false;
	if (
		(
			'vk-patterns' === get_post_type() ||
			'vk-patterns' === get_query_var( 'post_type' )
		) &&
		! empty( $_GET['view'] ) &&
		is_singular( 'vk-patterns' )
	) {
		$return = true;
	}
	return $return;
}

/**
 * 管理バーののスクリプトを追加
 */
function vkpdc_admin_scripts() {
	wp_dequeue_script( 'admin-bar' );
	wp_dequeue_style( 'admin-bar' );
}

/**
 * Ifreme 用のスクリプトを追加
 */
function vkpdc_iframe_scripts() {
	wp_enqueue_style( 'vk_patterns-iframe', VKPDC_PLUGIN_ROOT_URL . 'assets/build/css/iframe.css', array(), VKPDC_PLUGIN_VERSION );
}

/**
 * テーマサポートを追加
 */
function vkpdc_theme_support() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'block-templates' );
}
add_action( 'after_setup_theme', 'vkpdc_theme_support' );

/**
 * Iframe 時に専用のテンプレートに切り替え
 */
function vkpdc_load_iframe_template() {

	// Iframe 用のテンプレートでない場合何もしない.
	if ( ! vkpdc_is_iframe_view() ) {
		return;
	}

	// 管理バーを削除
	add_filter( 'show_admin_bar', '__return_false');
	add_action( 'wp_enqueue_scripts', 'vkpdc_admin_scripts', 2147483646 );

	// 現在のテーマがブロックテーマの場合.
	if ( wp_is_block_theme() ) {

		// ブロックテーマ用の Iframe テンプレートを用意して読み込む.
		$template  = VKPDC_PLUGIN_ROOT_PATH . '/views/view-block-theme.php';
		$type      = 'single';
		$templates = array(
			'content' => '<div class="vk-patterns-container"><!-- wp:post-content /--></div>',
		);
		include locate_block_template( $template, $type, $templates );
	} else { // 現在のテーマがクラッシックテーマの場合.

		// クラシックテーマ用の Iframe テンプレートを用意して読み込む.
		include VKPDC_PLUGIN_ROOT_PATH . '/views/view-classic-theme.php';
	}
	exit;
}
add_filter( 'template_redirect', 'vkpdc_load_iframe_template', 2147483647 );

