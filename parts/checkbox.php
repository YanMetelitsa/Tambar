<?php
	defined( 'ABSPATH' ) || exit;

	$tambar_option_name  = $args[ 'option_name' ] ?? $args[ 'label_for' ];
	$tambar_option_value = self::get_option( $tambar_option_name );

	if ( is_array( $tambar_option_value ) && isset( $args[ 'option_key' ] ) ) {
		$tambar_option_value = $tambar_option_value[ $args[ 'option_key' ] ] ?? false;
	}
?>

<label for="<?php echo esc_attr( $args[ 'label_for' ] ); ?>">
	<?php printf( '<input type="checkbox" name="%1$s" id="%1$s" value="1" %2$s>',
		esc_attr( $args[ 'label_for' ] ),
		checked( $tambar_option_value, true, false ),
	); ?>

	<?php if ( isset( $args[ 'label' ] ) ) : ?>
		<?php echo esc_html( $args[ 'label' ] ); ?>
	<?php endif; ?>
</label>