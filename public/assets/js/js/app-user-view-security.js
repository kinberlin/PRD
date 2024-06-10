"use strict";
!(function () {
  // Select all forms with the class 'formChangePassword'
  const forms = document.querySelectorAll(".formChangePassword");

  forms.forEach(form => {
    // Get the number from the data attribute
    const number = form.dataset.number;
    // Initialize form validation
    FormValidation.formValidation(form, {
      fields: {
        newPassword: {
          validators: {
            notEmpty: { message: "Please enter new password" },
            stringLength: {
              min: 8,
              message: "Le mot de passe doit être supérieure à 08 caractères.",
            },
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
              min: 8,
              message: "Le mot de passe doit contenir au moins 8 caractères.",
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
      init: (form) => {
        form.on("plugins.message.placed", function (e) {
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
