$(document).ready(function () {

    // Array of public holidays
    var publicHolidays = [
        new Date('2024-01-01'), // New Year
        new Date('2024-12-25'), // Christmas
        new Date('2024-05-01'), // Labour Day
        // Add more holidays as needed
    ];
    var counter = 0;

    $("#calculate").click(function () {
        var startDate = new Date($('.start_date').val());
        var duration = parseInt($('.duration').val());
        var currentDate = new Date();

        // Check if the start date is greater than the current date
        if (startDate <= currentDate) {
            alert("La date de début doit être supérieur a la date actuelle.");
            $('.end_date_display').val('');
            return;
        }
        // Add duration to start date
        var endDate = new Date(startDate.getTime() + duration * 24 * 60 * 60 * 1000);

        // Check if start or end date is Sunday or Saturday
        var startDayOfWeek = startDate.getDay();
        var endDayOfWeek = endDate.getDay();

        var currentDate = new Date(startDate);
        while (currentDate <= endDate) {
            var dayOfWeek = currentDate.getDay();
            // Saturday
            if (dayOfWeek === 6) {
                duration += 0.6667;
                console.log(duration);
            }
            // Sunday
            else if (dayOfWeek === 0) {
                duration += 1;
                console.log(duration);
            }
            currentDate.setDate(currentDate.getDate() + 1);
        }

        // Check for public holidays within the range
        for (var i = 0; i < publicHolidays.length; i++) {
            var holiday = publicHolidays[i];

            if (holiday >= startDate && holiday <= endDate) {
                duration += 1;
                console.log(duration);
            }
        }
        // Check for public holidays within the range
        for (var i = 0; i < publicHolidays.length; i++) {
            var holiday = publicHolidays[i];

            if (holiday > startDate && holiday < endDate) {
                // Check if the holiday falls on a Sunday
                if (holiday.getDay() === 0) {
                    duration -= 1;
                    console.log(duration); // Subtract 1 from the duration
                }
                // Check if the holiday falls on a Saturday
                else if (holiday.getDay() === 6) {
                    duration -= 0.6667; // Add (1 - 0.6667) to the duration
                    console.log(duration);
                }
            }
            if (holiday.getTime() === startDate.getTime() && holiday.getDay() === 0) {
                duration -= (1 * 1);
                console.log(duration);
            }
            // Saturday
            else if (holiday.getTime() === endDate.getTime() && holiday.getDay() === 6) {
                duration -= 1 * (0.6667);
                console.log(duration);
            }
            if (holiday.getTime() === endDate.getTime() && holiday.getDay() === 0) {
                duration -= (1 * 1);
                console.log(duration);
            }
            // Saturday
            else if (holiday.getTime() === startDate.getTime() && holiday.getDay() === 6) {
                duration -= 2 * (1 + 0.6667);
                console.log(duration);
            }
        }
        // Adjust the end date based on updated duration
        endDate = new Date(startDate.getTime() + duration * 24 * 60 * 60 * 1000);

        // Format the end date
        var options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        };
        var formattedEndDate = endDate.toLocaleDateString('fr-FR', options);

        // Display the end date in the input text field
        $('.end_date_display').val(formattedEndDate);
        var year = endDate.getFullYear();
        var month = String(endDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        var day = String(endDate.getDate()).padStart(2, '0');
        var hours = String(endDate.getHours()).padStart(2, '0');
        var minutes = String(endDate.getMinutes()).padStart(2, '0');

        // Concatenate the components into the desired format
        var endDateFormat = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes;

        $('#enddate').val(endDateFormat);
    });
    $("#add_holiday").click(function () {
        if (!$('#new_holiday_date').val()) {
            alert('Choisissez une date');
            return;
        }
        var startDate = new Date($('#new_holiday_date').val());
        var currentDate = new Date();
        // Check if the public holliday is greater than the current date
        if (startDate <= currentDate) {
            alert("La date du jour ferier doit être supérieur a la date actuelle.");
            return;
        }
        var newHolidayDate = new Date($('#new_holiday_date').val());

        // Check if the holiday already exists
        if (publicHolidays.some(holiday => holiday.getTime() === newHolidayDate.getTime())) {
            alert("La date existe déja dans la liste des jours feriés a considérer.");
            return;
        } else {
            publicHolidays.push(newHolidayDate);
            alert("La date a été ajouté avec succes.");
        }
        counter++;
        var uniqueId = 'input_' + counter;
        var inputHtml =
            '<div class="col-md-3"><input class="form-control" type="date" name="ferier[]" id="' +
            uniqueId + '" readonly required> </div>';
        inputHtml +=
            '<div class="col-md-3"><button class="removeInput btn btn-icon btn-label-danger" data-input-id="' +
            uniqueId + '"><span class="tf-icons bx bx-trash"></span></button></div>';

        $('#divferier').append(inputHtml);

        // Set value of the newly created input to the value of the public holliday date input
        $('#' + uniqueId).val($('#new_holiday_date').val());
    });
    $(document).on('click', '.removeInput', function () {
        var inputId = $(this).data('input-id');
        var removedDate = $('#' + inputId).val();

        var removedIndex = publicHolidays.findIndex(date => date.toISOString().substring(0, 10) ===
            removedDate);
        if (removedIndex !== -1) {
            publicHolidays.splice(removedIndex, 1);
            console.log(publicHolidays); // Optional: Log the updated array
        }
        $('.end_date_display').val('');
        $('#enddate').val('');
        $('#' + inputId).remove();
        $(this).remove();
    });
});

$(document).ready(function () {
    var currentDate = new Date().toISOString().split('T')[0];
    $('.start_date').attr('min', currentDate);
    $('#new_holiday_date').attr('min', currentDate);
    var selectedOption = $("#pmetype").find("option:selected");
    var extraInfo = selectedOption.data("extra-info");
    $("#pmemaxduration").val(extraInfo);
    $("#pmeduration").attr("placeholder",
        "Votre permission ne pourra pas aller au-dela de : " +
        extraInfo + " Jours");
    $("#pmeduration").attr("max", extraInfo);
    $("#pmeduration").val("");
});

$(document).ready(function () {
    var counter = 0;
    $("#pmetype").change(function () {
        var selectedOption = $(this).find("option:selected");
        var extraInfo = selectedOption.data("extra-info");
        $("#pmemaxduration").val(extraInfo);
        $("#pmeduration").attr("placeholder",
            "Votre permission ne pourra pas aller au-dela de : " +
            extraInfo + " Jours");
        $("#pmeduration").attr("max", extraInfo);
        $("#pmeduration").val("");

    });

    $("#pmeduration").on('input', function () {
        var enteredValue = $(this).val();
        var maxValue = parseInt($(this).attr('max'));
        $('.end_date_display').val('');
        $('#enddate').val('');
        if ($.isNumeric(enteredValue)) {
            var numericValue = parseInt(enteredValue);
            if (numericValue < 1 || numericValue > maxValue) {
                $(this).val('');
                alert("Entrer un chiffre compris entre 1 et " + maxValue
                );
            }
        } else {
            $(this).val('');
            alert("Entrer un nombre valide s'il vous plaît.");
        }
    });
});