$(document).ready(function () {
    var student_tab = {};
    student_tab = {
        enabletab: '',
        applytabbing: function (tablist) { 
            for (var i = 0; i <= tablist.length; i++) {
                var tabval = parseInt(tablist[i]);
                if (tabval == 1) {                    
                    this.openTag(i+1);
                }
            }
        },
        openTag: function (tabno) {   
            var counttab = 0;
            counttab = $('.kooltab').length;
            for (var i = 1; i <= counttab; i++) {
                this.hidetab(i);
            }
            $('#content-'+tabno).show();
            console.log(this.enabletab[tabno]);
            $('#tab-'+tabno).addClass('activetab');
        },
        
        hidetab : function(tabno){
            $('#content-'+tabno).hide();
        },
        
    };
    app.studentTab = student_tab;
});

