<?php defined('WPINC') || exit; ?>

<p class="hide-if-no-js">
	<a href="#" id="pollux-set-featured"><?php echo $thumbnail; ?></a>
</p>

<?php if (-1 != $image_id) { ?>

<p class="hide-if-no-js howto" id="set-post-thumbnail-desc"><?php echo $edit_image; ?></p>
<p class="hide-if-no-js">
	<a href="#" id="pollux-remove-featured"><?php echo $remove_image; ?></a>
</p>

<?php } ?>

<input type="hidden" id="featured" name="<?php echo $id; ?>[<?php echo $post_type; ?>][featured]" value="<?php echo esc_attr($image_id); ?>">
