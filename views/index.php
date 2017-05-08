<?php defined( 'WPINC' ) || die; ?>

<div class="wrap">
	<h1><?= $heading; ?></h1>
	<?php settings_errors(); ?>

	<h2 class="pollux-tabs nav-tab-wrapper">
		<a href="#general" class="nav-tab">General</a>
		<a href="#dependencies" class="nav-tab">Dependencies</a>
		<a href="#metaboxes" class="nav-tab">Meta Boxes</a>
		<a href="#post_types" class="nav-tab">Post Types</a>
		<a href="#taxonomies" class="nav-tab">Taxonomies</a>
	</h2>

	<form id="pollux-config" class="pollux-config" method="post" action="options.php" enctype="multipart/form-data">
		<?php settings_fields( $id ); ?>

		<div class="table" id="general">
			<table class="form-table">
				<tbody>
					<tr>
						<td>
							<fieldset>
								<!-- <legend class="screen-reader-text"><span>Default article settings</span></legend> -->
								<label for="disable_comments">
									<input name="pollux_config[disable_comments]" type="checkbox" id="disable_comments" value="1">
									Disable Comments
								</label>
								<br>
								<label for="disable_posts">
									<input name="pollux_config[disable_posts]" type="checkbox" id="disable_posts" value="1">
									Disable Posts
								</label>
								<br>
								<label for="remove_dashboard_widgets">
									<input name="pollux_config[remove_dashboard_widgets]" type="checkbox" id="remove_dashboard_widgets" value="1">
									Remove Dashboard Widgets
								</label>
								<br>
								<label for="remove_wordpress_menu">
									<input name="pollux_config[remove_wordpress_menu]" type="checkbox" id="remove_wordpress_menu" value="1">
									Remove WordPress Admin Bar Menu
								</label>
								<br>
								<label for="remove_wordpress_footer">
									<input name="pollux_config[remove_wordpress_footer]" type="checkbox" id="remove_wordpress_footer" value="1">
									Remove WordPress Footer
								</label>
							</fieldset>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="table" id="dependencies">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="pollux_depends">Site Dependencies</label>
						</th>
						<td>
							<textarea name="pollux_config[depends]" rows="20" cols="50" id="pollux_depends" class="large-text code pollux-code" placeholder="hello there"></textarea>
							<p class="description"></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="table" id="metaboxes">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="pollux_archives">Archive Meta Boxes</label>
						</th>
						<td>
							<textarea name="pollux_config[archives]" rows="20" cols="50" id="pollux_archives" class="large-text code pollux-code"></textarea>
							<p class="description"></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="pollux_metaboxes">Post Type Meta Boxes</label>
						</th>
						<td>
							<textarea name="pollux_config[metaboxes]" rows="20" cols="50" id="pollux_metaboxes" class="large-text code pollux-code"></textarea>
							<p class="description"></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="pollux_settings">Site Settings Meta Boxes</label>
						</th>
						<td>
							<textarea name="pollux_config[settings]" rows="20" cols="50" id="pollux_settings" class="large-text code pollux-code"></textarea>
							<p class="description"></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="table" id="post_types">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="pollux_post_types">Post Types</label>
						</th>
						<td>
							<textarea name="pollux_config[post_types]" rows="20" cols="50" id="pollux_post_types" class="large-text code pollux-code"></textarea>
							<p class="description"></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="pollux_columns">Post Type Columns</label>
						</th>
						<td>
							<textarea name="pollux_config[columns]" rows="10" cols="50" id="pollux_columns" class="large-text code pollux-code"></textarea>
							<p class="description"></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="table" id="taxonomies">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="pollux_taxonomies">Taxonomies</label>
						</th>
						<td>
							<textarea name="pollux_config[taxonomies]" rows="20" cols="50" id="pollux_taxonomies" class="large-text code pollux-code"></textarea>
							<p class="description"></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
