;
(function ($) {
    'use strict';

    $(function () {
        var $picker = $('.funnel-picker'),
            $picker_options = $picker.find('.funnel-picker__options-input'),
            $package = $picker.find('#package');

        $picker_options.on('change', function () {
            $package
                .find('option[value="' + this.value + '"]')
                .attr('selected', true)
                .trigger('change')
                .siblings()
                .attr('selected', false);
        });
    });
}(jQuery))