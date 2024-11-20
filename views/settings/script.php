<?php defined('WPINC') || exit; ?>

<script type="text/javascript">

	function pollux_metabox_validate(form) {
		if(jQuery.validator === undefined)return;
		var rules = {
			invalidHandler: function() {
				x('#submit').removeClass('button-primary-disabled');
				x('#publishing-action .spinner').removeClass('is-active');
				form.siblings('#validation-error').remove();
				form.before('<div id="validation-error" class="error"><p>' + rwmbValidate.summaryMessage + '</p></div>');
			},
			ignore: ':not([class|="rwmb"])',
		};
		jQuery('.rwmb-validation-rules').each(function() {
			var subRules = jQuery(this).data('rules');
			jQuery.extend(true, rules, subRules);
			jQuery.each(subRules.rules, function(key, value) {
				if(value['required']) {
					jQuery('#' + key).parent().siblings('.rwmb-label').addClass('required').append('<span>*</span>');
				}
			});
		});
		form.validate(rules);
	}

	jQuery(function(x) {
		'use strict';

		var form = x('#<?php echo $id; ?>');

		pollux_metabox_validate( form );

		x('.if-js-closed').removeClass('if-js-closed').addClass('closed');

		postboxes.add_postbox_toggles('<?php echo $hook; ?>');

		form.submit(function() {
			x('#submit').addClass('button-primary-disabled');
			x('#publishing-action .spinner').addClass('is-active');
		});

		x('#delete-action .submitdelete').on( 'click', function() {
			return confirm("<?php echo $confirm; ?>");
		});
	});
</script>
