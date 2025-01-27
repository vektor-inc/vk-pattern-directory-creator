<?php
/**
 * Parts of Pattern Directory
 *
 * @package VK Pattern Directory Creator
 */

/**
 * 幅を切り替えるボタンを作る関数
 *
 * @param string $page_type 個別ページかアーカイブページか.
 * @return string $select_button : 幅切り替えのドロップダウン.
 */
function vkpdc_get_size_selector_smaveksive( $page_type = 'single' ) {
	// iframe の幅のリスト.
	$size_array = vkpdc_iframe_sizes();

	$outer_classes = 'vkpdc_select-outer vkpdc_select-outer--size';
	if ( ! empty( $page_type ) ) {
		$outer_classes .= ' vkpdc_select-outer--' . $page_type;
	}

	// $html変数を初期化
	$html = '';

	// SmaVeksiveブロックがない場合のみ「Choose Screen Width / Full Width」を追加
	if ( vkpdc_has_smaponsive_block() ) {
		// iframe の幅をコントロールするボタン.
		$html  = '<div class="' . $outer_classes . '">';
		$html .= '<select class="vkpdc_select vkpdc_select--size">';
		$html .= '<option value="100%">' . __( 'Choose Screen Width / Full Width', 'vk-pattern-directory-creator' ) . '</option>';
		foreach ( $size_array as $size ) {
			$html .= '<option value="' . $size['value'] . '">' . $size['label'] . '</option>';
		}
		$html .= '</select>';
		$html .= '</div>';
	}

	return $html;
}

/**
 * Iframe を作る関数
 *
 * @param string  $post_id 投稿ID.
 * @param string  $page_type archive or single.
 *
 * @return string $iframe_content : Iframe で表示する内容
 */
function vkpdc_get_iframe_content_smaveksive( $post_id, $page_type = 'single' ) {

	$iframe_wrapper  = 'vkpdc_iframe-wrapper';
	if ( ! empty( $page_type ) ) {
		$iframe_wrapper .= ' vkpdc_iframe-wrapper--' . $page_type;
	}

	if ( ! vkpdc_has_smaponsive_block() ) {
	// 新しいクラスを追加
	$iframe_wrapper .= ' vkpdc_iframe-wrapper--smaveksive';
	}

	// 表示するテーマを設定
	$view = get_option( 'vkpdc_selected_theme', '' );

	if ( empty( $view ) ) {
		$view = vkpdc_iframe_view_theme();
	}

	// Iframe の href に指定する url.
	$url = get_permalink( $post_id ) . '?view=iframe';
	if ( $view !== 'default' ) {
		$url .= '&theme=' . urlencode( $view );
	}

	// Iframe で表示する要素の HTML.
	$iframe_content  = '<div class="' . esc_attr( $iframe_wrapper ) . '">';
	$iframe_content .= '<iframe class="vkpdc_iframe" src="' . esc_url( $url ) . '"></iframe>';
	$iframe_content .= '</div>';

	// iframe 化した コンテンツを返す.
	return $iframe_content;
}
