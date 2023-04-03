
<?php
/**
 * Template canvas file to render the current 'wp_template'.
 *
 * @package WordPress
 * 
 */
global $wp_embed;
$content = '<div class="vk-patterns-container container"><!-- wp:post-content /--></div>';
$content = $wp_embed->run_shortcode( $content );
$content = $wp_embed->autoembed( $content );
$content = do_blocks( $content );
$content = wptexturize( $content );
$content = convert_smilies( $content );
$content = shortcode_unautop( $content );
$content = wp_filter_content_tags( $content );
$content = do_shortcode( $content );
$content = str_replace( ']]>', ']]&gt;', $content );

global $post;
$style = '<style>';
$no_margin = get_post_meta( $post->ID, 'vk-patterns-no-margin', true );
if ( ! empty( $no_margin ) ) {
	$style .= 'html { margin-top: 0!important; margin-bottom: 0!important; }';
} else {
	$style .= 'html { margin-top: 2.6rem!important; margin-bottom: 2.6rem!important; }';
}
$style .= '</style>';

/*
 * Get the template HTML.
 * This needs to run before <head> so that blocks can add scripts and styles in wp_head().
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php wp_head(); ?>
	<?php echo $style; ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php echo $content; ?>

<?php wp_footer(); ?>
</body>
</html>