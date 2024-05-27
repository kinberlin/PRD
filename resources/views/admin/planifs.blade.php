@extends('admin.theme.main')
@section('title')
Page de Planification de Reunion Pour Signalements
@endsection
@section('manualstyle')
<link rel="stylesheet" href="{!! url('assets/vendor/libs/quill/editor.css') !!}" />
<link rel="stylesheet" href="{!! url('assets/vendor/libs/%40form-validation/form-validation.css') !!}" />
<link rel="stylesheet" href="{!! url('assets/vendor/libs/fullcalendar/fullcalendar.css') !!}" />
<link rel="stylesheet" href="{!! url('assets/vendor/css/pages/app-calendar.css') !!}" />
<link rel="stylesheet" href="{!! url('assets/vendor/libs/tagify/tagify.css') !!}" />
@endsection
@section('mainContent')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card app-calendar-wrapper">
        <div class="row g-0">
            <!-- Calendar Sidebar -->
            <div class="col app-calendar-sidebar" id="app-calendar-sidebar">
                <div class="border-bottom p-4 my-sm-0 mb-3">
                    <div class="d-grid">
                        <button class="btn btn-primary btn-toggle-sidebar" data-bs-toggle="offcanvas" data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                            <i class="bx bx-plus me-1"></i>
                            <span class="align-middle">Ajouter un Evenement</span>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <!-- inline calendar (flatpicker) -->
                    <div class="ms-n2">
                        <div class="inline-calendar"></div>
                    </div>

                    <hr class="container-m-nx my-4" />

                    <!-- Filter -->
                    <div class="mb-4">
                        <small class="text-small text-muted text-uppercase align-middle">Filtres</small>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked />
                        <label class="form-check-label" for="selectAll">Voir tout</label>
                    </div>

                    <div class="app-calendar-events-filter">
                        <div class="form-check form-check-danger mb-2">
                            <input class="form-check-input input-filter" type="checkbox" id="select-personal" data-value="Evaluation de Dysfonctionnement" checked />
                            <label class="form-check-label" for="select-personal">Evaluation</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input input-filter" type="checkbox" id="select-business" data-value="Résolution de Dysfonctionnement" checked />
                            <label class="form-check-label" for="select-business">Résolution</label>
                        </div>
                        <div class="form-check form-check-warning mb-2">
                            <input class="form-check-input input-filter" type="checkbox" id="select-family" data-value="Autres" checked />
                            <label class="form-check-label" for="select-family">Autres</label>
                        </div>
                    </div>
                    <!--<div class="app-calendar-events-filter">
                            <div class="form-check form-check-danger mb-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-personal"
                                    data-value="personal" checked />
                                <label class="form-check-label" for="select-personal">Personal</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-business"
                                    data-value="business" checked />
                                <label class="form-check-label" for="select-business">Business</label>
                            </div>
                            <div class="form-check form-check-warning mb-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-family"
                                    data-value="family" checked />
                                <label class="form-check-label" for="select-family">Family</label>
                            </div>
                            <div class="form-check form-check-success mb-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-holiday"
                                    data-value="holiday" checked />
                                <label class="form-check-label" for="select-holiday">Holiday</label>
                            </div>
                            <div class="form-check form-check-info">
                                <input class="form-check-input input-filter" type="checkbox" id="select-etc"
                                    data-value="etc" checked />
                                <label class="form-check-label" for="select-etc">ETC</label>
                            </div>
                        </div>-->
                </div>
            </div>
            <!-- /Calendar Sidebar -->

            <!-- Calendar & Modal -->
            <div class="col app-calendar-content">
                <div class="card shadow-none border-0">
                    <div class="card-body pb-0">
                        <!-- FullCalendar -->
                        <div id="calendar"></div>
                    </div>
                </div>
                <div class="app-overlay"></div>
                <!-- FullCalendar Offcanvas -->
                <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar" aria-labelledby="addEventSidebarLabel">
                    <div class="offcanvas-header border-bottom">
                        <h5 class="offcanvas-title mb-2" id="addEventSidebarLabel">
                            Nouveau Evenement
                        </h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <form class="event-form pt-0" id="myForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="eventTitle">Objet</label>
                                <input type="text" class="form-control" id="eventTitle" name="object" placeholder="Objet de Réunion" required />
                            </div>
                            <div class="mb-3">
                                <label for="dysfunctionList" class="form-label">Signalement /
                                    Dysfonctionnement</label>
                                <select id="dysfunctionList" class="select2-searching form-select form-select-lg" data-allow-clear="true" name="dysfunction" required>
                                    @foreach ($dys as $_d)
                                    <option value="{{ $_d->id }}">
                                        ID : {{ $_d->id }} | Emp. :
                                        {{ $_d->enterprise . ' (' . $_d->site . ')' }}
                                    </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="eventLabel">Motif</label>
                                <select class="select2 select-event-label form-select" id="eventLabel" name="motif" required>
                                    <option data-label="primary" value="Résolution de Dysfonctionnement" selected>
                                        Résolution de Dysfonctionnement
                                    </option>
                                    <option data-label="danger" value="Evaluation de Dysfonctionnement">
                                        Evaluation de Dysfonctionnement
                                    </option>
                                    <option data-label="warning" value="Autres">
                                        Autres
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="eventStartDate">Date et Heure</label>
                                <input type="text" class="form-control" id="eventStartDate" name="dates" placeholder="Horraire de la réunion" required />
                            </div>
                            <div class="mb-3">
                                <label for="flatpickr-begintime" class="form-label">Heure de Début</label>
                                <input type="text" class="form-control" placeholder="HH:MM" name="begin" id="flatpickr-begintime" placeholder="Heure de Début de la réunion" />
                            </div>
                            <div class="mb-3">
                                <label for="flatpickr-endtime" class="form-label">Heure de Fin</label>
                                <input type="text" class="form-control" placeholder="HH:MM" id="flatpickr-endtime" name="end" placeholder="Heure de Fin de la réunion" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="eventLocation">Lieu</label>
                                <input type="text" class="form-control" id="eventLocation" name="place" placeholder="Entrer le Lieu" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="eventURL">Lien de la réunion</label>
                                <input type="url" class="form-control" id="eventURL" name="link" placeholder="https://www.google.com/" />
                            </div>
                            <div class="mb-3 select2-primary">
                                <label class="form-label" for="eventGuests">Invités sur PRD</label>
                                <select class="select2 select-event-guests form-select" id="eventGuests" name="internal_invites[]" multiple>
                                    @foreach ($users as $u)
                                    <option data-avatar="{{ $u->image }}" value="{{ $u->email }}" @if(!empty($u->internal_invites)) @if (in_array($u->email, array_column(json_decode($u->internal_invites), 'email'), true)) selected @endif @endif>
                                        {{ $u->email }} <br> Matricule : {{ $u->matricule }}
                                    </option>
                                    @endforeach
                                </select>
                            </div><!--
                                                <div class="mb-3">
                                                    <label class="form-label" for="idemail">Invités externe sur PRD</label>
                                                     <input id="TagifyEmailList" class="tagify-email-list"
                                                        value="some56.name@website.com">
                                                    <button type="button"
                                                        class="btn btn-sm rounded-pill btn-icon btn-outline-primary mb-1"> <span
                                                            class="tf-icons bx bx-plus"></span> </button>
                                                </div>-->
                            <div class="mb-3">
                                <label class="form-label" for="idemail">Invités externe sur PRD</label>
                                <div class="form-repeater col-md-12" class="ext_invites">
                                    <div data-repeater-list="group-a" class="ext_invites1">
                                        <div data-repeater-item class="ext_invites2">
                                            <div class="row">
                                                <input type="email" class="form-control" id="idemail" name="extuser" placeholder="@ex.com" required />
                                                <button class="btn btn-label-danger" data-repeater-delete>
                                                    <i class="bx bx-x me-1"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <button class="btn btn-info" data-repeater-create>
                                            <i class="bx bx-plus me-1"></i>
                                        </button>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="eventDescription">Description</label>
                                <textarea class="form-control" name="description" id="eventDescription" required></textarea>
                            </div>
                            <div class="mb-3 d-flex justify-content-sm-between justify-content-start my-4">
                                <div>
                                    <button type="submit" id="regInvitation" value="" class="btn btn-primary btn-add-event me-sm-3 me-1">
                                        Ajouter
                                    </button>
                                    <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1" data-bs-dismiss="offcanvas">
                                        Annuler
                                    </button>
                                </div>
                                <button class="btn btn-label-danger btn-delete-event d-none">
                                    Supprimer
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- /Calendar & Modal -->
        </div>
    </div>
</div>
@endsection
@section('scriptContent')
<script src="{!! url('assets/vendor/libs/tagify/tagify.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/fullcalendar/fullcalendar.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/%40form-validation/popular.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/%40form-validation/bootstrap5.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/moment/moment.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/fullcalendar/fullcalendar.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/%40form-validation/auto-focus.js') !!}"></script>
<script src="{!! url('assets/js/js/app-calendar-eventss.js') !!}"></script>
<script src="{!! url('assets/js/js/app-calendarr.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/jquery-repeater/jquery-repeater.js') !!}"></script>
<script src="{!! url('assets/js/js/forms-extras.js') !!}"></script>
<script src="{!! url('assets/js/js/planifs.js') !!}"></script>
@endsection