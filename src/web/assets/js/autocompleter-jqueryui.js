(function ($) {
    'use strict';
    $.fn.autocompleter = function (options) {
        var settings = {
            url_list: '',
            url_get:  '',
            required: false
        };

        var required_string = '';

        if (settings.required) {
            required_string = 'required="required"';
        }

        return this.each(function () {
            if (options) {
                $.extend(settings, options);
            }
            var $this = $(this), $fakeInput = $('<input ' + required_string + ' type="text" name="fake' + $this.attr('name') + '">');
            $this.hide().after($fakeInput);
            $fakeInput.autocomplete({
                source: settings.url_list,
                select: function (event, ui) {
                    $this.val(ui.item.id);
                }
            });
            if ($this.val() !== '') {
                $.ajax({
                    url:     settings.url_get + $this.val(),
                    success: function (name) {
                        $fakeInput.val(name);
                    }
                });
            }
        });
    };
})(jQuery);
