<?php
/**
 * Template canvas file to render the current 'wp_template'.
 *
 * @package VK Pattern Directory Creator
 */

global $wp_embed;
$vkpdc_content = '<div class="vkpdc_container container"><!-- wp:post-content /--></div>';
$vkpdc_content = $wp_embed->run_shortcode( $vkpdc_content );
$vkpdc_content = $wp_embed->autoembed( $vkpdc_content );
$vkpdc_content = do_blocks( $vkpdc_content );
$vkpdc_content = wptexturize( $vkpdc_content );
$vkpdc_content = convert_smilies( $vkpdc_content );
$vkpdc_content = shortcode_unautop( $vkpdc_content );
$vkpdc_content = wp_filter_content_tags( $vkpdc_content );
$vkpdc_content = do_shortcode( $vkpdc_content );
$vkpdc_content = str_replace( ']]>', ']]&gt;', $vkpdc_content );

/*
 * Get the template HTML.
 * This needs to run before <head> so that blocks can add scripts and styles in wp_head().
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<script type="text/javascript">
		if (window.name !== "any" ) {
			window.location.reload();
			window.name = "any";
		} else {
			window.name = "";
		}
	</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php echo wp_kses_post( $vkpdc_content ); ?>

<?php wp_footer(); ?>
</body>
</html>
