function valholliday(id) {
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
                title: "Demande de Congé Validé!",
                text: "La validation est en cours et sera effectuée dans quelques instants...",
                customClass: { confirmButton: "btn btn-success" }
            });

            // Execute console.log after 2 seconds
            setTimeout(function () {
                console.log(x.data("extra-info"));
                window.location.href = '/employee/holliday/validate/' + x.val() + '/' + x.data("extra-info");
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
function rejholliday(id) {
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
                window.location.href = '/employee/holliday/cancel/' + x.val() + '/' + x.data("extra-info");
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