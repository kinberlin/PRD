function getFile(input) {
    var file = input.files[0];
    if (file.size > 5500000) {
        alert("La taille du fichier ne doit pas dépasser 5Mb (5120kb) ");
        input.value = "";
        return;
    }
}

$(document).ready(function () {
    var currentDate = new Date().toISOString().split('T')[0];
    $('#occur_date').attr('max', currentDate);
});

"use strict";
!(function () {
    var C = document.querySelector("#progress-steps");
    C &&
        (C.onclick = function () {
            const e = ["1", "2", "3"],
                i = Swal.mixin({
                    confirmButtonText: "Forward",
                    cancelButtonText: "Back",
                    progressSteps: e,
                    input: "text",
                    inputAttributes: { required: !0 },
                    validationMessage: "This field is required",
                });
            !(async function () {
                var t = [];
                let n;
                for (n = 0; n < e.length;) {
                    var o = await new i({
                        title: "Question " + e[n],
                        showCancelButton: 0 < n,
                        currentProgressStep: n,
                    });
                    o.value
                        ? ((t[n] = o.value), n++)
                        : "cancel" === o.dismiss && n--;
                }
                Swal.fire(JSON.stringify(t));
            })();
        });
})();
