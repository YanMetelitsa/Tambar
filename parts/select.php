<?php
	/** Exit if accessed directly */
	if ( !defined( 'ABSPATH' ) ) exit;

	$option_value = get_option( $args[ 'label_for' ] );
?>

<select name="<?php echo $args[ 'label_for' ]; ?>" id="<?php echo $args[ 'label_for' ]; ?>">
	<?php foreach ( $args[ 'values' ] as $value => $title ) : ?>
		<?php printf( '<option value="%s" %s>%s</option>',
			$value,
			selected( $option_value, $value, false ),
			$title,
		); ?>
	<?php endforeach; ?>
</select>
<?php if ( isset( $args[ 'description' ] ) ) : ?>
	<p class="description"><?php echo $args[ 'description' ]; ?></p>
<?php endif; ?>