
/**
 * Created by com53 on 16/11/15.
 */


function videoLoad(main, video, src) {
    var iframe = document.createElement("iframe");
    iframe.height ="317";
    iframe.width ="417";
    src += "?autoplay=1&rel=0";
    iframe.src=src;
    iframe.frameborder ="0";
     var id = document.getElementById(video);
     iframe.setAttribute('allowFullScreen', '')
     id.style.display = "none";
     document.getElementById(main).appendChild(iframe);



}
