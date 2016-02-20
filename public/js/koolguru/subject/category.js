$(document).ready(function () {
    $('#CategoryForm').validate({
        rules: {
            name: {
                required: true,
            },
            description: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter name",
            },
            description: {
                required: "Please enter description",
            },
        }
    });
});