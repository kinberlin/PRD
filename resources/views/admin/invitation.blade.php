@extends('admin.theme.main')
@section('title')
    Mes Invitations aux Réunions
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Invitations</h5>
                <hr class="m-0">
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <h5 class="card-title">Invitations sur PRD</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Dysfonctionnement</th>
                                <th>Objet &<br>Motif</th>
                                <th>Lieu &<br>Lien</th>
                                <th>Emmeteur</th>
                                <th>Date</th>
                                <th>Heure de Début<br>& de Fin </th>
                                <th>Ma Décision</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>
                                        @if ($dys->where('id', $d->dysfonction)->first() != null)
                                            No.{{ $dys->where('id', $d->dysfonction)->first()->code }}
                                        @else
                                            Ce dysfonctionnement n'existe plus.
                                        @endif
                                    </td>
                                    <td>Objet : <b>{{ $d->objet }}</b><br>Motif : <b>{{ $d->motif }}</b></td>
                                    <td>Lieu : <b>{{ blank($d->place) ? 'Aucun Lieu indiqué' : $d->place }}</b><br>Lien :
                                        <b>{{ blank($d->link) ? 'Aucun Lien indiqué' : $d->link }}</b>
                                    </td>
                                    <td>{{ $d->rq }}</td>
                                    <td>{{ $d->odates }}</td>
                                    <td>Début : {{ $d->begin }}<br>Fin : {{ $d->end }}</td>
                                    @php
                                        $invite = $d->findInviteByMatricule(Auth::user()->matricule);
                                    @endphp
                                    <td>{{ $invite->decision }}</td>
                                    <td>
                                        @if ($invite)
                                            @if ($invite->decision == 'En attente de Validation')
                                                <button type="button" class="btn btn-success fs-5 fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#majInviteC{{ $invite->matricule }}">
                                                    Confirmer
                                                </button><br>
                                                <button type="button" class="btn btn-warning fs-5 fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#majInviteR{{ $invite->matricule }}">
                                                    Désister
                                                </button>
                                    </td>
                                @else
                                    Aucune action requise
                            @endif
                            @endif
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
        @if ($invite)
            @if ($invite->decision == 'En attente de Validation')
                <div class="modal modal-top fade" id="majInviteC{{ $invite->matricule }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('invitation.invite.confirmation') }}">
                            @csrf
                            <input type="hidden" name="invitation" value="{{ $d->id }}" />
                            <input type="hidden" name="matricule" value="{{ $invite->matricule }}" />
                            <input type="hidden" name="decision" value="Accept" />
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTopTitle">Confirmation de
                                    Présence!</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <p class="card-text">
                                        En continuant, vous confirmer votre présence. Voulez
                                        vous Continuer ?
                                        <b>Notez que cette action est irréversible!</b>
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-label-success">Confirmer ma
                                    Présence</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal modal-top fade" id="majInviteR{{ $invite->matricule }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('invitation.invite.confirmation') }}">
                            @csrf
                            <input type="hidden" name="invitation" value="{{ $d->id }}" />
                            <input type="hidden" name="matricule" value="{{ $invite->matricule }}" />
                            <input type="hidden" name="decision" value="Reject" />
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTopTitle">Décliner
                                    l'Invitation!</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <p class="card-text">
                                        En continuant, vous décliner votre présence. Voulez
                                        vous Continuer ?
                                        <b>Notez que cette action est irréversible!</b>
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary"
                                    data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-label-danger">Décliner</button>
                            </div>
                        </form>
                    </div>
                </div>
                </td>
            @else
                Aucune action requise
            @endif
        @endif
        </td>
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
    <script>
        $(document).ready(function() {
            $('#selents').change(function() {
                var selectedOption = $(this).find(':selected');
                var selectedValue = selectedOption.attr('data-extra-info');
                $('#seldep option').hide();
                $('#seldep option[data-extra-info="' + selectedValue + '"]').show();
                $('#seldep').val($('#selsite option:visible:first').val());
            });
        });
    </script>
@endsection
