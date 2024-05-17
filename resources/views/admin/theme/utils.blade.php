<script src="{!! url('assets/vendor/libs/jquery/jquery.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/popper/popper.js') !!}"></script>
<script src="{!! url('assets/js/js/ui-modals.js') !!}"></script>
<script src="{!! url('assets/vendor/js/bootstrap.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/hammer/hammer.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/i18n/i18n.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/typeahead-js/typeahead.js') !!}"></script>
<script src="{!! url('assets/vendor/js/menu.js') !!}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{!! url('assets/vendor/libs/moment/moment.js') !!}"></script>
<script src="{!! url('assets/js/js//dataTables.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/apex-charts/apexcharts.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/sweetalert2/sweetalert2.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/flatpickr/flatpickr.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/select2/select2.js') !!}"></script>

<!-- Main JS -->
<script src="{!! url('assets/js/js/main.js') !!}"></script>

<!-- Page JS -->
<script src="{!! url('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') !!}"></script>
<script src="{!! url('assets/js/js/xlsx.full.min.js') !!}"></script>
<script src="{!! url('assets/js/js/jszip.js') !!}"></script>
<script src="{!! url('assets/js/js/FileSaver.min.js') !!}"></script>
<script src="{!! url('assets/vendor/libs/sweetalert2/sweetalert2.js') !!}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('myModal');
        if (modal) {
            modal.style.display = 'block';
            modal.classList.add("show")
        }
    });
    // Close the modal when the close button is clicked
    function closeModal() {
        const modal = document.getElementById('myModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
</script>
