@extends('employees.theme.main')
@section('title')
    Page vide pour simulation
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Dysfonctionnements /</span> Tâches
        </h4>

        <div class="row">
            <!-- Timeline Basic-->
            @foreach ($dys as $_d)
                <div class="col-xl-6 mb-4 mb-xl-0">
                    <div class="card">
                        <h5 class="card-header">Dysfonctionnement No. {{ $_d->code }}</h5>
                        <div class="card-body">
                            <ul class="timeline">
                                @foreach ($data->where('dysfunction', $_d->id) as $d)
                                    <li id="accordionPopoutTask{{$d->id}}" class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point-wrapper"><span
                                                class="timeline-point timeline-point-warning"></span></span>
                                        <div class="card accordion-item">
                                            <div class=" accordion-header" id="headingPopoutThree">
                                                <div class="timeline-header">
                                                    <h6 class="mb-0">Interview Schedule</h6>
                                                    <span class="text-muted">6th October</span>
                                                </div>
                                                <button type="button" class="accordion-button collapsed"
                                                    data-bs-toggle="collapse" data-bs-target="#accordionPopoutThree"
                                                    aria-expanded="false" aria-controls="accordionPopoutThree">
                                                    Voir Plus
                                                </button>
                                                <p>
                                                    Lorem ipsum, dolor sit amet consectetur
                                                    adipisicing elit. Possimus quos, voluptates
                                                    voluptas rem veniam expedita.
                                                </p>
                                                <hr />
                                            </div>
                                            <div id="accordionPopoutThree" class="accordion-collapse collapse"
                                                aria-labelledby="headingPopoutThree" data-bs-parent="#accordionPopoutTask{{$d->id}}">
                                                <div class="accordion-body">
                                                    Oat cake toffee chocolate bar jujubes. Marshmallow brownie lemon drops
                                                    cheesecake. Bonbon gingerbread
                                                    marshmallow sweet jelly beans muffin. Sweet roll bear claw candy canes
                                                    oat cake dragée caramels.
                                                </div>
                                                <a href="javascript:void(0)">
                                                    <i class="bx bx-link"></i>
                                                    bookingCard.pdf
                                                </a>
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
            @endforeach
            <!-- /Timeline Basic -->
            <!-- Timeline Basic-->
            <div class="col-xl-6 mb-4 mb-xl-0">
                <div class="card">
                    <h5 class="card-header">Basic</h5>
                    <div class="card-body">
                        <ul class="timeline">
                            <li id="accordionPopoutTask{{$d->id}}" class="timeline-item timeline-item-transparent">
                                <span class="timeline-point-wrapper"><span
                                        class="timeline-point timeline-point-warning"></span></span>
                                <div class="card accordion-item">
                                    <div class=" accordion-header" id="headingPopoutThree">
                                        <div class="timeline-header">
                                            <h6 class="mb-0">Interview Schedule</h6>
                                            <span class="text-muted">6th October</span>
                                        </div>
                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#accordionPopoutThree" aria-expanded="false"
                                            aria-controls="accordionPopoutThree">
                                            Voir Plus
                                        </button>
                                        <p>
                                            Lorem ipsum, dolor sit amet consectetur
                                            adipisicing elit. Possimus quos, voluptates
                                            voluptas rem veniam expedita.
                                        </p>
                                        <hr />
                                    </div>
                                    <div id="accordionPopoutThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingPopoutThree" data-bs-parent="#accordionPopoutTask{{$d->id}}">
                                        <div class="accordion-body">
                                            Oat cake toffee chocolate bar jujubes. Marshmallow brownie lemon drops
                                            cheesecake. Bonbon gingerbread
                                            marshmallow sweet jelly beans muffin. Sweet roll bear claw candy canes oat cake
                                            dragée caramels.
                                        </div>
                                        <a href="javascript:void(0)">
                                            <i class="bx bx-link"></i>
                                            bookingCard.pdf
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-end-indicator">
                                <i class="bx bx-check-circle"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Timeline Basic -->

        </div>
    </div>
@endsection
@section('scriptContent')
@endsection
