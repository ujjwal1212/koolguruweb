$(document).ready(function () {
    $('#chapter').validate({
        rules: {
            title: {
                required: true,
            },            
            chapter_type: {
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
            
            chapter_type: {
                required: "Please select chapter chapter type",
            },
        }
    });
});