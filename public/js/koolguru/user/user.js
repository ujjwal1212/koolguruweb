$(document).ready(function () {
    $("#country").select2();
    $('#role').select2({
        minimumResultsForSearch: -1
    });

    $('#user').validate({
            rules: {
                role: {
                    required: true,
                },
                fname: {
                    required: true,
                },
                lname: {
                    required: true,
                },
                email: {
                    required: true,
                    email:true,
                },
                country: {
                    required: true,
                }

            },
            messages: {
                role: {
                    required: "Please select role.",
                },
                fname: {
                    required: "Please enter first name.",
                },
                lname: {
                    required: "Please enter first name.",
                },
                email: {
                    required: "Please enter email id.",
                    email: "Please enter valid email id.",
                },
                country: {
                    required: "Please select country.",
                }
            }
        });
});