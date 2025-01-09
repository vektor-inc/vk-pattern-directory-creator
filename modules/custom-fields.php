<?php
/**
 * VK Patterns Custom Fields
 *
 * @package VK Patterns
 */

 /**
  * メタボックスの作成
  */
function vk_patterns_add_meta_box() {
    add_meta_box(
        'vk-patterns-matabox',          // Unique ID
        'VK Patterns Setting',     // タイトル
        'vk_patterns_meta_box_html',   // フォーム関数の呼び出し
        'vk-patterns',                 // 投稿タイプ
        'side',  // 表示場所
    );
}
add_action('add_meta_boxes', 'vk_patterns_add_meta_box');

/**
 * メタボックスの中身の HTML
 * 
 * @param Object $post 投稿の情報が詰まったオブジェクト.
 */
function vk_patterns_meta_box_html( $post ) {
    wp_nonce_field( 'vk_patterns_save_meta_box', 'vk_patterns_meta_box_nonce' );
    $no_margin     = ! empty( get_post_meta( $post->ID, 'vk-patterns-no-margin', true ) ) ? true : false;

    $html  = '<div class="vk-patterns-outer-wrap">';

    // 空白をつけるか否か
    $html .= '<div class="vk-patterns-label">余白設定</div>';
    $html .= '<div class="vk-patterns-input-wrap vkfs__input-wrap--checkbox">';
    $html .= '<label><input type="checkbox" name="vk-patterns-no-margin" ' . checked( $no_margin, true, false ) . ' />iframeの上下余白を無しにする</label>';
    $html .= '</div>';

    $html .= '</div>';
    echo $html;
}

/**
 * メタボックスの中身の HTML
 * 
 * @param in t $post_id 投稿ID.
 */
function vk_patterns_save_meta_box( $post_id ) {

    if (
        ! isset( $_POST['vk_patterns_meta_box_nonce'] ) ||
        ! wp_verify_nonce( $_POST['vk_patterns_meta_box_nonce'], 'vk_patterns_save_meta_box' ) ||
        defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE
     ) {
        return;
    }

    	// ユーザー権限の確認
	if ( isset( $_POST['post_type'] ) && 'vk-patterns' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

    // 空白をつけるか否かを保存
    $no_margin = ! empty( $_POST['vk-patterns-no-margin'] ) ? true : false;
    update_post_meta( $post_id, 'vk-patterns-no-margin', $no_margin );
}
add_action('save_post', 'vk_patterns_save_meta_box');