@extends('rq.theme.main')
@section('title')
    Gestion des Sites
@endsection
@section('manualstyle')
    @livewireStyles
@endsection
@section('mainContent')
    <!-- Site List Table -->
    @livewire('site-renderer')
    
@endsection
@section('scriptContent')
    <script src="{!! url('assets/vendor/libs/select2/select2.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/popular.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/bootstrap5.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/auto-focus.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave-phone.js') !!}"></script>
    <script src="{!! url('assets/js/js/accessory.js') !!}"></script>
    @livewireScripts

@endsection
