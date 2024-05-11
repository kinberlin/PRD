@extends('rq.theme.main')
@section('title')
    Détails des actions sur le Dysfonctionnement
@endsection
@section('manualstyle')
    <script src="{!! url('assets/js/gantt-master/codebase/dhtmlxgantt.js') !!}"></script>
    <link rel="stylesheet" href="{!! url('assets/js/gantt-master/codebase/dhtmlxgantt.css') !!}" type="text/css" />
    <link rel="stylesheet" href="https://docs.dhtmlx.com/gantt/samples/common/controls_styles.css" type="text/css" />
    <style>
        .complete_button {
            margin-top: 1px;
            background-image: url("{!! url('assets/img/logo/v_complete.png') !!}");
            width: 20px;
        }

        .dhx_btn_set.complete_button_set {
            background: #ACCAAC;
            color: #454545;
            border: 1px solid #94AD94;
        }

        .completed_task {
            border: 1px solid #94AD94;
        }

        .completed_task .gantt_task_progress {
            background: #ACCAAC;
        }

        .dhtmlx-completed {
            border-color: #669e60;
        }

        .dhtmlx-completed div {
            background: #81c97a;
        }

        .weekend {
            background: #f4f7f4;
        }

        .gantt_selected .weekend {
            background: #f7eb91;
        }
    </style>
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div id="gantt_here" style="width: 100%; height: 100vh"></div>
    </div>
@endsection
@section('scriptContent')
    <script src="{!! url('assets/js/js/planner.js') !!}"></script>
@endsection
