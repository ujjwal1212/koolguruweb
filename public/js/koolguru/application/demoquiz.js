$(document).ready(function () {
var demoquiz = {};

demoquiz = {
        currenttab : 0,
        hidetab: function (tabno) {
            $('#content-' + tabno).hide();
        },
        
        opencurrenttab : function(){             
            this.openTag(this.currenttab);
        },
        
        hidealltab : function (){
             $('.kooltab').each(function(){
                var tabid = $(this).attr('id');
                var split = tabid.split('-');
                $('#content-' + parseInt(split[1])).hide();;
             });
        },
        
        openTag : function(tabno){           
            this.hidealltab();
            $('#content-' + tabno).show();
            this.makeActiveTab(tabno);
        },
        
        makeActiveTab : function(tabid){
            this.makeInActiveAllTab();
            $('#tab-' + tabid).addClass('activetab');
        },
        
        makeInActiveAllTab : function(){
            $('.kooltab').each(function(){
                $(this).removeClass('activetab');
             });
        },
        
        makeEnableAllTab : function(){
            $('.kooltab').each(function(){
                $(this).addClass('enabletab');
             });
        },
        
        makeDisableAllTab : function(){
            $('.kooltab').each(function(){
                $(this).removeClass('enabletab');
             });
        }
        
    };

app.demoquiz = demoquiz;

});
