
var form = document.getElementById("myForm");
  
  if (form) {
    form.addEventListener("submit", function(event) {
      event.preventDefault(); // Prevent default form submission
      // Additional logic or actions can be performed here
    });
  }
$(document).ready(function () {
  // Store the original options of select2
  var select2Options = $('#multicol-language2 option').clone();

  /*$('#multicol-language1').change(function () {
    var selectedValue = $(this).val();
    var selectedExtraInfo = $(this).find('option:selected').data('extra-info');

    // Clear select2 and add the original options
    $('#multicol-language2').empty().append(select2Options);

    // Filter and remove the corresponding option based on the selected extra info
    $('#multicol-language2 option[data-extra-info="' + selectedExtraInfo + '"]').remove();
  });
    var mySelect = $('.impact_proc_select');
    if (mySelect.length) {
        mySelect.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Select an option',
            allowClear: true,
            dropdownParent: mySelect.parent(),
        });
    }*/
  $('.impact_proc_select').select2({
                placeholder: 'Select an option',
                allowClear: true
            });
  $("#saveActionsBtn").click(function (event) {
    // Submit the form

    $("input[name*='delay']").each(function () {
      $(this).attr('name', 'delay[]');
    });
    $("input[name*='action']").each(function () {
      $(this).attr('name', 'action[]');
    });
    $("select[name*='userr']").each(function () {
      $(this).attr('name', 'user[]');
    });
    $("select[name*='departmentt']").each(function () {
      $(this).attr('name', 'department[]');
    });
    var form = $("#myForm");

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
