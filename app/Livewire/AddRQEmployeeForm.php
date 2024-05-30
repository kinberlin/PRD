<?php

namespace App\Livewire;

use App\Models\AuthorisationPilote;
use App\Models\AuthorisationRq;
use Livewire\Component;
use App\Models\Enterprise;
use App\Models\Users;

class AddRQEmployeeForm extends Component
{
    public $enterprises;
    public $authorisations;
    public $users;
    public $selectedEnterprise = null;
    public $selectedUser = null;
    public $isInterim = 1;
    public $disableNoRadio = false;
    public $disableYesRadio = false;
    public $message = null;
    public $disableSubmit = true;

    public function mount()
    {
        $this->enterprises = Enterprise::all();
        $this->authorisations = AuthorisationRq::all();
        $pltU = AuthorisationPilote::where('interim', 0)->get();
        $this->users = Users::whereNotIn('id', $pltU->pluck('user'))->where('role', '<>', 1)->get();
        $this->selectedEnterprise = $this->enterprises[0]->id;
        $this->selectedUser = $this->users[0]->id;
        $this->checkUserInEnterprise();
        $this->checkFormReady();
    }

    public function updatedSelectedEnterprise()
    {
        $this->checkUserInEnterprise();
    }

    public function updatedSelectedUser()
    {
        $this->checkUserInEnterprise();
    }

    public function updatedIsInterim()
    {
        $this->checkUserInEnterprise();
    }

    public function checkUserInEnterprise()
    {
        // Assuming a method to check if user belongs to enterprise
        $user = Users::find($this->selectedUser);

        if ($user != null) {
            $_auths = $this->authorisations->where('user', $user->id); //Oui : 1 Non : 0

            if (!blank($_auths->where('enterprise', $this->selectedEnterprise)->where('interim', $this->isInterim))) {
                if ($this->isInterim == 0) {
                    $this->disableNoRadio = true;
                    $this->disableYesRadio = false;
                    $this->message = 'M/Mme ' . $user->firstname . ' est présentement Responsable Qualité principale à ' . $this->enterprises->where('id', $this->selectedEnterprise)->first()->name;
                    $this->isInterim = 1;
                } else {
                    $this->disableNoRadio = false;
                    $this->disableYesRadio = true;
                    $this->message = 'M/Mme ' . $user->firstname . ' est présentement Responsable Qualité par intérim à ' . $this->enterprises->where('id', $this->selectedEnterprise)->first()->name;
                    $this->isInterim = 0;
                }
                $this->checkFormReady();
            }else{
                $this->disableNoRadio = false;
                $this->disableYesRadio = false;
            }
        }
    }

    public function checkFormReady()
    {
        if (!is_null($this->selectedEnterprise) && !is_null($this->selectedUser) && !is_null($this->isInterim)) {
            $this->disableSubmit = false;
        } else {
            $this->disableSubmit = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.add-r-q-employee-form');
    }
}
