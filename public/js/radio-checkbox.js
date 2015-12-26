var _count = 0;
$(document).ready(function() {
    if (_count == 0) {
        $('input[type=checkbox], input[type=radio]').customRadioCheck();
        _count = _count + 1;
    }
});

(function() {
    $.fn.customRadioCheck = function() {

        return this.each(function() {
            var $this = $(this);
            var $span = $('<span/>');
            if (!$('#' + $this.attr('id')).next('span').hasClass('custom-check')) {
                $span.addClass('custom-' + ($this.is(':checkbox') ? 'check' : 'radio'));
                $this.is(':checked') && $span.addClass('checked'); // init
                $span.insertAfter($this);

                $this.parent('label').addClass('custom-label')
                        .attr('onclick', ''); // Fix clicking label in iOS
                // hide by shifting left
                $this.css({position: 'absolute', left: '-9999px'});
            }
            if ($('input[name=booking_option]')) {
                $span.addClass($(this).val());
            }
            // Events
            $this.on({
                click: function() {
                    if ($this.is(':radio')) {
                        //$this.parent().siblings('label')
                        //.find('.custom-radio').removeClass('checked');
                        if ($('#resit_change').val() != '1') {
                            $("input[name='" + $this.attr("name") + "']").parent().find("span").removeClass('checked');
                        }
                    }
                    $span.toggleClass('checked', $this.is(':checked'));
                    if ($('input[name=booking_option]') && $('#book_status').val() == 'booked') {
                        $('input[name=booking_option]').next('span.disabled').removeClass('focus')
                        $('input[name=booking_option]').next('span.disabled').removeClass('checked')
                        $('input[name=booking_option]').next('span.checked-disabled').addClass('focus');
                        $('input[name=booking_option]').next('span.checked-disabled').addClass('checked');
                    }
                },
                focus: function() {
                    $span.addClass('focus');
                },
                blur: function() {
                    $span.removeClass('focus');
                },
            });
            if ($this.is(':disabled')) {
                $span.addClass("disabled");
            }
            if ($this.is(':checked:disabled')) {
                $span.removeClass("disabled").css({"cursor": "default", "opacity": "0.8"});
            }

        });
    };
}());
