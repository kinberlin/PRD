function executeAfterAjax() {
    let direction = "ltr"; // Initialize direction
    // Check if RTL mode is enabled and adjust direction if needed
    isRtl && (direction = "rtl");
    const v = document.getElementById("calendar"),
        m = document.querySelector(".app-calendar-sidebar"),
        p = document.getElementById("addEventSidebar"),
        f = document.querySelector(".app-overlay"),
        g = {
            "Résolution de Dysfonctionnement": "primary",
            "Evaluation de Dysfonctionnement": "danger",
            Autres: "warning",
        },
        b = document.querySelector(".offcanvas-title"),
        h = document.querySelector(".btn-toggle-sidebar"),
        //y = document.querySelector('button[type="submit"]'),
        y = document.getElementById("regInvitation"),
        z = document.querySelector('button[type="submit"]'),
        S = document.querySelector(".btn-delete-event"),
        L = document.querySelector(".btn-cancel"),
        E = document.querySelector("#eventTitle"),
        k = document.querySelector("#eventStartDate"),
        w = document.querySelector("#eventEndDate"),
        x = document.querySelector("#eventURL"),
        q = $("#eventLabel"),
        D = $("#eventGuests"),
        P = document.querySelector("#eventLocation"),
        M = document.querySelector("#eventDescription"),
        T = document.querySelector(".allDay-switch"),
        A = document.querySelector(".select-all"),
        F = [].slice.call(document.querySelectorAll(".input-filter")),
        Y = document.querySelector(".inline-calendar");
    let a,
        l = events,
        r = !1,
        e;
    const C = new bootstrap.Offcanvas(p);

    $(".btn-toggle-sidebar").click(function (event) {
        console.log("test toggle");
        L.classList.remove("d-none");
        b && (b.innerHTML = "Ajouter un Evenement"),
            (y.innerHTML = "Ajouter"),
            y.classList.remove("btn-update-event"),
            y.classList.add("btn-add-event"),
            S.classList.add("d-none"),
            m.classList.remove("show"),
            f.classList.remove("show");
        $("#eventTitle").val("");
        $("#dysfunctionList").val("");
        $("#eventLabel").val("");
        $("#eventStartDate").val("");
        $("#flatpickr-begintime").val("");
        $("#flatpickr-endtime").val("");
        $("#eventLocation").val("");
        $("#eventURL").val("");
        $("#eventDescription").val("");
        $(".ext_invites").html("");
        D.prop("selected", false);
        D.val(null).trigger("change");
    });
    function t(e) {
        return e.id
            ? "<span class='badge badge-dot bg-" +
                  $(e.element).data("label") +
                  " me-2'> </span>" +
                  e.text
            : e.text;
    }
    A &&
        A.addEventListener("click", (e) => {
            console.log("clicked");
            e.currentTarget.checked
                ? document
                      .querySelectorAll(".input-filter")
                      .forEach((e) => (e.checked = 1))
                : document
                      .querySelectorAll(".input-filter")
                      .forEach((e) => (e.checked = 0)),
                i.refetchEvents();
        });
    F &&
        F.forEach((e) => {
            e.addEventListener("click", () => {
                document.querySelectorAll(".input-filter:checked").length <
                document.querySelectorAll(".input-filter").length
                    ? (A.checked = !1)
                    : (A.checked = !0),
                    i.refetchEvents();
            });
        });
    function n(e) {
        return e.id
            ? "<div class='d-flex flex-wrap align-items-center'><div class='avatar avatar-xs me-2'><img src='" +
                  assetsPath +
                  $(e.element).data("avatar") +
                  "' alt='#' class='rounded-circle' /></div>" +
                  e.text +
                  "</div>"
            : e.text;
    }
    var d, o;
    function s() {
        var e = document.querySelector(".fc-sidebarToggle-button");
        for (
            e.classList.remove("fc-button-primary"),
                e.classList.add("d-lg-none", "d-inline-block", "ps-0");
            e.firstChild;

        )
            e.firstChild.remove();
        e.setAttribute("data-bs-toggle", "sidebar"),
            e.setAttribute("data-overlay", ""),
            e.setAttribute("data-target", "#app-calendar-sidebar"),
            e.insertAdjacentHTML(
                "beforeend",
                '<i class="bx bx-menu bx-sm text-heading"></i>'
            );
    }
    q.length &&
        q.wrap('<div class="position-relative"></div>').select2({
            placeholder: "Select value",
            dropdownParent: q.parent(),
            templateResult: t,
            templateSelection: t,
            minimumResultsForSearch: -1,
            escapeMarkup: function (e) {
                return e;
            },
        }),
        D.length &&
            D.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Select value",
                dropdownParent: D.parent(),
                closeOnSelect: !1,
                templateResult: n,
                templateSelection: n,
                escapeMarkup: function (e) {
                    return e;
                },
            }),
        k &&
            (d = k.flatpickr({
                enableTime: !0,
                altFormat: "Y-m-dTH:i:S",
                minDate: "today",
                onReady: function (e, t, n) {
                    n.isMobile && n.mobileInput.setAttribute("step", null);
                },
            })),
        w &&
            (o = w.flatpickr({
                enableTime: !0,
                altFormat: "Y-m-dTH:i:S",
                onReady: function (e, t, n) {
                    n.isMobile && n.mobileInput.setAttribute("step", null);
                },
            })),
        Y &&
            (e = Y.flatpickr({
                monthSelectorType: "static",
                inline: !0,
            }));
    let i = new Calendar(v, {
        initialView: "dayGridMonth",
        events: function (e, t) {
            let n = (function () {
                let t = [],
                    e = [].slice.call(
                        document.querySelectorAll(".input-filter:checked")
                    );
                return (
                    e.forEach((e) => {
                        t.push(e.getAttribute("data-value").toLowerCase());
                    }),
                    t
                );
            })();
            t(
                l.filter(function (e) {
                    return n.includes(e.extendedProps.calendar.toLowerCase());
                })
            );
        },
        plugins: [dayGridPlugin, interactionPlugin, listPlugin, timegridPlugin],
        editable: !0,
        dragScroll: !0,
        dayMaxEvents: 2,
        eventResizableFromStart: !0,
        customButtons: { sidebarToggle: { text: "Sidebar" } },
        headerToolbar: {
            start: "sidebarToggle, prev,next, title",
            end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
        },
        direction: direction,
        initialDate: new Date(),
        navLinks: !0,
        eventClassNames: function ({ event: e }) {
            return ["fc-event-" + g[e._def.extendedProps.calendar]];
        },
        dateClick: function (e) {
            e = moment(e.date).format("YYYY-MM-DD");
            u(),
                C.show(),
                b && (b.innerHTML = "Ajouter un Evenement"),
                (y.innerHTML = "Add"),
                y.classList.remove("btn-update-event"),
                y.classList.add("btn-add-event"),
                S.classList.add("d-none"),
                $("#eventTitle").val(""),
                $("#dysfunctionList").val("1"),
                $("#eventLabel").val(""),
                $("#eventStartDate").val(""),
                $("#flatpickr-begintime").val(""),
                $("#flatpickr-endtime").val(""),
                $("#eventLocation").val(""),
                $("#eventURL").val(""),
                $("#eventDescription").val(""),
                $(".ext_invites").html(""),
                (k.value = e);
            //(w.value = e);
        },
        eventClick: function (e) {
            (e = e),
                (a = e.event).url &&
                    (e.jsEvent.preventDefault(), window.open(a.url, "_blank")),
                C.show(),
                b && (b.innerHTML = "Mettre a jour"),
                (y.innerHTML = "MAJ"),
                y.classList.add("btn-update-event"),
                y.classList.remove("btn-add-event"),
                S.classList.remove("d-none"),
                (E.value = a.title),
                d.setDate(a.start, !0, "Y-m-d"),
                //!0 === a.allDay ? (T.checked = !0) : (T.checked = !1),
                /*null !== a.end
                    ? o.setDate(a.end, !0, "Y-m-d")
                    : o.setDate(a.start, !0, "Y-m-d"),*/
                q.val(a.extendedProps.calendar).trigger("change"),
                void 0 !== a.extendedProps.location &&
                    (P.value = a.extendedProps.location),
                void 0 !== a.extendedProps.guests &&
                    D.val(a.extendedProps.guests).trigger("change"),
                void 0 !== a.extendedProps.description &&
                    (M.value = a.extendedProps.description);
            console.log("Click evenement");
            $.ajax({
                url: "/invitations/show/" + a.id,
                method: "GET",
                dataType: "json",
                success: function (response) {
                    // Loop through the array of events
                    var eventsArray = JSON.parse(response.data);
                    $.each(eventsArray, function (index, eventData) {
                        // Populate the form fields with the event data
                        $("#eventTitle").val(eventData.object);
                        $("#dysfunctionList").val(eventData.dysfonction);
                        $("#eventLabel").val(eventData.motif);
                        $("#eventStartDate").val(eventData.dates);
                        $("#flatpickr-begintime").val(eventData.begin);
                        $("#flatpickr-endtime").val(eventData.end);
                        $("#eventLocation").val(eventData.place);
                        $("#eventURL").val(eventData.link);
                        $("#eventDescription").val(eventData.description);
                        $("#regInvitation").val(eventData.id);
                        D.prop("selected", false);
                        D.val(null).trigger("change");

                        // For internal invites select box
                        // Extract emails from JSON response
                        console.log(eventData.internal_invites);
                        var internalInvites = Array.isArray(eventData.internal_invites) ? eventData.internal_invites : JSON.parse(eventData.internal_invites);
                        var emails = internalInvites.map(function (item) {
                            return item.email;
                        });
                        console.log(emails);
                        // Unselect all options
                        var selectElement = $("#eventGuests");
                        selectElement.prop("selected", false);

                        // Track emails not found in select options
                        var emailsNotFound = [];

                        // Loop through emails and select matching options
                        emails.forEach(function (email) {
                            var optionFound = false;
                            selectElement.find("option").each(function () {
                                if ($(this).data("extra-info") === email) {
                                    $(this).prop("selected", true);
                                    optionFound = true;
                                    //return false; // Exit the loop
                                }
                            });
                            if (!optionFound) {
                                emailsNotFound.push(email);
                            }
                        });

                        // Display emails not found
                        if (emailsNotFound.length > 0) {
                            var spanElement = $("<span>").text(
                                "Emails not found in select: " +
                                    emailsNotFound.join(", ")
                            );
                            selectElement.after(spanElement);
                        }

                        // For external invites input fields
                        //var externalInvitesContainer = $('[data-repeater-list="group-a"]');
                        let newItem = "";
                        $.each(
                            JSON.parse(eventData.external_invites),
                            function (index, email) {
                                newItem +=
                                    '<div data-repeater-item class="ext_invites2" style>' +
                                    '<div class="row">' +
                                    '<input type="email" class="form-control" id="form-repeater--' +
                                    index +
                                    '-100" name="group-a[' +
                                    (2 + index) * -1 +
                                    '][extuser]" value="' +
                                    email +
                                    '" placeholder="@ex.com" required>' +
                                    '<button class="btn btn-label-danger" data-repeater-delete>' +
                                    '<i class="bx bx-x me-1"></i>' +
                                    "</button>" +
                                    "</div>" +
                                    "</div>";
                                /*Code below To Append
                            if ($('.ext_invites2').length) { $('.ext_invites2').append(newItem); }
                            else if ($('.ext_invites1').length) { $('.ext_invites1').append('<div data-repeater-item class="ext_invites2">' + newItem + '</div>'); }
                            else if ($('.ext_invites').length) { $('.ext_invites').append('<div data-repeater-list="group-a" class="ext_invites1"><div data-repeater-item class="ext_invites2">' + newItem + '</div></div>'); }*/
                            }
                        );
                        if ($(".ext_invites2").length) {
                            $(".ext_invites2").html(newItem);
                        } else if ($(".ext_invites1").length) {
                            $(".ext_invites1").html(
                                '<div data-repeater-item class="ext_invites2">' +
                                    newItem +
                                    "</div>"
                            );
                        } else if ($(".ext_invites").length) {
                            $(".ext_invites").html(
                                '<div data-repeater-list="group-a" class="ext_invites1"><div data-repeater-item class="ext_invites2">' +
                                    newItem +
                                    "</div></div>"
                            );
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching events:", error);
                },
            });
        },
        datesSet: function () {
            s();
        },
        viewDidMount: function () {
            s();
        },
    });
    i.render(), s();
    var c = document.getElementById("eventForm");
    function u() {
        (x.value = ""),
            (k.value = ""),
            (E.value = ""),
            (P.value = ""),
            D.val("").trigger("change"),
            (M.value = "");
        console.log("Im in");
    }
    FormValidation.formValidation(c, {
        fields: {
            object: {
                validators: {
                    notEmpty: { message: "Entrer un Objet " },
                },
            },
            dysfunction: {
                validators: {
                    notEmpty: {
                        message:
                            "Veuillez choisir le dysfonctionnement concerné ",
                    },
                },
            },
            motif: {
                validators: {
                    notEmpty: { message: "Veuillez choisir un motif " },
                },
            },
            dates: {
                validators: {
                    notEmpty: { message: "Entrer la date et l'heure  " },
                },
            },
            begin: {
                validators: {
                    notEmpty: { message: "Entrer l'heure de début " },
                },
            },
            end: {
                validators: {
                    notEmpty: { message: "Entrer l'heure de fin " },
                },
            },
            place: {
                validators: {
                    notEmpty: { message: "Entrer un lieu " },
                },
            },
            link: {
                validators: {},
            },
            description: {
                validators: {
                    notEmpty: { message: "Entrez une courte description svp" },
                },
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: "",
                rowSelector: function (e, t) {
                    return ".mb-3";
                },
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus(),
        },
    })
        .on("core.form.valid", function () {
            r = !0;
        })
        .on("core.form.invalid", function () {
            r = !1;
        }),
        h &&
            h.addEventListener("click", (e) => {
                L.classList.remove("d-none");
            }),
        S.addEventListener("click", (e) => {
            var t;
            (t = parseInt(a.id)),
                (l = l.filter(function (e) {
                    return e.id != t;
                })),
                i.refetchEvents(),
                C.hide();
        }),
        p.addEventListener("hidden.bs.offcanvas", function () {
            u();
        }),
        h.addEventListener("click", (e) => {
            b && (b.innerHTML = "Ajouter un Evenement"),
                (y.innerHTML = "Ajouter"),
                y.classList.remove("btn-update-event"),
                y.classList.add("btn-add-event"),
                S.classList.add("d-none"),
                m.classList.remove("show"),
                f.classList.remove("show");
        }),
        e.config.onChange.push(function (e) {
            i.changeView(i.view.type, moment(e[0]).format("YYYY-MM-DD")),
                s(),
                m.classList.remove("show"),
                f.classList.remove("show");
        });
}
