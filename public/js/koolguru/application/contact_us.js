$(document).ready(function () {
   $('#contactForm').validate({
        rules: {
            name: {
                required: true,
            },            
            mobile: {
                required: true,
                customphone: true,
            },
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            name: {
                required: "Please enter name.",
            },            
            mobile: {
                required: "Please enter mobile number.",
            },
            email: {
                required: "Please enter email address.",
                email: "Please enter valid email address.",
            },
        }
    });
    $.validator.addMethod('customphone', function (value, element) {
        return this.optional(element) || /^\d{10}$/.test(value);
    }, "Please enter a valid phone number");
});

