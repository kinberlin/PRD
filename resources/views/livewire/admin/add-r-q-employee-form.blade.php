<div class="offcanvas-body mx-0 flex-grow-0" style ="height : 100%">
    <form class="add-new-user pt-0 fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin.authrq.store') }}"
        method="POST">
        @csrf
        @if ($message != null)
            <div class="mb-3 fv-plugins-icon-container">
                <label class="form-label text-warning">{{ $message }}</label>
            </div>
        @endif
        <div class="mb-3 fv-plugins-icon-container">
            <label class="form-label" for="selents">Entreprise/Filiale d'action</label>
            <select id="selents" name="enterprise" class="form-select" wire:model.live="selectedEnterprise" required>
                @foreach ($enterprises as $index => $e)
                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3 fv-plugins-icon-container">
            <div class="col-md">
                <label class="form-label">Responsable qualité en Intérim ?</label>
                <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input" type="radio" name="interim" id="inlineRadio1" value="1"
                        wire:model.live="isInterim" {{ $disableYesRadio ? 'disabled' : '' }}>
                    <label class="form-check-label" for="inlineRadio1">Oui</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="interim" id="inlineRadio2" value="0"
                        wire:model.live="isInterim" {{ $disableNoRadio ? 'disabled' : '' }}>
                    <label class="form-check-label" for="inlineRadio2">Non</label>
                </div>
            </div>
        </div>
        <div class="mb-3 fv-plugins-icon-container">
            <label class="form-label" for="eventGuests">Employé a Pourvoir</label>
            <select class="select22 form-select" id="eventGuests" name="user" wire:model.live="selectedUser"
                required>
                @foreach ($users as $index => $u)
                    <option value="{{ $u->id }}">
                        Matricule : ({{ $u->matricule }}) {{ $u->firstname . ' ' . $u->lastname }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1"
            style="{{ $disableSubmit ? 'background-color: grey;' : '' }}"
            {{ $disableSubmit ? 'disabled' : '' }}>Ajouter</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Annuler</button>

        <div wire:loading class="mb-3 fv-plugins-icon-container">
            <label class="form-label text-info">Verification ...</label>
        </div>
    </form>
</div>
