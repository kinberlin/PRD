@extends('rq.theme.main')
@section('title')
    Signaler un Dysfonctionnement
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">......</span> .....
        </h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Détail sur le Signalement</h5>
                        <small class="text-muted float-end">Infos</small>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Noms</label>
                                <input type="text" class="form-control" id="basic-default-fullname"
                                    placeholder="Drystan Tchamba" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-company">Matricule</label>
                                <input type="text" class="form-control" id="basic-default-company" placeholder="OOMMAHU"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-email">Contact</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" id="basic-default-email" class="form-control"
                                        placeholder="john.doe" aria-label="john.doe"
                                        aria-describedby="basic-default-email2">
                                    <span class="input-group-text" id="basic-default-email2">@example.com/673955909</span>
                                </div>
                                <div class="form-text"> Extras</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date d'enregistrement sur Glitch</label>
                                <input type="date" class="form-control" value="2024-04-02" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-message">Date de Constat</label>
                                <input type="date" class="form-control" value="2024-04-04" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-message">Description</label>
                                <textarea id="basic-default-message" class="form-control" placeholder="Jai constaté que .... " readonly></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pieces Jointes soumises</h5>
                        <small class="text-muted float-end">avec ce Signalement </small>
                    </div>
                    <div class="card-body">
                        <form>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Multi Column with Form Separator -->
        <div class="card mb-4">
            <h5 class="card-header">Informations complementaires</h5>
            <form class="card-body">
                <hr class="my-4 mx-n4">
                <h6> Info Supplementaires</h6>
                <div class="row g-3">
                    <div class="col-md-6 select2-primary">
                        <label class="form-label" for="multicol-language1">Processus Concernés (<span style="color: red">*</span>)</label>
                        <select id="multicol-language1" class="select2 form-select" required>
                            <option value="en" selected>Achats</option>
                            <option value="fr">Productions</option>
                            <option value="de">Ventes</option>
                        </select>
                    </div>
                    <div class="col-md-6 select2-primary">
                        <label class="form-label" for="multicol-language2">Processus Impactés (<span style="color: red">*</span>)</label>
                        <select id="multicol-language2" class="select2 form-select" multiple required>
                            <option value="en" selected>Achats</option>
                            <option value="fr" selected>Productions</option>
                            <option value="de">Ventes</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gravité (<span style="color: red">*</span>)</label>
                        <select class="form-control" data-allow-clear="true" required>
                            <option value="en" selected>Légere</option>
                            <option value="fr" selected>Grave</option>
                            <option value="de">Tres Grave</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-last-name">Probabilité (<span style="color: red">*</span>)</label>
                        <input type="number" placeholder="Entrer un chiffre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-country">Site Concernés(<span style="color: red">*</span>)</label>
                        <select id="multicol-country" class="select2 form-select" data-allow-clear="true" required>
                            <option value="">Choisissez un site</option>
                            <option value="Australia">Australia</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Belarus">Belarus</option>
                            <option value="Brazil">Brazil</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Responsables probable de l'incident</label>
                        <input type="text" class="form-control"
                            placeholder="Le(s) Nom(s) de(s) Responsable(s) & matricule(s) si possible">
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Soumettre</button>
                    <button type="reset" class="btn btn-label-secondary">Annulé</button>
                </div>
            </form>
        </div>
        <!-- Collapsible Section -->
        <div class="row my-4">
            <div class="col">
                <h6> Mesures correctives</h6>
                <div class="accordion" id="collapsibleSection">
                    <div class="card accordion-item">
                        <h2 class="accordion-header" id="headingDeliveryAddress">
                            <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                data-bs-target="#collapseDeliveryAddress" aria-expanded="true"
                                aria-controls="collapseDeliveryAddress"> Actions correctives</button>
                        </h2>
                        <div id="collapseDeliveryAddress" class="accordion-collapse collapse show"
                            data-bs-parent="#collapsibleSection">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="form-repeater col-md-12">
                                        <div data-repeater-list="group-a">
                                            <div data-repeater-item>
                                                <div class="row">
                                                    <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label" for="form-repeater-1-1">Action</label>
                                                        <input type="text" id="form-repeater-1-1" class="form-control"
                                                            placeholder="..." />
                                                    </div>
                                                    <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label"
                                                            for="form-repeater-1-3">Département</label>
                                                        <select id="form-repeater-1-3" class="form-select">
                                                            <option value="Male">Info...</option>
                                                            <option value="Female">RH...</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label" for="multicol-country">Personnes</label>
                                                        <select id="multicol-country" class="select2 form-select"
                                                            data-allow-clear="true">
                                                            <option value="Australia">Monsieur Y</option>
                                                            <option value="Bangladesh">M. Z</option>
                                                            <option value="Belarus">M. B</option>
                                                            <option value="Brazil">M. A</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label">Delai</label>
                                                        <input type="text" class="form-control dob-picker"
                                                            placeholder="YYYY-MM-DD">
                                                    </div>
                                                    <div
                                                        class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                                        <button class="btn btn-label-danger mt-4" data-repeater-delete>
                                                            <i class="bx bx-x me-1"></i>
                                                            <span class="align-middle">Retirer</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="mb-0">
                                            <button class="btn btn-primary" data-repeater-create>
                                                <i class="bx bx-plus me-1"></i>
                                                <span class="align-middle">Ajouter une Action</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scriptContent')
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave-phone.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/jquery-repeater/jquery-repeater.js') !!}"></script>
    <script src="{!! url('assets/js/js/dysfonction.js') !!}"></script>
    <script src="{!! url('assets/js/js/forms-extras.js') !!}"></script>
    <script src="{!! url('assets/js/js/form-layouts.js') !!}"></script>
@endsection
