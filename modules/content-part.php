<?php
/**
 * Parts of Pattern Directory
 *
 * @package VK Pattern Directory Creator
 */

// 必要なフィルターを設置.
add_filter( 'vkpdc_content', 'do_blocks', 9 );
add_filter( 'vkpdc_content', 'wptexturize' );
add_filter( 'vkpdc_content', 'convert_smilies', 20 );
add_filter( 'vkpdc_content', 'shortcode_unautop' );
add_filter( 'vkpdc_content', 'prepend_attachment' );
add_filter( 'vkpdc_content', 'wp_filter_content_tags' );
add_filter( 'vkpdc_content', 'do_shortcode', 11 );
add_filter( 'vkpdc_content', 'capital_P_dangit', 11 );


/**
 * 幅を切り替えるボタンを作る関数
 *
 * @param string $page_type 個別ページかアーカイブページか.
 * @return string $select_button : 幅切り替えのドロップダウン.
 */
function vkpdc_get_size_selector( $page_type = 'single' ) {
	// iframe の幅のリスト.
	$size_array = vkpdc_iframe_sizes();

	$select_outer_classes = 'vk-pattern-directory-creator-size';
	if ( ! empty( $page_type ) ) {
		$select_outer_classes .= ' vk-pattern-directory-creator-size--' . $page_type;
		if ( 'single' === $page_type ) {
			$select_outer_classes .= ' container';
		}
	}

	// iframe の幅をコントロールするボタン.
	$select_button  = '<div class="' . $select_outer_classes . '">';
	$select_button .= '<select class="vk-pattern-directory-creator-size-select">';
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
 * @param boolean $mini ラッパーをミニサイズにするか否か.
 *
 * @return string $iframe_content : Iframe で表示する内容
 */
function vkpdc_get_iframe_content( $post_id, $page_type = 'single', $mini = false ) {

	// 投稿 ID から情報を取得.
	$post    = get_post( $post_id );
	$title   = $post->post_title;
	$content = $post->post_content;

	$pattern_content = apply_filters( 'vkpdc_content', $content );
	$iframe_wrapper  = 'vk-pattern-directory-creator-iframe-wrapper';
	if ( ! empty( $page_type ) ) {
		$iframe_wrapper .= ' vk-pattern-directory-creator-iframe-wrapper--' . $page_type;
	}
	$patterns_container = true === $mini ? 'vk-pattern-directory-creator-container-mini' : 'vk-pattern-directory-creator-container';
	$scroling           = 'single' === $page_type ? 'yes' : 'no';

	// Iframe の href に指定する url.
	$url = get_permalink( $post_id ) . '?view=true';

	// Iframe で表示する要素の HTML.
	$iframe_content  = '<div class="' . $iframe_wrapper . '">';
	$iframe_content .= '<iframe class="vk-pattern-directory-creator-iframe" scrolling="' . $scroling . '" src="' . $url . '"></iframe>';
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

	// プレミアムパターンか否か.
	$is_premium = vkpdc_is_premium_pattern( $post_id );

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
	$copy_outer_classes = 'vk-pattern-directory-creator-copy';

	// ページタイプに応じてクラス名を追加.
	if ( ! empty( $page_type ) ) {
		$copy_outer_classes .= ' vk-pattern-directory-creator-copy--' . $page_type;
	}

	// プレミアムパターンならクラスを追加.
	if ( ! empty( $is_premium ) ) {
		$copy_outer_classes .= ' vk-pattern-directory-creator-premium';
	}

	// コピーボタンのタイトル属性.
	$copy_title = $copy_button_data[ $page_type ]['copy_title'];

	// コピーボタンのテキスト.
	$copy_text = $copy_button_data[ $page_type ]['copy_text'];

	// コピーボタンを生成.
	$copy_button  = '<div class="' . $copy_outer_classes . '"  data-post="' . $post_id . '">';
	$copy_button .= '<a class="vk-pattern-directory-creator-copy-button btn btn-primary" data-clipboard-text="' . esc_attr( $content ) . '"' . $copy_title . '>';
	$copy_button .= '<span class="vk-pattern-directory-creator-copy-button-icon"><i class="fas fa-copy"></i></span>';
	$copy_button .= '<span class="vk-pattern-directory-creator-copy-button-text">' . $copy_text . '</span>';
	$copy_button .= '</a>';
	$copy_button .= '</div>';

	// コピーボタンを返す.
	return $copy_button;
}
