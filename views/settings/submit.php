<?php defined( 'WPINC' ) || die; ?>

<div id="submitpost" class="submitbox">
	<div id="major-publishing-actions">
		<div id="delete-action"><a href="<?= $reset_url; ?>" class="submitdelete deletion"><?= $reset; ?></a></div>
		<div id="publishing-action"><span class="spinner"></span><?= $submit; ?></div>
		<div class="clear"></div>
	</div>
</div>
