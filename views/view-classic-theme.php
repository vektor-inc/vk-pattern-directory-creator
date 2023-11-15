<?php
/**
 * Pattern preview template.
 *
 * @package VK Pattern Directory Creator
 */

if ( ! empty( $_GET['reload'] ) ) {
	$current_url = vkpdc_get_current_url();
	$redirect_url = str_replace( '&reload=true', '', $current_url );
	wp_safe_redirect( $redirect_url );
}
global $post;
$style = '<style>';
$no_margin = get_post_meta( $post->ID, 'vk-patterns-no-margin', true );
if ( ! empty( $no_margin ) ) {
	$style .= 'html { margin-top: 0!important; margin-bottom: 0!important; }';
} else {
	$style .= 'html { margin-top: 2.6rem!important; margin-bottom: 2.6rem!important; }';
}
$style .= '</style>';
$content = apply_filters( 'vkpdc_the_content', get_the_content() );
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

<?php echo apply_filters( 'the_content', $content ) ?>

<?php wp_footer(); ?>
</body>
</html>
