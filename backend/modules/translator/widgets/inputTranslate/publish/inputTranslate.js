(function($) {

    $.fn.inputTranslate = function(options) {

        let settings = $.extend({}, {
            url: null,
            output: null,
            text: "Translate",
            inputId: "post-title",
            buttonId: "input-translate-button",
            loaderId: "input-translate-loader"
        }, options);

        let $this = $(this),
            loader = getById(settings.loaderId);

        this.each(function() {

            $this.after( $('<div />', {
                'id': settings.buttonId,
                'text': settings.text,
                'click': function () {
                    loader.attr("style", "display:inline");

                    $.ajax({
                        type: 'POST',
                        url: settings.url,
                        data: {
                            str: getById(settings.inputId).val()
                        },
                    })
                    .done(function(data) {
                        getById(settings.output).val(data);
                    })
                    .fail(function() {
                        // TODO проверить!!!
                        alert( "error" );
                    })
                    .always(function () {
                        loader.attr("style", "display:none");
                    });
                }
            }));
        });

        /**
         * @param $id
         * @returns {*|jQuery|HTMLElement}
         */
        function getById($id) {
            return $('#'+$id);
        }

        return this;
    };

})(jQuery);