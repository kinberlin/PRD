
"use strict";
!(function () {
    // Select all forms with the class 'formChangePassword'
    const forms = document.querySelectorAll(".formChangePassword");

    forms.forEach(form => {
        // Initialize form validation
        FormValidation.formValidation(form, {
            fields: {
                newPassword: {
                    validators: {
                        notEmpty: { message: "Please enter new password" },
                        stringLength: {
                            min: 6,
                            message: "Le mot de passe doit être supérieure à 06 caractères.",
                        },
                        regexp: {
                            regexp: /^(?=.*[A-Z])(?=.*\W).+$/,
                            message: "Assurez-vous que ces exigences sont respectées : au moins 6 caractères, une lettre majuscule et un symbole."
                        }
                    },
                },
                confirmPassword: {
                    validators: {
                        notEmpty: { message: "Please confirm new password" },
                        identical: {
                            compare: function () {
                                return form.querySelector('[name="newPassword"]').value;
                            },
                            message: "Le mot de passe et sa confirmation ne sont pas identiques.",
                        },
                        stringLength: {
                            min: 6,
                            message: "Le mot de passe doit contenir au moins 6 caractères.",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: "",
                    rowSelector: ".form-password-toggle",
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
            },
            init: (instance) => {
                instance.on('core.form.valid', function () {
                    // Submit the form after successful validation
                    form.submit();
                });

                instance.on("plugins.message.placed", function (e) {
                    e.element.parentElement.classList.contains("input-group") &&
                    e.element.parentElement.insertAdjacentElement(
                        "afterend",
                        e.messageElement
                    );
                });
            },
        });
    });
})();
