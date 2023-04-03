<?php
/**
 * VK Patterns Content Single
 *
 * @package VK Patterns
 */

/**
 * Add Copy Button after content
 *
 * @param string $content Post Content.
 */
function vk_patterns_content_single( $content ) {

	// グローバル変数 $post を確保.
	global $post;
	$post_id        = $post->ID;
	$options        = vk_patterns_get_options();
	$return_content = '';

	// 投稿タイプ VK Patterns の場合.
	if ( 'vk-patterns' === $post->post_type && empty( $_GET['view'] ) ) {

		// プレミアムパターンか否か
		$is_premium = vk_patterns_is_premium_pattern( $post_id );

		// プレミアムアラート
		$premium_alert = vk_patterns_premium_alert();

		// プロダクトアラートを追加
		$product_alert = vk_patterns_get_product_alert( $post_id );

		// iframe の幅をコントロールするボタン.
		$select_button = vk_patterns_get_size_selector( 'single' );

		// Iframe を適用したコンテンツを取得.
		$iframe_content = vk_patterns_get_iframe_content( $post_id, 'single' );

		// コピーボタン用の HTML.
		$copy_button = vk_patterns_get_copy_button( $post_id, 'single' );

		// ログインボタン
		$login_button = vk_patterns_get_login_button();
		
		// デモサイトボタンの HTML
		$demo_site_button = vk_patterns_get_demo_site_button( $post_id, 'single' );

		// プレミアムボタン
		$premium_button = vk_patterns_get_premium_button();

		// お気に入りボタン
		$favorite_button = vk_patterns_get_favorite_button( $post_id, 'single' );

		// コンテンツの生成開始
		$return_content  = '<div class="vk-patterns"><div class="vk-patterns-outer-single">';

		// プレミアムパターンかつ 未ログイン or 無料ユーザーの場合アラートを追加
		if ( ( ! is_user_logged_in() || current_user_can( 'free-user' ) ) && ! empty( $is_premium ) ) {
			$return_content .= $premium_alert;
		}

		// 使用プロダクト・幅切り替え・パターンのコンテンツを追加
		$return_content .= $product_alert . $select_button . $iframe_content;

		// プレミアムパターンの場合
		if ( ! empty( $is_premium ) ) {

			// ログインしていない場合
			if ( ! is_user_logged_in() ) {

				// ログインボタンとプレミアムボタンを表示
				$return_content .= '<div class="container">' . $login_button . $premium_button . '</div>';

				// 無料ユーザーがログインしている場合				
			} elseif ( current_user_can( 'free-user' ) ) {

				// デモサイトボタンとプレミアムボタンを表示
				$return_content .= '<div class="container">' . $demo_site_button . $premium_button . '</div>';

				// 管理者かライセンス保有ユーザーがログインしている場合
			} elseif ( current_user_can( 'pro-user' ) || current_user_can( 'administrator' ) ) {

				// コピーボタン・デモサイトボタン・お気に入りボタンを表示
				$return_content .= '<div class="container">' . $copy_button . $demo_site_button . $favorite_button . '</div>';
			}
			
			// 普通のパターンの場合
		} else {

			// 管理者かライセンス保有ユーザーがログインしている場合
			if ( current_user_can( 'pro-user' ) || current_user_can( 'administrator' ) ) {

				// コピーボタン・デモサイトボタン・お気に入りボタンを表示
				$return_content .= '<div class="container">' . $copy_button . $demo_site_button . $favorite_button . '</div>';

				// そうでない場合
			} else {

				// コピーボタン・デモサイトボタンを表示
				$return_content .= '<div class="container">' . $copy_button . $demo_site_button . '</div>';
			}			
		}

		$return_content .= '</div></div>';

		// コピーボタンを記事本文直後に追加.
		if ( ! empty( $options['developer_mode'] ) && true === $options['developer_mode'] ) {
			$return_content .= '<div class="vk-patterns"><div class="vk-patterns-outer-development">' . $content . '</div></div>';
		}
		$content = $return_content;
	}

	return $content;
}
add_filter( 'the_content', 'vk_patterns_content_single', 0 );
