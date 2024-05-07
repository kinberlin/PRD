$(document).ready(function() {
  $("#multicol-language1").change(function() {
    var selectedOptions = $(this).find("option:selected");

    // Clear all options from multicol-language2
    $("#multicol-language2").empty();

    // Repopulate multicol-language2 options except for the selected options in select1
    $("#multicol-language2 option").each(function() {
      var optionValue = $(this).val();
      var optionData = $(this).attr("data-extra-info");
      var optionExists = false;

      selectedOptions.each(function() {
        if ($(this).val() === optionValue && $(this).attr("data-extra-info") === optionData) {
          optionExists = true;
          return false; // Exit the inner loop
        }
      });

      if (!optionExists) {
        $("#multicol-language2").append('<option value="' + optionValue + '" data-extra-info="' + optionData + '">' + $(this).text() + '</option>');
      }
    });
  });
});