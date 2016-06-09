<div class="wrap">
	<h1>Importing</h1>

	<?php if ( isset( $output['xml'] ) ): ?>
		<div class="cei-output cei-error">Your XML file appears to be invalid, see the issues below:</div>
		<?php foreach ( $output['xml'] as $message ): ?>
			<div class="cei-output"><?php echo esc_html( $message->message ) ?></div>
		<?php endforeach ?>
	<?php endif; ?>

	<?php if ( isset( $output['posts'] ) ): ?>
		<p>Importing posts/pages:</p>
		<?php foreach ( $output['posts'] as $message ): ?>
			<div class="cei-output cei-<?php echo esc_attr( $message['type'] ) ?>"><?php echo esc_html( $message['description'] ) ?></div>
		<?php endforeach ?>
	<?php endif; ?>

	<?php if ( isset( $output['menus'] ) ): ?>
		<p>Importing menus:</p>
		<?php foreach ( $output['menus'] as $message ): ?>
			<div class="cei-output cei-<?php echo esc_attr( $message['type'] ) ?>"><?php echo esc_html( $message['description'] ) ?></div>
		<?php endforeach ?>
	<?php endif; ?>

	<?php if ( ! isset( $output['xml'] ) ): ?>
		<p>Import complete</p>
	<?php endif; ?>
</div>


