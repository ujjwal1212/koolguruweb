$(document).ready(function () {
    $('#SubjectForm').validate({
        rules: {
            title: {
                required: true,
            },
            code: {
                required: true,
            },
            
            course_id: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter name",
            },
            code: {
                required: "Please enter code",
            },
            
            course_id: {
                required: "Please select course",
            },
        }
    });

    //binds to onchange event of your input field
    $('#image_path').on('change', function () {
        var fileSize = this.files[0];
        var sizeInMb = fileSize.size / (1024*1024);
        if (sizeInMb > 2) {
            alert('Maximum file size allowed to upload is 2 MB,please upload a file of defined size');
            $('#image_path').val('');
        }
    });
});