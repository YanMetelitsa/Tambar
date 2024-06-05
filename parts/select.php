<?php
	/** Exit if accessed directly */
	if ( !defined( 'ABSPATH' ) ) exit;

	$option_value = get_option( $args[ 'label_for' ] );
?>

<select name="<?= $args[ 'label_for' ]; ?>" id="<?= $args[ 'label_for' ]; ?>">
	<?php foreach ( $args[ 'values' ] as $value => $title ) : ?>
		<option value="<?= $value; ?>" <?= ( $option_value == $value ? 'selected' : '' ); ?>>
			<?= $title; ?>
		</option>
	<?php endforeach; ?>
</select>
<?php if ( isset( $args[ 'description' ] ) ) : ?>
	<p class="description"><?= $args[ 'description' ]; ?></p>
<?php endif; ?>