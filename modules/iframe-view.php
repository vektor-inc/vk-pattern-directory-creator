<?php
/**
 * Iframe の表示内容
 *
 * @package VK Pattern Directory Creator
 */

/**
 * 現在の URL を取得
 */
function vkpdc_get_current_url() {
	// 現在の URL を取得
	return ( empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

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
 * パターンブロックを削除する。
 * 
 * @param 
 */
function vkpdc_delete_pattern_description( $content ) {

	// 投稿タイプが vk-patterns でない場合は処理を中断
	if ( 'vk-patterns' !== get_post_type() ) {
		return $content;
	}

	// パターンの説明を削除
	$content = preg_replace( '/<!-- wp:vkpdc\/pattern-description -->((.|\n|\r|\s)+?)<!-- \/wp:vkpdc\/pattern-description -->/', '', $content );

	return $content;

}

/**
 * Iframe 時に専用のテンプレートに切り替え
 */
function vkpdc_load_iframe_template() {

	$view_type = vkpdc_is_iframe_view();

	// Iframe 用のテンプレートでない場合何もしない.
	if ( false === $view_type ) {
		return;
	}

	// パターンの説明を削除
	add_filter( 'the_content', 'vkpdc_delete_pattern_description', 0 );

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
			'content' => '<div class="vkpdc_container"><!-- wp:post-content /--></div>',
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

/**
 * iframe内 のテーマを切り替える関数
 */
function vkpdc_switch_theme_for_iframe() {
	// iframe 判定 (URL パラメータ)
	if ( ! isset( $_GET['view'] ) || sanitize_text_field( $_GET['view'] ) !== 'iframe' ) {
		error_log( 'Not an iframe view. Exiting.' );
		return;
	}

	// 選択されたテーマを取得
	$selected_theme = isset( $_GET['theme'] ) ? sanitize_text_field( $_GET['theme'] ) : 'default';
	if ( $selected_theme !== 'default' ) {
		$theme = wp_get_theme( $selected_theme );
		if ( $theme->exists() ) {
			error_log( 'Switching to theme: ' . $theme->get( 'Name' ) );

			// テーマ切り替えをiframe内に限定
			add_filter( 'template_directory', function() use ( $theme ) {
				return $theme->get_template_directory();
			});

			add_filter( 'stylesheet_directory', function() use ( $theme ) {
				return $theme->get_stylesheet_directory();
			});

			add_filter( 'stylesheet', function() use ( $theme ) {
				return $theme->get_stylesheet();
			});

			add_filter( 'template', function() use ( $theme ) {
				return $theme->get_template();
			});

			// テーマ固有のリソース（スタイル・スクリプト）を登録
			add_action( 'wp_enqueue_scripts', function() use ( $theme ) {
				wp_enqueue_style( 'iframe-theme-style', $theme->get_stylesheet_directory_uri() . '/style.css', array(), null );

				if ( file_exists( $theme->get_stylesheet_directory() . '/js/main.js' ) ) {
					wp_enqueue_script( 'iframe-theme-script', $theme->get_stylesheet_directory_uri() . '/js/main.js', array(), null, true );
				}
			}, 20 );
		} else {
			error_log( 'Theme does not exist: ' . $selected_theme );
		}
	}
}
add_action( 'setup_theme', 'vkpdc_switch_theme_for_iframe', 1 );
