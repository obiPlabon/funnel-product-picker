;
(function ($) {
	'use strict';

	$(function () {
		var $picker = $('.funnel-picker'),
			$picker_options = $picker.find('.funnel-picker__options-input'),
			$package = $picker.find('#' + $picker.data('attribute_key')),
			$form = $picker.find('.variations_form'),
			variation_data = $form.data('product_variations'),
			$price_base = $picker.find('.funnel-picker__top-price-base'),
			$price_bottle = $picker.find('.funnel-picker__top-price-bottle');

		function updatePriceTitle() {
			var variation_id = $picker_options.closest(':checked').data('vid'),
				type = $picker.find('.wcsatt-options-prompt-action-input:checked').val(),
				bottle_price_type = type === 'yes' ? 'funnel_subs_bottle_price' : 'funnel_ot_bottle_price',
				price_type = type === 'yes' ? 'subscription' : 'onetime',
				$base_price_markup = $picker.find('.funnel-' + price_type + '-price-radio.funnel-product-' + variation_id);

			$.each(variation_data, function (index, item) {
				if (item.variation_id !== variation_id) {
					return;
				}
				$price_bottle.html('(' + item[bottle_price_type] + ')');
			});

			if (type === 'yes') {
				$price_base.html($base_price_markup.find(':first-child').clone().html());
			} else {
				$price_base.html($base_price_markup.html());
			}
		}

		$picker_options.on('change.onPackageChage', function (event) {
			event.preventDefault();
			$package
				.find('option[value="' + this.value + '"]')
				.attr('selected', true)
				.trigger('change')
				.siblings()
				.attr('selected', false);

			updatePriceTitle();
		});

		$picker.on('change.onPromptActionChange', '.wcsatt-options-prompt-action-input', function (event) {
			event.preventDefault();
			var bottle_price_type = 'funnel_ot_bottle_price';
			// Subscription
			if (this.value === 'yes') {
				bottle_price_type = 'funnel_subs_bottle_price';
			}

			$.each(variation_data, function (index, item) {
				var $bottle_price = $picker
					.find('.funnel-picker__options-input[data-vid="' + item.variation_id + '"]')
					.parents('.funnel-picker__options-item')
					.find('.funnel-picker__options-price');

				$bottle_price.html(item[bottle_price_type]);
			});

			updatePriceTitle();
		});
	});
}(jQuery));