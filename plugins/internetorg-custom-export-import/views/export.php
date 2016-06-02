
<div class="wrap">
	<h1>Export</h1>
	<p>When you click the button below WordPress will create an special XML file for you to save to your computer.</p>
	<p>This format will contain your posts and pages. Shortcodes are converted into custom tags which are compatible with the Facebook translation tool.</p>
	<p>Once a translation has been completed, for example French. Please visit the French the site and select import add the new translated content to the site.</p>

	<form action="<?php echo esc_attr( $form_action ) ?>" method="post">
	<h2>Choose which site to export from.</h2>
	<ul>
		<li>
			<label>
				<span class="label-responsive">Site:</span>
				<select name="site" class="">
					<?php foreach ($sites as $site): ?>
						<option value="<?php echo esc_attr( $site['blog_id'] ) ?>"><?php echo esc_html( $site['path'] ) ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</li>
	<ul>
	<h2>Choose what to export</h2>
	<ul>
		<li>
			<label>
				<span class="label-responsive">Type:</span>
				<select name="type" class="">
					<option value="0">All</option>
					<?php foreach ($types as $type): ?>
						<option value="<?php echo esc_attr( $type ) ?>"><?php echo esc_html( $type ) ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</li>
		<li>
			<label>
				<span class="label-responsive">Status:</span>
				<select name="status" class="">
					<option value="0">All</option>
					<?php foreach ($statuses as $status): ?>
						<option value="<?php echo esc_attr( $status ) ?>"><?php echo esc_html( $status ) ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</li>
		<li>
			<label>
				<span class="label-responsive">Author:</span>
				<select name="author" class="">
					<option value="0">All</option>
					<?php foreach ( $users as $user ): ?>
						<option value="<?php echo esc_attr( $user->ID ) ?>"><?php echo esc_html( $user->display_name ) ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</li>
		<li>
			<label>
				<span class="label-responsive">Start Date:</span>
				<input type="text" name="start_date" value="" class="datepicker" />
			</label>
		</li>
		<li>
			<label>
				<span class="label-responsive">End Date:</span>
				<input type="text" name="end_date" value="" class="datepicker" />
			</label>
		</li>
	</ul>
	<h2>Or provide a list of post/page IDs</h2>
	<label>
		<span class="label-responsive">Example: 167,456,1987</span>
		<input type="text" name="ids" value="" class="" />
	</label>
	<h2>Extras</h2>
	<ul>
		<li>
			<label>
				<span class="label-responsive">Include Menus</span>
				<select name="include_menus" class="">
					<option value="yes">Yes</option>
					<option value="no">No</option>
				</select>
			</label>
		</li>
		<li>
			<label>
				<span class="label-responsive">Include PO</span>
				<select name="include_po" class="">
					<option value="yes">Yes</option>
					<option value="no">No</option>
				</select>
			</label>
		</li>
		<li>
			<label>
				<span class="label-responsive">PO Strings</span>
			</label>

			<textarea name="po_strings" style="width:100%; height: 300px; display: block;"><?php echo esc_textarea( $po_strings ) ?></textarea>
		</li>
	</ul>

	<?php wp_nonce_field( 'iorg_cei_export', 'nonce' ); ?>

	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Download Export File"></p>
	</form>

</div>


<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.datepicker').datepicker();
});
</script>
