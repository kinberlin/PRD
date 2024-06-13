@extends('admin.theme.main')
@section('title')
    Rapport complet sur un dysfonctionnement
@endsection
@section('manualstyle')
<link rel="stylesheet" href="{!! url('assets/vendor/css/pages/page-faq.css') !!}" />
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="faq-header d-flex flex-column justify-content-center align-items-center h-px-300 position-relative">
            <img src="{{url('assets/img/pages/header.png')}}" class="scaleX-n1-rtl faq-banner-img" alt="background image" />
            <h3 class="text-center">Plus d'info sur un Dysfonctionnement signalé ?</h3>
            <div class="input-wrapper my-3 input-group input-group-merge">
                <span class="input-group-text" id="basic-addon1"><i class="bx bx-search-alt bx-xs text-muted"></i></span>
                <input type="text" class="form-control form-control-lg" placeholder="Entrez le code d'un dysfonctionnement"
                    aria-label="Search" aria-describedby="basic-addon1" />
            </div>
            <p class="text-center mb-0 px-3">
                ou alors contacter le DQ si difficulté survient
            </p>
        </div>
    </div>
@endsection
@section('scriptContent')
@endsection
