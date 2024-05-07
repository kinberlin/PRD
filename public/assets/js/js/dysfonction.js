function getFile(input) {
        // Get all file input fields
        const fileInputs = document.querySelectorAll('input[type="file"][name^="group-a"]');
        let totalSize = 0;

        // Calculate the total size of selected files
        fileInputs.forEach(input => {
            if (input.files.length > 0) {
                totalSize += input.files[0].size; // Assuming only one file is selected per input
            }
        });

        // Convert total size to MB
        const totalSizeInMB = totalSize / (1024 * 1024);

        // Check if total size exceeds 5MB
        if (totalSizeInMB > 5) {
            // Display error message
            alert('Total size of files cannot exceed 5MB.');
            // Clear the file input fields to prevent submission
            fileInputs.forEach(input => {
                input.value = ''; // Clear the file input value
            });
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

