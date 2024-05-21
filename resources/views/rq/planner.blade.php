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

        /*recalculations*/
        .gantt_task_line.gantt_dependent_task {
            background-color: #65c16f;
            border: 1px solid #3c9445;
        }

        .gantt_task_line.gantt_dependent_task .gantt_task_progress {
            background-color: #46ad51;
        }

        .hide_project_progress_drag .gantt_task_progress_drag {
            visibility: hidden;
        }

        .gantt_task_progress {
            text-align: left;
            padding-left: 10px;
            box-sizing: border-box;
            color: white;
            font-weight: bold;
        }
    </style>
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Dysfonctionnement No.{{ $id }} /</span> Actions
            Correctives</h4>
        <input type="hidden" value="{{ $id }}" id="uselessDysId" />
        <!-- Modal -->
        <div class="modal fade" id="fileUploadModal" tabindex="-1" aria-labelledby="fileUploadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileUploadModalLabel">Soumettre une Preuve d'achevement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <form id="fileUploadForm">
                            <div class="mb-3">
                                <label for="proofFile" class="form-label">Choisir un fichier :</label>
                                <input type="file" class="form-control" id="proofFile" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" data-task-id="" id="uploadProofButton">Soummettre</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="gantt_here" style="width: 100%; height: 100vh"></div>
    </div>
@endsection
@section('scriptContent')
    <script src="{!! url('assets/js/js/planner.js') !!}"></script>
@endsection
