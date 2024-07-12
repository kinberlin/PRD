@extends('admin.theme.main')
@section('title')
    Rapport complet sur un dysfonctionnement
@endsection
@section('manualstyle')
    <link rel="stylesheet" href="{!! url('assets/vendor/css/pages/page-faq.css') !!}" />
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="faq-header d-flex flex-column justify-content-center align-items-center h-px-300 position-relative" style="margin-bottom: 25px;">
            <img src="{{ url('assets/img/pages/header.png') }}" class="scaleX-n1-rtl faq-banner-img" alt="background image" />
            <h3 class="text-center">Plus d'info sur un Dysfonctionnement signalé ?</h3>
            <div class="input-wrapper my-3 input-group input-group-merge">
                <span class="input-group-text" id="basic-addon1"><i class="bx bx-search-alt bx-xs text-muted"></i></span>
                <input type="text" class="form-control form-control-lg"
                    placeholder="Entrez le code d'un dysfonctionnement" aria-label="Search"
                    aria-describedby="basic-addon1" />
            </div>
            <p class="text-center mb-0 px-3">
                ou alors contacter le DQ si difficulté survient
            </p>
        </div>
        <div class="row">
        <div class="card mb-4" style="margin-bottom: 25px;">
            <h5 class="card-header">Information sur la déclaration</h5>
            <form class="card-body" >
                <!--<hr class="my-4 mx-n4">
                                                                                <h6> Info Supplementaires</h6>-->
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="basic-default-fullname">Noms</label>
                        <input type="text" class="form-control" id="basic-default-fullname"
                            value={{ $data->emp_signaling }} readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="basic-default-company">Matricule</label>
                        <input type="text" class="form-control" id="basic-default-company"
                            value="{{ $data->emp_matricule }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="basic-default-email">Contact</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="basic-default-email" class="form-control"
                                value="{{ $data->emp_email }}" aria-label="john.doe"
                                aria-describedby="basic-default-email2">
                            <span class="input-group-text" id="basic-default-email2">@</span>
                        </div>
                        <div class="form-text"> Extras</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date d'enregistrement sur PRD</label>
                        <input type="text" class="form-control"
                            value="{{ formatDateInFrench($data->created_at, 'complete')}}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="basic-default-message">Date de Constat</label>
                        <input type="text" class="form-control"
                            value="{{ formatDateInFrench($data->occur_date, 'complete')}}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="basic-default-message">Description</label>
                        <textarea id="basic-default-message" class="form-control" placeholder="Aucune description n'a été faites" readonly> {{ $data->description }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <h4 class="form-label text-center" style="font-size: 18px" for="basic-default-message">Status : <span class="text-primary">{{$data->status_id->name}}</span></h2>
                    </div>
            </form>
        </div></div>
        <!-- Project Cards -->
        <div class="row g-4 mt-6">
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="avatar me-3">
                                    <img src="../../assets/img/icons/brands/social-label.png" alt="Avatar"
                                        class="rounded-circle" />
                                </div>
                                <div class="me-2">
                                    <h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Social Banners</a>
                                    </h5>
                                    <div class="client-info d-flex align-items-center">
                                        <h6 class="mb-0 me-1">Client:</h6><span>Christian Jimenez</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <div class="dropdown z-2">
                                    <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="bx bx-dots-vertical-rounded"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave
                                                Project</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="bg-lighter p-2 rounded me-auto mb-3">
                                <h6 class="mb-1">$24.8k <span class="text-body fw-normal">/ $18.2k</span></h6>
                                <span>Total Budget</span>
                            </div>
                            <div class="text-end mb-3">
                                <h6 class="mb-1">Start Date: <span class="text-body fw-normal">14/2/21</span></h6>
                                <h6 class="mb-1">Deadline: <span class="text-body fw-normal">28/2/22</span></h6>
                            </div>
                        </div>
                        <p class="mb-0">We are Consulting, Software Development and Web Development Services.</p>
                    </div>
                    <div class="card-body border-top">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-1">All Hours: <span class="text-body fw-normal">380/244</span></h6>
                            <span class="badge bg-label-success ms-auto">28 Days left</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small>Task: 290/344</small>
                            <small>95% Completed</small>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 95%;" aria-valuenow="95"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Vinnie Mostowy" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Allen Rieske" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/12.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Julee Rossignol" class="avatar avatar-sm pull-up me-2">
                                        <img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar">
                                    </li>
                                    <li><small class="text-muted">280 Members</small></li>
                                </ul>
                            </div>
                            <div class="ms-auto">
                                <a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 15</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="avatar me-3">
                                    <img src="../../assets/img/icons/brands/react-label.png" alt="Avatar"
                                        class="rounded-circle" />
                                </div>
                                <div class="me-2">
                                    <h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Admin
                                            Template</a></h5>
                                    <div class="client-info d-flex align-items-center">
                                        <h6 class="mb-0 me-1">Client: </h6><span>Jeffrey Phillips</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <div class="dropdown z-2">
                                    <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="bx bx-dots-vertical-rounded"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave
                                                Project</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="bg-lighter p-2 rounded me-auto mb-3">
                                <h6 class="mb-1">$2.4k <span class="text-body fw-normal">/ 1.8k</span></h6>
                                <span>Total Budget</span>
                            </div>
                            <div class="text-end mb-3">
                                <h6 class="mb-1">Start Date: <span class="text-body fw-normal">18/8/21</span></h6>
                                <h6 class="mb-1">Deadline: <span class="text-body fw-normal">21/6/22</span></h6>
                            </div>
                        </div>
                        <p class="mb-0">Time is our most valuable asset, that's why we want to help you save it by
                            creating…</p>
                    </div>
                    <div class="card-body border-top">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-1">All Hours: <span class="text-body fw-normal">98/135</span></h6>
                            <span class="badge bg-label-warning ms-auto">15 Days left</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small>Task: 12/90</small>
                            <small>42% Completed</small>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 42%;" aria-valuenow="42"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Kaith D'souza" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="John Doe" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/1.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Alan Walker" class="avatar avatar-sm pull-up me-2">
                                        <img class="rounded-circle" src="../../assets/img/avatars/16.png" alt="Avatar">
                                    </li>
                                    <li><small class="text-muted">1.1k Members</small></li>
                                </ul>
                            </div>
                            <div class="ms-auto">
                                <a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 236</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="avatar me-3">
                                    <img src="../../assets/img/icons/brands/vue-label.png" alt="Avatar"
                                        class="rounded-circle" />
                                </div>
                                <div class="me-2">
                                    <h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">App Design</a>
                                    </h5>
                                    <div class="client-info d-flex align-items-center">
                                        <h6 class="mb-0 me-1">Client: </h6><span>Ricky McDonald</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <div class="dropdown z-2">
                                    <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="bx bx-dots-vertical-rounded"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave
                                                Project</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="bg-lighter p-2 rounded me-auto mb-3">
                                <h6 class="mb-1">$980 <span class="text-body fw-normal">/ $420</span></h6>
                                <span>Total Budget</span>
                            </div>
                            <div class="text-end mb-3">
                                <h6 class="mb-1">Start Date: <span class="text-body fw-normal">24/7/21</span></h6>
                                <h6 class="mb-1">Deadline: <span class="text-body fw-normal">8/10/21</span></h6>
                            </div>
                        </div>
                        <p class="mb-0">App design combines the user interface (UI) and user experience (UX).</p>
                    </div>
                    <div class="card-body border-top">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-1">All Hours: <span class="text-body fw-normal">880/421</span></h6>
                            <span class="badge bg-label-danger ms-auto">45 Days left</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small>Task: 22/140</small>
                            <small>68% Completed</small>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 68%;" aria-valuenow="68"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Jimmy Ressula" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/4.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Kristi Lawker" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/2.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Danny Paul" class="avatar avatar-sm pull-up me-2">
                                        <img class="rounded-circle" src="../../assets/img/avatars/7.png" alt="Avatar">
                                    </li>
                                    <li><small class="text-muted">458 Members</small></li>
                                </ul>
                            </div>
                            <div class="ms-auto">
                                <a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 98</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="avatar me-3">
                                    <img src="../../assets/img/icons/brands/html-label.png" alt="Avatar"
                                        class="rounded-circle" />
                                </div>
                                <div class="me-2">
                                    <h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Create
                                            Website</a></h5>
                                    <div class="client-info d-flex align-items-center">
                                        <h6 class="mb-0 me-1">Client:</h6><span>Hulda Wright</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <div class="dropdown z-2">
                                    <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="bx bx-dots-vertical-rounded"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave
                                                Project</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="bg-lighter p-2 rounded me-auto mb-3">
                                <h6 class="mb-1">$8.5k <span class="text-body fw-normal">/ $2.43k</span></h6>
                                <span>Total Budget</span>
                            </div>
                            <div class="text-end mb-3">
                                <h6 class="mb-1">Start Date: <span class="text-body fw-normal">10/2/19</span></h6>
                                <h6 class="mb-1">Deadline: <span class="text-body fw-normal">12/9/22</span></h6>
                            </div>
                        </div>
                        <p class="mb-0">Your domain name should reflect your products or services so that your...</p>
                    </div>
                    <div class="card-body border-top">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-1">All Hours: <span class="text-body fw-normal">1.2k/820</span></h6>
                            <span class="badge bg-label-warning ms-auto">126 Days left</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small>Task: 237/420</small>
                            <small>72% Completed</small>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 72%;" aria-valuenow="72"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Andrew Tye" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Rishi Swaat" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/9.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Rossie Kim" class="avatar avatar-sm pull-up me-2">
                                        <img class="rounded-circle" src="../../assets/img/avatars/12.png" alt="Avatar">
                                    </li>
                                    <li><small class="text-muted">137 Members</small></li>
                                </ul>
                            </div>
                            <div class="ms-auto">
                                <a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 120</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="avatar me-3">
                                    <img src="../../assets/img/icons/brands/figma-label.png" alt="Avatar"
                                        class="rounded-circle" />
                                </div>
                                <div class="me-2">
                                    <h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Figma
                                            Dashboard</a></h5>
                                    <div class="client-info d-flex align-items-center">
                                        <h6 class="mb-0 me-1">Client: </h6><span>Jerry Greene</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <div class="dropdown z-2">
                                    <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="bx bx-dots-vertical-rounded"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave
                                                Project</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="bg-lighter p-2 rounded me-auto mb-3">
                                <h6 class="mb-1">$52.7k <span class="text-body fw-normal">/ $28.4k</span></h6>
                                <span>Total Budget</span>
                            </div>
                            <div class="text-end mb-3">
                                <h6 class="mb-1">Start Date: <span class="text-body fw-normal">12/12/20</span></h6>
                                <h6 class="mb-1">Deadline: <span class="text-body fw-normal">25/12/21</span></h6>
                            </div>
                        </div>
                        <p class="mb-0">Use this template to organize your design project. Some of the key features are…
                        </p>
                    </div>
                    <div class="card-body border-top">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-1">All Hours: <span class="text-body fw-normal">142/420</span></h6>
                            <span class="badge bg-label-danger ms-auto">5 Days left</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small>Task: 29/285</small>
                            <small>35% Completed</small>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 35%;" aria-valuenow="35"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Kim Merchent" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/10.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Sam D'souza" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/13.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Nurvi Karlos" class="avatar avatar-sm pull-up me-2">
                                        <img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar">
                                    </li>
                                    <li><small class="text-muted">82 Members</small></li>
                                </ul>
                            </div>
                            <div class="ms-auto">
                                <a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 20</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="avatar me-3">
                                    <img src="../../assets/img/icons/brands/xd-label.png" alt="Avatar"
                                        class="rounded-circle" />
                                </div>
                                <div class="me-2">
                                    <h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Logo Design</a>
                                    </h5>
                                    <div class="client-info d-flex align-items-center">
                                        <h6 class="mb-0 me-1">Client:</h6><span>Olive Strickland</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <div class="dropdown z-2">
                                    <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="bx bx-dots-vertical-rounded"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave
                                                Project</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="bg-lighter p-2 rounded me-auto mb-3">
                                <h6 class="mb-1">$1.3k <span class="text-body fw-normal">/ $655</span></h6>
                                <span>Total Budget</span>
                            </div>
                            <div class="text-end mb-3">
                                <h6 class="mb-1">Start Date: <span class="text-body fw-normal">17/8/21</span></h6>
                                <h6 class="mb-1">Deadline: <span class="text-body fw-normal">02/11/21</span></h6>
                            </div>
                        </div>
                        <p class="mb-0">Premium logo designs created by top logo designers. Create the branding of
                            business.</p>
                    </div>
                    <div class="card-body border-top">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-1">All Hours: <span class="text-body fw-normal">580/445</span></h6>
                            <span class="badge bg-label-success ms-auto">4 Days left</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small>Task: 290/290</small>
                            <small>100% Completed</small>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Kim Karlos" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/3.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Katy Turner" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/9.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Peter Adward" class="avatar avatar-sm pull-up me-2">
                                        <img class="rounded-circle" src="../../assets/img/avatars/15.png" alt="Avatar">
                                    </li>
                                    <li><small class="text-muted">16 Members</small></li>
                                </ul>
                            </div>
                            <div class="ms-auto">
                                <a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 37</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Project Cards -->
    </div>
@endsection
@section('scriptContent')
@endsection
