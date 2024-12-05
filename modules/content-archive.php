<?php
/**
 * VK Patterns Content Archive
 *
 * @package VK Patterns
 */

/**
 * Archive Single Pattern 
 *
 * @param string $post 投稿のオブジェクト.
 */
function vkpdc_get_archive_single_post( $post = null ) {

	$html    = '';
	$post = ! empty( $post ) ? $post : get_post( get_the_ID() );

	if ( ! empty( $post ) ) {        

		/* iframe */
		$iframe = vkpdc_get_iframe_content( $post->ID, 'archive' );

		/* タクソノミー */
		// 変数初期化
		$taxonomy_html = '';

		// タクソノミーの取得.
		$args       = array(
			'template'      => '<dt class="vkpdc_post_taxonomy_title"><span class="vkpdc_post_taxonomy_title_inner">%s</span></dt><dd class="vkpdc_post_taxonomy_terms">%l</dd>',
			'term_template' => '<a href="%1$s">%2$s</a>',
		);
		$taxonomies = get_the_taxonomies( $post->ID, $args );

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
		   $taxonomy_html .= '<div class="vkpdc_post_taxonomies">';
			foreach ( $taxonomies as $key => $value ) {
			   $taxonomy_html .= '<dl class="vkpdec_post_taxonomy vkpdec_post_taxonomy-' . $key . '">' . $value . '</dl>';
			}
		   $taxonomy_html .= '</div>';
		}

		/* 日付 */
		$date = get_the_date( '', $post->ID );
		$modified_date = get_the_modified_date( '', $post->ID );

		/* リンクボタン */
		$link_button  = '<div class="vkpdc_button-outer vkpdc_button-outer--view">';
		$link_button .= '<a class="vkpdc_button vkpdc_button--view" href="' . esc_attr( get_the_permalink( $post->ID ) ) . '">';
		$link_button .= '<span class="vkpdc_button-icon vkpdc_button-icon--view"><i class="fa-solid fa-circle-arrow-right fa-fw"></i></span>';
		$link_button .= '<span class="vkpdc_button-text vkpdc_button-text--view">' . __( 'Read More', 'vk-pattern-directory-creator' ) . '</span>';
		$link_button .= '</a>';
		$link_button .= '</div>';

		/* コピーボタン	*/
		$copy_button = vkpdc_get_copy_button( $post->ID, 'archive' );

		/* ボタンの集合体 */
		$buttons = apply_filters( 'vkpdc_archive_buttons', $link_button . $copy_button );

		/* 最初の article */
		$html .= '<article id="post-' . esc_attr( $post->ID ) . '" class="vkpdec_post ' . join( ' ', get_post_class( apply_filters( 'vkpdc_single_post_outer_class', '' ) ) ) . '">';

		/* 中身の追加 */
		// iframe
		$html .= '<div class="vkpdc_iframe-outer vkpdc_iframe-outer--archive">';
		$html .= '<a class="vkpdc_iframe-outer--view" href="' . esc_attr( get_the_permalink( $post->ID ) ) . '">';
		$html .= $iframe . apply_filters( 'vkpdc_single_post_iframe_after', '' );
		$html .= '</a>';
		$html .= '</div>';
		// タイトル
		$html .= '<div class="vkpdc_post_title">';
		$html .= '<a class="vkpdc_post_title--view" href="' . esc_attr( get_the_permalink( $post->ID ) ) . '">';
		$html .= apply_filters( 'vkpdc_post_title', get_the_title( $post->ID ), $post );
		$html .= '</a>';
		$html .= '</div>';
		// 日付と更新日
		$html .= '<div class="vkpdc_post_date">';
		$html .= '<span class="vkpdc_post_date--published">' . __( 'Published:', 'vk-pattern-directory-creator' ) . ' ' . esc_html( $date ) . '</span>';
		$html .= '<span class="vkpdc_post_date--modified">' . __( 'Updated:', 'vk-pattern-directory-creator' ) . ' ' . esc_html( $modified_date ) . '</span>';
		$html .= '</div>';
		// タクソノミー
		$html .= $taxonomy_html;
		// パターンID
		$html .= '<div class="vkpdc_post_id">';
		$html .= '<span>' . __( 'Pattern ID:', 'vk-pattern-directory-creator' ) . ' ' . esc_html( $post->ID ) . '</span>';
		$html .= '</div>';
		// ボタン
		$html .= '<div class="vkpdc_buttons vkpdc_buttons--archive">' . $buttons . '</div>';

		/* 最後の article */
		$html .= '</article>';
	}

	return $html;    

}
add_shortcode( 'vkpdc_archive_single_post', 'vkpdc_get_archive_single_post' );

/**
 * Archive Loop
 * 
 * @param string $query クエリ.
 */
function vkpdc_get_archive_loop( $query = null, $attributes = [] ) {

	global $wp_query;
	$query = ! empty( $query ) ? $query : $wp_query;
	$theme = get_template();

	$attributes = wp_parse_args(
		$attributes,
		[
			'colWidthMin'       => '300px',
			'colWidthMinTablet' => '300px',
			'colWidthMinPC'     => '300px',
			'gap'               => '1.5rem',
			'gapRow'            => '1.5rem',
		]
	);

	// 動的スタイルを生成
	$styles = sprintf(
		'--col-width-min: %s; --col-width-min-tablet: %s; --col-width-min-pc: %s; --gap: %s; --gap-row: %s;',
		esc_attr( $attributes['colWidthMin'] ),
		esc_attr( $attributes['colWidthMinTablet'] ),
		esc_attr( $attributes['colWidthMinPC'] ),
		esc_attr( $attributes['gap'] ),
		esc_attr( $attributes['gapRow'] )
	);

	$html = '';

	
	if ( $query->have_posts() ) {
		$html .= '<div class="vkpdc_posts vkpdc_posts_theme--' . esc_attr( $theme ) . '" style="' . esc_attr( $styles ) . '">';

		while ( $query->have_posts() ) {
			$query->the_post();
			$post  = get_post( get_the_ID() );
			$html .= vkpdc_get_archive_single_post( $post, $attributes );
		}
		
		$html .= '</div>';
		
	} else {

		$html .= '<div class="vkpdc_posts vkpdc_posts--none">';
		$html .= '<div class="vkpdc_post_title">' . __( 'No posts found.', 'vk-pattern-directory-creator' ) . '</div>';
		$html .= '</div>';

	}

	wp_reset_postdata();
	return $html;
}


function vkpdc_get_patterns_archive_shortcode() {
	if ( 'vk-patterns' === get_post_type() || 'vk-patterns' === get_query_var('post_type') ) {
		$html = vkpdc_get_archive_loop();
	}
	return $html;
}
add_shortcode( 'vkpdc_archive_loop', 'vkpdc_get_patterns_archive_shortcode' );
