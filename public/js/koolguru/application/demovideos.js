$(document).ready(function () {
    var demo_video_tab = {};
    demo_video_tab = {        
        applytabbing: function (tablist) {
            for (var i = 0; i <= tablist.length; i++) {
                var tabval = parseInt(tablist[i]);
                if (tabval == 1) {
                    this.openTag(i + 1);
                }
            }
        },
        openTag: function (tabno) {
            var _this = this;
            $('.kooltab').each(function () {
                var tabid = $(this).attr('id');
                tabid = tabid.replace("tab-", "");
                $('#tab-' + tabid).removeClass('activetab');
                $('#content-' + tabid).hide();
                _this.pausevideo(parseInt(tabid));
                if (parseInt(tabid) == parseInt(tabno)) {
                    $('#tab-' + tabid).addClass('activetab');
                    _this.startvideo(tabno);
                    $('#content-' + tabno).show();
                }
            });

        },
        hidetab: function (tabno) {
            $('#content-' + tabno).hide();
        },
        
        startvideo : function(tabno){
            var pl = videojs('#video-' + tabno);
            pl.play();
        },
        
        pausevideo : function(tabno){            
            var pl = videojs('#video-' + tabno);
            pl.pause();
        },
    };
    app.demoVideotab = demo_video_tab;
});

function demo_vedi_click(){
    alert(demo_vedi_click);
}