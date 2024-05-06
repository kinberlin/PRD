@if (session('error'))
    <div class="modal fade" id="myModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Message (Systeme)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="closeModal()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <p class="card-text">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                        onclick="closeModal()">Fermer</button>

                </div>
            </div>
        </div>
    </div>
@endif
@if ($errors->has('file'))
    <div class="modal fade" id="myModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Erreur (Utilisateurs)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="closeModal()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <p class="card-text">
                                {{ $errors->first('file') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                        onclick="closeModal()">Fermer</button>

                </div>
            </div>
        </div>
    </div>
@endif
<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            ©
            <script>
                document.write(new Date().getFullYear())
            </script>, Cadyst Group - <a href="">PRD</a>
        </div>
    </div>
</footer>
