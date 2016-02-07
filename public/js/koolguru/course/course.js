$(document).ready(function () {
    $('#course').validate({
        rules: {
            title: {
                required: true,
            },
            description: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter course title",
            },
            description: {
                required: "Please enter course description",
            },
        }
    });
});