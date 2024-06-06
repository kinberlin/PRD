<div class="container-xxl flex-grow-1 container-p-y">
    @if (session('errors'))
        <div class="card col-6">
            <div class="card-body">
                <h5 class="card-title">Notification</h5>
                <p class="card-text text-danger">
                    {{ session('errors') }}
                </p>
            </div>
        </div>
    @endif
    <div class="col-12">
        <div class="card mb-4">
            <h5 class="card-header">Sites
                <span wire:loading wire:target="save"><button class="btn btn-secondary" disabled>
                        <span class="spinner-grow me-1" aria-hidden="true"></span>
                        En Cours d'execution ...
                    </button></span>
            </h5>

            <div class="card-body">
                <div class="demo-inline-spacing">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">Ajouter un Site</button>
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd"
                        aria-labelledby="offcanvasEndLabel" aria-modal="true" role="dialog">
                        <div class="offcanvas-header">
                            <h5 id="offcanvasEndLabel" class="offcanvas-title">Formulaire d'Ajout de Site
                            </h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body mx-0 flex-grow-0">
                            <form class="add-new-user pt-0 fv-plugins-bootstrap5 fv-plugins-framework"
                                wire:submit="save">
                                @csrf
                                <div class="mb-3 fv-plugins-icon-container">
                                    <label class="form-label" for="name">Nom du Site</label>
                                    <input type="text" class="form-control" id="name" placeholder="..."
                                        wire:model.debounce.1s="name" required>
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('name')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container">
                                    <label class="form-label" for="location">Emplacement</label>
                                    <input type="text" class="form-control" id="location" placeholder="..."
                                        wire:model.debounce.1s="location" required>
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('location')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container">
                                    <label class="form-label" for="ents">Entreprise</label>
                                    <select id="ents" class="form-select" wire:model="enterprise" required>
                                        <option value="" style="display: none;"></option>
                                        @foreach ($ents as $e)
                                            <option value="{{ $e->id }}">{{ $e->name }}</option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('enterprise')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Ajouter</button>
                                <button type="reset" class="btn btn-label-secondary"
                                    data-bs-dismiss="offcanvas">Annuler</button>
                                <input type="hidden">
                            </form>
                        </div>
                    </div>
                    <button type="button" class="btn btn-info" id="importBtn">Importer depuis un
                        fichier</button>
                    <button type="button" class="btn btn-success" id="exportXlsxBtn">Exporter le tableau
                        vers
                        Excel</button>
                    <input type="file" style="visibility: hidden" id="excelFileInput">

                    <form action="/admin/site" method="POST">
                        @csrf
                        <table id="dataTable" class="display" style="width:100%">
                        </table>
                        <button id="checkAllBtn" class="secondary-btn">Vérifier</button>
                        <button id="submitBtn" type="submit">Soumettre</button>
                    </form>
                </div>
            </div>
            <hr class="m-0">
        </div>
    </div>
    <div class="card">

        <div class="card-body">
            <h5 class="card-title">Liste des Sites sur PRD</h5>
            <div class=" align-items-start justify-content-between">
                <table id="datatables-orders"
                    class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                    <thead>
                        <tr>
                            <th>#Id</th>
                            <th>Entreprise</th>
                            <th>Site</th>
                            <th>Emplacement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td>{{ $ents->where('id', $d->enterprise)->first()->name }} (ID
                                    :{{ $d->enterprise }})</td>
                                <td>{{ $d->name }} </td>
                                <td>{{ $d->location }}</td>
                                <td> <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#majentreprise{{ $d->id }}">
                                        M.A.J
                                    </button>
                                    <div class="modal animate__animated animate__bounceInUp"
                                        id="majentreprise{{ $d->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form class="modal-content" action="/admin/site/{{ $d->id }}"
                                                method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">M.A.J
                                                        {{ $d->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col mb-3">
                                                            <label for="nameBasic{{ $d->id }}"
                                                                class="form-label">Nom</label>
                                                            <input type="text" id="nameBasic{{ $d->id }}"
                                                                name="name" value="{{ $d->name }}"
                                                                class="form-control" placeholder="Entrer le nom">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-3">
                                                            <label for="namelocation{{ $d->id }}"
                                                                class="form-label">Emplacement</label>
                                                            <input type="text" name="location"
                                                                value="{{ $d->location }}"
                                                                id="namelocation{{ $d->id }}"
                                                                class="form-control"
                                                                placeholder="Entrer l'emplacement">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-3">
                                                            <label for="nameents{{ $d->id }}"
                                                                class="form-label">Choisissez
                                                                l'Entreprise</label>
                                                            <select name="enterprise"
                                                                id="nameents{{ $d->id }}" class="form-select"
                                                                required>
                                                                @foreach ($ents as $e)
                                                                    @if ($d->enterprise == $e->id)
                                                                        <option value="{{ $e->id }}" selected>
                                                                            {{ $e->name }}</option>
                                                                    @else
                                                                        <option value="{{ $e->id }}">
                                                                            {{ $e->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-label-secondary"
                                                        data-bs-dismiss="modal">Fermer</button>
                                                    <button type="submit"
                                                        class="btn btn-primary">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>