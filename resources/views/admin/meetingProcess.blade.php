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
                                <th>Objet <br>& motif</th>
                                <th>Horraire</th>
                                <th>Lieu &<br>Lien</th>
                                <th>Initiateur</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    @php
                                    $_dys = $dys->where('id',$d->dysfonction)->first();
                                @endphp
                                    <td>{{$d->id}}</td>
                                    <td>ID : {{$d->dysfonction}} {{$_dys->enterprise .' ('.$_dys->site.') '.' '.$_dys->gravity  }} </td>
                                    <td>{{ $d->object }}<br>{{$d->motif}}</td>
                                    <td>Date : {{$d->dates}}<br>Début : {{$d->begin}}<br>Fin : {{$d->end}}</td>
                                    <td>{{ $d->place }}
                                    <br>@if($d->link) <a href="{{$d->link}}" target="_blank" >Lien</a> @else Aucun lien n'a été enregistré.@endif</br>
                                    <td>{{ $d->rq }}</td>
                                    <td>{{$d->closed_at != null ? 'Terminée le : '.$d->closed_at .'.': 'En traitement.'}}</td>
                                    <td><button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#majemp{{ $d->id }}">
                                            Participation
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
                                                        <h5 class="modal-title" id="modalCenterTitle">Invitations Reunion : No. #{{ $d->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="formChangePassword" method="POST"
                                                            action="{{ route('admin.user.updatePassword', $d->id) }}"
                                                            data-number="30{{ $d->id }}">
                                                            @csrf
                                                            <div class="table-responsive">
                                                                <table class="table border-top table-striped">
                                                                  <thead>
                                                                    <tr>
                                                                      <th class="text-nowrap">Invités</th>
                                                                      <th class="text-nowrap text-center">✅ Accepté</th>
                                                                      <th class="text-nowrap text-center">❌ Rejeté</th>
                                                                      <th class="text-nowrap text-center">👩🏻‍💻 Présent</th>
                                                                    </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                    @foreach ($d->getInternalInvites() as $i)
                                                                    @php
                                                                        $u = $users->where('matricule', $i->matricule)->first();
                                                                    @endphp
                                                                    <tr>
                                                                      <td class="text-nowrap">{{$u != null ? $u->firstname. ' ('.$u->matricule.')' : 'Utilisateur Introuvable.'}}</td>
                                                                      <td>
                                                                        <div class="form-check d-flex justify-content-center">
                                                                          <input class="form-check-input" type="checkbox" @if ($i->decision == "Confirmer")checked @endif disabled />
                                                                        </div>
                                                                      </td>
                                                                      <td>
                                                                        <div class="form-check d-flex justify-content-center">
                                                                          <input class="form-check-input" type="checkbox" @if ($i->decision == "Rejeté")checked @endif disabled/>
                                                                        </div>
                                                                      </td>
                                                                      <td>
                                                                        <div class="form-check d-flex justify-content-center">
                                                                          <input class="form-check-input" type="checkbox"/>
                                                                        </div>
                                                                      </td>
                                                                    </tr>
                                                                    @endforeach
                                                                    @foreach (json_decode($d->external_invites, true) as $e)
                                                                    <tr>
                                                                      <td class="text-nowrap">{{$e}}</td>
                                                                      <td>
                                                                        <div class="form-check d-flex justify-content-center">
                                                                          <input class="form-check-input" type="checkbox" disabled/>
                                                                        </div>
                                                                      </td>
                                                                      <td>
                                                                        <div class="form-check d-flex justify-content-center">
                                                                          <input class="form-check-input" type="checkbox" disabled/>
                                                                        </div>
                                                                      </td>
                                                                      <td>
                                                                        <div class="form-check d-flex justify-content-center">
                                                                          <input class="form-check-input" type="checkbox"/>
                                                                        </div>
                                                                      </td>
                                                                    </tr>
                                                                    @endforeach
                                                                  </tbody>
                                                                </table>
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
