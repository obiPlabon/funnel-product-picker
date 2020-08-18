;
(function ($) {
    'use strict';

    $(function () {
        var $picker = $('.funnel-picker'),
            $picker_options = $picker.find('.funnel-picker__options-input'),
            $package = $picker.find('#package'),
            $form = $picker.find('.variations_form'),
            variation_data = $form.data('product_variations');

        $picker_options.on('change', function () {
            $package
                .find('option[value="' + this.value + '"]')
                .attr('selected', true)
                .trigger('change')
                .siblings()
                .attr('selected', false);
        });

        $picker.on('change.onPromptAction', '.wcsatt-options-prompt-action-input', function () {
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
        });
    });
}(jQuery));