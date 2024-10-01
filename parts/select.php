<?php
	// Exits if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) exit;

	$option_value = get_option( $args[ 'label_for' ] );
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
<?php if ( isset( $args[ 'description' ] ) ) : ?>
	<p class="description"><?php echo esc_html( $args[ 'description' ] ); ?></p>
<?php endif; ?>