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

	$outer_classes = 'vkpdc_select-outer vkpdc_select-outer--size';
	if ( ! empty( $page_type ) ) {
		$outer_classes .= ' vkpdc_select-outer--' . $page_type;
	}

	// iframe の幅をコントロールするボタン.
	$html  = '<div class="' . $outer_classes . '">';
	$html .= '<select class="vkpdc_select vkpdc_select--size">';
	$html .= '<option value="100%">' . __( 'Choose Screen Width / Full Width', 'vk-patterns' ) . '</option>';
	foreach ( $size_array as $size ) {
		$html .= '<option value="' . $size['value'] . '">' . $size['label'] . '</option>';
	}
	$html .= '</select>';
	$html .= '</div>';

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
function vkpdc_get_iframe_content( $post_id, $page_type = 'single' ) {

    $iframe_wrapper  = 'vkpdc_iframe-wrapper';
    if ( ! empty( $page_type ) ) {
        $iframe_wrapper .= ' vkpdc_iframe-wrapper--' . $page_type;
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
	$copy_outer_classes = 'vkpdc_button-outer vkpdc_button-outer--copy';

	// ページタイプに応じてクラス名を追加.
	if ( ! empty( $page_type ) ) {
		$copy_outer_classes .= ' vkpdc_button--' . $page_type;
	}

	// コピーボタンのタイトル属性.
	$copy_title = $copy_button_data[ $page_type ]['copy_title'];

	// コピーボタンのテキスト.
	$copy_text = $copy_button_data[ $page_type ]['copy_text'];

	// ボタン本体の属性
	$button_attributes  = ' data-clipboard-text="' . esc_attr( $content ) . '"' . $copy_title;
	$button_attributes .= apply_filters( 'vkpdc_copy_button_attributes', '' );


	// コピーボタンを生成.
	$copy_button  = '<div class="' . $copy_outer_classes . '">';
	$copy_button .= '<a class="vkpdc_button vkpdc_button--copy"' . $button_attributes . '>';
	$copy_button .= '<span class="vkpdc_button-icon vkpdc_button-icon--copy"><i class="fa-solid fa-copy fa-fw"></i></span>';
	$copy_button .= '<span class="vkpdc_button-text vkpdc_button-text--copy">' . $copy_text . '</span>';
	$copy_button .= '</a>';
	$copy_button .= '</div>';

	// コピーボタンを返す.
	return $copy_button;
}