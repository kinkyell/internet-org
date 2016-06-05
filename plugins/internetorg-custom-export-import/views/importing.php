<div class="wrap">
	<h1>Importing</h1>

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
	<p>Import complete</p>
</div>


