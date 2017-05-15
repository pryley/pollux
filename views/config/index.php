<?php defined( 'WPINC' ) || die; ?>

<div class="wrap">
	<h1><?= $heading; ?></h1>

	<h2 class="pollux-tabs nav-tab-wrapper">
		<a class="nav-tab" href="#general"><?= __( 'General', 'pollux' ); ?></a>
		<a class="nav-tab" href="#metaboxes"><?= __( 'Meta Boxes', 'pollux' ); ?></a>
		<a class="nav-tab" href="#post_types"><?= __( 'Post Types', 'pollux' ); ?></a>
		<a class="nav-tab" href="#taxonomies"><?= __( 'Taxonomies', 'pollux' ); ?></a>
	</h2>

	<form class="pollux-config" method="post" action="options.php" enctype="multipart/form-data">

		<?php settings_fields( $id ); ?>

		<input type="hidden" id="pollux-active-tab" name="_active_tab">

		<table class="form-table" id="general">
			<tbody>
				<tr>
					<td>
						<fieldset>
							<label for="disable_posts">
								<input type="checkbox" id="disable_posts" name="pollux_config[disable_posts]" value="1" <?php checked( $config->disable_posts ); ?>>
								<?= __( 'Disable Posts', 'pollux' ); ?>
							</label>
							<br>
							<label for="enable_archive_page">
								<input type="checkbox" id="enable_archive_page" name="pollux_config[enable_archive_page]" value="1" <?php checked( $config->enable_archive_page ); ?>>
								<?= __( 'Enable Archive Page', 'pollux' ); ?>
							</label>
							<br>
							<label for="remove_dashboard_widgets">
								<input type="checkbox" id="remove_dashboard_widgets" name="pollux_config[remove_dashboard_widgets]" value="1" <?php checked( $config->remove_dashboard_widgets ); ?>>
								<?= __( 'Remove Dashboard Widgets', 'pollux' ); ?>
							</label>
							<br>
							<label for="remove_wordpress_footer">
								<input type="checkbox" id="remove_wordpress_footer" name="pollux_config[remove_wordpress_footer]" value="1" <?php checked( $config->remove_wordpress_footer ); ?>>
								<?= __( 'Remove the WordPress Admin Footer', 'pollux' ); ?>
							</label>
							<br>
							<label for="remove_wordpress_menu">
								<input type="checkbox" id="remove_wordpress_menu" name="pollux_config[remove_wordpress_menu]" value="1" <?php checked( $config->remove_wordpress_menu ); ?>>
								<?= __( 'Remove the WordPress Menu From the Admin Bar', 'pollux' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table" id="metaboxes">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pollux_archives"><?= __( 'Archive Meta Boxes', 'pollux' ); ?></label>
					</th>
					<td>
						<?php
							$readonly = !$config->enable_archive_page || !$has_meta_box
								? ' readonly'
								: '';

							$data_disabled = !$has_meta_box
								? __( 'This field requires the Meta Box plugin.', 'pollux' )
								: __( 'Archive Page is not enabled.', 'pollux' );
						?>
						<textarea id="pollux_archives" name="pollux_config[archives]" rows="10" cols="50" class="large-text code pollux-code" placeholder="" data-disabled="<?= $data_disabled; ?>"<?= $readonly; ?>><?= $config->archives; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pollux_metaboxes"><?= __( 'Post Type Meta Boxes', 'pollux' ); ?></label>
					</th>
					<td>
						<textarea id="pollux_metaboxes" name="pollux_config[metaboxes]" rows="10" cols="50" class="large-text code pollux-code" placeholder="" data-disabled="<?= __( 'This field requires the Meta Box plugin.', 'pollux' ); ?>" <?= !$has_meta_box ? 'readonly' : ''; ?>><?= $config->metaboxes; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pollux_settings"><?= __( 'Site Settings Meta Boxes', 'pollux' ); ?></label>
					</th>
					<td>
						<textarea id="pollux_settings" name="pollux_config[settings]" rows="10" cols="50" class="large-text code pollux-code" placeholder="" data-disabled="<?= __( 'This field requires the Meta Box plugin.', 'pollux' ); ?>" <?= !$has_meta_box ? 'readonly' : ''; ?>><?= $config->settings; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table" id="post_types">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pollux_post_types"><?= __( 'Post Types', 'pollux' ); ?></label>
					</th>
					<td>
						<textarea id="pollux_post_types" name="pollux_config[post_types]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""><?= $config->post_types; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pollux_columns"><?= __( 'Post Type Columns', 'pollux' ); ?></label>
					</th>
					<td>
						<textarea id="pollux_columns" name="pollux_config[columns]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""><?= $config->columns; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table" id="taxonomies">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pollux_taxonomies"><?= __( 'Taxonomies', 'pollux' ); ?></label>
					</th>
					<td>
						<textarea id="pollux_taxonomies" name="pollux_config[taxonomies]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""><?= $config->taxonomies; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
			<a href="<?= $reset_url; ?>" id="reset" class="button pollux-reset"><?= __( 'Reset to Defaults', 'pollux' ); ?></a>
		</p>

	</form>
</div>
