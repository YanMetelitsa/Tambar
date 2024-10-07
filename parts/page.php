<?php
	// Exits if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form action="options.php" method="POST">
		<p><?php echo wp_kses_post( __( 'To make the plugin work, you must use the <code>body_class()</code> function in your template.', 'tambar' ) ); ?></p>

		<?php 
			settings_fields( 'tambar' );
			do_settings_sections( 'tambar' );
			
			submit_button();
		?>
	</form>
</div>