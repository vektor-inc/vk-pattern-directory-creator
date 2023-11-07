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
function vkpdc_get_size_selector( $page_type = 'single' ) {
	// iframe の幅のリスト.
	$size_array = vkpdc_iframe_sizes();

	$select_outer_classes = 'vkpdc-size';
	if ( ! empty( $page_type ) ) {
		$select_outer_classes .= ' vkpdc-size--' . $page_type;
		if ( 'single' === $page_type ) {
			$select_outer_classes .= ' vkpdc-container';
		}
	}

	// iframe の幅をコントロールするボタン.
	$select_button  = '<div class="' . $select_outer_classes . '">';
	$select_button .= '<select class="vkpdc-size-select">';
	foreach ( $size_array as $size ) {
		$select_button .= '<option value="' . $size['value'] . '">' . $size['label'] . '</option>';
	}
	$select_button .= '</select>';
	$select_button .= '</div>';

	return $select_button;
}

/**
 * Iframe を作る関数
 *
 * @param string  $post_id 投稿ID.
 * @param string  $page_type archive or single.
 *
 * @return string $iframe_content : Iframe で表示する内容
 */
function vkpdc_get_iframe_content( $post_id, $page_type = 'single' ) {

	$iframe_wrapper  = 'vkpdc-iframe-wrapper';
	if ( ! empty( $page_type ) ) {
		$iframe_wrapper .= ' vkpdc-iframe-wrapper--' . $page_type;
	}

	// 表示するテーマを設定
	$view = vkpdc_iframe_view_theme();

	// Iframe の href に指定する url.
	$url = get_permalink( $post_id ) . '?view=' . $view;

	// Iframe で表示する要素の HTML.
	$iframe_content  = '<div class="' . $iframe_wrapper . '">';
	$iframe_content .= '<iframe class="vkpdc-iframe" src="' . $url . '"></iframe>';
	$iframe_content .= '</div>';

	// iframe 化した コンテンツを返す.
	return $iframe_content;
}

/**
 * コピーボタンの共通データ
 *
 * @return array $data : コピーボタンの共通データ
 */
function vkpdc_get_copy_button_data() {
	$data = array(
		'single'  => array(
			'copy_title' => '',
			'copy_text'  => __( 'Copy This Pattern', 'vk-pattern-directory-creator' ),
		),
		'archive' => array(
			'copy_title' => ' title="' . __( 'Copy This Pattern', 'vk-pattern-directory-creator' ) . '" ',
			'copy_text'  => __( 'Copy', 'vk-pattern-directory-creator' ),
		),
	);

	return $data;
}

/**
 * Copy ボタンを作る関数
 *
 * @param string $post_id 投稿ID.
 * @param string $page_type 個別ページかアーカイブページか.
 *
 * @return string $copy_button : コピーボタン
 */
function vkpdc_get_copy_button( $post_id, $page_type = 'single' ) {

	// コピーボタンのデータを取得.
	$copy_button_data = vkpdc_get_copy_button_data();

	// ページタイプを確定.
	$page_type = array_key_exists( $page_type, $copy_button_data ) ? $page_type : 'single';

	// 投稿 ID から情報を取得.
	$post    = get_post( $post_id );
	$content = $post->post_content;

	// コピーボタン用にエスケープ処理を追加.
	$content = str_replace( '[', '\[', $content );
	$content = str_replace( ']', '\]', $content );

	// HTML エスケープ.
	$content = htmlspecialchars(
		$content,
		ENT_QUOTES,
		'UTF-8',
		true
	);

	// ボタンの外側のクラスを作成.
	$copy_outer_classes = 'vkpdc-copy';

	// ページタイプに応じてクラス名を追加.
	if ( ! empty( $page_type ) ) {
		$copy_outer_classes .= ' vkpdc-copy--' . $page_type;
	}

	// コピーボタンのタイトル属性.
	$copy_title = $copy_button_data[ $page_type ]['copy_title'];

	// コピーボタンのテキスト.
	$copy_text = $copy_button_data[ $page_type ]['copy_text'];

	// コピーボタンを生成.
	$copy_button  = '<div class="' . $copy_outer_classes . '"  data-post="' . $post_id . '">';
	$copy_button .= '<a class="vkpdc-copy-button" data-clipboard-text="' . esc_attr( $content ) . '"' . $copy_title . '>';
	$copy_button .= '<span class="vkpdc-copy-button-icon"><i class="fa-solid fa-copy  fa-fw"></i></span>';
	$copy_button .= '<span class="vkpdc-copy-button-text">' . $copy_text . '</span>';
	$copy_button .= '</a>';
	$copy_button .= '</div>';

	// コピーボタンを返す.
	return $copy_button;
}