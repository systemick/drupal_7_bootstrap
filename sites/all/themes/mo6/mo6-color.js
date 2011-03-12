// Support for farbtastic for color picking in theme settings.

(function($) { 

$(document).ready(function () {
  var farb = $.farbtastic("#colorpicker");
  $("input.color_textfield").each(function () {
    $(this).css("background-color", farb.color);
    farb.linkTo(this);
    $(this).focus(function () {
      farb.linkTo(this);
    });
  });
});

}) (jQuery);
