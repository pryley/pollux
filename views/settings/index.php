<?php defined( 'WPINC' ) || die; ?>

<div class="wrap">
	<h1><?= $heading; ?></h1>
	<?php settings_errors(); ?>
	<form method="post" action="options.php" enctype="multipart/form-data" id="<?= $id; ?>">
		<?php settings_fields( $id ); ?>
		<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
		<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-<?= $columns; ?>">
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
