<div class="wrap">
	<h1>Importing</h1>

	<p>Importing posts/pages:</p>
	<?php foreach ( $output['posts'] as $message ): ?>
		<div class="cei-output cei-<?php echo esc_attr( $message['type'] ) ?>"><?php echo esc_html( $message['description'] ) ?></div>
	<?php endforeach ?>

	<p>Importing menus:</p>
	<?php foreach ( $output['menus'] as $message ): ?>
		<div class="cei-output cei-<?php echo esc_attr( $message['type'] ) ?>"><?php echo esc_html( $message['description'] ) ?></div>
	<?php endforeach ?>

	<p>Import complete</p>
</div>


