
<?php
/**
 * Pattern preview template.
 */
global $post;
$style = '<style>';
$no_margin = get_post_meta( $post->ID, 'vk-patterns-no-margin', true );
if ( ! empty( $no_margin ) ) {
	$style .= 'html { margin-top: 0!important; margin-bottom: 0!important; }';
} else {
	$style .= 'html { margin-top: 2.6rem!important; margin-bottom: 2.6rem!important; }';
}
$style .= '</style>';
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

<div class="vk-patterns-container container">
	<?php the_content(); ?>
</div>

<?php wp_footer(); ?>
</body>
</html>