<?php
	/** Exit if accessed directly */
	if ( !defined( 'ABSPATH' ) ) exit;

	$option_value = get_option( $args[ 'label_for' ] );
?>

<label for="<?= $args[ 'label_for' ]; ?>">
	<?php printf( '<input name="%1$s" type="checkbox" id="%1$s" %2$s>',
		$args[ 'label_for' ],
		$option_value ? 'checked' : '',
	); ?>
	<?= $args[ 'label' ]; ?>
</label>