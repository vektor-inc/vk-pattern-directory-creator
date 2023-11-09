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
function vkpdc_content_single( $content ) {
	if ( is_singular() ) {

		// グローバル変数 $post を確保.
		global $post;
		$post_id        = $post->ID;
		$return_content = '';

		// 投稿タイプ VK Patterns の場合.
		if ( 'vk-patterns' === $post->post_type && empty( $_GET['view'] ) ) {

			// iframe の幅をコントロールするボタン.
			$select_button = vkpdc_get_size_selector();

			// Iframe を適用したコンテンツを取得.
			$iframe_content = vkpdc_get_iframe_content( $post_id, 'single' );

			// コピーボタン用の HTML.
			$copy_button = vkpdc_get_copy_button( $post_id, 'single' );

			// iframe 上部のコンテンツ
			$iframe_before = apply_filters( 'vkpdc_iframe_before', $select_button );

			// 下部のボタン
			$iframe_after = apply_filters( 'vkpdc_iframe_after', $copy_button );

			// コンテンツの生成開始.
			$return_content = '<div class="vkpdc vkpdc_single">';

			// トップ部分を追加
			$return_content .= $iframe_before;
			$return_content .= '<div class="vkpdc_iframe-outer vkpdc_iframe-outer--single">' . $iframe_content . '</div>';

			// コピーボタンを表示.
			$return_content .= '<div class="vkpdc_buttons vkpdc_buttons-single">';
			$return_content .= $iframe_after;
			$return_content .= '</div>';


			$return_content .= '</div>';

			// コピーボタンを記事本文直後に追加.
			if ( ! empty( $options['developer_mode'] ) && true === $options['developer_mode'] ) {
				$return_content .= '<div class="vkpdc"><div class="vkpdc_outer-development">' . $content . '</div></div>';
			}

			$content = $return_content;
		}
	}

	return $content;
}
add_filter( 'the_content', 'vkpdc_content_single', 0 );
