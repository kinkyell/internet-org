<div class="wrap">
	<h1>Import</h1>

	<form action="<?php echo esc_attr( $form_action ) ?>" method="post" enctype="multipart/form-data">
		<h2>Please upload a file to Import.</h2>
		<ul>
		<li>
			<label>
				<span class="label-responsive">From Site:</span>
				<select name="site" class="">
					<?php foreach ($sites as $site): ?>
						<option value="<?php echo esc_attr( $site['blog_id'] ) ?>"><?php echo esc_html( $site['path'] ) ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</li>
			<li>
				<label>
					<span class="label-responsive">Import File:</span>
					<input type="file" name="import-file" value="" />
				</label>
			</li>
		</ul>

		<?php wp_nonce_field( 'iorg_cei_import', 'nonce' ); ?>

		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Upload Import File"></p>
	</form>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.example-datepicker').datepicker();
});
</script>
