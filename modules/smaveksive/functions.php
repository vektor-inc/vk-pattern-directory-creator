<?php
/**
 * SmaVeksive用の関数
 *
 * @package VK Pattern Directory Creator
 */

/**
 * SmaVeksiveブロックが存在するかを確認する関数
 *
 * @return bool
 */
function vkpdc_has_smaponsive_block() {
    global $post;
    if ( ! $post ) {
        return false;
    }

    // 投稿内容に特定のブロックが含まれているかを確認
    return has_block( 'vk-blocks/smaponsive', $post->post_content );
} 