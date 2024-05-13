$(document).ready(function () {
    var currentDate = new Date().toISOString().split('T')[0];
    $('#eventStartDate').attr('min', currentDate);

});
"use strict";
$(function () {
    var e = $(".selectpicker"),
        t = $(".select2-searching"),
        n = $(".select2-icons");

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
var     e = Array.apply(null, Array(100)).map(function () {
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
});