<?php
	defined( 'ABSPATH' ) || exit;

	$option_name  = $args[ 'label_for' ];
	$option_value = self::get_option( $option_name );
?>

<select name="<?php echo esc_attr( $args[ 'label_for' ] ); ?>" id="<?php echo esc_attr( $args[ 'label_for' ] ); ?>">
	<?php foreach ( $args[ 'values' ] as $value => $label ) : ?>
		<?php printf( '<option value="%s" %s>%s</option>',
			esc_attr( $value ),
			selected( $option_value, $value, false ),
			esc_html( $label ),
		); ?>
	<?php endforeach; ?>
</select>