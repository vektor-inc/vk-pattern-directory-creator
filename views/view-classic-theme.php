<?php
/**
 * Pattern preview template.
 *
 * @package VK Pattern Directory Creator
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>	
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="vkpdc_container container">
	<?php the_content(); ?>
</div>

<?php wp_footer(); ?>
</body>
</html>
