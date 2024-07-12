
var form = document.getElementById("myForm");
  
  if (form) {
    form.addEventListener("submit", function(event) {
      event.preventDefault(); // Prevent default form submission
      // Additional logic or actions can be performed here
    });
  }
$(document).ready(function () {
  // Store the original options of select2
  $('.impact_proc_select').select2({
                placeholder: 'Select an option',
                allowClear: true
            });
  $("#confirmEvaluation").click(function (event) {
    // Submit the form
    var form = $("#evaluateConfirmForm");

    // Check if all required fields are filled
    if (form[0].checkValidity()) {
      // Submit the form if all required fields are filled
      form.submit();
    } else {
      // If required fields are not filled, trigger HTML5 validation
      form[0].reportValidity();
    }
  });
  $("#btnCloseDys").click(function (event) {
    // Submit the form
    var form = $("#dysConfirmForm");

    // Check if all required fields are filled
    if (form[0].checkValidity()) {
      // Submit the form if all required fields are filled
      form.submit();
    } else {
      // If required fields are not filled, trigger HTML5 validation
      form[0].reportValidity();
    }
  });
});
