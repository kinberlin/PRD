<?php

namespace App\Livewire;

use App\Models\AuthorisationRq;
use Livewire\Component;
use App\Models\Enterprise;
use App\Models\Users;

class AddRQEmployeeForm extends Component
{
    public $enterprises;
    public $authorisations;
    public $title = 'big';
    public $users;
    public $selectedEnterprise = '';
    public $selectedUser = '';
    public $isInterim = null;
    public $disableNoRadio = false;
    public $disableYesRadio = false;
    public $message = null;
    public $disableSubmit = true;

    public function mount()
    {
        $this->enterprises = Enterprise::all();
        $this->authorisations = AuthorisationRq::all();
        $this->users = Users::all();
        $this->selectedEnterprise = $this->enterprises[0];
        $this->selectedUser = $this->users[0]->id;
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
        $user = Users::where('email', $this->selectedUser)->first();
        if ($user) {
            $_auths = $this->authorisations->where('user', $user->id); //Oui : 1 Non : 0
            if (!blank($_auths->where('enterprise', $this->selectedEnterprise)->where('interim', 0))) {
                $this->disableNoRadio = true;
                $this->disableYesRadio = false;
                $this->message = 'M/Mme ' . $user->firstname . ' est présentement Responsable Qualité principale à ' . $this->enterprises->where('id', $this->selectedEnterprise)->first()->name;
            }
            if (!blank($_auths->where('enterprise', $this->selectedEnterprise)->where('interim', 1))) {
                $this->disableNoRadio = false;
                $this->disableYesRadio = true;
                $this->message = 'M/Mme ' . $user->firstname . ' est présentement Responsable Qualité par intérim à ' . $this->enterprises->where('id', $this->selectedEnterprise)->first()->name;
            }
            $this->checkFormReady();
        }
    }

    public function checkFormReady()
    {
        if (!empty($this->selectedEnterprise) && !empty($this->selectedUser) && !is_null($this->isInterim)) {
            $this->disableSubmit = false;
        } else {
            $this->disableSubmit = true;
        }
    }

    public function render()
    {
        return view('livewire.add-r-q-employee-form');
    }
}
