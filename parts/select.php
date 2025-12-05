<?php
	defined( 'ABSPATH' ) || exit;

	$tambar_option_name  = $args[ 'label_for' ];
	$tambar_option_value = self::get_option( $tambar_option_name );
?>

<select name="<?php echo esc_attr( $args[ 'label_for' ] ); ?>" id="<?php echo esc_attr( $args[ 'label_for' ] ); ?>">
	<?php foreach ( $args[ 'values' ] as $tambar_value => $tambar_label ) : ?>
		<?php printf( '<option value="%s" %s>%s</option>',
			esc_attr( $tambar_value ),
			selected( $tambar_option_value, $tambar_value, false ),
			esc_html( $tambar_label ),
		); ?>
	<?php endforeach; ?>
</select>