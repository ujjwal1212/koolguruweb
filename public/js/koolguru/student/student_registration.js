$(document).ready(function () {
    var student_tab = {};
    student_tab = {
        enabletab: '',
        applytabbing: function (tablist) {            
            for (var i = 0; i <= tablist.length; i++) {
                var tabval = parseInt(tablist[i]);
                if (tabval == 1) {
                    this.openTag(i);
                }
            }
        },
        openTag: function (tagid) {
            $('#tab' + (tagid + 1)).click();
        },
    };
    app.studentTab = student_tab;
});

