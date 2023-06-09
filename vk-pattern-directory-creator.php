<?php
/**
 * Plugin Name: VK Pattern Directory Creator
 * Plugin URI: https://github.com/vektor-inc/vk-pattern-directory-creator
 * Description: This is a plugin that you can make original pattern directory site.
 * Version: 0.1.0
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * Author:  Vektor,Inc.
 * Author URI: https://vektor-inc.co.jp
 * Text Domain: vk-pattern-directory-creator
 * License: GPL 2.0 or Later
 *
 * @package VK Pattern Directory Creator
 */

defined( 'ABSPATH' ) || exit;

// Define Plugin  Root Path.
define( 'VKPDC_PLUGIN_ROOT_PATH', plugin_dir_path( __FILE__ ) );
// Define Plugin Root URL.
define( 'VKPDC_PLUGIN_ROOT_URL', plugin_dir_url( __FILE__ ) );
// Define Plugin Version.
$vkpdc_plugin_data = get_file_data( __FILE__, array( 'version' => 'Version' ) );
define( 'VKPDC_PLUGIN_VERSION', $vkpdc_plugin_data['version'] );

// 共通パスを定義.
$vkpdc_modules_path = dirname( __FILE__ ) . '/modules/';

// カスタム投稿タイプを作成.
require_once $vkpdc_modules_path . 'register-post-type.php';


