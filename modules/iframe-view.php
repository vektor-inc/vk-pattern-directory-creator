<?php
/**
 * Iframe の表示内容
 *
 * @package VK Patterrns
 */

/**
 * iframe で表示中か
　*/
function vk_patterns_is_iframe_view() {
    $return = false;
    if (
        (
            'vk-patterns' === get_post_type() ||
            'vk-patterns' === get_query_var( 'post_type' )
        ) &&
        ! empty( $_GET['view']) &&
        is_singular( 'vk-patterns' )
    ) {
        $return = true;
    }
    return $return;

}

/**
 * 管理バーののスクリプトを追加
 */
function vk_patterns_admin_scripts() {
    wp_dequeue_script( 'admin-bar' );
    wp_dequeue_style( 'admin-bar' );
}

/**
 * Ifreme 用のスクリプトを追加
 */
function vk_patterns_iframe_scripts() {
    wp_enqueue_style( 'vk_patterns-iframe', VK_PATTERNS_PLUGIN_URL . 'assets/build/css/iframe.css', array(), VK_PATTERNS_PLUGIN_VERSION );
}

/**
 * X-T9 のスクリプトを追加
 */
function vk_patterns_xt9_scripts() {
    $xt9_url = WP_CONTENT_URL . '/themes/x-t9/';
    $xt9_ver = wp_get_theme( 'x-t9' )->Version;
    
    // Lightning のCSS・JSを削除
    wp_deregister_style( 'lightning-common-style' );
    wp_deregister_style( 'lightning-design-style' );
    wp_deregister_style( 'lightning-theme-style' );
    wp_deregister_style( 'vk-header-top' );
    wp_deregister_style( 'vk-header-layout' );
    wp_deregister_style( 'vk-campaign-text' );
    wp_deregister_style( 'vk-mobile-fix-nav' );
    wp_deregister_style( 'classic-theme-styles' );
    wp_deregister_script( 'lightning-js' );

    // X-T9 の CSS・JS を登録
    wp_enqueue_style( 'xt9-style', $xt9_url . '/assets/css/style.css', array(), $xt9_ver );
    wp_enqueue_style( 'bootatrap-style', VK_PATTERNS_PLUGIN_URL . 'library/bootstrap/css/bootstrap.min.css', array(), '4.6.2' );    
    wp_register_script( 'xt9-js', $xt9_url . '/assets/js/main.js', array(), $xt9_ver, true );

	$options = array(
		'header_scrool' => true,
	);
	wp_localize_script( 'xt9-js', 'xt9Opt', apply_filters( 'xt9_localize_options', $options ) );

	wp_enqueue_script( 'xt9-js' );    
}

/**
 * 使用プロダクトのリストの配列
 */
function vk_patterns_get_used_products() {
    // 使用プロダクトの配列を取得
    global $post;
    $terms = get_the_terms( $post->ID, 'pattern-product' );
    $term_array = array();

    if ( ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $term_array[] = $term->slug;
        }
    }

    return $term_array;
}

/**
 * スキンのスクリプトを追加
 */
function vk_patterns_skin_scripts() {
    $skins = Lightning_Design_Manager::get_skins();
    $products = vk_patterns_get_used_products();

    if ( in_array( 'evergreen', $products ) ) {
        wp_deregister_style( 'lightning-design-style' );
        wp_enqueue_style( 'lightning-design-style', $skins['evergreen']['css_url'], array( 'lightning-common-style' ), $skins['evergreen']['version'] );
    } elseif ( in_array( 'vekuan', $products ) ) {
        wp_deregister_style( 'lightning-design-style' );
        wp_enqueue_style( 'lightning-design-style', $skins['vekuan-g3']['css_url'], array( 'lightning-common-style' ), $skins['vekuan-g3']['version'] );
    }
}

/**
 * テーマサポートを追加
 */
function vk_patterns_theme_support() {
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'block-templates' );
}
add_action( 'after_setup_theme', 'vk_patterns_theme_support' );

/**
 * iframe 時に専用のテンプレートに切り替え
 */
function vk_patterns_load_iframe_template() {

    // iframe 用のテンプレートでない場合何もしない
    if ( ! vk_patterns_is_iframe_view() ) {
        return;
	}

    // 現在のテーマを取得
    $current_theme = get_template();
    // 使用プロダクト一覧を取得
    $products      = vk_patterns_get_used_products();

    // VK グローバルナビを削除
    global $vk_super_global_navigation;
    remove_filter( 'wp_footer', array( &$vk_super_global_navigation, 'add_navigation' ) );
    remove_action( 'wp_enqueue_scripts', array( &$vk_super_global_navigation, 'add_style' ));
    remove_action( 'wp_enqueue_scripts', array( &$vk_super_global_navigation, 'add_script' ) );

    // ページトップボタンを削除
    remove_action( 'wp_footer', 'veu_add_pagetop' );

    // 管理バーを削除
    add_filter( 'show_admin_bar', '__return_false');
    add_action( 'wp_enqueue_scripts', 'vk_patterns_admin_scripts', 2147483646 );
    
    // Iframe 用の CSS を追加
    add_action( 'wp_enqueue_scripts', 'vk_patterns_iframe_scripts', 2147483647 );

    //　現在のテーマがブロックテーマの場合
    if ( wp_is_block_theme() ) {

        // ブロックテーマ用の Iframe テンプレートを用意して読み込む
        $template  = VK_PATTERNS_PLUGIN_PATH . '/views/view-block-theme.php';
        $type      = 'single';       
        $templates = array( 
            'content' => '<div class="vk-patterns-container"><!-- wp:post-content /--></div>'
        );
        include locate_block_template( $template, $type, $templates );

    } elseif ( in_array( 'x-t9', $products ) ) { // X-T9 を想定したパターンの場合

        // テーマを X-T9 に切り替え
        switch_theme( 'x-t9' );

        // X-T9 で必要なスクリプトを読み込み不要なスクリプトを排除
        add_action( 'wp_enqueue_scripts', 'vk_patterns_xt9_scripts', 2147483646 );

        // ブロックテーマ用の Iframe テンプレートを用意して読み込む
        $template  = VK_PATTERNS_PLUGIN_PATH . '/views/view-block-theme.php';
        $type      = 'single';       
        $templates = array( 
            'content' => '<div class="vk-patterns-container"><!-- wp:post-content /--></div>'
        );
        include locate_block_template( $template, $type, $templates );

        // テーマをもともとのテーマに戻す
        switch_theme( $current_theme );

    } else { // Lightning を想定したパターンの場合

        // テーマを Lightning に切り替え
        switch_theme( 'lightning' );

        // スキンを使用している場合スキンの CSS に切り替え
        if ( class_exists( 'Lightning_Design_Manager' ) && in_array( 'evergreen', $products ) || in_array( 'vekuan', $products ) ) {
            add_action( 'wp_enqueue_scripts', 'vk_patterns_skin_scripts', 2147483646 );
        }

        // クラシックテーマ用の Iframe テンプレートを用意して読み込む
        include VK_PATTERNS_PLUGIN_PATH . '/views/view-classic-theme.php';

         // テーマをもともとのテーマに戻す
        switch_theme( $current_theme );
        
    }
    exit;

}
add_filter( 'template_redirect', 'vk_patterns_load_iframe_template', 2147483647 );

