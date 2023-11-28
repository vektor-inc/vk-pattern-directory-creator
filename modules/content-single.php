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
		

		// 投稿タイプ VK Patterns の場合.
		if ( 'vk-patterns' === $post->post_type && empty( $_GET['view'] ) ) {
			$content = '';
			// iframe の幅をコントロールするボタン.
			$select_button = vkpdc_get_size_selector();

			// Iframe を適用したコンテンツを取得.
			$iframe_content = vkpdc_get_iframe_content( $post_id, 'single' );

			// コピーボタン用の HTML.
			$copy_button = vkpdc_get_copy_button( $post_id, 'single' );

			// iframe 上部のコンテンツ
			$setect = apply_filters( 'vkpdc_single_select', $select_button );

			// 下部のボタン
			$buttons = apply_filters( 'vkpdc_single_buttons', $copy_button );

			// コンテンツの生成開始.
			$content = '<div class="vkpdc vkpdc_single">';

			// トップ部分を追加
			$content .= $setect;
			$content .= '<div class="vkpdc_iframe-outer vkpdc_iframe-outer--single">' . $iframe_content . '</div>';

			// コピーボタンを表示.
			$content .= '<div class="vkpdc_buttons vkpdc_buttons--single">';
			$content .= $buttons;
			$content .= '</div>';

			$content .= '</div>';
		}
	}

	return $content;
}
add_filter( 'the_content', 'vkpdc_content_single', 0 );
