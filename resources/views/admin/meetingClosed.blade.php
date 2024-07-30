@extends('admin.theme.main')
@section('title')
    Gestion des Réunions clôturés
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Réunions clôturés</h5>
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
                                <th>Objet <br>& motif</th>
                                <th>Horraire</th>
                                <th>Lieu &<br>Lien</th>
                                <th>Initiateur</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    @php
                                        $_dys = $dys->where('id', $d->dysfonction)->first();
                                    @endphp
                                    <td>{{ $d->id }}</td>
                                    <td>ID : {{ $d->dysfonction }}
                                        {{ $_dys->enterprise . ' (' . $_dys->site . ') ' . ' ' . $_dys->gravity }} </td>
                                    <td>{{ $d->object }}<br>{{ $d->motif }}</td>
                                    <td>Date : {{ $d->odates }}<br>Début : {{ $d->begin }}<br>Fin :
                                        {{ $d->end }}</td>
                                    <td>{{ $d->place }}
                                        <br>
                                        @if ($d->link)
                                            <a href="{{ $d->link }}" target="_blank">Lien</a>
                                        @else
                                            Aucun lien n'a été enregistré.
                                        @endif
                                        </br>
                                    <td>{{ $d->rq }}</td>
                                    <td>Terminée le : {{ $d->closed_at }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#majsecureemp{{ $d->id }}">
                                            Participants
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!--Begin with datatable Modals -->
    @foreach ($data as $d)
        <div class="modal fade" id="majsecureemp{{ $d->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" class="modal-content" action="{{ route('invitation.participation', $d->id) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Invitations Reunion :
                            No. #{{ $d->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="table-responsive">
                            <table class="table border-top table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">Invités</th>
                                        <th class="text-nowrap text-center">✅ Accepté
                                        </th>
                                        <th class="text-nowrap text-center">❌ Rejeté
                                        </th>
                                        <th class="text-nowrap text-center">👩🏻‍💻
                                            Présent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($d->getInternalInvites() as $i)
                                        @php
                                            $u = $users->where('matricule', $i->matricule)->first();
                                            $p = $d->findParticipantByMatricule($i->matricule);
                                        @endphp
                                        <tr>
                                            <td class="text-nowrap">
                                                {{ $u != null ? $u->firstname . ' (' . $u->matricule . ')' : 'Utilisateur Introuvable.' }}
                                            </td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox"
                                                        @if ($i->decision == 'Confirmer') checked @endif disabled />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox"
                                                        @if ($i->decision == 'Rejeté') checked @endif disabled />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" name="participant[]"
                                                        value="{{ $i->matricule }}"
                                                        @if ($p != null) checked @endif />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach (json_decode($d->external_invites, true) as $e)
                                        @php
                                            $p = $d->findParticipantByMatricule($e);
                                        @endphp
                                        <tr>
                                            <td class="text-nowrap">{{ $e }}
                                            </td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" disabled />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" disabled />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" name="participantext[]"
                                                        value="{{ $e }}"
                                                        @if ($p != null) checked @endif />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Soumettre</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
    <!--End with datatable Modals -->
@endsection
@section('scriptContent')
    <script src="{!! url('assets/vendor/libs/select2/select2.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/popular.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/bootstrap5.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/auto-focus.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave-phone.js') !!}"></script>

    <script src="{!! url('assets/js/js/accessory.js') !!}"></script>
@endsection
