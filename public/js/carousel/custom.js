$(function(){
  SyntaxHighlighter.all();
});
$(window).load(function(){
  $('.flexslider').flexslider({
    animation: "slide",
    animationLoop: true,
    itemWidth: 1170,
    itemMargin: 5,
    pausePlay: true,
    start: function(slider){
      $('body').removeClass('loading');
    }
  });
});