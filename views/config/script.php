<?php defined( 'WPINC' ) || die; ?>

<script type="text/javascript">
	jQuery(function(x) {
		'use strict';
		x('.pollux-reset').on( 'click', function() {
			return confirm("<?= __( 'Are you sure want to do this?', 'pollux' ); ?>");
		});
	});
</script>
