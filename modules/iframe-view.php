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
		$return = $_GET['view'];
	}
	return $return;
}

/**
 * Iframe に適用するテーマの選定
 */
function vkpdc_iframe_view_theme() {
	return apply_filters( 'vkpdc_iframe_theme', 'defauilt' );
}

/**
 * 管理バーののスクリプトを追加
 */
function vkpdc_admin_scripts() {
	wp_dequeue_script( 'admin-bar' );
	wp_dequeue_style( 'admin-bar' );
}

/**
 * Ifreme 用の個別ページ用スクリプトを追加
 */
function vkpdc_iframe_single_scripts() {
	wp_enqueue_style( 'vk_patterns-iframe-single', VKPDC_PLUGIN_ROOT_URL . 'assets/build/css/iframe-single.css', array(), VKPDC_PLUGIN_VERSION );
}

/**
 * Ifreme 用のサムネイルスクリプトを追加
 */
function vkpdc_iframe_thumbnail_scripts() {
	wp_enqueue_style( 'vk_patterns-iframe-thumbnail', VKPDC_PLUGIN_ROOT_URL . 'assets/build/css/iframe-thumbnail.css', array(), VKPDC_PLUGIN_VERSION );
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

	$view_type = vkpdc_is_iframe_view();

	// Iframe 用のテンプレートでない場合何もしない.
	if ( false === $view_type ) {
		return;
	}

	// 管理バーを削除
	add_filter( 'show_admin_bar', '__return_false');
	add_action( 'wp_enqueue_scripts', 'vkpdc_admin_scripts', 2147483646 );

	// 各種テーマの追加処理
	do_action( 'vkpdc_iframe_comoon_settings' );

	// iframe の中身の CSS を適用
	if ( 'single' === $view_type ) {
		add_action( 'wp_enqueue_scripts', 'vkpdc_iframe_single_scripts' );
	} elseif ( 'thumbnail' === $view_type ) {
		add_action( 'wp_enqueue_scripts', 'vkpdc_iframe_thumbnail_scripts' );
	}	

	// 現在のテーマがブロックテーマの場合.
	if ( wp_is_block_theme() ) {

		// ブロックテーマで iframe を表示するときのの追加処理
		do_action( 'vkpdc_iframe_block_theme_settings' );

		// ブロックテーマ用の Iframe テンプレートを用意して読み込む.
		$template  = VKPDC_PLUGIN_ROOT_PATH . '/views/view-block-theme.php';
		$type      = 'single';
		$templates = array(
			'content' => '<div class="vk-patterns-container"><!-- wp:post-content /--></div>',
		);
		include locate_block_template( $template, $type, $templates );
	} else { // 現在のテーマがクラッシックテーマの場合.

		// クラシックテーマで iframe を表示するときのの追加処理
		do_action( 'vkpdc_iframe_block_theme_settings' );
		// クラシックテーマ用の Iframe テンプレートを用意して読み込む.
		include VKPDC_PLUGIN_ROOT_PATH . '/views/view-classic-theme.php';
	}
	exit;
}
add_filter( 'template_redirect', 'vkpdc_load_iframe_template', 2147483647 );
