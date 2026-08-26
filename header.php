<?php
/**
 * Site header.
 *
 * @package tpph
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'tpph' ); ?></a>

<header class="site-header" id="site-header">
	<div class="site-header__inner">

		<?php
		$logo_png  = TPPH_URI . '/assets/theme/logos/tpph-logo.png';
		$logo_webp = TPPH_URI . '/assets/theme/logos/tpph-logo.webp';
		$is_home   = is_front_page();
		?>
		<a class="site-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo $is_home ? ' aria-current="page"' : ''; ?>>
			<picture>
				<source srcset="<?php echo esc_url( $logo_webp ); ?>" type="image/webp">
				<img
					src="<?php echo esc_url( $logo_png ); ?>"
					width="480"
					height="173"
					alt="<?php esc_attr_e( 'The Park Private Hospital', 'tpph' ); ?>"
					fetchpriority="high"
					decoding="sync"
				>
			</picture>
		</a>

		<button
			class="site-header__burger"
			type="button"
			aria-expanded="false"
			aria-controls="primary-navigation"
		>
			<span class="screen-reader-text"><?php esc_html_e( 'Open the main menu', 'tpph' ); ?></span>
			<span class="site-header__burger-bars" aria-hidden="true"></span>
		</button>

		<nav
			class="nav"
			id="primary-navigation"
			aria-label="<?php esc_attr_e( 'Primary', 'tpph' ); ?>"
		>
			<?php tpph_primary_menu(); ?>

			<?php
			// The header call to action sits outside the menu in every artboard,
			// so it is its own element rather than a menu item with a class.
			$payment_url = tpph_field( 'header_cta_url', 'option', home_url( '/make-a-payment/' ) );
			?>
			<a class="btn btn--sm site-header__cta" href="<?php echo esc_url( $payment_url ); ?>">
				<?php echo esc_html( tpph_field( 'header_cta_label', 'option', __( 'Make a Payment', 'tpph' ) ) ); ?>
			</a>
		</nav>

	</div>
</header>
