/*globals Drupal:false ,jQuery:false */
(function ($) {
  "use strict";

  //Apply text change when page load for current selected option.
  var selected = $("#edit-field-rig-count-region").val();

  if (selected == 3){
    $("#edit-field-rig-count-weekly-change-wrapper label").text("Monthly Change");
    $("#edit-field-rig-count-weekly-direction-wrapper label").text("Monthly Change Direction");
  }
  else {
    $("#edit-field-rig-count-weekly-change-wrapper label").text("Weekly Change");
    $("#edit-field-rig-count-weekly-direction-wrapper label").text("Weekly Change Direction");
  }

  //Change the text when select option dropdown change.
  $("#edit-field-rig-count-region").change(function(){
    var selected = $(this).children("option:selected").val();

    if (selected == 3){
      $("#edit-field-rig-count-weekly-change-wrapper label").text("Monthly Change");
      $("#edit-field-rig-count-weekly-direction-wrapper label").text("Monthly Change Direction");
    }else {
      $("#edit-field-rig-count-weekly-change-wrapper label").text("Weekly Change");
      $("#edit-field-rig-count-weekly-direction-wrapper label").text("Weekly Change Direction");
    }
  });

})(jQuery);