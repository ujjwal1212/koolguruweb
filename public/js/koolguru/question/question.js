$(document).ready(function () {
    $('#level,#type,#category_id').select2();

    $('#previous').attr('disabled', true);
    $('#step2').hide();
    $('#next').click(function () {
        if ($("#question").valid()) {
            $('#step1').hide();
            $('#step2').show();
            $('#next').attr('disabled', true);
            $('#previous').attr('disabled', false);
        }

    });
    $('#previous').click(function () {
        $('#step2').hide();
        $('#step1').show();
        $('#next').attr('disabled', false);
        $('#previous').attr('disabled', true);
    });

    $('#question').validate({
        rules: {
            name: {
                required: true,
            },
            description: {
                required: true,
            },
            min_marks: {
                required: true,
                number : true,
                min:0,
                max:10,
            },
            max_marks: {
                required: true,
                number : true,
                min:0,
                max:10,
            },
            level: {
                required: true,
            },
            type: {
                required: true,
            },
            category_id: {
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
            min_marks: {
                required: "Please enter minimum marks",
            },
            max_marks: {
                required: "Please enter maximum marks",
            },
            level: {
                required: "Please select level",
            },
            type: {
                required: "Please select type",
            },
            category_id: {
                required: "Please select category",
            },
        }
    });

    $('#question').submit(function (event) {
        if (window.parent.$('#option_table > tbody > tr').length == 0) {
            alert('Atleast one option should be added in the option list!');
            event.preventDefault();
        }
    });

    /**
     * Function to add options in the question
     */

    $('#add_option').click(function () {
        var tableRowId = window.parent.$('#option_table > tbody > tr').length + 1;
        //var optionDesc = $('#question_desc').text();
        var optionDesc = CKEDITOR.instances['question_desc'].getData();
        //return false;
        alert(optionDesc);
        if ($('#is_correct').is(':checked')) {
            var correct = 'Yes';
            var isCorrect = '1';
        } else {
            var correct = 'No';
            var isCorrect = '0';
        }
        if (optionDesc != '') {
            var rowBody = "<tr id=" + tableRowId + ">\n\
                           <td class='fname'><input type='hidden' name='option_description[]' value='" + optionDesc + "'/>" + optionDesc + "</td>\n\
                           <td class='lname'><input type='hidden' name='option_correct[]' value='" + isCorrect + "'/>" + correct + "</td>\n\
                           <td><a href='javascript:void(0)' class='btnDelete delete-icon' id='row_" + tableRowId + "' title='delete'></a></td></tr>";
            $("#option_table > tbody:last").append(rowBody);
            bindDelete('row_' + tableRowId);
            tableRowId++;
        } else {
            alert('Please enter description text for Option');
        }
    });
});