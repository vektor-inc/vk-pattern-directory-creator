<?php


/**
 * カスタムメタタグフィールドを登録する。
 */
function vkpdc_add_pattern_description_meta() {
    register_post_meta(
        'vk-patterns',
        'pattern_description', 
        array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        )
    );
}
add_action( 'init', 'vkpdc_add_pattern_description_meta' );

/**
 * Registers the `vk-pattern-directory-creator/favorite-filter` block. */

 function vkpdc_add_pattern_description_block() {

    $asset_file = 'block.asset.php';

	$block_js = './block.js';
	wp_register_script(
		'vkpdc-pattern-description-block',
		plugins_url( $block_js, __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	$editor_css = './editor.css';
	wp_register_style(
		'vkpdc-pattern-description-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		$asset_file['version']
	);

	$style_css = './style.css';
	wp_register_style(
		'vkpdc-pattern-description-style',
		plugins_url( $style_css, __FILE__ ),
		array(),
		$asset_file['version']
	);

	register_block_type(
		__DIR__,
		array(
			'editor_script'   => 'vkpdc-pattern-description-block',
			'editor_style'    => 'vkpdc-pattern-description-editor',
			'style'           => 'vkpdc-pattern-description-style',
		)
	);
}
add_action( 'init', 'vkpdc_add_pattern_description_block', 9999 );

/**
 * パターンの説明を保存する。
 * 
 * @param int $post_id Post ID.
 * @param WP_Post $post Post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function vkpdc_save_pattern_description( $post_id, $post, $update ) {

	// 投稿タイプが vk-patterns でない場合は処理を中断
	if ( 'vk-patterns' !== $post->post_type ) {
		return $post_id;
	}

	// 自動保存時は処理を中断
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
  
    // ユーザー権限の確認
    if ( ! current_user_can( 'edit_page', $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }
	
	

    $delete_flag = true;

	// 空のブロックを削除
	$content = preg_replace( '/<!-- wp:vkpdc\/pattern-description \{.*?\} -->((\n|\r|\s)*?)<div class="wp-block-vkpdc-pattern-description vkpdc_pattern-description">((\n|\r|\s)*?)<\/div>((\n|\r|\s)*?)<!-- \/wp:vkpdc\/pattern-description -->/', '', $post->post_content );

    // 投稿コンテンツからパターンの説明を取得
    preg_match_all( '/<!-- wp:vkpdc\/pattern-description \{.*?\} -->((.|\n|\r|\s)+?)<!-- \/wp:vkpdc\/pattern-description -->/', $content, $block_content_array );


    // パターンの説明を保存
    if ( ! empty( $block_content_array ) ) {
		foreach ( $block_content_array[0] as $block_content ) {
			update_post_meta( $post_id, 'pattern_description', $block_content );
			$delete_flag = false;		
		}        
    }
	
	if ( true === $delete_flag ) {
		delete_post_meta( $post_id, 'pattern_description' );
	}
}
add_action( 'save_post', 'vkpdc_save_pattern_description', 10, 3 );