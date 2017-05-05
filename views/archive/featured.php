<?php defined( 'WPINC' ) || die; ?>

<p class="hide-if-no-js">
	<a href="<?= $thickbox_url; ?>" id="set-post-thumbnail" class="thickbox"><?= $thumbnail; ?></a>
</p>

<?php if( get_post( $image_id )) : ?>

<p class="hide-if-no-js howto" id="set-post-thumbnail-desc"><?= $edit_image; ?></p>
<p class="hide-if-no-js">
	<a href="#" id="remove-post-thumbnail"><?= $remove_image; ?></a>
</p>

<?php endif; ?>

<input type="hidden" id="featured" name="<?= $id; ?>[<?= $post_type; ?>][featured]" value="<?= esc_attr( $image_id ); ?>">
