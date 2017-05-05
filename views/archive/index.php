<?php defined( 'WPINC' ) || die; ?>

<div class="wrap">
	<h1><?= $heading; ?></h1>
	<?php settings_errors(); ?>
	<form method="post" action="options.php" enctype="multipart/form-data" id="<?= $id; ?>">
		<?php settings_fields( $id ); ?>
		<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
		<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
		<input type="hidden" id="archive-type" name="archive-type" value="<?= $post_type; ?>">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-<?= $columns; ?>">
				<div id="post-body-content">
					<div id="titlediv">
						<input type="text" id="title" name="<?= $id; ?>[<?= $post_type; ?>][title]" value="<?= $title; ?>" placeholder="<?= __( 'Enter title here', 'pollux' ); ?>" size="30" spellcheck="true" autocomplete="off">
					</div>
					<div id="postdivrich" class="postarea wp-editor-expand">
						<?php do_action( 'pollux/archives/editor', $content, $post_type ); ?>
						<table id="post-status-info">
						<tbody>
						<tr>
							<td id="wp-word-count" class="hide-if-no-js">
								<?= sprintf( __( 'Word count: %s', 'pollux' ), '<span class="word-count">0</span>' ); ?>
							</td>
							<td id="content-resize-handle" class="hide-if-no-js">
								<br>
							</td>
						</tr>
						</tbody>
						</table>
					</div>
				</div>
				<div id="postbox-container-1" class="postbox-container">
					<?php do_meta_boxes( null, 'side', null ); ?>
				</div>
				<div id="postbox-container-2" class="postbox-container">
					<?php do_meta_boxes( null, 'normal', null ); ?>
					<?php do_meta_boxes( null, 'advanced', null ); ?>
				</div>
			</div>
			<br class="clear">
		</div>
	</form>
</div>
