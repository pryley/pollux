<?php defined( 'WPINC' ) || die; ?>

<p class="hide-if-no-js">
	<a href="#" id="pollux-set-featured"><?= $thumbnail; ?></a>
</p>

<?php if( $image_id != -1 ) : ?>

<p class="hide-if-no-js howto" id="set-post-thumbnail-desc"><?= $edit_image; ?></p>
<p class="hide-if-no-js">
	<a href="#" id="pollux-remove-featured"><?= $remove_image; ?></a>
</p>

<?php endif; ?>

<input type="hidden" id="featured" name="<?= $id; ?>[<?= $post_type; ?>][featured]" value="<?= esc_attr( $image_id ); ?>">
