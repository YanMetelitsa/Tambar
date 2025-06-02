<?php defined( 'ABSPATH' ) || exit; ?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form action="options.php" method="POST">
		<p>
			<?php echo wp_kses_post( sprintf(
				/* translators: %s: body_class */
				__( 'To make the plugin work, you must use the %s function in your theme.', 'tambar' ),
				'<a href="https://developer.wordpress.org/reference/functions/body_class/" target="_blank"><code>body_class()</code></a>',
			)); ?>
		</p>

		<?php 
			settings_fields( 'tambar' );
			do_settings_sections( 'tambar' );
			submit_button();
		?>
	</form>
</div>