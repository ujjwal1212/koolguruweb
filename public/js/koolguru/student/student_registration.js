$(document).ready(function () {
    $('#sex,#highest_degree,#completion_year,#native_state').select2();
    $('#studentForm').validate({
        rules: {
            fname: {
                required: true,
            },
            lname: {
                required: true,
            },
            sex: {
                required: true,
            },
            father_occupation: {
                required: true,
            },
            highest_degree: {
                required: true,
            },
            completion_year: {
                required: true,
            },
            native_state: {
                required: true,
            },
            city: {
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
            fname: {
                required: "Please enter first name.",
            },
            lname: {
                required: "Please enter last name.",
            },
            sex: {
                required: "Please select sex.",
            },
            father_occupation: {
                required: "Please enter father occupation.",
            },
            highest_degree: {
                required: "Please select highest degree.",
            },
            completion_year: {
                required: "Please select completion year.",
            },
            native_state: {
                required: "Please select native state.",
            },
            city: {
                required: "Please enter city details.",
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

    var student_tab = {};
    student_tab = {
        enabletab: '',
        enabletabcontent: '',
        applytabbing: function (tablist) {
            for (var i = 0; i <= tablist.length; i++) {
                var tabval = parseInt(tablist[i]);
                if (tabval == 1) {
                    this.openTag(i + 1);
                }
            }
        },
        openTag: function (tabno) {
            if($('#content-'+tabno).html().trim() == ''){                
                return false;
            }
            $('.kooltab').each(function () {
                var tabid = $(this).attr('id');
                tabid = tabid.replace("tab-", "");
                
                
                
                $('#tab-' + tabid).removeClass('activetab');
                $('#content-' + tabid).hide();
                if (parseInt(tabid) == parseInt(tabno)) {
                    $('#tab-' + tabid).addClass('activetab');
                    $('#content-' + tabno).show();
                }
            });

        },
        hidetab: function (tabno) {
            $('#content-' + tabno).hide();
        },
    };
    app.studentTab = student_tab;
});

