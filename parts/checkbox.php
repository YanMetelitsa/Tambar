<?php
	// Exits if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;

	$option_value = get_option( $args[ 'label_for' ] );
?>

<label for="<?php echo esc_attr( $args[ 'label_for' ] ); ?>">
	<?php printf( '<input type="checkbox" name="%1$s" id="%1$s" value="1" %2$s>',
		esc_attr( $args[ 'label_for' ] ),
		checked( $option_value, true, false ),
	); ?>
	<?php echo esc_html( $args[ 'label' ] ); ?>
</label>