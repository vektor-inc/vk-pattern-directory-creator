<?php
/**
 * VK Patterns Content Archive
 *
 * @package VK Patterns
 */

/**
 * Archive Loop
 */
function vkpdc_adjust_query( $query ) {
	
	// 管理画面ではなく、`vk-patterns` のアーカイブでのみ処理
	if (
		! is_admin() &&
		$query->is_main_query() &&
		( 
			is_archive( 'vk-patterns' ) || 
			is_archive( 'vk-patterns' ) && is_tax() || 
			is_front_page() 
		)
	) {
		// ブロックテーマかどうかを判定
		if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
			// ブロックテーマの場合
			$number_posts = 1;
			$paged = get_query_var( 'paged', 1 );

			// クエリの設定
			$query->set( 'posts_per_page', $number_posts );
			$query->set( 'paged', $paged );
		} else {
			// クラシックテーマの場合
			$number_posts = get_option( 'vkpdc_numberPosts', 6 );
			$paged = get_query_var( 'paged', 1 );

			// クエリの設定
			$query->set( 'posts_per_page', $number_posts );
			$query->set( 'paged', $paged );
		}
	}
	
}
add_action( 'pre_get_posts', 'vkpdc_adjust_query', 99 );

/**
 * Get Block Default Attributes
 */
function vkpdc_get_block_default_attributes() {
	return array(
		'numberPosts'            => 6,
		'order'                  => 'DESC',
		'orderby'                => 'date',
		'display_author'         => true,
		'display_date_publiched' => true,
		'display_date_modified'  => true,
		'display_new'            => true,
		'display_taxonomies'     => true,
		'pattern_id'             => true,
		'display_btn_view'       => true,
		'display_btn_copy'       => true,
		'display_image'          => 'featured',
		'thumbnail_size'         => 'full',
		'new_date'               => 7,
		'new_text'               => 'New!!',
		'display_btn_view_text'  => __( 'Read More', 'vk-pattern-directory-creator' ),
		'colWidthMinMobile'            => '300px',
		'colWidthMinTablet'      => '300px',
		'colWidthMinPC'          => '300px',
		'gap'                    => '1.5rem',
		'gapRow'                 => '1.5rem',
	);
}

/* 
 * Get Shortcode Default Attributes
 */
function vkpdc_get_shortcode_default_attributes() {
	return array(
		'numberPosts'            => 6,
		'order'                  => 'DESC',
		'orderby'                => 'date',
		'display_author'         => true,
		'display_date_publiched' => true,
		'display_date_modified'  => true,
		'display_new'            => true,
		'display_taxonomies'     => true,
		'pattern_id'             => true,
		'display_btn_view'       => true,
		'display_btn_copy'       => true,
		'display_image'          => 'featured',
		'thumbnail_size'         => 'full',
		'new_date'               => 7,
		'new_text'               => 'New!!',
		'display_btn_view_text'  => __( 'Read More', 'vk-pattern-directory-creator' ),
		'colWidthMinMobile'      => '300px',
		'colWidthMinTablet'      => '300px',
		'colWidthMinPC'          => '300px',
		'gap'                    => '1.5rem',
		'gapRow'                 => '1.5rem',
	);
}

/**
 * Generate Single Page HTML
 *
 * @param WP_Post $post 投稿オブジェクト.
 * @param array $attributes ブロックの属性.
 * @return string HTMLコンテンツ.
 */
function vkpdc_render_post_item( $post = null, $attributes = [] ) {

	$html    = '';
	$post = ! empty( $post ) ? $post : get_post( get_the_ID() );

	if ( ! empty( $post ) ) {        

		/* iframe */
		$iframe = vkpdc_get_iframe_content( $post->ID, 'archive' );

		/* タクソノミー */
		$taxonomy_html = '';
		$has_taxonomies = ! empty( $attributes['display_taxonomies'] );
		$has_pattern_id = ! empty( $attributes['pattern_id'] );

		if ( $has_taxonomies ) {
			// タクソノミーの取得.
			$args       = array(
				'template'      => '<dt class="vkpdc_post_taxonomy_title"><span class="vkpdc_post_taxonomy_title_inner">%s</span></dt><dd class="vkpdc_post_taxonomy_contents">%l</dd>',
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
			
			$taxonomy_html .= '<div class="vkpdc_post_taxonomies">';        

			// タクソノミーごとにタームを表示
			if ( ! empty( $taxonomies ) ) {
				foreach ( $taxonomies as $key => $value ) {
					$taxonomy_html .= '<dl class="vkpdc_post_taxonomy vkpdc_post_taxonomy-' . $key . '">' . $value . '</dl>';
				}
			}
			$taxonomy_html .= '</div>';
		}

		// パターンIDはタクソノミーの有無に関わらず出力
		if ( $has_pattern_id ) {
			$taxonomy_html .= '<div class="vkpdc_post_id">';
			$taxonomy_html .= '<span class="vkpdc_post_id_title_inner">' . __( 'Pattern ID', 'vk-pattern-directory-creator' ) . '</span>';
			$taxonomy_html .= '<span class="vkpdc_post_id_contents">' . esc_html( $post->ID ) . '</span>';
			$taxonomy_html .= '</div>';
		}

		if ( $has_taxonomies || $has_pattern_id ) {
			$taxonomy_html = '<div class="vkpdc_post_info">' . $taxonomy_html . '</div>';
		}

		/* 日付 */
		$date = ! empty( $attributes['display_date_publiched'] ) ? get_the_date( '', $post->ID ) : '';
		$modified_date = ! empty( $attributes['display_date_modified'] ) ? get_the_modified_date( '', $post->ID ) : '';

		/* リンクボタン */
		$link_button = '';
		if ( ! empty( $attributes['display_btn_view'] ) ) {
			$link_button  = '<div class="vkpdc_button-outer vkpdc_button-outer--view">';
			$link_button .= '<a class="vkpdc_button vkpdc_button--view" href="' . esc_attr( get_the_permalink( $post->ID ) ) . '">';
			$link_button .= '<span class="vkpdc_button-icon vkpdc_button-icon--view"><i class="fa-solid fa-circle-arrow-right fa-fw"></i></span>';
			$link_button .= '<span class="vkpdc_button-text vkpdc_button-text--view">' . esc_html( $attributes['display_btn_view_text'] ) . '</span>';
			$link_button .= '</a>';
			$link_button .= '</div>';
		}

		/* コピーボタン */
		$copy_button = ! empty( $attributes['display_btn_copy'] ) ? vkpdc_get_copy_button( $post->ID, 'archive' ) : '';

		/* ボタンの集合体 */
		$buttons = $link_button . $copy_button;

		/* 著者情報 */
		$author_html = '';
		if ( ! empty( $attributes['display_author'] ) ) {
			$author_id = $post->post_author;
			$author_name = get_the_author_meta( 'display_name', $author_id );

			// VK Post Author Displayの画像取得ロジック
			$profile_image_id = get_the_author_meta( 'user_profile_image', $author_id );
			if ( $profile_image_id ) {
				$profile_image_src = wp_get_attachment_image_src( $profile_image_id, 'thumbnail' );
			}
			if ( isset( $profile_image_src ) && is_array( $profile_image_src ) ) {
				$profile_image = '<img src="' . esc_url( $profile_image_src[0] ) . '" alt="' . esc_attr( get_the_author_meta( 'display_name', $author_id ) ) . '" />';
			} else {
				$profile_image = get_avatar( get_the_author_meta( 'email', $author_id ), 64 );
			}

			$author_html  = '<div class="vkpdc_post_entry_meta_item vkpdc_post_author">';
			$author_html .= '<div class="vkpdc_post_author_avatar">' . $profile_image . '</div>';
			$author_html .= '<div class="vkpdc_post_author_name">' . esc_html( $author_name ) . '</div>';
			$author_html .= '</div>';
		}

		/* 新しい投稿マーク */
		$new_mark_html = '';
		if ( ! empty( $attributes['display_new'] ) && ! empty( $attributes['new_date'] ) && ! empty( $attributes['new_text'] ) ) {
			$post_date = get_the_date( 'U', $post->ID );
			$current_date = current_time( 'timestamp' );
			$date_diff = ( $current_date - $post_date ) / DAY_IN_SECONDS;

			if ( $date_diff <= $attributes['new_date'] ) {
				$new_mark_html = '<span class="vkpdc_post_title_new">' . esc_html( $attributes['new_text'] ) . '</span>';
			}
		}

		/* 最初の article */
		$html .= '<article id="post-' . esc_attr( $post->ID ) . '" class="vkpdc_post ' . join( ' ', get_post_class( apply_filters( 'vkpdc_single_post_outer_class', '' ) ) ) . '">';

		/* 中身の追加 */
		// iframe
		if ( ! empty( $attributes['display_image'] ) ) {
			$html .= '<div class="vkpdc_iframe-outer vkpdc_iframe-outer--archive">';
			$html .= '<a class="vkpdc_iframe-outer--view" href="' . esc_attr( get_the_permalink( $post->ID ) ) . '">';
			if ( $attributes['display_image'] === 'featured' ) {
				if ( has_post_thumbnail() ) {
					$size = ! empty( $attributes['thumbnail_size'] ) ? $attributes['thumbnail_size'] : 'full';
					$html .= '<div class="vkpdc_iframe-wrapper vkpdc_iframe-wrapper--archive">';
					$html .= get_the_post_thumbnail( $post->ID, $size );
					$html .= '</div>';
				} else {
					$html .= '<div class="vkpdc_iframe-wrapper vkpdc_iframe-wrapper--archive">';
					$html .= $iframe . apply_filters( 'vkpdc_single_post_iframe_after', '' );
					$html .= '</div>';
				}
			} elseif ( $attributes['display_image'] === 'iframe' ) {
				$html .= '<div class="vkpdc_iframe-wrapper vkpdc_iframe-wrapper--archive">';
				$html .= $iframe . apply_filters( 'vkpdc_single_post_iframe_after', '' );
				$html .= '</div>';
			}
			$html .= '</a>';
			$html .= '</div>';
		}
		// タイトル
		$html .= '<div class="vkpdc_post_title">';
		$html .= '<a class="vkpdc_post_title--view" href="' . esc_attr( get_the_permalink( $post->ID ) ) . '">';
		$html .= apply_filters( 'vkpdc_post_title', get_the_title( $post->ID ), $post );
		$html .= '</a>';
		$html .= $new_mark_html;
		$html .= '</div>';
		// タクソノミーとパターンID
		$html .= $taxonomy_html;

		// 公開日、更新日、著者情報がある場合のみ出力
		if ( $date || $modified_date || $author_html ) {
			$html .= '<div class="vkpdc_post_entry_meta">';
			if ( $date || $modified_date ) {
				$html .= '<div class="vkpdc_post_entry_meta_item vkpdc_post_date">';
				if ( $date ) {
					$html .= '<span class="vkpdc_post_date--published"><i class="far fa-calendar-alt" aria-label="' . __( 'Published Date', 'vk-pattern-directory-creator' ) . '"></i>' . esc_html( $date ) . '</span>';
				}
				if ( $modified_date ) {
					$html .= '<span class="vkpdc_post_date--modified"><i class="fas fa-history" aria-label="' . __( 'Modified Date', 'vk-pattern-directory-creator' ) . '"></i>' . esc_html( $modified_date ) . '</span>';
				}
				$html .= '</div>';
			}
			// 著者情報
			$html .= $author_html;
			$html .= '</div>';
		}

		// ボタン
		if ( ! empty( $buttons ) ) {
			$html .= '<div class="vkpdc_buttons vkpdc_buttons--archive">' . $buttons . '</div>';
		}

		/* 最後の article */
		$html .= '</article>';
	}

	return $html;    

}

/* 
 * Generate Shortcode
 */
function vkpdc_get_patterns_archive_shortcode( $atts ) {
	// ショートコードのデフォルト値
	$default_attributes = vkpdc_get_shortcode_default_attributes();

	// 管理画面の保存値を取得してデフォルト値に統合
	foreach ( $default_attributes as $key => $default ) {
		$option_value = get_option( 'vkpdc_' . $key, $default );
		$default_attributes[ $key ] = $option_value;
	}

	// ショートコード引数を適用（引数が優先される）
	$attributes = shortcode_atts( $default_attributes, $atts );

    // 現在のクエリ情報を取得
    $queried_object = get_queried_object();
    $tax_query = array();

    // タクソノミー情報がある場合
    if ( is_tax() && isset( $queried_object->taxonomy, $queried_object->slug ) ) {
        $tax_query = array(
            array(
                'taxonomy' => $queried_object->taxonomy,
                'field'    => 'slug',
                'terms'    => $queried_object->slug,
            ),
        );
    }
	
	// WP_Query 引数を生成
	$query_args = array(
		'post_type'      => 'vk-patterns',
		'posts_per_page' => intval( $attributes['numberPosts'] ),
		'order'          => $attributes['order'],
		'orderby'        => $attributes['orderby'],
		'paged'          => get_query_var( 'paged', 1 ),
        'tax_query'      => $tax_query,
	);

	$query = new WP_Query( $query_args );

	return vkpdc_generate_archive_html( $query, $attributes );
}
add_shortcode( 'vkpdc_archive_loop', 'vkpdc_get_patterns_archive_shortcode' );

/**
 * Generate HTML
 *
 * @param WP_Query $query クエリオブジェクト.
 * @param array $attributes ブロックの属性.
 * @return string HTMLコンテンツ.
 */
function vkpdc_generate_archive_html( $query, $attributes ) {
	$html = '';

	// カスタムクラスを取得
	$custom_class = get_option( 'vkpdc_classname', '' );
	$class = 'vkpdc_posts ' . esc_attr( $custom_class );
	
	// 動的スタイルを生成
	$styles = sprintf(
		'--col-width-min-mobile: %s; --col-width-min-tablet: %s; --col-width-min-pc: %s; --gap: %s; --gap-row: %s;',
		esc_attr( $attributes['colWidthMinMobile'] ),
		esc_attr( $attributes['colWidthMinTablet'] ),
		esc_attr( $attributes['colWidthMinPC'] ),
		esc_attr( $attributes['gap'] ),
		esc_attr( $attributes['gapRow'] )
	);

	if ( $query->have_posts() ) {
		$html .= '<div class="' . $class . '" style="' . esc_attr( $styles ) . '">';

		while ( $query->have_posts() ) {
			$query->the_post();
			$post  = get_post( get_the_ID() );
			$html .= vkpdc_render_post_item( $post, $attributes );
		}
		
		$html .= vkpdc_add_placeholder_articles( $query, $attributes );

		$html .= '</div>';
	} else {
		$html .= '<div class="vkpdc_posts vkpdc_posts--none">';
		$html .= '<div class="vkpdc_post_title">' . __( 'No posts found.', 'vk-pattern-directory-creator' ) . '</div>';
		$html .= '</div>';
	}

    // ページネーションの生成
    if ( $attributes['display_paged'] ) {
        $pagination = paginate_links( array(
            'base'      => trailingslashit( get_pagenum_link( 1 ) ) . 'page/%#%/',
            'format'    => 'page/%#%/',
            'total'     => $query->max_num_pages,
            'current'   => max( 1, get_query_var( 'paged', 1 ) ),
            'type'      => 'array',
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
        ) );

        if ( $pagination ) {
            $html .= '<nav class="vkpdc_pagination navigation pagination" aria-label="' . __( 'Posts pagination', 'vk-pattern-directory-creator' ) . '">';
            $html .= '<h2 class="screen-reader-text">' . __( 'Posts pagination', 'vk-pattern-directory-creator' ) . '</h2>';
            $html .= '<div class="nav-links"><ul class="page-numbers">';

            foreach ( $pagination as $link ) {
                if ( strpos( $link, 'current' ) !== false ) {
                    $html .= '<li><span class="page-numbers current">' . strip_tags( $link ) . '</span></li>';
                } else {
                    $html .= '<li>' . $link . '</li>';
                }
            }

            $html .= '</ul></div></nav>';
        }
    }

    wp_reset_postdata();
    return $html;
}

/**
 * Generate Archive Loop
 * 
 * @param string $query クエリ.
 */
function vkpdc_get_archive_loop( $query = null, $attributes = [] ) {

	global $wp_query;
	$query = ! empty( $query ) ? $query : $wp_query;
	$theme = get_template();

	$html = '';

	
	if ( $query->have_posts() ) {
		$html .= '<div class="vkpdc_posts vkpdc_posts_theme--' . esc_attr( $theme ) . '">';

		while ( $query->have_posts() ) {
			$query->the_post();
			$post  = get_post( get_the_ID() );
			$html .= vkpdc_render_post_item( $post, $attributes );
		}
		
		$html .= vkpdc_add_placeholder_articles( $query, $attributes );

		$html .= '</div>';
		
	} else {

		$html .= '<div class="vkpdc_posts vkpdc_posts--none">';
		$html .= '<div class="vkpdc_post_title">' . __( 'No posts found.', 'vk-pattern-directory-creator' ) . '</div>';
		$html .= '</div>';

	}

	wp_reset_postdata();
	return $html;
}

/**
 * Pattern list render callback
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function vkpdc_render_pattern_list_callback( $attributes ) {
	$default_attributes = vkpdc_get_block_default_attributes();
	$attributes = wp_parse_args( $attributes, $default_attributes );

	// カスタムクラスを取得
	$custom_class = get_option( 'vkpdc_classname', '' );
	$class = 'vkpdc_posts ' . esc_attr( $custom_class );

	// 動的スタイルを生成
	$styles = sprintf(
		'--col-width-min-mobile: %s; --col-width-min-tablet: %s; --col-width-min-pc: %s; --gap: %s; --gap-row: %s;',
		esc_attr( $attributes['colWidthMinMobile'] ),
		esc_attr( $attributes['colWidthMinTablet'] ),
		esc_attr( $attributes['colWidthMinPC'] ),
		esc_attr( $attributes['gap'] ),
		esc_attr( $attributes['gapRow'] )
	);

    // 現在のクエリ情報を取得
    $queried_object = get_queried_object();
    $tax_query = array();

    // タクソノミー情報がある場合
    if ( is_tax() && isset( $queried_object->taxonomy, $queried_object->slug ) ) {
        $tax_query = array(
            array(
                'taxonomy' => $queried_object->taxonomy,
                'field'    => 'slug',
                'terms'    => $queried_object->slug,
            ),
        );
    }

	// 現在のページを取得
	$current_page = max( 1, get_query_var( 'paged', 1 ) );

	// クエリの設定
	$query_args = array(
		'post_type'      => 'vk-patterns',
		'posts_per_page' => intval( $attributes['numberPosts'] ),
		'order'          => $attributes['order'],
		'orderby'        => $attributes['orderby'],
		'paged'          => $current_page,
		'tax_query'      => $tax_query,
	);

	$query = new WP_Query( $query_args );

	$html = '';
	// 投稿リストのラッパーを開始
	$html .= '<div class="' . $class . '" style="' . esc_attr( $styles ) . '">';

	// 投稿ループ
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$html .= vkpdc_render_post_item( get_post(), $attributes );
		}

		$html .= vkpdc_add_placeholder_articles( $query, $attributes );

	} else {
		$html .= '<p>' . __( 'No patterns found.', 'vk-pattern-directory-creator' ) . '</p>';
	}

	$html .= '</div>';

	// ページネーションの生成
	if ( $attributes['display_paged'] ) {
		$pagination = paginate_links( array(
			'base'      => esc_url( get_pagenum_link( 1 ) ) . '%_%',
			'format'    => 'page/%#%/',
			'total'     => $query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged', 1 ) ),
			'type'      => 'array',
			'prev_text' => '&laquo;',
			'next_text' => '&raquo;',
		) );
	
		if ( $pagination ) {
			$html .= '<nav class="vkpdc_pagination navigation pagination" aria-label="' . __( 'Posts pagination', 'vk-pattern-directory-creator' ) . '">';
			$html .= '<h2 class="screen-reader-text">' . __( 'Posts pagination', 'vk-pattern-directory-creator' ) . '</h2>';
			$html .= '<div class="nav-links"><ul class="page-numbers">';
	
			// ページリンクをリスト化
			foreach ( $pagination as $link ) {
				if ( strpos( $link, 'current' ) !== false ) {
					$html .= '<li><span class="page-numbers current">' . strip_tags( $link ) . '</span></li>';
				} else {
					$html .= '<li>' . $link . '</li>';
				}
			}
	
			$html .= '</ul></div></nav>';
		}
	}	

	// 投稿データをリセット
	wp_reset_postdata();

	return $html;
}

/**
 * Remove image size attributes
 * 
 * @param array $attr Image attributes.
 * @return array
 */
function remove_image_sizes_attributes( $attr ) {
	unset( $attr['style'] );
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'remove_image_sizes_attributes', 9999, 1 );

/**
 * Add placeholder articles to maintain grid structure on the last page.
 *
 * @param WP_Query $query WP_Query instance.
 * @param array    $attributes Block attributes.
 * @return string HTML content with placeholders.
 */
function vkpdc_add_placeholder_articles( $query, $attributes ) {
	$html = '';
	$posts_per_page = intval( $attributes['numberPosts'] );
	$current_count = $query->post_count;

	// ダミー記事の数を計算
	$placeholders_needed = $posts_per_page - $current_count;

	// ダミー記事を生成
	if ( $placeholders_needed > 0 ) {
		for ( $i = 0; $i < $placeholders_needed; $i++ ) {
			$html .= '<article class="placeholder-article"></article>';
		}
	}

	return $html;
}