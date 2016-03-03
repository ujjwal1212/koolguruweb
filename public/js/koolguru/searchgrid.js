$(document).ready(function() {
    /**
     * Js file for handling common search scenarios
     */
    $('#search_box').keypress(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            var search_val = $('#search_box').val();
            $('#search_box_value').val(search_val);
            if ($('#form_search').valid()) {
                grid.search();
            }
        }
    });

    $('#searchbutton').click(function() {
        var search_box = $('#search_box').val();
        $('#search_box_value').val(search_box);
        if ($('#form_search').valid()) {
            grid.search();
        }
    });
});


/**
 * Js for handling common searching and sorting features
 * @type type
 */
var grid = {
    search: function() {
        var _uri = $('#form_search').attr('action') + '?' + $('#form_search').serialize();
        this.list(_uri);
    },
    sort: function(_orderby, _order) {
        $("#order_by").val(_orderby);
        $("#order").val(_order);
        this.search();
    },
    list: function(_url) {
        $.fancybox.showLoading();
        $.get(_url, function(_data) {
            $("#grid-list").replaceWith(_data);
            $.fancybox.hideLoading();
            $('.tooltipStyle').hide();
        }).fail(function() {
            $("#grid-list").html('');
        })
    }

};

