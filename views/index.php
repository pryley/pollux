<?php defined( 'WPINC' ) || die; ?>

<div class="wrap">
	<h1><?= $heading; ?></h1>

	<?php settings_errors(); ?>

	<h2 class="pollux-tabs nav-tab-wrapper">
		<a class="nav-tab" href="#general">General</a>
		<a class="nav-tab" href="#dependencies">Dependencies</a>
		<a class="nav-tab" href="#metaboxes">Meta Boxes</a>
		<a class="nav-tab" href="#post_types">Post Types</a>
		<a class="nav-tab" href="#taxonomies">Taxonomies</a>
	</h2>

	<form class="pollux-config" method="post" action="options.php" enctype="multipart/form-data">

		<?php settings_fields( $id ); ?>

		<table class="form-table" id="general">
			<tbody>
				<tr>
					<td>
						<fieldset>
							<label for="disable_comments">
								<input type="checkbox" id="disable_comments" name="pollux_config[disable_comments]" value="1">
								Disable Comments
							</label>
							<br>
							<label for="disable_posts">
								<input type="checkbox" id="disable_posts" name="pollux_config[disable_posts]" value="1">
								Disable Posts
							</label>
							<br>
							<label for="enable_archive_pages">
								<input type="checkbox" id="enable_archive_pages" name="pollux_config[enable_archive_pages]" value="1">
								Enable Archive Pages
							</label>
							<br>
							<label for="remove_dashboard_widgets">
								<input type="checkbox" id="remove_dashboard_widgets" name="pollux_config[remove_dashboard_widgets]" value="1">
								Remove Dashboard Widgets
							</label>
							<br>
							<label for="remove_wordpress_footer">
								<input type="checkbox" id="remove_wordpress_footer" name="pollux_config[remove_wordpress_footer]" value="1">
								Remove the WordPress Admin Footer
							</label>
							<br>
							<label for="remove_wordpress_menu">
								<input type="checkbox" id="remove_wordpress_menu" name="pollux_config[remove_wordpress_menu]" value="1">
								Remove the WordPress Menu From the Admin Bar
							</label>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table" id="dependencies">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pollux_depends">Site Dependencies</label>
					</th>
					<td>
						<textarea id="pollux_depends" name="pollux_config[depends]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""></textarea>
						<p class="description"></p>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table" id="metaboxes">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pollux_archives">Archive Meta Boxes</label>
					</th>
					<td>
						<textarea id="pollux_archives" name="pollux_config[archives]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""></textarea>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pollux_metaboxes">Post Type Meta Boxes</label>
					</th>
					<td>
						<textarea id="pollux_metaboxes" name="pollux_config[metaboxes]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""></textarea>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pollux_settings">Site Settings Meta Boxes</label>
					</th>
					<td>
						<textarea id="pollux_settings" name="pollux_config[settings]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""></textarea>
						<p class="description"></p>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table" id="post_types">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pollux_post_types">Post Types</label>
					</th>
					<td>
						<textarea id="pollux_post_types" name="pollux_config[post_types]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""></textarea>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pollux_columns">Post Type Columns</label>
					</th>
					<td>
						<textarea id="pollux_columns" name="pollux_config[columns]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""></textarea>
						<p class="description"></p>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table" id="taxonomies">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pollux_taxonomies">Taxonomies</label>
					</th>
					<td>
						<textarea id="pollux_taxonomies" name="pollux_config[taxonomies]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""></textarea>
						<p class="description"></p>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>

	</form>
</div>
