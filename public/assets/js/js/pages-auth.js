"use strict"; 
const formAuthentication = document.querySelector("#formAuthentication"); 

document.addEventListener("DOMContentLoaded", function (e) { 
    var t; 
    formAuthentication && FormValidation.formValidation(formAuthentication, { 
        fields: { 
            username: { 
                validators: { 
                    notEmpty: { message: "Please enter username" }, 
                    stringLength: { min: 6, message: "Username must be more than 6 characters" } 
                } 
            }, 
            email: { 
                validators: { 
                    notEmpty: { message: "Votre Identifiant ..." }, 
                    stringLength: { min: 3, message: "Identifiant non valide" }  
                } 
            }, 
            "matricule": { 
                validators: { 
                    notEmpty: { message: "Votre Matricule/Email est requis dans ce champ"}, 
                    stringLength: { min: 6, message: "Identifiant non valide" } 
                } 
            }, 
            password: { 
                validators: { 
                    notEmpty: { message: "Votre mot de passe est requis dans ce champ" }, 
                    stringLength: { min: 1, message: "Le mot de passe ne peut pas être vide" } 
                } 
            }, 
            "confirm-password": { 
                validators: { 
                    notEmpty: { message: "Please confirm password" }, 
                    identical: { 
                        compare: function () { 
                            return formAuthentication.querySelector('[name="password"]').value 
                        }, 
                        message: "The password and its confirm are not the same" 
                    }, 
                    stringLength: { min: 6, message: "Password must be more than 6 characters" } 
                } 
            }, 
            terms: { 
                validators: { 
                    notEmpty: { message: "Please agree terms & conditions" } 
                } 
            } 
        }, 
        plugins: { 
            trigger: new FormValidation.plugins.Trigger, 
            bootstrap5: new FormValidation.plugins.Bootstrap5({ 
                eleValidClass: "", 
                rowSelector: ".mb-3" 
            }), 
            submitButton: new FormValidation.plugins.SubmitButton, 
            defaultSubmit: new FormValidation.plugins.DefaultSubmit, 
            autoFocus: new FormValidation.plugins.AutoFocus 
        }, 
        init: e => { 
            e.on("plugins.message.placed", function (e) { 
                e.element.parentElement.classList.contains("input-group") && e.element.parentElement.insertAdjacentElement("afterend", e.messageElement) 
            }) 
        } 
    }), 
    (t = document.querySelectorAll(".numeral-mask")).length && t.forEach(e => { 
        new Cleave(e, { numeral: !0 }) 
    }) 
}); 
