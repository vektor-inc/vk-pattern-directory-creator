<?php
/**
 * VK Patterns Content Archive Settings with Shortcode Generator
 *
 * @package VK Patterns
 */

// CSSエンキュー
function vkpdc_enqueue_styles() {
	$css_file = plugin_dir_path( __FILE__ ) . 'assets/build/css/style.css';
	wp_enqueue_style(
		'vkpdc-style',
		plugins_url( 'assets/build/css/style.css', __FILE__ ),
		array(),
		filemtime( $css_file )
	);
}
add_action( 'wp_enqueue_scripts', 'vkpdc_enqueue_styles' );

/**
 * デフォルトオプション取得関数
 */
function vkpdc_get_default_options() {
	return array(
		'vkpdc_numberposts'          => 6,
		'vkpdc_order'                => 'DESC',
		'vkpdc_orderby'              => 'date',
		'vkpdc_display_image'        => 'featured',
		'vkpdc_display_btn_view_text'=> __( 'Read More', 'vk-pattern-directory-creator' ),
		'vkpdc_colWidthMin'          => '300px',
		'vkpdc_colWidthMinTablet'    => '300px',
		'vkpdc_colWidthMinPC'        => '300px',
		'vkpdc_gap'                  => '1.5rem',
		'vkpdc_gapRow'               => '1.5rem',
	);
}

/**
 * オプションの保存処理
 */
function vkpdc_save_settings() {
	check_admin_referer( 'vkpdc_save_settings', 'vkpdc_settings_nonce' );

	if ( isset( $_POST['reset'] ) ) {
		// リセット処理
		foreach ( vkpdc_get_default_options() as $key => $value ) {
			update_option( $key, $value );
		}
		add_settings_error( 'vkpdc_settings', 'reset', __( 'Settings reset to default.', 'vk-pattern-directory-creator' ), 'updated' );
	} else {
		// 保存処理
		foreach ( vkpdc_get_default_options() as $key => $default ) {
			if ( isset( $_POST[ str_replace( 'vkpdc_', '', $key ) ] ) ) {
				$value = sanitize_text_field( $_POST[ str_replace( 'vkpdc_', '', $key ) ] );
				update_option( $key, $value );
			}
		}
		add_settings_error( 'vkpdc_settings', 'saved', __( 'Settings saved.', 'vk-pattern-directory-creator' ), 'updated' );
	}
}

/**
 * 設定ページレンダリング
 */
function vkpdc_render_settings_page_with_shortcode() {
	if ( ! current_user_can( 'manage_options' ) ) return;

	if ( isset( $_POST['vkpdc_settings_nonce'] ) ) vkpdc_save_settings();

	settings_errors( 'vkpdc_settings' );
	$options = vkpdc_get_default_options();
	foreach ( $options as $key => $default ) {
		$options[ $key ] = get_option( $key, $default );
	}

	$generated_shortcode = sprintf(
		'[vkpdc_archive_loop numberposts="%d" order="%s" orderby="%s" display_image="%s" display_btn_view_text="%s" colWidthMin="%s" colWidthMinTablet="%s" colWidthMinPC="%s" gap="%s" gapRow="%s"]',
		$options['vkpdc_numberposts'],
		esc_attr( $options['vkpdc_order'] ),
		esc_attr( $options['vkpdc_orderby'] ),
		esc_attr( $options['vkpdc_display_image'] ),
		esc_attr( $options['vkpdc_display_btn_view_text'] ),
		esc_attr( $options['vkpdc_colWidthMin'] ),
		esc_attr( $options['vkpdc_colWidthMinTablet'] ),
		esc_attr( $options['vkpdc_colWidthMinPC'] ),
		esc_attr( $options['vkpdc_gap'] ),
		esc_attr( $options['vkpdc_gapRow'] )
	);

	include 'templates/settings-page.php';
}

/**
 * 管理画面のメニュー追加
 */
function vkpdc_add_settings_page() {
	add_options_page(
		__( 'VK Patterns Settings', 'vk-pattern-directory-creator' ),
		__( 'VK Patterns', 'vk-pattern-directory-creator' ),
		'manage_options',
		'vk-patterns-settings',
		'vkpdc_render_settings_page_with_shortcode'
	);
}
add_action( 'admin_menu', 'vkpdc_add_settings_page' );

/**
 * プレビュー用のショートコード出力
 */
function vkpdc_render_preview_page() {
	if ( isset( $_GET['vkpdc_preview'] ) && $_GET['vkpdc_preview'] === '1' ) {
		$options = vkpdc_get_default_options();
		foreach ( $options as $key => $default ) {
			$options[ $key ] = get_option( $key, $default );
		}

		echo '<!DOCTYPE html><html><head>';
		wp_head();
		echo '</head><body>';
		echo do_shortcode( sprintf(
			'[vkpdc_archive_loop numberposts="%d" order="%s" orderby="%s" display_image="%s" display_btn_view_text="%s" colWidthMin="%s" colWidthMinTablet="%s" colWidthMinPC="%s" gap="%s" gapRow="%s"]',
			$options['vkpdc_numberposts'],
			esc_attr( $options['vkpdc_order'] ),
			esc_attr( $options['vkpdc_orderby'] ),
			esc_attr( $options['vkpdc_display_image'] ),
			esc_attr( $options['vkpdc_display_btn_view_text'] ),
			esc_attr( $options['vkpdc_colWidthMin'] ),
			esc_attr( $options['vkpdc_colWidthMinTablet'] ),
			esc_attr( $options['vkpdc_colWidthMinPC'] ),
			esc_attr( $options['vkpdc_gap'] ),
			esc_attr( $options['vkpdc_gapRow'] )
		) );
		wp_footer();
		echo '</body></html>';
		exit;
	}
}
add_action( 'template_redirect', 'vkpdc_render_preview_page' );
