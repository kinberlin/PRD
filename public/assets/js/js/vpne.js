function valpne(id) {
    var x = $("#" + id);
    Swal.fire({
        title: "Êtes-vous certain?",
        text: "Vous ne pourrez pas revenir en arrière!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, Continuer & Valider!",
        cancelButtonText: "Fermer",
        customClass: { confirmButton: "btn btn-primary me-3", cancelButton: "btn btn-label-secondary" },
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            Swal.fire({
                icon: "success",
                title: "PNE Validé!",
                text: "La validation est en cours et sera effectuée dans quelques instants...",
                customClass: { confirmButton: "btn btn-success" }
            });

            // Execute console.log after 2 seconds
            setTimeout(function () {
                console.log(x.data("extra-info"));
                window.location.href = '/employee/pne/validate/' + x.val() + '/' + x.data("extra-info");
            }, 2000);

        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
                title: "Annulé",
                text: "Processus de validation en cours d'annulation",
                icon: "error",
                customClass: { confirmButton: "btn btn-success" }
            });
        }
    });
}
function rejpne(id) {
    var x = $("#" + id);
    Swal.fire({
        title: "Avertissement!",
        text: " Cette action est irréversible!",
        icon: "warning",
        cancelButtonText: "Fermer",
        showClass: { popup: "animate__animated animate__flipInX" },
        customClass: { confirmButton: "btn btn-primary me-3", cancelButton: "btn btn-label-secondary" },
        buttonsStyling: !1
    }).then(function (result) {
        if (result.value) {
            Swal.fire({
                title: "Annulé",
                text: "Processus de validation en cours d'annulation",
                icon: "error",
                customClass: { confirmButton: "btn btn-success" }
            });

            // Execute console.log after 2 seconds
            setTimeout(function () {
                console.log(x.data("extra-info"));
                window.location.href = '/employee/pne/cancel/' + x.val() + '/' + x.data("extra-info");
            }, 2000);

        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
                title: "Annulé",
                text: "Fin de l'opération.",
                icon: "error",
                customClass: { confirmButton: "btn btn-success" }
            });
        }
    });
}

"use strict";

(function () {
    var /*t = document.querySelector("#basic-alert"),
        n = document.querySelector("#with-title"),
        o = document.querySelector("#footer-alert"),
        e = document.querySelector("#html-alert"),
        i = document.querySelector("#position-top-start"),
        c = document.querySelector("#position-top-end"),
        s = document.querySelector("#position-bottom-start"),
        r = document.querySelector("#position-bottom-end"),
        a = document.querySelector("#bounce-in-animation"),
        u = document.querySelector("#fade-in-animation"),
        l = document.querySelector("#flip-x-animation"),
        m = document.querySelector("#tada-animation"),
        f = document.querySelector("#shake-animation"),
        b = document.querySelector("#type-success"),
        d = document.querySelector("#type-info"),
        S = document.querySelector("#type-warning"),
        y = document.querySelector("#type-error"),
        p = document.querySelector("#type-question"),
        w = document.querySelector("#custom-image"),
        h = document.querySelector("#auto-close"),
        g = document.querySelector("#outside-click"),
        C = document.querySelector("#progress-steps"),
        B = document.querySelector("#ajax-request"),
        k = document.querySelector("#confirm-text"),*/
        x = document.querySelector("#valpne");

    /*if (t) {
        t.onclick = function () {
            Swal.fire({
                title: "Any fool can use a computer",
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (n) {
        n.onclick = function () {
            Swal.fire({
                title: "The Internet?,",
                text: "That thing is still around?",
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (o) {
        o.onclick = function () {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong!",
                footer: "<a href>Why do I have this issue?</a>",
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (e) {
        e.onclick = function () {
            Swal.fire({
                title: "<strong>HTML <u>example</u></strong>",
                icon: "info",
                html: 'You can use <b>bold text</b>, <a href="https://pixinvent.com/" target="_blank">links</a> and other HTML tags',
                showCloseButton: !0,
                showCancelButton: !0,
                focusConfirm: !1,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
                confirmButtonAriaLabel: "Thumbs up, great!",
                cancelButtonText: '<i class="fa fa-thumbs-down"></i>',
                cancelButtonAriaLabel: "Thumbs down",
                customClass: { confirmButton: "btn btn-primary me-3", cancelButton: "btn btn-label-secondary" },
                buttonsStyling: !1
            });
        }
    }

    if (i) {
        i.onclick = function () {
            Swal.fire({
                position: "top-start",
                icon: "success",
                title: "Your work has been saved",
                showConfirmButton: !1,
                timer: 1500,
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (c) {
        c.onclick = function () {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Your work has been saved",
                showConfirmButton: !1,
                timer: 1500,
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (s) {
        s.onclick = function () {
            Swal.fire({
                position: "bottom-start",
                icon: "success",
                title: "Your work has been saved",
                showConfirmButton: !1,
                timer: 1500,
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (r) {
        r.onclick = function () {
            Swal.fire({
                position: "bottom-end",
                icon: "success",
                title: "Your work has been saved",
                showConfirmButton: !1,
                timer: 1500,
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (a) {
        a.onclick = function () {
            Swal.fire({
                title: "Bounce In Animation",
                showClass: { popup: "animate__animated animate__bounceIn" },
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (u) {
        u.onclick = function () {
            Swal.fire({
                title: "Fade In Animation",
                showClass: { popup: "animate__animated animate__fadeIn" },
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (l) {
        l.onclick = function () {
            Swal.fire({
                title: "Flip In Animation",
                showClass: { popup: "animate__animated animate__flipInX" },
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (m) {
        m.onclick = function () {
            Swal.fire({
                title: "Tada Animation",
                showClass: { popup: "animate__animated animate__tada" },
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (f) {
        f.onclick = function () {
            Swal.fire({
                title: "Shake Animation",
                showClass: { popup: "animate__animated animate__shakeX" },
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (b) {
        b.onclick = function () {
            Swal.fire({
                title: "Good job!",
                text: "You clicked the button!",
                icon: "success",
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (d) {
        d.onclick = function () {
            Swal.fire({
                title: "Info!",
                text: "You clicked the button!",
                icon: "info",
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (S) {
        S.onclick = function () {
            Swal.fire({
                title: "Warning!",
                text: " You clicked the button!",
                icon: "warning",
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (y) {
        y.onclick = function () {
            Swal.fire({
                title: "Error!",
                text: " You clicked the button!",
                icon: "error",
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (p) {
        p.onclick = function () {
            Swal.fire({
                title: "Question!",
                text: " You clicked the button!",
                icon: "question",
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (w) {
        w.onclick = function () {
            Swal.fire({
                title: "Sweet!",
                text: "Modal with a custom image.",
                imageUrl: assetsPath + "img/backgrounds/15.jpg",
                imageWidth: 400,
                imageAlt: "Custom image",
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (h) {
        h.onclick = function () {
            var t;
            Swal.fire({
                title: "Auto close alert!",
                html: "I will close in <strong></strong> seconds.",
                timer: 2e3,
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1,
                willOpen: function () {
                    Swal.showLoading(), t = setInterval(function () {
                        Swal.getHtmlContainer().querySelector("strong").textContent = Swal.getTimerLeft()
                    }, 100)
                },
                willClose: function () { clearInterval(t) }
            }).then(function (t) { t.dismiss === Swal.DismissReason.timer && console.log("I was closed by the timer") });
        }
    }

    if (g) {
        g.onclick = function () {
            Swal.fire({
                title: "Click outside to close!",
                text: "This is a cool message!",
                backdrop: !0,
                allowOutsideClick: !0,
                customClass: { confirmButton: "btn btn-primary" },
                buttonsStyling: !1
            });
        }
    }

    if (C) {
        C.onclick = function () {
            const e = ["1", "2", "3"],
                i = Swal.mixin({
                    confirmButtonText: "Forward",
                    cancelButtonText: "Back",
                    progressSteps: e,
                    input: "text",
                    inputAttributes: { required: !0 },
                    validationMessage: "This field is required"
                });
            !async function () {
                var t = [];
                let n;
                for (n = 0; n < e.length;) {
                    var o = await new i({
                        title: "Question " + e[n],
                        showCancelButton: 0 < n,
                        currentProgressStep: n
                    });
                    o.value ? (t[n] = o.value, n++) : "cancel" === o.dismiss && n--
                }
                Swal.fire(JSON.stringify(t))
            }();
        }
    }

    if (B) {
        B.onclick = function () {
            Swal.fire({
                title: "Submit your Github username",
                input: "text",
                inputAttributes: { autocapitalize: "off" },
                showCancelButton: !0,
                confirmButtonText: "Look up",
                showLoaderOnConfirm: !0,
                customClass: { confirmButton: "btn btn-primary me-3", cancelButton: "btn btn-label-danger" },
                preConfirm: t => fetch("//api.github.com/users/" + t).then(t => {
                    if (t.ok) return t.json();
                    throw new Error(t.statusText)
                }).catch(t => { Swal.showValidationMessage("Request failed:" + t) }),
                backdrop: !0,
                allowOutsideClick: () => !Swal.isLoading()
            }).then(t => {
                t.isConfirmed && Swal.fire({
                    title: t.value.login + "'s avatar",
                    imageUrl: t.value.avatar_url,
                    customClass: { confirmButtonText: "Close me!", confirmButton: "btn btn-primary" }
                })
            });
        }
    }

    if (k) {
        k.onclick = function () {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                customClass: { confirmButton: "btn btn-primary me-3", cancelButton: "btn btn-label-secondary" },
                buttonsStyling: !1
            }).then(function (t) {
                t.value && Swal.fire({
                    icon: "success",
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    customClass: { confirmButton: "btn btn-success" }
                })
            });
        }
    }*/

    if (x) {
        x.onclick = function () {
            Swal.fire({
                title: "Êtes vous certains?",
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Oui, Continuer & Valider!",
                cancelButtonText: "Fermer",
                customClass: { confirmButton: "btn btn-primary me-3", cancelButton: "btn btn-label-secondary" },
                buttonsStyling: !1
            }).then(function (t) {
                console.log(+ ' ' + $('#valpne').data("extra-info"));
                setTimeout(function () {
                    window.location.href = '/employee/pne/validate/' + $('#valpne').val() + '/' + $('#valpne').data("extra-info");
                }, 2000);
                t.value ? Swal.fire({
                    icon: "success",
                    title: "PNE Valider!",
                    text: "La validation est en cours et sera effectuer dans quelques instants...",
                    customClass: { confirmButton: "btn btn-success" }

                })
                    : t.dismiss === Swal.DismissReason.cancel && Swal.fire({
                        title: "Annulé",
                        text: "Processus de validation en cours d'annulation",
                        icon: "error",
                        customClass: { confirmButton: "btn btn-success" }
                    })
            });
        }
    }
})();
