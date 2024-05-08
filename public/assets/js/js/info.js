
$(document).ready(function () {
  // Store the original options of select2
  var select2Options = $('#multicol-language2 option').clone();

  $('#multicol-language1').change(function () {
    var selectedValue = $(this).val();
    var selectedExtraInfo = $(this).find('option:selected').data('extra-info');

    // Clear select2 and add the original options
    $('#multicol-language2').empty().append(select2Options);

    // Filter and remove the corresponding option based on the selected extra info
    $('#multicol-language2 option[data-extra-info="' + selectedExtraInfo + '"]').remove();
  });
  $('#myForm').submit(function (event) {
    // Prevent the default form submission
    event.preventDefault();

    $('input[name^="delay"]').each(function () {
      $(this).attr('name', 'delay[]');
    });
    $('input[name^="action"]').each(function () {
      $(this).attr('name', 'action[]');
    });
    $('input[name^="user"]').each(function () {
      $(this).attr('name', 'user[]');
    });
    $('input[name^="department"]').each(function () {
      $(this).attr('name', 'department[]');
    });

    // After updating the names, submit the form
    $(this).unbind('submit').submit();
  });
});
$(document).ready(function () {

});