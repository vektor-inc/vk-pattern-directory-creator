<?php
/**
 * VK Patterns Content Archive
 *
 * @package VK Patterns
 */

/**
 * Archive Single Pattern 
 *
 * @param string $content Post Content.
 */
function vkpdc_content_archive_single_post( $post_id = null ) {

    $html    = '';
    $post_id = ! empty( $post_id ) ? $post_id : get_the_ID();
    $post    = get_post( $post_id );

    if ( ! empty( $post ) ) {        

        /* iframe */
        $iframe = vkpdc_get_iframe_content( $post_id, 'archive' );

        /* タクソノミー */
        // 変数初期化
        $taxonomy_html = '';

        // タクソノミーの取得.
        $args       = array(
            'template'      => '<dt class="vkpdc_post_taxonomy_title"><span class="vkpdc_post_taxonomy_title_inner">%s</span></dt><dd class="vkpdc_post_taxonomy_terms">%l</dd>',
            'term_template' => '<a href="%1$s">%2$s</a>',
        );
        $taxonomies = get_the_taxonomies( $post_id, $args );

        // 除外するタクソノミー.
        $exclusion = array( 'product_type', 'language' );
        $exclusion = apply_filters( 'vkpdc_archive_display_taxonomies_exclusion', $exclusion );

        // 除外するタクソノミーを削除.
        if ( is_array( $exclusion ) ) {
            foreach ( $exclusion as $key => $value ) {
                unset( $taxonomies[ $value ] );
            }
        }

        // タクソノミーごとにタームを表示
        if ( ! empty( $taxonomies ) ) {
            $taxonomy_list .= '<div class="vkpdec_post_taxonomies">';
            foreach ( $taxonomies as $key => $value ) {
                $taxonomy_list .= '<dl class="vkpdec_post_taxonomy vkpdec_post_taxonomy-' . $key . '">' . $value . '</dl>';
            }
            $taxonomy_list .= '</div>';
        }

        /* リンクボタン */
        $link_button  = '<div class="vkpdc_button-outer vkpdc_button-outer--view">';
        $link_button .= '<a class="vkpdc_button vkpdc_button--view" href="' . esc_attr( get_the_permalink( $post_id ) ) . '">';
        $link_button .= '<span class="vkpdc_button-icon vkpdc_button-icon--view"><i class="fa-solid fa-circle-arrow-right fa-fw"></i></span>';
        $link_button .= '<span class="vkpdc_button-text vkpdc_button-text--view">' . __( 'Read More', 'vk-pattern-directory-creator' ) . '</span>';
        $link_button .= '</a>';
        $link_button .= '</div>';

        /* コピーボタン	*/
        $copy_button = vkpdc_get_copy_button( $post_id, 'archive' );

        /* ボタンの集合体 */
        $buttons = apply_filters( 'vkpdc_archive_buttons', $link_button . $copy_button );

        /* 最初の div */
        $html .= '<div id="post-' . esc_attr( $post_id ) . '" class="vkpdec_post vkpdc_post-type--' . esc_attr( $post->post_type ) . ' ' . join( ' ', get_post_class( apply_filters( 'vkpdc_single_post_outer_class', '' ) ) ) . '">';

        /* 中身の追加 */
        // iframe
        $html .= '<div class="vkpdc_iframe-outer vkpdc_iframe-outer--archive">' . $iframe . apply_filters( 'vkpdc_single_post_iframe_after', '' ) . '</div>';
        // タイトル
        $html .= '<div class="vkpdc_post_title">' . apply_filters( 'vkpdc_post_title', get_the_title( $post_id ), $post, $options ) . '</div>';
        // タクソノミー
        $html .= $taxonomy_list;
        // ボタン
        $$html .= '<div class="vkpdc_buttons vkpdc_buttons--archive">' . $buttons . '</div>';

        /* 最後の div */
        $html .= '</div>';
    }

    return $html;    

}