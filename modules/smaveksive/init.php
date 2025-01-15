<?php
/**
 * SmaVeksive 初期化
 *
 * @package VK Pattern Directory Creator
 */

// SmaVeksive用の関数を読み込む
require_once plugin_dir_path( __FILE__ ) . 'functions.php';

// SmaVeksive用のコンテンツパーツを読み込む
require_once plugin_dir_path( __FILE__ ) . 'content-part.php';

// SmaVeksive用のコンテンツシングルを読み込む
require_once plugin_dir_path( __FILE__ ) . 'content-single.php';

// スタイルを読み込む
function vkpdc_enqueue_smaveksive_styles() {
    wp_enqueue_style(
        'vkpdc-smaveksive-style',
        plugin_dir_url( __FILE__ ) . 'style.css',
        array(),
        '1.0.0'
    );
}
add_action( 'wp_enqueue_scripts', 'vkpdc_enqueue_smaveksive_styles' ); 