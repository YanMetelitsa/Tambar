<?php
	/** Exit if accessed directly */
	if ( !defined( 'ABSPATH' ) ) exit;
?>

<div class="wrap">
	<h1>
		<?= esc_html( get_admin_page_title() ); ?>
		<span style="font-size: 12px;"><?= TAMBAR_VERSION; ?></span>
	</h1>

	<form action="options.php" method="post">
		<p><?= __( 'To make the plugin works, you must use <code>body_class()</code> function in your template.', 'tambar' ); ?></p>

		<?php 
			settings_fields( 'tambar' );
			do_settings_sections( 'tambar' );
			
			submit_button();
		?>
	</form>
</div>