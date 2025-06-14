<?php
	defined( 'ABSPATH' ) || exit;

	$option_name  = $args[ 'option_name' ] ?? $args[ 'label_for' ];
	$option_value = self::get_option( $option_name );

	if ( is_array( $option_value ) && isset( $args[ 'option_key' ] ) ) {
		$option_value = $option_value[ $args[ 'option_key' ] ] ?? false;
	}
?>

<label for="<?php echo esc_attr( $args[ 'label_for' ] ); ?>">
	<?php printf( '<input type="checkbox" name="%1$s" id="%1$s" value="1" %2$s>',
		esc_attr( $args[ 'label_for' ] ),
		checked( $option_value, true, false ),
	); ?>

	<?php if ( isset( $args[ 'label' ] ) ) : ?>
		<?php echo esc_html( $args[ 'label' ] ); ?>
	<?php endif; ?>
</label>