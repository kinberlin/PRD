<?php

namespace App\Livewire;

use App\Models\AuthorisationPilote;
use App\Models\AuthorisationRq;
use App\Models\Process;
use App\Models\Processes;
use App\Models\Users;
use Livewire\Component;

class AddpltEmployeeForm extends Component
{
    public $processes;
    public $authorisations;
    public $users;
    public $selectedProcess = null;
    public $selectedUser = null;
    public $isInterim = 1;
    public $disableNoRadio = false;
    public $disableYesRadio = false;
    public $message = null;
    public $disableSubmit = true;

    public function mount()
    {
        $this->processes = Processes::all();
        $this->authorisations = AuthorisationPilote::all();
        $rqU = AuthorisationRq::where('interim', 0)->get();
        $this->users = Users::whereNotIn('id', $rqU->pluck('user'))->where('role', '<>', 1)->get();
        $this->selectedProcess = $this->processes[0]->id;
        $this->selectedUser = $this->users[0]->id;
        $this->checkUserInProcess();
        $this->checkFormReady();
    }

    public function updatedSelectedProcess()
    {
        $this->checkUserInProcess();
    }

    public function updatedSelectedUser()
    {
        $this->checkUserInProcess();
    }

    public function updatedIsInterim()
    {
        $this->checkUserInProcess();
    }

    public function checkUserInProcess()
    {
        // Assuming a method to check if user belongs to Process
        $user = Users::find($this->selectedUser);

        if ($user != null) {
            $_auths = $this->authorisations->where('user', $user->id); //Oui : 1 Non : 0

            if (!blank($_auths->where('process', $this->selectedProcess)->where('interim', $this->isInterim))) {
                if ($this->isInterim == 0) {
                    $this->disableNoRadio = true;
                    $this->disableYesRadio = false;
                    $this->message = 'M/Mme ' . $user->firstname . ' est présentement le pilote principale à ' . $this->processes->where('id', $this->selectedProcess)->first()->name;
                    $this->isInterim = 1;
                } else {
                    $this->disableNoRadio = false;
                    $this->disableYesRadio = true;
                    $this->message = 'M/Mme ' . $user->firstname . ' est présentement le pilote par intérim à ' . $this->processes->where('id', $this->selectedProcess)->first()->name;
                    $this->isInterim = 0;
                }
                $this->checkFormReady();
            }
        }
    }

    public function checkFormReady()
    {
        if (!is_null($this->selectedProcess) && !is_null($this->selectedUser) && !is_null($this->isInterim)) {
            $this->disableSubmit = false;
        } else {
            $this->disableSubmit = true;
        }
    }
    public function render()
    {
        return view('livewire.admin.addplt-employee-form');
    }
}
