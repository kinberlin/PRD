@extends('employees.theme.main')
@section('title')
    Consulter mes assignations de tâches
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Dysfonctionnements /</span> Tâches
        </h4>
        <div class="row mt-2">
            <!-- Timeline Basic-->
            @foreach ($pltU as $ps)
                @php
                    $_p = $processes->where('id', $ps->process)->first();
                @endphp
                <h4 class="mt-4">Processus : {{ is_null($_p) ? 'Nous ne trouvons pas ce processus' : $_p->name }}</h4>
                <hr style="border-top: 1px solid var(--bs-info);">
                @if (!is_null($_p))
                    @php
                    $tasks = $data->where('process', $_p->id);
                    $fildys = $dys->whereIn('id', $tasks->pluck('dysfunction')->unique());
                        /*$fildys = $dys->filter(function ($x) use ($_p) {
                            // Decode the JSON string into a PHP array
                            $s = json_decode($x->impact_processes, true);

                            // Check if the search string is in the array
                            return is_array($s) && in_array($_p->name, $s);
                        });*/
                    @endphp
                    @if (count($fildys) > 0)
                        @foreach ($fildys as $_d)
                            @if (count($data->where('dysfunction', $_d->id)) > 0)
                                <div class="col-xl-6 mb-4 mb-xl-0">
                                    <div class="card">
                                        <h5 class="card-header">Dysfonctionnement No. {{ $_d->code }}</h5>
                                        <div class="card-body">
                                            <ul class="timeline">
                                                @foreach ($data->where('dysfunction', $_d->id) as $d)
                                                    <li id="accordionPopoutTask{{ $d->id }}"
                                                        class="timeline-item timeline-item-transparent">
                                                        <span class="timeline-point-wrapper"><span
                                                                class="timeline-point @if ($d->progress < 0.5) timeline-point-danger @elseif($d->progress < 0.99)timeline-point-warning @else timeline-point-success @endif"></span></span>
                                                        <div class="card accordion-item">
                                                            <div class=" accordion-header"
                                                                id="headingTask{{ $d->id }}">
                                                                <div class="timeline-header">
                                                                    <h6 class="mb-0">{{ $d->text }}</h6>
                                                                    <span class="text-muted">{{ $d->start_date }}</span>
                                                                </div>
                                                                <button type="button" class="accordion-button collapsed"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#accordionTask{{ $d->id }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="accordionTask{{ $d->id }}">
                                                                    Voir Plus
                                                                </button>
                                                                <p>
                                                                    Progression de la tâche : <br>
                                                                <div class="progress">
                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                                        role="progressbar"
                                                                        style="width: {{ $d->progress * 100 }}%;"
                                                                        aria-valuenow="{{ $d->progress * 100 }}"
                                                                        aria-valuemin="0" aria-valuemax="100">
                                                                        {{ $d->progress * 100 }}%</div>
                                                                </div>
                                                                </p>
                                                                <hr />
                                                            </div>
                                                            <div id="accordionTask{{ $d->id }}"
                                                                class="accordion-collapse collapse"
                                                                aria-labelledby="headingTask{{ $d->id }}"
                                                                data-bs-parent="#accordionPopoutTask{{ $d->id }}">
                                                                <div class="accordion-body">
                                                                    {{ $d->description }}<br>Assigner par :
                                                                    {{ $d->created_by }}<br>Durée : {{ $d->duration }}
                                                                    Jours
                                                                </div>
                                                                @if ($d->proof != null)
                                                                    <a href="{{ Storage::url($d->proof) }}">
                                                                        <i class="bx bx-link"></i>
                                                                        Voir la preuve de complétude de la tâche.
                                                                    </a>
                                                                @else
                                                                    <h6>Aucune preuve de complétude de tâche n'a été ajoutée
                                                                    </h6>
                                                                @endif

                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach

                                                <li class="timeline-end-indicator">
                                                    <i class="bx bx-check-circle"></i>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <h4 class="text-info" style="text-align: center">Aucune tâche</h4>
                        @endif
                @endif
                <hr style="border-top: 1px solid var(--bs-info);">
            @endforeach
            <!-- /Timeline Basic -->
        </div>
    </div>
@endsection
@section('scriptContent')
@endsection
