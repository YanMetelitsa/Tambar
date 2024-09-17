<?php
	/** Exit if accessed directly */
	if ( !defined( 'ABSPATH' ) ) exit;

	$option_value = get_option( $args[ 'label_for' ] );
?>

<label for="<?php echo $args[ 'label_for' ]; ?>">
	<?php printf( '<input type="checkbox" name="%1$s" id="%1$s" value="1" %2$s>',
		$args[ 'label_for' ],
		$option_value ? 'checked' : '',
	); ?>
	<?php echo $args[ 'label' ]; ?>
</label>