<?php defined( 'WPINC' ) || die; ?>

<script type="text/javascript">
	jQuery(function(x) {
		'use strict';

		x('.if-js-closed').removeClass('if-js-closed').addClass('closed');

		postboxes.add_postbox_toggles('<?= $hook; ?>');

		x('#<?= $id; ?>').submit(function() {
			x('#publishing-action .spinner').css('visibility', 'visible');
		});

		x('#delete-action .submitdelete').on( 'click', function() {
			return confirm("<?= $confirm; ?>");
		});
	});
</script>
