document.getElementById("myForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission
    // Additional logic or actions can be performed here
});
$(document).ready(function () {
    var currentDate = new Date().toISOString().split('T')[0];
    $('#eventStartDate').attr('min', currentDate);
    $(".btn-add-event").click(function (event) {
        // Submit the form

        $("input[name*='extuser']").each(function () {
            $(this).attr('name', 'extuser[]');
        });
        var form = $("#myForm");

        // Check if all required fields are filled
        if (form[0].checkValidity()) {
            // Submit the form if all required fields are filled
            form.action = "/rq/invitation";
            form.submit();
        } else {
            // If required fields are not filled, trigger HTML5 validation
            form[0].reportValidity();
        }
    });
    $(".btn-update-event").click(function (event) {
        // Submit the form

        $("input[name*='extuser']").each(function () {
            $(this).attr('name', 'extuser[]');
        });
        var form = $("#myForm");

        // Check if all required fields are filled
        if (form[0].checkValidity()) {
            // Submit the form if all required fields are filled
            form.action = "/rq/invitation/update/"+$('#regInvitation').val();
            form.submit();
        } else {
            // If required fields are not filled, trigger HTML5 validation
            form[0].reportValidity();
        }
    });

});

"use strict";
$(function () {
    var e = $(".selectpicker"),
        t = $(".select2-searching"),
        n = $(".select2-icons"),
        t = document.querySelector("#flatpickr-endtime"),
        b = document.querySelector("#flatpickr-begintime");
    t.flatpickr({ enableTime: !0, noCalendar: !0 });
    b.flatpickr({ enableTime: !0, noCalendar: !0 });
    function i(e) {
        return e.id ?
            "<i class='" + $(e.element).data("icon") + " me-2'></i>" + e.text :
            e.text;
    }
    e.length && e.selectpicker(),
        t.length &&
        t.each(function () {
            var e = $(this);
            e.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Select value",
                dropdownParent: e.parent(),
            });
        }),
        n.length &&
        n.wrap('<div class="position-relative"></div>').select2({
            dropdownParent: n.parent(),
            templateResult: i,
            templateSelection: i,
            escapeMarkup: function (e) {
                return e;
            },
        });
});
/*
var e = Array.apply(null, Array(100)).map(function () {
    return (
        Array.apply(null, Array(~~(10 * Math.random() + 3)))
            .map(function () {
                return String.fromCharCode(26 * Math.random() + 97);
            })
            .join("") + "@gmail.com"
    );
});
const n = document.querySelector("#TagifyEmailList"),
    s = new Tagify(n, {
        pattern:
            /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
        whitelist: e,
        callbacks: {
            invalid: function (a) {
                console.log("invalid", a.detail);
            },
        },
        dropdown: { position: "text", enabled: 1 },
    }),
    l = n.nextElementSibling;
l.addEventListener("click", function () {
    s.addEmptyTag();
});*/