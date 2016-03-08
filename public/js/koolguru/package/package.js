$(document).ready(function () {
    $('#course_id').select2();

    $('#previous').attr('disabled', true);
    $('#step2').hide();
    $('#next').click(function () {
        if ($("#PackageForm").valid()) {
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

    $('#PackageForm').validate({
        rules: {
            title: {
                required: true,
            },
            description: {
                required: true,
            },
            price: {
                required: true,
                number: true,
            },
            duration: {
                required: true,
                number: true,
            },
            ff_classroom: {
                required: true,
                number: true,
            },
            relevant_for: {
                required: true,
            },
            advantage: {
                required: true,
            },
            whatuserget: {
                required: true,
            },
        },
        messages: {
            title: {
                required: 'Please fill the name of package',
            },
            description: {
                required: 'Please fill the description of package',
            },
            price: {
                required: 'Please fill the price of package',
                number: 'Price should be a valid number',
            },
            duration: {
                required: 'Please fill the duration of package',
                number: 'Duration should be a valid number',
            },
            ff_classroom: {
                required: 'Please fill the number of face to face classes provided in the package',
                number: 'Number of classrooms provided should be a valid number',
            },
            relevant_for: {
                required: 'Please fill the relevancy of package',
            },
            advantage: {
                required: 'Please fill the advantage of package',
            },
            whatuserget: {
                required: 'Fill comma seperated description',
            },
        }
    });

    //binds to onchange event of your input field
    $('#image_path').on('change', function () {
        var fileSize = this.files[0];
        var sizeInMb = fileSize.size / (1024 * 1024);
        if (sizeInMb > 2) {
            alert('Maximum file size allowed to upload is 2 MB,please upload a file of defined size');
            $('#image_path').val('');
        }
    });

    /**
     * Function to add options in the question
     */

    $('#add_option').click(function () {
        var tableRowId = window.parent.$('#course_table > tbody > tr').length + 1;
        var courseId = $('#course_id').val();
        var courseName = $('#course_id option:selected').text();
        if (courseName != '') {
            var rowBody = "<tr id=" + tableRowId + ">\n\
                           <td class='fname'><input type='hidden' name='courseId[]' value='" + courseId + "'/>" + courseName + "</td>\n\
                           <td><a href='javascript:void(0)' class='btnDelete delete-icon' id='row_" + tableRowId + "' title='delete'></a></td></tr>";
            $("#course_table > tbody:last").append(rowBody);
            bindDelete('row_' + tableRowId);
            tableRowId++;
        } else {
            alert('Please select course to add into the list');
        }
    });
    //binds to onchange event of your input field
    $('#image_path').on('change', function () {
        var fileSize = this.files[0];
        var sizeInMb = fileSize.size / (1024 * 1024);
        if (sizeInMb > 2) {
            alert('Maximum file size allowed to upload is 2 MB,please upload a file of defined size');
            $('#image_path').val('');
        }
    });
});