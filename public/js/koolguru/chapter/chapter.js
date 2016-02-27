$(document).ready(function () {
    $('#chapter').validate({
        rules: {
            title: {
                required: true,
            },
            content: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter chapter title",
            },
            content: {
                required: "Please enter chapter description",
            },
        }
    });
});