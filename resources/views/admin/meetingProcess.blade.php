@extends('admin.theme.main')
@section('title')
    Gestion Réunions en cours
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Réunions en cours</h5>
                <hr class="m-0">
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <h5 class="card-title">En cours de traitement ...</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Dysfonctionnement</th>
                                <th>Objet de la<br>réunion & motif</th>
                                <th>Horraire</th>
                                <th>Lieu</th>
                                <th>Lien</th>
                                <th>Initiateur</th>
                                <th>Invités Interne</th>
                                <th>Invités Externe</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    @php
                                    $_dys = $dys->where('id',$d->dysfonction)->id
                                @endphp
                                    <td>{{$d->id}}</td>
                                    <td>{{$_dys->enterprise .' ('.$_dys->site.') '.' '.$_dys->gravity  }} ID : {{$d->dysfonction}}</td>
                                    <td>{{ $d->object }}<br>{{$d->motif}}</td>
                                    <td>Date : {{$d->dates}}<br>Heure de Début : {{$d->begin}}<br>Heure de Fin : {{$d->end}}</td>
                                    <td>{{ $d->place }}</td>
                                    <td>{{ $d->link != null ? $d->link : "Aucun lien n'a été enregistré."  }}</td>
                                    <td>{{ $d->rq }}</td>
                                    <td>{{ $d->matricule }}</td>
                                    <td><button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#majemp{{ $d->id }}">
                                            M.A.J
                                        </button><br>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#majsecureemp{{ $d->id }}">
                                            Accessibilité
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="majsecureemp{{ $d->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalCenterTitle">Paramètre
                                                            d'Accessibilité ({{ $d->firstname }})</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="formChangePassword" method="POST"
                                                            action="{{ route('admin.user.updatePassword', $d->id) }}"
                                                            data-number="30{{ $d->id }}">
                                                            @csrf
                                                            <div class="alert alert-warning" role="alert">
                                                                <h6 class="alert-heading mb-1">Assurez-vous que ces
                                                                    exigences sont respectées : </h6>
                                                                <span>au moins 8 caractères, une lettre majuscule et un
                                                                    symbole.</span>
                                                            </div>
                                                            <div class="row">
                                                                <div class="mb-3 col-12 col-sm-6 form-password-toggle">
                                                                    <label class="form-label"
                                                                        for="newPassword30{{ $d->id }}">Nouveau Mot
                                                                        de Passe</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <input class="form-control" type="password"
                                                                            id="newPassword30{{ $d->id }}"
                                                                            name="newPassword"
                                                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                                                        <span class="input-group-text cursor-pointer"><i
                                                                                class="bx bx-hide"></i></span>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3 col-12 col-sm-6 form-password-toggle">
                                                                    <label class="form-label"
                                                                        for="confirmPassword30{{ $d->id }}">Confirmer
                                                                        le Mot de Passe</label>
                                                                    <div class="input-group input-group-merge">
                                                                        <input class="form-control" type="password"
                                                                            name="confirmPassword"
                                                                            id="confirmPassword30{{ $d->id }}"
                                                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                                                        <span class="input-group-text cursor-pointer"><i
                                                                                class="bx bx-hide"></i></span>
                                                                    </div>
                                                                </div>
                                                                <div><label class="switch switch-lg">
                                                                        <input type="checkbox" name="access"
                                                                            class="switch-input"
                                                                            @if ($d->access == 1) checked @endif>
                                                                        <span class="switch-toggle-slider">
                                                                            <span class="switch-on">
                                                                                <i class="bx bx-check"></i>
                                                                            </span>
                                                                            <span class="switch-off">
                                                                                <i class="bx bx-x"></i>
                                                                            </span>
                                                                        </span>
                                                                        <span class="switch-label"> Accès à la
                                                                            Plateforme</span>
                                                                    </label>
                                                                    <button type="submit"
                                                                        class="btn btn-primary me-2">Enregistrer les
                                                                        paramètres</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-label-secondary"
                                                            data-bs-dismiss="modal">Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-onboarding modal fade animate__animated"
                                            id="majemp{{ $d->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form class="modal-content text-center" method="POST" enctype="multipart/form-data"
                                                    action="{{ route('admin.user.updateProfile', $d->id) }}">
                                                    <div class="modal-header border-0">
                                                        <a class="text-muted close-label" href="javascript:void(0);"
                                                            data-bs-dismiss="modal">close</a>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                        <div class="onboarding-media">
                                                            <div class="mx-2">
                                                                <img src="../../assets/img/illustrations/pencil-rocket.png"
                                                                    alt="girl-unlock-password-light" class="img-fluid"
                                                                    style="width: 136px; height:140px"
                                                                    data-app-dark-img="illustrations/pencil-rocket.png"
                                                                    data-app-light-img="illustrations/pencil-rocket.png">
                                                            </div>
                                                        </div>
                                                        <div class="onboarding-content mb-0">
                                                            <h4 class="onboarding-title text-body">Mettre a Jour le Profil
                                                                de {{ $d->firstname }}</h4>
                                                            <div class="onboarding-info">Veuillez a renseigner tout les
                                                                champs obligatoires.</div>
                                                            <div>
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="mb-3">
                                                                            <label for="firstnamemaj{{ $d->id }}"
                                                                                class="form-label">Nom</label>
                                                                            <input class="form-control"
                                                                                placeholder="Entrez le Nom"
                                                                                name="firstname" type="text"
                                                                                value="{{ $d->firstname }}"
                                                                                maxlength="20" tabindex="0"
                                                                                id="firstnamemaj{{ $d->id }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="mb-3">
                                                                            <label for="lastnamemaj{{ $d->id }}"
                                                                                class="form-label">Prenom</label>
                                                                            <input class="form-control"
                                                                                placeholder="Entrez le Prenom"
                                                                                name="lastname" type="text"
                                                                                value="{{ $d->lastname }}"
                                                                                tabindex="0" maxlength="20"
                                                                                id="lastnamemaj{{ $d->id }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="mb-3">
                                                                            <label for="phonemaj{{ $d->id }}"
                                                                                class="form-label">Telephone</label>
                                                                            <input class="form-control"
                                                                                placeholder="No. Tel" type="text"
                                                                                value="{{ $d->phone }}"
                                                                                maxlength="10" tabindex="0"
                                                                                name="phone"
                                                                                id="phonemaj{{ $d->id }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="mb-3">
                                                                            <label for="postemaj{{ $d->id }}"
                                                                                class="form-label">Intitulé de
                                                                                Poste</label>
                                                                            <input class="form-control"
                                                                                placeholder="Poste actuel" type="text"
                                                                                value="{{ $d->poste }}"
                                                                                maxlength="30" tabindex="0"
                                                                                name="poste"
                                                                                id="postemaj{{ $d->id }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="mb-3">
                                                                            <label for="emailmaj{{ $d->id }}"
                                                                                class="form-label">Adresse Mail</label>
                                                                            <input class="form-control"
                                                                                placeholder="Email" name="email"
                                                                                type="email"
                                                                                value="{{ $d->email }}"
                                                                                tabindex="0"
                                                                                id="emailmaj{{ $d->id }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="mb-3">
                                                                            <label for="departmentmaj{{ $d->id }}"
                                                                                class="form-label">Département</label>
                                                                            <select class="form-select" tabindex="0"
                                                                                name="department"
                                                                                id="departmentmaj{{ $d->id }}">
                                                                                @foreach ($deps->where('enterprise', $d->enterprise) as $_d)
                                                                                    <option value="{{ $_d->id }}"
                                                                                        @if ($_d->id == $d->department) selected @endif>
                                                                                        {{ $_d->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-3">
                                                                        <div class="avatar avatar-xl">
                                                                            <img id="pvwProfile{{ $d->id }}"
                                                                                src="{{ asset('storage/' . $d->image ) }}"
                                                                                alt="Avatar-{{ $d->firstname }}"
                                                                                class="rounded">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-9">
                                                                        <div class="mb-3">
                                                                            <label for="profileImg{{ $d->id }}"
                                                                                class="form-label">Choisir une photo de
                                                                                Profil</label>
                                                                            <input type="file" name="image"
                                                                                class="form-control-file fileInput"
                                                                                id="profileImg{{ $d->id }}"
                                                                                data-img="pvwProfile{{ $d->id }}"
                                                                                tabindex="0">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-label-secondary"
                                                            data-bs-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-primary">MAJ</button>
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
@endsection
@section('scriptContent')
    <script src="{!! url('assets/vendor/libs/select2/select2.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/popular.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/bootstrap5.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/auto-focus.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave-phone.js') !!}"></script>

    <script src="{!! url('assets/js/js/accessory.js') !!}"></script>
    <script src="{!! url('assets/js/js/app-user-view-security.js') !!}"></script>
    <script>
        $(document).ready(function() {
            $('#selents').change(function() {
                var selectedOption = $(this).find(':selected');
                var selectedValue = selectedOption.attr('data-extra-info');
                $('#seldep option').hide();
                $('#seldep option[data-extra-info="' + selectedValue + '"]').show();
                $('#seldep').val($('#selsite option:visible:first').val());
            });
            $('.fileInput').on('change', function(event) {
                var file = event.target.files[0];
                var imgId = $(this).data('img');
                var $img = $('#' + imgId);

                // Check if file is an image
                if (file && file.type.startsWith('image/')) {
                    // Check if file size is less than 2MB (2 * 1024 * 1024 bytes)
                    if (file.size <= 2 * 1024 * 1024) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $img.attr('src', e.target.result).show();
                        }

                        reader.readAsDataURL(file);
                    } else {
                        alert('The file size exceeds 2MB. Please choose a smaller file.');
                        $(this).val(''); // Clear the input value
                        $img.hide(); // Hide the image if the file is too large
                    }
                } else {
                    alert('Please select a valid image file.');
                    $(this).val(''); // Clear the input value
                    $img.hide(); // Hide the image if it's not a valid image file
                }
            });
        });
    </script>
@endsection
