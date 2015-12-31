$(document).ready(function () {
    var student_tab = {};
    student_tab = {
        enabletab: '',
        enabletabcontent : '',
        applytabbing: function (tablist) { 
            for (var i = 0; i <= tablist.length; i++) {
                var tabval = parseInt(tablist[i]);
                if (tabval == 1) {                    
                    this.openTag(i+1);
                }
            }
        },        
        openTag: function (tabno) { 
            $('.kooltab').each(function(){
                var tabid = $(this).attr('id');
                tabid = tabid.replace("tab-", "");                
                $('#tab-'+tabid).removeClass('activetab');
                $('#content-'+tabid).hide();
                if(parseInt(tabid) == parseInt(tabno)){
                    $('#tab-'+tabid).addClass('activetab');
                    $('#content-'+tabno).show();
                }
            });
            
        },
        
        hidetab : function(tabno){  
            $('#content-'+tabno).hide();
        },
        
    };
    app.studentTab = student_tab;
});

