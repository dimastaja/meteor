$(function() { 
var offset = $("#sticker").offset(); 
var topPadding = 15; 
$(window).scroll(function() { 
if ($(window).scrollTop() > offset.top) { 
$("#sticker").stop().animate({marginTop: $(window).scrollTop() - offset.top + topPadding}); 
} 
else {$("#sticker").stop().animate({marginTop: 0});};}); 
});